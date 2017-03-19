<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ChromeSelenium;
use App\Services\ImageProccessing;
use Facebook\WebDriver\WebDriverBy;

class RunClicker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'runClicker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'run selenium bots on site';

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
    public function handle(ChromeSelenium $selenium, ImageProccessing $i)
    {
        function getLines($file) {
            $f = fopen($file, 'r');
            if (!$f) throw new Exception();
            while ($line = fgets($f)) {          
                yield trim($line);
            }
            fclose($f);
        }

        $selenium->clearScreenshotDir();

        // common settings for all bots
        $query = 'hilaces ru';


        foreach (getLines(storage_path('app/proxy_list')) as $proxy) {
            try {


            var_dump("Create bot...");
            $selenium->createDriver($proxy);

            // find site on yandex
            $baseUrl = 'https://www.yandex.ru/';
            $selenium->driver->get($baseUrl);
            $input = $selenium->driver->findElement(
                WebDriverBy::cssSelector('input.input__control[name=text]')
            );
            $input->sendKeys($query)->submit();

            sleep(2);
            $advLi = $selenium->driver->findElements(
                WebDriverBy::cssSelector('li.serp-adv-item')
            );
            // TODO: for all advLi
            $link = $advLi[0]->findElement(WebDriverBy::cssSelector('a.organic__url'));
            // $selenium->takeScreenshot('link', $link);


            var_dump("Cur proxy: " . $proxy);
            var_dump("Cur UA: " . $selenium->useragent);
            var_dump("finded links: " . $link->getText());

            $link->click();
            sleep(2);

            // change tab
            $handles = $selenium->driver->getWindowHandles();
            var_dump(end($handles));
            $selenium->driver->switchTo()->window(end($handles));
            var_dump("switch!");

            $sleepped = rand(5, 10);
            var_dump('sleep rand: ' . $sleepped);
            sleep($sleepped);
            // $selenium->takeScreenshot('site');

            // $firstFind = $this->webDriver->findElement(WebDriverBy::tag('js-command-bar-field'));

            var_dump("Bot delete...");
            $selenium->driver->quit();


            } catch (\Exception $e) {
                var_dump('error: ' . $e->getMessage());
                $selenium->driver->quit();
                continue;
            }

            // after
            $sleepped = rand(10, 60);
            var_dump('sleep rand: ' . $sleepped);
        }





    }
}
