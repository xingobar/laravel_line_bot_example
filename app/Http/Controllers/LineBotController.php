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

        $response = $this->linebot->reply($request->events[0]);
        return \response()->json($response);
    }

    public function createRichMenu(Request $request)
    {
        $respnose = $this->linebot->createRichMenu();
        return \response()->json($respnose);
    }

    public function getRichMenuList(Request $request)
    {
        $response = $this->linebot->getRichList();
        return \response()->json(json_decode($response->getRawBody(), true));
    }

    public function uploadImage(Request $request)
    {
        $response = $this->linebot->uploadImageForMenu('richmenu-');
        return \response()->json($response);
    }

    public function cancel()
    {
        $response = $this->linebot->cancelRichMenu();
        return \response()->json($response);
    }

    public function setDefault()
    {
        $this->linebot->setDefault();
    }
}
