<?php


namespace App\Bot\Line;


use App\Service\LineBotService;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\Constant\ActionType;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;

class LineBot extends AbstractLine
{
    protected $lineBotService;

    public function __construct(LineBotService $lineBotService)
    {
        $this->lineBotService = $lineBotService;
        parent::__construct();
    }

    public function chat($message)
    {
        $message = $this->lineBotService->fetchMessageBuilder($message);
        $response = $this->bot->pushMessage(env('LINE_USER_ID'), $message);
        return $this->getResponse($response);
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

    /**
     * 回復使用者
     * @param $event
     * @return false|mixed|string
     */
    public function reply($event)
    {
        $token = $event['replyToken'];
        $content = $this->lineBotService->resolveUserText($event['message']);
        $response = $this->bot->replyMessage($token, $content);
        return $this->getResponse($response);
    }
}