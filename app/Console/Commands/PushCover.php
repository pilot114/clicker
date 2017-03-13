<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;

class PushCover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pushCover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'push new cover to group';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Image $image)
    {
        $coverName = storage_path('app/public') . '/cover.png';
        $cover = fopen($coverName, 'r');

        $img = Image::make('storage/app/public/cover.png');
        $img->resize(1590, 400);
        // $img->insert('storage/app/public/watermark.png');
        $img->save('storage/app/public/cover2.jpg');

        echo 'ok';
    }
}
