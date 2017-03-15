<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use PulkitJalan\Google\Facades\Google;

Route::get('', function(){
	$routeCollection = Route::getRoutes();
	foreach ($routeCollection as $value) {
		if (in_array('GET', $value->methods())) {
			$uri = $value->uri();
		    echo "<a href='$uri'> $uri </a><br>";
		}
	}
	return '';
});

Route::group(['prefix' => 'selenium'], function () {
    Route::get('', 'SeleniumController@index');
    Route::get('client_info', 'SeleniumController@clientInfo');
    Route::get('proxy_check', 'SeleniumController@proxyCheck');
    Route::get('google', 'SeleniumController@google');
});

Route::group(['prefix' => 'vk'], function () {
	Route::get('', 'VkController@index');
	Route::get('/test', 'VkController@test');
});


Route::get('youtube_like', function(){
	// https://developers.google.com/youtube/v3/docs

	$client = new \Google_Client();
	$client->setApplicationName("ItsClicker");
	$client->setDeveloperKey("AIzaSyAyeuA7BELuIFc1d-C2S1V6a-BgZx-t0o0");

	$service = new \Google_Service_YouTube($client);
	$part = 'snippet';
	$options = ['playlistId' => 'LLkAmeWdasZTJZl5HV8TI1MA', 'maxResults' => 50];
	$response = $service->playlistItems->listPlaylistItems($part, $options);

	$result = [];
	foreach ($response as $item) {
		$result[] = $item->getSnippet()->toSimpleObject();
	}
	return $result;
});
