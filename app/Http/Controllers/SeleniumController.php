<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facebook\WebDriver\WebDriverBy;
use App\Services\ChromeSelenium;

// https://facebook.github.io/php-webdriver/latest
// https://github.com/SeleniumHQ/selenium/wiki/DesiredCapabilities

class SeleniumController extends Controller
{
	private $selenium;

	public function __construct(ChromeSelenium $selenium)
	{
		$this->selenium = $selenium;
	}

	public function index()
	{
		return 'selenium';
	}

	public function clientInfo()
	{
		echo '<pre>';
		echo $_SERVER['REMOTE_ADDR'];
		echo '</pre>';
		echo '<pre>';
		print_r(getallheaders());
		echo '</pre>';
		return '';
	}

    public function proxyCheck()
    {
		$proxies = [
			'58.9.99.41:3128', // ++
			'195.138.86.112:3128', // +-+
			// '218.76.106.78:3128', // ! (цепочка)
			'124.88.67.32:843', // ++
			// '5.hidemyass.com/ip-1/encoded/czovL3d3dy5nb29nbGUuY28udWsvc2VhcmNo', // просрочен
		];
		$proxy = $proxies[array_rand($proxies)];

		$this->selenium->createDriver($proxy);

		// proxy
		$checkingUrl = 'http:/wshell.ru/selenium/client_info';
		$this->selenium->driver->get($checkingUrl);

		$this->selenium->wait(WebDriverBy::tagName('pre'));
		$remoteAddr = $this->selenium->driver->findElement(WebDriverBy::tagName('pre'))->getText();
		$this->selenium->driver->quit();

		return compact('proxy', 'remoteAddr');
    }

    public function google()
    {
    	$this->selenium->createDriver();

    	$colors = ['красный', 'зелёный', 'жёлтый', 'синий', 'чёрный', 'белый'];
    	$animals = ['крот', 'кит', 'дрозд', 'жираф', 'слон', 'мангуст'];
    	$color = $colors[array_rand($colors)];
    	$animal = $animals[array_rand($animals)];
    	$search = $color .' '.$animal;
    	$this->selenium->get('https://www.google.ru/search?q=' . $search);

		$links = $this->selenium->driver->findElements(WebDriverBy::cssSelector('h3.r a'));

		$prettyLinks = [];
		foreach ($links as $link) {
			// Facebook/WebDriver/Remote/RemoteWebElement
			$prettyLinks[] = [
				'text' => $link->getText(),
				'link' => $link->getAttribute('href')
			];
		}

		$this->selenium->driver->quit();
		return compact('prettyLinks');
    }
}
