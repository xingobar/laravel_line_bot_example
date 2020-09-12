<?php


namespace App\Bot;


use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;

class LineBot
{
    protected $http_client;
    protected $bot;

    public function __construct()
    {
        $this->http_client = new CurlHTTPClient(env('LINEBOT_TOKEN'));
        $this->bot  = new \LINE\LINEBot($this->http_client,  [
           'channelSecret' => env('LINEBOT_SECRET')
        ]);
    }

    public function chat(string $message)
    {
        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
        $response = $this->bot->pushMessage(env('LINE_USER_ID'), $textMessageBuilder);
        return $response->getHTTPStatus();
    }

    public function image()
    {
        $image = new ImageMessageBuilder('https://i.imgur.com/BlBH2HE.jpg',
            'https://i.imgur.com/BlBH2HE.jpg');
        $response = $this->bot->pushMessage(env('LINE_USER_ID'), $image);
        return $response->getHTTPStatus();
    }
}