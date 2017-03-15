<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use App\Services\VkApi;

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
    public function handle(Image $image, VkApi $vk)
    {
        $texts = ['Привет Руся', 'Шимбердробля', 'Ya TExTazaza'];
        $text = $texts[array_rand($texts)];
        $options = [
            'file_in'  => 'cover.jpg',
            'file_out' => 'cover2.jpg',
            'text'     => $text
        ];
        $image->buildCover($options);
        $cover = fopen(storage_path('app/public') . '/cover2.jpg', 'r');
        $coverArray = $this->vk->uploadGroupCover(['group_id' => 141515764], $cover);

        return $coverArray;
    }
}
