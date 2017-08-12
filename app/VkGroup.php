<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class VkGroup extends Model
{
    protected $collection = 'vk_groups';

    protected $fillable = [
    	'name',
        'cover_image',
        'owner_vk_uid',
        'balance',
        'enabled',
        'access_token'
    ];
}
