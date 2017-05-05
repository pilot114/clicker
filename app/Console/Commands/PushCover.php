<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageProccessing;
use App\Services\VkApi;

class PushCover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:pushCover';

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
    public function handle(ImageProccessing $image, VkApi $vk)
    {
        $texts = [
            "Продвижение сообществ vk",
            "Продвижение instgram",
            "Анализ вашего бизнеса",
            "Продвижение в поисковых системах",
            "Бизнес консалтинг",
            "Youtube продвижение",
            "Создание landing page"
        ];
        $text = $texts[array_rand($texts)];
        $options = [
            'file_in'  => storage_path('public') . '/' . 'cover.png',
            'file_out' => storage_path('public') . '/' . 'cover2.png',
            'text'     => $text
        ];
        $image->buildCover($options);
        $cover = fopen($options['file_out'], 'r');
        $coverArray = $vk->uploadGroupCover(['group_id' => 141515764], $cover);

        return $coverArray;
    }
}
