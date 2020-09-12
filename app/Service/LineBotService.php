<?php


namespace App\Service;


use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;

class LineBotService
{
    const TEXT_TYPE = 'text';
    const VIDEO_TYPE ='video';
    const IMAGE_TYPE = 'image';
    const AUDIO_TYPE = 'audio';

    /**
     * message builder
     * @param $content
     * @return TextMessageBuilder
     */
    public function fetchMessageBuilder($content)
    {
        if (is_string($content)) {
            $content = new TextMessageBuilder($content);
        }
        return $content;
    }

    /**
     * 圖片輪播
     * @param string $label - 圖片標籤
     * @param string $direct_uri - 導向至哪個位置
     * @param string $image_path - 預覽圖片
     * @param string $text - 文字
     * @return TemplateMessageBuilder
     */
    public function buildImageCarouselBuilder(string $label, string $direct_uri, string $image_path, string $text)
    {
        $uri = new UriTemplateActionBuilder($label, $direct_uri);
        $carousel_column = new ImageCarouselColumnTemplateBuilder($image_path, $uri);
        $template = new ImageCarouselTemplateBuilder([$carousel_column]);
        return new TemplateMessageBuilder($text, $template);
    }

    /**
     * 產生圖片訊息
     * @param string $image_url - 圖片
     * @param string $preview_url - 預覽圖
     * @return ImageMessageBuilder
     */
    public function buildImageBuilder(string $image_url, string $preview_url)
    {
        return new ImageMessageBuilder($image_url, $preview_url);
    }

    public function resolveUserText($message)
    {
        switch ($message['type']) {
            case self::TEXT_TYPE:
                $content = new TextMessageBuilder($message['text']);
                break;
            default:
                $content = new TextMessageBuilder('請輸入訊息');
                break;
        }
        return $content;
    }
}