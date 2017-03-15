<?php

namespace App\Services;

use Intervention\Image\Facades\Image;

class ImageProccessing
{
	public $image;
	
	function __construct(Image $image)
	{
		$this->image = $image;
	}

	public function buildCover($options)
	{
        $img = Image::make(storage_path('app/public') . '/' . $options['file_in']);

        // text
        $img->text($options['text'], 740, 360, function($font) {
            $font->file(public_path('font') . '/Werfus.ttf');
            $font->size(128);
            $font->color('#fdf6e3');
        });

        // clock
        $img->text(date('h:i'), 1350, 100, function($font) {
            $font->file(public_path('font') . '/Werfus.ttf');
            $font->size(100);
            $font->color('#DAA520');
        });

        $img->resize(795, 200);
        $img->save(storage_path('app/public') . '/' . $options['file_out']);
	}
}