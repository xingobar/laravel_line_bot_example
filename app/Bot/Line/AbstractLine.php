<?php


namespace App\Bot\Line;


use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

abstract class AbstractLine
{
    const TEXT_TYPE = 'text';
    const VIDEO_TYPE ='video';
    const IMAGE_TYPE = 'image';
    const AUDIO_TYPE = 'audio';

    const USER_FOLLOW = 'follow';
    const USER_UNFOLLOW = 'unfoloow';
    const LINE_MESSAGE = 'message';
    const USER_JOIN = 'memberJoined';
    const USER_LEAVE = 'memberLeft';

    protected $http_client;
    protected $bot;

    public function __construct()
    {
        $this->http_client = new CurlHTTPClient(env('LINEBOT_TOKEN'));
        $this->bot  = new \LINE\LINEBot($this->http_client,  [
            'channelSecret' => env('LINEBOT_SECRET')
        ]);
    }

    public function getResponse($response)
    {
        Log::debug($response->getRawBody());
        Log::debug($response->getHTTPStatus());
        if ($response->getHTTPStatus() != 200 ) {
            return json_decode($response->getRawBody(), true);
        }
        return json_decode($response->getRawBody(), JSON_UNESCAPED_UNICODE);
    }
}