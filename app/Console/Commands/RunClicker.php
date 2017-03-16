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
        $selenium->clearScreenshotDir();
        $selenium->createDriver();

        die();
        $query = 'hilaces ru';

        $selenium->clearScreenshotDir();
        $selenium->createDriver();
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

        $selenium->takeScreenshot('link', $link);

        $link->click();
        sleep(2);

        // change tab
        $handles = $selenium->driver->getWindowHandles();
        $selenium->driver->switchTo()->window(end($handles));

        $selenium->takeScreenshot('site');
            // $firstFind = $this->webDriver->findElement(WebDriverBy::tag('js-command-bar-field'));

        $selenium->driver->quit();
    }
}
