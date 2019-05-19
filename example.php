<?php

namespace Liudb\Graphic;

/**
 *
 * example.php Created by ldb at 2019/5/19T11:31 AM
 */
include __DIR__ . "/Graphic/Rect.php";
include __DIR__ . "/Graphic/Font.php";
include __DIR__ . "/Graphic/Color.php";
include __DIR__ . "/Graphic/Text.php";
include __DIR__ . "/Graphic/ShareImage.php";

function public_path($file) {
    return __DIR__ . $file;
}



/**
 * @param $src
 * @param $w
 * @param $h
 *
 * @return array
 */
function fixImageSize($src, $w, $h)
{
    $image = NULL;
    $temp_image = NULL;
    $base_scale_low = 0.5;
    $base_scale_up = 2.0;

    $scale = $w/$h;
    $scale_w = 686;
    $scale_h = ceil($scale_w / $w * $h);

    $align = 'c';
    if ($base_scale_low > $scale) {
        $align = 't';
        if ($scale_h > 800) {
            $scale_h = 800;
        }
        $temp_image = new ShareImage('png', $w, $h);
        $temp_image->reset($src)->scale($scale_w);
    } elseif ($scale > $base_scale_up) {
        $scale_h = 343;
        $temp_w = ceil($scale * $scale_h);
        $temp_image = new ShareImage('png', $w, $h);
        $temp_image->reset($src)->scale($temp_w, $scale_h);
        $align = 'l';
    } else {
        if ($scale_h > 800) {
            $scale_h = 800;
        }
        $temp_image = new ShareImage('png', $w, $h);
        $temp_image->reset($src)->scale($scale_w);
    }

    $image = new ShareImage('png', $scale_w, $scale_h);
    $image->copyAlign($temp_image->getImg(), 0, 0, $align);

    return [$image, $scale_w, $scale_h];
}


/**
 * 通过url获取
 * @param string $url
 *
 * @return resource
 */
function getImgByUrl($url = '')
{
    return imagecreatefromstring(file_get_contents($url));
}

//基础资源数据
$base_circle_bg = imagecreatefrompng(public_path('/images/shares/base_circle_bg.png'));
$bg_logo = imagecreatefrompng(public_path('/images/shares/bg_logo.png'));
$quotes = imagecreatefrompng(public_path('/images/shares/quotes.png'));
$logo = imagecreatefrompng(public_path('/images/shares/logo.png'));
$gradient_shadow = imagecreatefrompng(public_path('/images/shares/gradient_shadow.png'));
$avatar_shadow = imagecreatefrompng(public_path('/images/shares/avatar_shadow.png'));
$gradient_top_shadow = imagecreatefrompng(public_path('/images/shares/gradient_top_shadow.png'));
$gradient_bottom_shadow = imagecreatefrompng(public_path('/images/shares/gradient_bottom_shadow.png'));
$avatar_img = getImgByUrl("http://s-dev.lanqb.com/20170911/59b650d79e9e0.jpg?imageMogr2/auto-orient/size-limit/80k!/thumbnail/250x/gravity/Center/crop/189x189"); //"http://s-dev.lanqb.com/20170911/59b650d79e9e0.jpg?imageMogr2/auto-orient/size-limit/80k!/thumbnail/250x/gravity/Center/crop/189x189"
$qrcode = getImgByUrl('http://qr.topscan.com/api.php?text=http://m.lanqb.com/hyxq/work/cs');

