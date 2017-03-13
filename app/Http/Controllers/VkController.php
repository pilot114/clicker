<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VkApi;

class VkController extends Controller
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

	public function cover()
	{
	    $coverName = storage_path('app/public') . '/cover.png';
	    $cover = fopen($coverName, 'r');
	    // add Text to file
	    // ...
		$coverArray = $this->vk->uploadGroupCover(['group_id' => 81023175], $cover);
		return $coverArray;
	}
}
