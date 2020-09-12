<?php


namespace App\Bot;


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

    public function __construct()
    {
        $this->http_client = new CurlHTTPClient(env('LINEBOT_TOKEN'));
        $this->bot  = new \LINE\LINEBot($this->http_client,  [
           'channelSecret' => env('LINEBOT_SECRET')
        ]);
    }

    public function chat($message)
    {
        if (is_string($message)) {
            // 純文字訊息
            $message = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
        }
        $response = $this->bot->pushMessage(env('LINE_USER_ID'), $message);
        Log::debug($response->getRawBody());
        return $response->getHTTPStatus();
    }

    // 傳送圖片有連結
    public function image()
    {
        $target = $this->buildTemplateMessageBuilder(
            'https://i.imgur.com/BlBH2HE.jpg',
            'https://google.com',
            'test');
        return $this->chat($target);
    }

    // 建立 image 輪播
    public function buildTemplateMessageBuilder(
        string $imagePath,
        string $directUri,
        string $label
    ): TemplateMessageBuilder {
        $aa = new UriTemplateActionBuilder($label, $directUri);
        $bb =  new ImageCarouselColumnTemplateBuilder($imagePath, $aa);
        $target = new ImageCarouselTemplateBuilder([$bb]);
        return new TemplateMessageBuilder('test123', $target);
    }

    /**
     * 純圖片訊息
     * @return int
     */
    public function imageMessage()
    {
        $image = new ImageMessageBuilder('https://i.imgur.com/BlBH2HE.jpg', 'https://i.imgur.com/BlBH2HE.jpg');
        return $this->chat($image);
    }

    public function reply($event)
    {
        $token = $event['replyToken'];
        $message = $event['message'];

        switch ($message['type']) {
            case self::TEXT_TYPE:
                $content = new TextMessageBuilder($message['text']);
                break;
            default:
                $content = new TextMessageBuilder('請輸入訊息');
                break;
        }
        $response = $this->bot->replyMessage($token, $content);
        return $response->getHTTPStatus();
    }
}