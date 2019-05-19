<?php
/**
 *
 * Color.php Created by ldb at 2019/5/9T11:17 AM
 */

namespace Liudb\Graphic;


class Color
{
    private $red = 0;
    private $green = 0;
    private $blue = 0;
    //0 表示完全不透明，127 表示完全透明
    private $alpha = 0;


    function __construct($red = 0, $green = 0, $blue = 0, $alpha = 0) {
        $this->red = $red % 256;
        $this->green = $green % 256;
        $this->blue =  $blue % 256;
        $this->alpha = intval($alpha * 127);
    }

    /**
     * @return int
     */
    public function getAlpha()
    {
        return $this->alpha;
    }

    /**
     * @return int
     */
    public function getBlue()
    {
        return $this->blue;
    }

    /**
     * @return int
     */
    public function getGreen()
    {
        return $this->green;
    }

    /**
     * @return int
     */
    public function getRed()
    {
        return $this->red;
    }

    /**
     * @param Color $from
     * @param Color $to
     * @param int   $len
     *
     * @return static
     */
    static function getStep(self $from, self $to, $len = 0)
    {
        $red = ($to->getRed() - $from->getRed() ) / $len;
        $green = ($to->getGreen() - $from->getGreen() ) / $len;
        $blue = ($to->getBlue() - $from->getBlue() ) / $len;
        $alpha = ($to->getAlpha() - $from->getAlpha() ) / $len;

        return compact('red', 'green', 'blue', 'alpha');
    }

    /**
     * @param $step
     *
     * @return $this
     */
    public function addStep($step)
    {
        $this->red = ($this->red + $step['red']) >= 255 ? 255: ($this->red + $step['red']);
        $this->green = ($this->green + $step['green']) >= 255 ? 255: ($this->green + $step['green']);
        $this->blue = ($this->blue + $step['blue']) >= 255 ? 255: ($this->blue + $step['blue']);
        $this->alpha = ($this->alpha + $step['alpha']) >= 127 ? 127: ($this->alpha + $step['alpha']);

        return $this;
    }

    /**
     * @param string $color
     *
     * @return static
     * @throws \Exception
     */
    public function getHexToRgba($color = '')
    {
        $color = str_replace('#', '', $color);
        if (strlen($color) != 6) {
            throw new \Exception('', 422);
        }
        $rgba = array();
        for ($x=0; $x < 4; $x++){
            $rgba[$x] = hexdec(substr($color, (2*$x),2));
        }
        return new static($rgba[0], $rgba[1], $rgba[2], ($rgba[3] / 255));
    }

    /**
     * @param $img
     *
     * @return int
     */
    public function getColor(&$img)
    {
        return imagecolorallocatealpha($img, $this->red, $this->green, $this->blue, $this->alpha);
    }

    /**
     * @param $img
     *
     * @return int
     */
    public function setColor(&$img)
    {
        return imagecolorallocatealpha($img, $this->red, $this->green, $this->blue, $this->alpha);
    }

    /**
     * 设置透明通道，0 表示完全不透明，127 表示完全透明。
     * @param $alpha
     */
    public function setAlpha($alpha)
    {
        $this->alpha = $alpha * 127;
    }

    /**
     * 颜色 #000000FF
     * @return static
     */
    static public function alpha()
    {
        return new static(0, 0, 0, 1.0);
    }

    /**
     * 颜色 #000000FF
     * @return static
     */
    static public function forAlpha()
    {
        return new static(245, 71, 194, 0);
    }

    /**
     * 颜色 #3F4345FF
     * @return static
     */
    static public function black_3F4345FF()
    {
        return new static(63, 67, 69, 0);
    }

    /**
     * 颜色 #3F434588
     * @return static
     */
    static public function black_3F434588()
    {
        return new static(63, 67, 69, 0.8);
    }

    /**
     * 颜色 #FFFFFFFF
     * @return static
     */
    static public function white()
    {
        return new static(255, 255, 255, 0);
    }

    /**
     * 颜色 ##B3B3B3FF
     * @return static
     */
    static public function gray_B3B3B3FF()
    {
        return new static(179, 179, 179, 0);
    }

    /**
     * # E6E6E6FF
     * @return static
     */
    static public function gray_E6E6E6FF()
    {
        return new static(230, 230, 230,  0);
    }

    /**
     * 颜色 #7BB5F7FF
     * @return static
     */
    static public function blue_7BB5F7FF()
    {
        return new static(123, 181, 247,  0);
    }

    /**
     * 颜色 #457EECFF
     * @return static
     */
    static public function blue_457EECFF()
    {
        return new static(69, 126, 236,  0);
    }

    /**
     * #22757bFF
     * @return static
     */
    static public function green_22757bFF()
    {
        return new static(34, 117, 123,  0);
    }

    /**
     * #34ac91FF
     * @return static
     */
    static public function green_34ac91FF()
    {
        return new static(52, 172, 145,  0);
    }

}
