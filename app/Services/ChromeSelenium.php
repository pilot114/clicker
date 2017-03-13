<?php

namespace App\Services;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;

use Facebook\WebDriver\Exception\WebDriverCurlException;
use Facebook\WebDriver\Exception\NoSuchDriverException;

class ChromeSelenium
{
	private $seleniumHost = "http://selenium:4444/wd/hub";
	private $screenshotPath;
	public $driver;

	public function __costruct()
	{
		$this->screenshotPath = storage_path('app/public');
	}

	public function createDriver($proxy = null, $sessionId = null)
	{
		$capabilities = [
		    WebDriverCapabilityType::BROWSER_NAME => 'chrome',
		];

		if ($proxy) {
			$proxyConf = [];
		    // types: ftpProxy, httpProxy, sslProxy, socksProxy
		    // only for SOCKS: socksUsername socksPassword
		    // noProxy - specifies proxy bypass addresses
		    if (substr($proxy, -3) === ":80") {
				$proxyConf['ftpProxy'] = $proxy;
		    } else {
				$proxyConf['httpProxy'] = $proxy;
		    }
		    $proxyConf['proxyType'] = 'manual';
			$capabilities[WebDriverCapabilityType::PROXY] = $proxyConf;
		}
		try {
			if ($sessionId) {
				$this->driver = RemoteWebDriver::createBySessionID($sessionId, $this->seleniumHost);
			} else {
				$defaultCreateTimeout = 5000;
				$this->driver = RemoteWebDriver::create($this->seleniumHost, $capabilities, $defaultCreateTimeout);
			}
		} catch(WebDriverCurlException $e) {
			$data = ['error' => $e->getMessage()];
			if ($proxy) {
				$data['proxy'] = $proxy;
			}
			response($data)->send();
		}
	}

	public function wait(WebDriverBy $element)
	{
		try {
			$this->driver->wait()->until(
				WebDriverExpectedCondition::presenceOfElementLocated($element)
			);
		} catch(Exception $e) {
			try {
				$this->driver->quit();
			} catch(Exception $e) {
				$error = get_class($e) .':' . $e->getMessage();
				return compact('proxy', 'error');
			}
			$error = get_class($e) .':' . $e->getMessage();
			return compact('proxy', 'error');
		}
	}

	public function get($url)
	{
		try {
	    	$this->driver->get($url);
		} catch (NoSuchDriverException $e) {
			response(['error' => $e->getMessage()])->send();
		}
	}

	public function takeScreenshot($element=null)
	{
	    $screenshot = $this->screenshotPath . 'full_' . time() . ".png";

	    $this->driver->takeScreenshot($screenshot);
	    if(!file_exists($screenshot)) {
			response(['error' => 'Could not save screenshot'])->send();
	    }

	    if( ! (bool) $element) {
	        return $screenshot;
	    }

	    $element_screenshot = $this->screenshotPath . $element->getText() . '_' . time() . ".png";

	    $element_width = $element->getSize()->getWidth();
	    $element_height = $element->getSize()->getHeight();

	    $element_src_x = $element->getLocation()->getX();
	    $element_src_y = $element->getLocation()->getY();

	    $src = imagecreatefrompng($screenshot);
	    $dest = imagecreatetruecolor($element_width, $element_height);

	    imagecopy($dest, $src, 0, 0, $element_src_x, $element_src_y, $element_width, $element_height);
	    imagepng($dest, $element_screenshot);

	    unlink($screenshot);

	    if( ! file_exists($element_screenshot)) {
			response(['error' => 'Could not save element screenshot'])->send();
	    }

	    return $element_screenshot;
	}
}