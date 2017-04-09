<?php

namespace App\Services;

// use MetzWeb\Instagram\Instagram;
use InstagramAPI\Instagram;

class InstagramApi
{
	public $insta;
	
	function __construct()
	{
		// oficial Instagram API dont provide media upload!
		// $clientID = "f7951b49e2de42b1885f9cd2d47ab3b1";
		// $clientSecret = "2eb827b986384d6e94d5d2c07cb0eb19";
		// $cb = "http://clicker.dev/oauth/insta";

		// $this->insta = new Instagram([
		// 	'apiKey'      => $clientID,
		// 	'apiSecret'   => $clientSecret,
		// 	'apiCallback' => $cb
		// ]);

		$username = 'pilot114';
		$password = 'zzzxxx000';
		$debug = false;
		$truncatedDebug = false;
		$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
		try {
		    $ig->setUser($username, $password);
		    $ig->login();
		} catch (\Exception $e) {
		    echo 'Something went wrong: '.$e->getMessage()."\n";
		    exit(0);
		}
		$this->insta = $ig;
	}
}