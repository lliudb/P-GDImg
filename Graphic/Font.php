<?php
/**
 *
 * Font.php Created by ldb at 2019/5/9T10:59 AM
 */

namespace Liudb\Graphic;


class Font
{
    const  F_DEFAULT = "fonts/pingfang/苹方黑体-准-简.ttf";

    const  PING_FONT1 = "fonts/pingfang/苹方黑体-中粗-繁.ttf";
    const  PING_FONT2 = "fonts/pingfang/苹方黑体-中粗-简.ttf";
    const  PING_FONT3 = "fonts/pingfang/苹方黑体-中黑-简.ttf";
    const  PING_FONT4 = "fonts/pingfang/苹方黑体-中黑-繁.ttf";
    const  PING_FONT5 = "fonts/pingfang/苹方黑体-准-简.ttf";
    const  PING_FONT6 = "fonts/pingfang/苹方黑体-准-繁.ttf";
    const  PING_FONT7 = "fonts/pingfang/苹方黑体-极细-简.ttf";
    const  PING_FONT8 = "fonts/pingfang/苹方黑体-极细-繁.ttf";
    const  PING_FONT9 = "fonts/pingfang/苹方黑体-纤细-简.ttf";
    const  PING_FONT10 = "fonts/pingfang/苹方黑体-纤细-繁.ttf";
    const  PING_FONT11 = "fonts/pingfang/苹方黑体-细-简.ttf";
    const  PING_FONT12 = "fonts/pingfang/苹方黑体-细-繁.ttf";

    const  OTH_FONT1 = "fonts/others/rzzyt.ttf"; //锐字真言体
    const  OTH_FONT2 = "fonts/others/syht.otf"; //思源黑体
    const  OTH_FONT3 = "fonts/others/syst.otf"; //思源宋体

    private $font;

    function __construct($path = '') {
        $this->font = $path ? __DIR__ . '/' . $path : __DIR__ . '/' . static::F_DEFAULT;
    }


    /**
     * @return string
     */
    public function getFont()
    {
        return $this->font;
    }


    static public function getConstFont($path)
    {
        return __DIR__ . '/' . $path;
    }
}