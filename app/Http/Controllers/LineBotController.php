<?php

namespace App\Http\Controllers;

use App\Bot\LineBot;
use Illuminate\Http\Request;

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
}
