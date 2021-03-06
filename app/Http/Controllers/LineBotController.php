<?php

namespace App\Http\Controllers;

use App\Bot\Line\LineBot;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Jieba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LineBotController extends Controller
{
    protected $linebot;

    public function __construct(LineBot $lineBot)
    {
//        Jieba::init();
//        Finalseg::init();
        $this->linebot = $lineBot;
    }

    /**
     * 單純信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        $message = $request->input("message", '請輸入訊息');
        $response = $this->linebot->chat($message);
        return \response()->json($response);
    }

    /**
     * 圖片輪播
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function image(Request $request)
    {
        $response = $this->linebot->image();
        return \response()->json($response);
    }

    /**
     * 純圖片訊息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function imageMessage(Request $request)
    {
        $response = $this->linebot->imageMessage();
        return \response()->json($response);
    }

    public function message(Request $request)
    {
        Log::debug($request->events);
        Log::debug($request->events[0]['replyToken']);

        if (count($request->events) <= 0) {
            return;
        }
        $event = $request->events[0];
        $type = $event['type'];

        $response = [];
        if ($type === LineBot::USER_FOLLOW) {
            // 要加 rich menu
        } else if ($type === LineBot::USER_UNFOLLOW) {
            // 要取消 rich menu
        } else if ($type === LineBot::LINE_MESSAGE or $type === LineBot::POST_BACK){
            $response = $this->linebot->reply($request->events[0]);
        } else if ($type === LineBot::POST_BACK) {
            parse_str($event['postback']['data'], $output);
            Log::debug($event['postback']['data']);
            Log::debug($output);
        }
        return \response()->json($response);
    }
}