$base_div_width = 686;
//基础高度
$base_div_blank_h = 164;
$quotes_blank_h = 120;
//
$display_h = -30;
if (isset($display_img) && is_resource($display_img)) {
    $display_h = imagesy($display_img);
    $display_w = imagesx($display_img);
    list($display_img, $display_w, $display_h) = fixImageSize($display_img, $display_w, $display_h);

    //用户作品
    $work_image = new ShareImage("png", $base_div_width, $display_h);
    $border_w   = 10;
    $work_image->setBackground(Color::black_3F4345FF())
               ->copyAlign($display_img->getImg(), 0, 0, 'c')
               ->drawRect((new Rect($border_w, $border_w, $base_div_width - $border_w, $display_h - $border_w))->setBorderWidth($border_w), Color::black_3F4345FF(), FALSE)
               ->copy($gradient_top_shadow, $border_w, $border_w, 0, 0)
               ->copy($gradient_bottom_shadow, $border_w, $display_h - 50, 0, 0)
               ->repeat($gradient_shadow, (new Rect(0, 0, $base_div_width, $display_h))->setPadding($border_w, 50, $border_w, 50), 'vertical');
}
//slogan
$qrcode_w = 140;
$qcode = new ShareImage('png', imagesx($qrcode), imagesy($qrcode));
$slogan = new ShareImage('png',$base_div_width, 200);
$slogan->setBackground(Color::white())
       ->drawLine((new Rect(0, 0,$base_div_width, 10, FALSE))->setMargin(32,5,32,4), Color::gray_E6E6E6FF())
       ->setText(
           new Text("长按二维码\n查看作品详情",18, Font::getConstFont(Font::PING_FONT5)),
           Color::gray_B3B3B3FF(),
           (new Rect(0, 0, $base_div_width, 200))->setMarginLeft(335)->setMarginTop(80)
       )
       ->copy($logo, 34, 74,0, 0)
       ->copy($qcode->reset($qrcode)->scale($qrcode_w)->getImg(), 505, 44,0, 0);

//切圆头像
$avatar_w = imagesx($avatar_img);
$avatar_h = imagesy($avatar_img);
$avatar = new ShareImage('png', $avatar_w,$avatar_h);
$avatar->setBackground(Color::green_34ac91FF())
       ->copy($avatar_img, 0, 0, 0, 0)
       ->scale(104)
       ->crop();

//大白底
$info_blank_h = 178;
$text_content_h = 400;
$info_bottom_blank_h = 60;

$text_box_h = $display_h + $info_blank_h + $quotes_blank_h;
$rect = (new Rect(0, 0, $base_div_width, $text_content_h))
    ->setMarginLeft(32)
    ->setMarginTop($text_box_h);
//处理文本
if (empty($desc)) {
    $desc = '这个人啥都没写！！！';
}
$text = new Text($desc, 28, Font::getConstFont(Font::PING_FONT3));

$text->formatText($rect, FALSE);

$base_div_height = $info_blank_h + $display_h + $quotes_blank_h + $text->getTextHeight() + $slogan->getH() + $info_bottom_blank_h;

$base_div = new ShareImage('png', $base_div_width, $base_div_height);
$base_div->setBackground(Color::white())
         ->setText(
             new Text('一个昵称', 22, Font::getConstFont(Font::PING_FONT3)),
             Color::black_3F4345FF(),
             (new Rect(0, 0, $base_div_width,  $info_blank_h))->setMarginLeft(48)->setMarginTop(72)
         )
         ->setText(
             new Text("发布了一个作品",20, Font::getConstFont(Font::PING_FONT5)),
             Color::black_3F4345FF(),
             (new Rect(0, 0, $base_div_width,  $info_blank_h))->setMarginLeft(48)->setMarginTop(116)
         );

if (isset($work_image)) {
    $base_div->copy($work_image->getImg(), 0, $info_blank_h, 0, 0);
}

$base_div->copy($quotes, 32, $display_h + $info_blank_h + 30, 0, 0)
         ->setText($text, Color::black_3F4345FF(), $rect)
         ->copy($slogan->getImg(), 0,  $text_box_h + 30 + $text->getTextHeight(), 0, 0)
         ->radius(20);

//基底
$big_base_div = $base_div_height + $base_div_blank_h;
$bigImg = new ShareImage('jpeg',750, $big_base_div);
$bigImg
    ->linearGradient(Color::blue_457EECFF(), Color::blue_7BB5F7FF(), new Rect(0, 0, 750, $big_base_div))
    ->copy($base_circle_bg, 0, 0, 0, 0)
    ->copy($bg_logo, 550, 38, 0, 0)
    ->copy($base_div->getImg(), 32, 110, 0, 0)
    ->copy($avatar->getImg(), 80, 56, 0, 0)
    ->copy($avatar_shadow, 68, 46, 0, 0);

header('content-type:image/png');
return $bigImg->outputImg();