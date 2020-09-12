<?php


namespace App\Service;


use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Jieba;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\Constant\Flex\ComponentAlign;
use LINE\LINEBot\Constant\Flex\ComponentBorderWidth;
use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentFontWeight;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectMode;
use LINE\LINEBot\Constant\Flex\ComponentImageSize;
use LINE\LINEBot\Constant\Flex\ComponentLayout;
use LINE\LINEBot\Constant\Flex\ComponentMargin;
use LINE\LINEBot\Constant\Flex\ComponentPosition;
use LINE\LINEBot\Constant\Flex\ComponentSpaceSize;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\Constant\Flex\ContainerDirection;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SpacerComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder;
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
        $action = new MessageTemplateActionBuilder("產品", "產品");
        $action1 = new MessageTemplateActionBuilder("熱門", "熱門");
        $action2 = new MessageTemplateActionBuilder('最新', '最新');

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

    public function generateContents()
    {
        $builders = [];
        for ($i = 1; $i <= 4; $i++) {
            $build = (new IconComponentBuilder('https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png'))
                ->setSize(ComponentImageSize::SM);

            $builders[] = $build;
        }
        $builders[] =  (new TextComponentBuilder("4.0"))
            ->setSize(ComponentFontSize::SM)
            ->setColor("#999999");
        return $builders;
    }

    /**
     * create product message
     * @return FlexMessageBuilder
     */
    public function generateProductMessage()
    {
        $image_builder = ImageComponentBuilder::builder()
            ->setUrl('https://scdn.line-apps.com/n/channel_devcenter/img/fx/01_1_cafe.png')
            ->setSize(ComponentImageSize::FULL)->setAspectMode(ComponentImageAspectMode::COVER)
            ->setAction(new UriTemplateActionBuilder(null, 'http://linecorp.com/'));

        $body = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setContents([
                (new TextComponentBuilder("Brown Cafe"))
                ->setWeight(ComponentFontWeight::BOLD),

                BoxComponentBuilder::builder()
                ->setLayout(ComponentLayout::BASELINE)
                ->setContents([
                    (new IconComponentBuilder('https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png'))
                    ->setSize(ComponentImageSize::SM),

                    (new IconComponentBuilder('https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png'))
                        ->setSize(ComponentImageSize::SM),

                    (new IconComponentBuilder('https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png'))
                        ->setSize(ComponentImageSize::SM),

                    (new IconComponentBuilder('https://scdn.line-apps.com/n/channel_devcenter/img/fx/review_gold_star_28.png'))
                        ->setSize(ComponentImageSize::SM),

                    (new TextComponentBuilder("4.0"))
                    ->setSize(ComponentFontSize::SM)
                    ->setColor("#999999")
                ]),

                BoxComponentBuilder::builder()
                ->setLayout(ComponentLayout::VERTICAL)
                ->setContents([
                    BoxComponentBuilder::builder()
                    ->setLayout(ComponentLayout::BASELINE)
                    ->setContents([
                        (new TextComponentBuilder("Place"))
                        ->setColor("#aaaaaa")
                        ->setSize(ComponentFontSize::SM)
                        ->setFlex(1),

                        (new TextComponentBuilder("Miraina Tower, 4-1-6 Shinjuku, Tokyo"))
                        ->setColor('#aaaaaa')
                        ->setSize(ComponentFontSize::SM)
                        ->setFlex(5),
                    ])
                    ->setSpacing(ComponentSpacing::SM),

                    BoxComponentBuilder::builder()
                    ->setLayout(ComponentLayout::BASELINE)
                    ->setContents([
                        (new TextComponentBuilder("Time"))
                        ->setFlex(1)
                        ->setColor("#aaaaaa")
                        ->setSize(ComponentFontSize::SM),

                        (new TextComponentBuilder("10:00 - 12:00"))
                        ->setFlex(5)
                        ->setColor("#aaaaaa")
                        ->setSize(ComponentFontSize::SM),
                    ])
                    ->setSpacing(ComponentSpacing::SM)
                ])
            ])
            ->setPaddingAll(ComponentSpacing::XXL);


        $footer = BoxComponentBuilder::builder()
                ->setLayout(ComponentLayout::VERTICAL)
                ->setContents([
                    ButtonComponentBuilder::builder()
                    ->setFlex(6)
                    ->setAction(new UriTemplateActionBuilder("CALL", "https://linecorp.com")),

                    ButtonComponentBuilder::builder()
                    ->setFlex(6)
                    ->setAction(new UriTemplateActionBuilder("WEBSITE", 'https://linecorp.com')),

                    SpacerComponentBuilder::builder()->setSize(ComponentSpacing::SM),
                ]);

        $container = BubbleContainerBuilder::builder()
                    ->setDirection(ContainerDirection::LTR)
                    ->setHero($image_builder)
                    ->setBody($body)
                    ->setFooter($footer);
        $content = new FlexMessageBuilder('test', $container);

        return $content;
    }

    public function resolveUserText($message)
    {
        $text = $message['text'];
        $array = []; //Jieba::cut($text);
        switch ($message['type']) {
            case self::TEXT_TYPE:
                //$content = new TextMessageBuilder($message['text']);
                if (count(array_intersect($array, ['選單', '菜單', 'menu'])) > 0) {
                    $content = $this->generateMenuTemplate();
                } else if (count(array_intersect($array, ['產品', 'product'])) > 0) {
                    $content = $this->generateProductMessage();
                } else {
                    //$content = new TextMessageBuilder($text);
                    $content = $this->generateMenuTemplate();
                }
                break;
            default:
                $content = $this->generateMenuTemplate();
                break;
        }

        return $content;
    }
}