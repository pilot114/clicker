<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VkApi;
use App\Services\ImageProccessing;

class VkApiController extends Controller
{
	private $vk;

	public function __construct(VkApi $vk)
	{
		$this->vk = $vk;
	}

	public function index()
	{
		return 'vk';
	}

	public function test(ImageProccessing $image)
	{
        return '$coverArray';
	}

	// https://vk.com/dev/groups.search
	public function group()
	{
		$result = null;
		$options = [
			'q' => 'Король планеты',
			'count' => 1000,
			// 'country_id' =>
			// 'city_id' =>
		];
		$result = [];

		$count = $this->vk->vk->request('groups.search', $options)->count;
	    $this->vk->vk->request('groups.search', $options)->each(function($i, $v) use (&$result) {
	    	$result[] = $v;
	    });
	    return [$count , $result];
	}
}
