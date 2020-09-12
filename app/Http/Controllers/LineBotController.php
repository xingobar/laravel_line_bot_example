<?php

namespace App\Http\Controllers;

use App\Bot\LineBot;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LineBotController extends Controller
{
    protected $linebot;

    public function __construct(LineBot $lineBot)
    {
        $this->linebot = $lineBot;
    }

    public function send(Request $request)
    {
        $message = $request->input("message", '請輸入訊息');
        $response = $this->linebot->chat($message);
        return \response()->json($response);
    }

    public function image(Request $request)
    {
        $response = $this->linebot->image();
        return \response()->json($response);
    }

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

        $response = $this->linebot->reply($request->events[0]);
        return \response()->json($response);
    }
}
