<?php


namespace App\Bot\Line\RichMenu;


use App\Bot\Line\AbstractLine;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\RichMenuBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;

class LineRichMenu extends AbstractLine
{

    protected $width = 2500;
    protected $height = 1686;
    protected $menuName = 'menu_name';
    protected $chatBarText = 'chat_bar_text';
    protected $label = 'label';
    protected $uri = 'https://google.com';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 設定大小
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function setSize(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }

    /**
     * 設定選單名稱
     * @param string $menuName
     * @return $this
     */
    public function setMenuName(string $menuName)
    {
        $this->menuName = $menuName;
        return $this;
    }

    public function setChatBarText(string $chat_bar_text)
    {
        $this->chatBarText = $chat_bar_text;
        return $this;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
        return $this;
    }

    public function setUri(string $uri)
    {
        $this->uri = $uri;
        return $this;
    }

    public function createRichMenu()
    {
        $size = RichMenuBuilder\RichMenuSizeBuilder::getFull();
        $bound = new RichMenuAreaBoundsBuilder(0,0, $this->width, $this->height);
        $action = new UriTemplateActionBuilder($this->label, $this->uri);
        $area = new RichMenuBuilder\RichMenuAreaBuilder($bound, $action);
        $builder = new RichMenuBuilder($size, true, $this->menuName, $this->chatBarText, [$area]);
        $response = $this->bot->createRichMenu($builder);
        return $this->getResponse($response);
    }

    /**
     * 取得 menu list
     * @return \LINE\LINEBot\Response
     */
    public function get()
    {
        $response = $this->bot->getRichMenuList();
        return $this->getResponse($response);
    }

    /**
     * 更新圖片
     * @param $menu_id - rich menu 編號
     * @return int
     */
    public function uploadImage($menu_id)
    {
        $imagePath = storage_path('test.jpg');//'https://i.imgur.com/BlBH2HE.jpg';
        $contentType = 'image/jpeg';
        $response = $this->bot->uploadRichMenuImage($menu_id, $imagePath, $contentType);
        return $this->getResponse($response);
    }

    /**
     * 取消使用者預設的 rich menu
     * @return false|mixed|string
     */
    public function cancelDefault()
    {
        $response = $this->bot->cancelDefaultRichMenuId();
        return $this->getResponse($response);
    }

    /**
     * 設定預設的 rich menu
     * @param $menu_id - menu 編號
     * @return false|mixed|string
     */
    public function setDefault($menu_id)
    {
        $response = $this->bot->setDefaultRichMenuId($menu_id);
        return $this->getResponse($response);
    }
}