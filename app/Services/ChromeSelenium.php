<?php

namespace App\Services;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;

use Facebook\WebDriver\Exception\WebDriverCurlException;
use Facebook\WebDriver\Exception\NoSuchDriverException;

use Facebook\WebDriver\Chrome\ChromeOptions;

// TODO: functional test with js-rich test page

class ChromeSelenium
{
	private $seleniumHost = "http://selenium:4444/wd/hub";
	private $screenshotPath;

	public $useragent;
	public $driver;

	function __construct()
	{
		$this->screenshotPath = storage_path('app/screenshots');
	}

	public function createDriver($proxy = null, $sessionId = null)
	{
		$useragents = file(storage_path('app/useragents'), FILE_IGNORE_NEW_LINES);
		$this->useragent = $useragents[array_rand($useragents)];

		$options = new ChromeOptions();
		$options->addArguments(['--user-agent=' . $this->useragent]);

		$capabilities = [
		    WebDriverCapabilityType::BROWSER_NAME => 'chrome',
		    ChromeOptions::CAPABILITY => $options
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

	// TODO __call for: $selenium->wait(WebDriverBy::className('rg_i')); -> $selenium->waitClassName('rg_i');
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

	public function clearScreenshotDir()
	{
		$files = glob($this->screenshotPath . '/*');
		foreach ($files as $file){
			if (is_file($file)) {
				unlink($file);
			}
		}
	}

	public function takeScreenshot($name, $element=null)
	{
		$name = $name . '_' . time() . ".png";
	    $screenshot = $this->screenshotPath . '/' . $name;

	    $this->driver->takeScreenshot($screenshot);
	    if(!file_exists($screenshot)) {
			response(['error' => 'Could not save screenshot'])->send();
	    }

	    if( ! (bool) $element) {
	        return $screenshot;
	    }

	    $element_screenshot = $this->screenshotPath . '/el_' . $name;

        $element_width = $element->getSize()->getWidth();
        $element_height = $element->getSize()->getHeight();
        
        $element_src_x = $element->getLocation()->getX();
        $element_src_y = $element->getLocation()->getY();
        
        // Create image instances
        $src = imagecreatefrompng($screenshot);
        $dest = imagecreatetruecolor($element_width, $element_height);

        // Copy
        imagecopy($dest, $src, 0, 0, $element_src_x, $element_src_y, $element_width, $element_height);
        imagepng($dest, $element_screenshot);
        unlink($screenshot);
        
        if( ! file_exists($element_screenshot)) {
            throw new Exception('Could not save element screenshot');
        }
        
        return $element_screenshot;
	}
}