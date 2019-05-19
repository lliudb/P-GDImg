<?php
/**
 *
 * Str.php Created by ldb at 2019/5/9T11:00 AM
 */

namespace Liudb\Graphic;


class Text
{

    private $size = 12;
    private $text;
    private $angle = 0;
    private $font = NULL;
    private $text_size = [];


    function __construct($text = '', $size = 14, $font = NULL, $angle = 0)
    {
        $this->text  = $text;
        $this->size = $size;
        $this->font  = $font ? $font : Font::getConstFont(Font::F_DEFAULT);
        $this->angle = $angle;
        $this->text_size = $this->offsetCorrect(imagettfbbox($size, 0, $font, $text));
    }


    /**
     * @param Rect $rect
     * @param      $is_force
     *
     * @return $this
     */
    public function formatText(Rect $rect, $is_force)
    {
        $width = $rect->getWidthWithoutPadding();
        $height = $rect->getHeightWithoutPadding();
        // 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
        $content = "";
        $letter = [];
// 将字符串拆分成一个个单字 保存到数组 letter 中
        for ($i = 0; $i < mb_strlen($this->text); $i++) {
            $l =  mb_substr($this->text, $i, 1, 'utf-8');
            if (strlen($l) >= 4) {
                $letter[] = "?";
            } else {
                $letter[] = $l;
            }
        }

        foreach ($letter as $l) {
            $teststr = $content . "" . $l;
            $fontBox = imagettfbbox($this->size, 0, $this->font, $teststr);
            // 判断拼接后的字符串是否超过预设的宽度
            if ((($fontBox[2] + $this->size) > $width) && ($content !== "")) {
                $content .= "\n";
            }
            if ($is_force == TRUE && ($fontBox[3] + $this->size) > $height) {
                break;
            }
            $content .= $l;
        }

        $this->text = $content;
        $fontBox = imagettfbbox($this->size, 0, $this->font, $content);
        $this->offsetCorrect($fontBox);

        return $this;
    }


    public function offsetCorrect($fontBox)
    {
        $this->text_size = [
            $fontBox[0],
            $fontBox[1] + $this->size,
            $fontBox[2],
            $fontBox[3] + $this->size,
            $fontBox[4],
            $fontBox[5] + $this->size,
            $fontBox[6],
            $fontBox[7] + $this->size
        ];
    }


    /**
     * 获取文本的宽度
     * @return int
     */
    public function getTextWidth()
    {
        return $this->text_size[2];
    }

    /**
     * 获取文本的高度
     * @return int
     */
    public function getTextHeight()
    {
        return $this->text_size[3];
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }


    /**
     * @return int
     */
    public function getAngle()
    {
        return $this->angle;
    }


    /**
     * @return Font|null
     */
    public function getFont()
    {
        return $this->font;
    }


    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }


    /**
     * @return array
     */
    public function getTextSize()
    {
        return $this->text_size;
    }
}