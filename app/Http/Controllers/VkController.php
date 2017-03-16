<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VkApi;
use App\Services\ImageProccessing;

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

	public function test(ImageProccessing $image)
	{
        return '$coverArray';
	}
}
