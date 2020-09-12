<?php

namespace App\Http\Controllers;

use App\Bot\Line\RichMenu\LineRichMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LineRichMenuController extends Controller
{
    protected $lineRichMenu;

    public function __construct(LineRichMenu $lineRichMenu)
    {
        $this->lineRichMenu = $lineRichMenu;
    }

    /**
     * 新增 menu
     * @return false|mixed|string
     */
    public function create()
    {
        $response = $this->lineRichMenu->createRichMenu();
        return \response()->json($response);
    }

    /**
     * 取得所有 menu
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $respnose = $this->lineRichMenu->get();
        return \response()->json($respnose);
    }

    /**
     * 上傳圖片到 menu
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        $menu_id = $request->input('menu_id', '');
        $response = $this->lineRichMenu->uploadImage($menu_id);
        return \response()->json($response);
    }

    /**
     * 取消所有使用者 menu
     * @return false|mixed|string
     */
    public function cancelDefault()
    {
        $response =  $this->lineRichMenu->cancelDefault();
        return \response()->json($response);
    }

    /**
     * 設定menu到使用者
     * @param Request $request
     * @return false|mixed|string
     */
    public function setDefault(Request $request)
    {
        $menu_id = $request->input('menu_id', '');
        $response = $this->lineRichMenu->setDefault($menu_id);
        return \response()->json($response);
    }
}
