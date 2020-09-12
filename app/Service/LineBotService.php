<?php


namespace App\Service;


use LINE\LINEBot\Constant\Flex\ComponentAlign;
use LINE\LINEBot\Constant\Flex\ComponentBorderWidth;
use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentLayout;
use LINE\LINEBot\Constant\Flex\ComponentMargin;
use LINE\LINEBot\Constant\Flex\ComponentPosition;
use LINE\LINEBot\Constant\Flex\ComponentSpaceSize;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\Constant\Flex\ContainerDirection;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
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

    /**
     * 產生 template messages
     * @reference: https://developers.line.biz/zh-hant/reference/messaging-api/#template-messages
     * @return MultiMessageBuilder
     */
    public function generateMenuTemplate()
    {
        $builders = new MultiMessageBuilder();
        $action = new MessageTemplateActionBuilder("label", "text");
        $action1 = new MessageTemplateActionBuilder("label1", "text1");
        $action2 = new MessageTemplateActionBuilder('label2', 'text2');

        $column = new CarouselColumnTemplateBuilder('column_title', 'column', null, [
            $action,
            $action1,
            $action2
        ]);

        $builder = new TemplateMessageBuilder('請選擇以下選單',
            new CarouselTemplateBuilder([$column]));

        $builders->add($builder);
        return $builders;
    }

    /**
     * 產生 flex message
     * @return FlexMessageBuilder
     */
    public function generateFlexMessage()
    {
        $componentBuilder = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setContents([
                (new TextComponentBuilder('Hello, World!'))->setColor("#ffffff"),
            ])
            ->setFlex(3)
            ->setSpacing(ComponentSpacing::SM)
            ->setMargin(ComponentMargin::XS)
            ->setAction(new MessageTemplateActionBuilder('ok', 'OK'))
            ->setPaddingAll(ComponentSpacing::NONE)
            ->setPaddingTop('5%')
            ->setPaddingBottom('5px')
            ->setPaddingStart(ComponentSpacing::LG)
            ->setPaddingEnd(ComponentSpacing::XL)
            ->setBackgroundColor('#000000')
            ->setBorderColor('#000000')
            ->setBorderWidth(ComponentBorderWidth::SEMI_BOLD)
            ->setCornerRadius(ComponentSpacing::XXL)
            ->setPosition(ComponentPosition::RELATIVE)
            ->setOffsetTop('4px')
            ->setOffsetBottom('4%')
            ->setOffsetStart(ComponentSpacing::NONE)
            ->setOffsetEnd(ComponentSpacing::SM);

        $container = new BubbleContainerBuilder($direction = ContainerDirection::LTR ,$bodyComponentBuilder = $componentBuilder);
        $content = new FlexMessageBuilder('test', $container);
        return $content;
    }

    public function resolveUserText($message)
    {
        switch ($message['type']) {
            case self::TEXT_TYPE:
                //$content = new TextMessageBuilder($message['text']);
                $content = $this->generateMenuTemplate();
                break;
            default:
                $content = $this->generateMenuTemplate();
                break;
        }

        return $content;
    }
}