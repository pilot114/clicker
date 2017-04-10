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
use App\Services\InstagramApi;
use App\VkGroup;
use Illuminate\Support\Facades\Auth;

Route::get('', function(){
	return 'hello, traveller';
});

Route::get('smm_covers', function(){
	$user = Auth::user();
	if (!$user) {
		return '<a href="/login">Войти</a>';
	}
	$vk_groups = VkGroup::all();

	return view('vk_groups', compact('vk_groups'));
});
Route::resource('vk_group', 'VkGroupController');


Route::get('routes', function(){
	$routeCollection = Route::getRoutes();
	$out = '';
	foreach ($routeCollection as $value) {
		if (in_array('GET', $value->methods())) {
			$uri = $value->uri();
		    $out .= "<a href='$uri'> $uri </a><br>";
		}
	}
	return $out;
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
	Route::get('/group', 'VkController@group');
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

Route::get('/insta', 'InstagramController@index');

Route::get('/oauth/insta', function(InstagramApi $api){
	$code = $_GET['code'];
	$data = $api->insta->getOAuthToken($code);
	var_dump($data);
});

Auth::routes();

Route::get('/home', 'HomeController@index');
