<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ChromeSelenium;
use Facebook\WebDriver\WebDriverBy;

use GuzzleHttp\Client;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Pool;

class GetImagesFromGoogle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:ifg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get images from google';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ChromeSelenium $selenium)
    {
        $query = 'cats';

        $selenium->createDriver();
        
        $storage = storage_path('app/google_images');
        if(!file_exists($storage)){
            mkdir($storage);
        }

        $baseUrl = 'https://www.google.ru/search?&tbm=isch&q=' . $query;
        $selenium->driver->get($baseUrl);
        $selenium->wait(WebDriverBy::className('rg_i'));

        $js = "
        var scrollTo = arguments[0];
        var images = [];
        var isButton = false;
        var select = document.querySelectorAll('a.rg_l .rg_i');
        for(var i=0; i<select.length; i++){
            if(select[i].src){
                images.push(select[i].src);
            }
        }
        // check visible button 'more images'
        if(document.getElementById('smb').offsetParent !== null){
            document.getElementById('smb').click();
            isButton = true;
        }
        window.scrollTo(0, scrollTo * 1000)
        return [images, isButton];
        ";

        $images   = [];
        $counters = [];
        $isButton = false;
        $isNew    = true;
        $i = 1;
        try {
            // timeout load page(sec), timeout try(msec)
            $selenium->driver->wait(60, 500)
            ->until(function ($driver) use (&$i, &$js, &$images, &$counters, &$isNew, &$isButton) {
                list($images, $isButton) = $selenium->driver->executeScript($js, [$i]);
                $counters[] = count($images);
                $testSize = 3;
                $test = array_slice($counters, -$testSize, $testSize);
                if ($i >= $testSize && reset($test) === end($test)) {
                    $isNew = false;
                }
                $i++;
                if ($isNew || $isButton) {
                    return false;
                } else {
                    return true;
                }
            });
        } catch (WebDriverException $e) {
            echo "WebDriverException: ".get_class($e)."\n";
            echo $e->getMessage();
            die();
        }

        // save images
        $hashes = [];
        $client = new Client([
            'timeout' => 60,
            'defaults' => [
        //      'debug'  => true
            ]
        ]);
        $requests = [];
        foreach ($images as $image) {
            if(strpos($image, 'http') === 0){
                $requests[] = $client->createRequest('GET', trim($image));
            } else {
                list($meta, $image) = explode(',', $image);
                $format = explode(';',$meta);
                $format = reset($format);
                $format = explode('/', $format);
                $format = end($format);
                $data = base64_decode($image);
                $hash = hash('sha256', $data);
                $hashes[] = $hash;
                file_put_contents($storage . '/' . $hash  . '.' . $format, $data);
            }
        }

        $startDownload = microtime(true);
        Pool::send($client, $requests, [
            'complete' => function (CompleteEvent $event) use (&$complete, &$storage, &$hashes){
                $data   = $event->getResponse()->getBody();
                $format = end(explode('/', $event->getResponse()->getHeader('Content-Type')));
                $hash = hash('sha256', $data);
                $hashes[] = $hash;
                file_put_contents($storage . '/' . $hash  . '.' . $format, $data);
            },
            'error' => function (ErrorEvent $event) use (&$error){
                echo $event->getException()->getMessage() . "\n";
            },
            'pool_size' => 12
        ]);
        echo "Найдено:" . count($images) . "\n";
        echo "Скачано:" . count($hashes) . "\n";
        $hashes = array_keys(array_count_values($hashes));
        echo "Хешей:" . count($hashes) . "\n";
        
        // $monger = new \Catter\Monger();
        // try {
        //     $monger->addBatch($hashes, $query);
        // } catch (Exception $e) {
        //     echo $e->getMessage() . "<br>";
        //     $selenium->driver->quit()();
        // }

        $selenium->driver->quit();
    }
}
