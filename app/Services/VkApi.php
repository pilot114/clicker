<?php

namespace App\Services;

// get AT
// http://oauth.vk.com/authorize?client_id=3470411&scope=messages,photos,groups,status,wall,offline&redirect_uri=blank.html&display=page&v=5.5&response_type=token

// basic
// $users = [];
// $vk->request('users.get', ['user_ids' => range(1000000, 1000009)])->each(function($i, $user) use (&$users) {
// 	$users[] = (array)$user;
	//    });
// return $users;

// SEE Wrapper directory for examples!!!
// $vk->request() return TRANSACTION object... with SUPER methods =)

class VkApi
{
	public $vk;
	
	function __construct()
	{
		$accessToken = "dabbe858a18330263275d401d783766f110f5593c600b3488ceda394abd4e86e7f82dd7b5b9b42bccdb23";
		$this->vk = \getjump\Vk\Core::getInstance()->apiVersion('5.63')->setToken($accessToken);
	}

	public function uploadGroupCover($options, $file)
	{
		$uploadUrl = '';
		// crop_x crop_y crop_x2 crop_y2
		$this->vk->request('photos.getOwnerCoverPhotoUploadServer', $options)
		->each(function($i, $v) use (&$uploadUrl) {
			$uploadUrl = $v;
	    });

		$client = new \GuzzleHttp\Client();
	    $response = $client->request('POST', $uploadUrl, [
	    	'multipart' => [
		    	'photo' => [
		    		'name' => 'photo',
			    	'contents' => $file,
		    	],
	    	]
		]);

	    $data = (array)json_decode($response->getBody());

	    $result = null;
	    $this->vk->request('photos.saveOwnerCoverPhoto', $data)->each(function($i, $v) use (&$result) {
	    	$result = $v;
	    });
	    return $result;
	}

	public function getGroupInfo($options)
	{
		$result = null;
	    $this->vk->vk->request('groups.getById', $options)->each(function($i, $v) use (&$result) {
	    	$result = $v;
	    });
	    return $result;
	}
}