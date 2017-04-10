<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InstagramApi;

class InstagramController extends Controller
{
	public function index(InstagramApi $api)
	{
		$ig = $api->insta;

		$files = glob(storage_path('insta') . '/*.*');
    	$photoFilename = $files[array_rand($files)];
		$captionText = 'shit';

		try {
		    $ig->uploadTimelinePhoto($photoFilename, ['caption' => $captionText]);
		} catch (\Exception $e) {
		    echo 'Something went wrong: '.$e->getMessage()."\n";
		}
		unlink($photoFilename);

		return 'ok';
	}
}
