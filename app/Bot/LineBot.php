<?php


namespace App\Bot;


use App\Service\LineBotService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\RichMenuBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;

class LineBot
{
    const TEXT_TYPE = 'text';
    const VIDEO_TYPE ='video';
    const IMAGE_TYPE = 'image';
    const AUDIO_TYPE = 'audio';

    protected $http_client;
    protected $bot;
    protected $lineBotService;

    public function __construct(LineBotService $lineBotService)
    {
        $this->http_client = new CurlHTTPClient(env('LINEBOT_TOKEN'));
        $this->bot  = new \LINE\LINEBot($this->http_client,  [
           'channelSecret' => env('LINEBOT_SECRET')
        ]);
        $this->lineBotService = $lineBotService;
    }

    public function chat($message)
    {
        $message = $this->lineBotService->fetchMessageBuilder($message);
        $response = $this->bot->pushMessage(env('LINE_USER_ID'), $message);
        Log::debug($response->getRawBody());
        return $response->getHTTPStatus();
    }

    // 傳送圖片有連結
    public function image()
    {
        $builder = $this->lineBotService
                        ->buildImageCarouselBuilder(
                            'test',
                            'https://google.com',
                            'https://i.imgur.com/BlBH2HE.jpg',
                            'text');
        return $this->chat($builder);
    }

    /**
     * 純圖片訊息
     * @return int
     */
    public function imageMessage()
    {
        $image = $this->lineBotService->buildImageBuilder('https://i.imgur.com/BlBH2HE.jpg', 'https://i.imgur.com/BlBH2HE.jpg');
        return $this->chat($image);
    }

    public function reply($event)
    {
        $token = $event['replyToken'];
        $content = $this->lineBotService->resolveUserText($event['message']);

        $response = $this->bot->replyMessage($token, $content);
        return $response->getHTTPStatus();
    }

    public function createRichMenu()
    {
        $size = RichMenuBuilder\RichMenuSizeBuilder::getFull();
        $bound = new RichMenuAreaBoundsBuilder(0,0, 2500, 1686);
        $action = new UriTemplateActionBuilder("test", "https://google.com");
        $area = new RichMenuBuilder\RichMenuAreaBuilder($bound, $action);
        $builder = new RichMenuBuilder($size, true, 'menu', 'bar_text', [$area]);
        $response = $this->bot->createRichMenu($builder);
        Log::debug('====== response ======');
        Log::debug($response->getHTTPStatus());
        Log::debug($response->getRawBody());
        Log::debug($builder->build());
        return $response;
    }

    public function getRichList()
    {
        return $this->bot->getRichMenuList();
    }

    public function uploadImageForMenu($menu_id)
    {
        $imagePath = storage_path('test.jpg');//'https://i.imgur.com/BlBH2HE.jpg';
        $contentType = 'image/jpeg';
        $response = $this->bot->uploadRichMenuImage($menu_id, $imagePath, $contentType);
        Log::debug($response->getRawBody());
        return $response->getHTTPStatus();
    }

    public function cancelRichMenu()
    {
        $respnose = $this->bot->cancelDefaultRichMenuId();
        return $respnose->getHTTPStatus();
    }

    public function setDefault()
    {
        $response = $this->bot->setDefaultRichMenuId('richmenu-');
        Log::debug($response->getRawBody());
        Log::debug($response->getHTTPStatus());
    }
}