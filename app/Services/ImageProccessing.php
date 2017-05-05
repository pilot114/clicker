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
        $img = Image::make($options['file_in']);

        // text
        $img->text($options['text'], 690, 360, function($font) {
            $font->file(public_path('font') . '/AgencyFB.ttf');
            $font->size(64);
            $font->color('#1E90FF');
        });

        // clock
        date_default_timezone_set('Asia/Novosibirsk');
        $img->text(date('H:i'), 1380, 100, function($font) {
            $font->file(public_path('font') . '/AgencyFB.ttf');
            $font->size(64);
            $font->color('#FFFFFF');
        });

        $img->resize(795, 200);
        $img->save($options['file_out']);
	}
}