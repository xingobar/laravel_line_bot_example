<?php


namespace App\Bot;


use App\Service\LineBotService;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
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
}