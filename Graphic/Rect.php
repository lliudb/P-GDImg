<?php
/**
 *
 * Rect.php Created by ldb at 2019/5/9T11:34 AM
 */

namespace Liudb\Graphic;


class Rect
{
    private $start_x = 0;
    private $start_y = 0;
    private $end_x = 0;
    private $end_y = 0;

    private $border_left = 0;
    private $border_top = 0;
    private $border_bottom = 0;
    private $border_right = 0;

    private $padding_left = 0;
    private $padding_top = 0;
    private $padding_bottom = 0;
    private $padding_right = 0;

    private $margin_left = 0;
    private $margin_top = 0;
    private $margin_bottom = 0;
    private $margin_right = 0;

    private $keep_size = TRUE; //保持box的size不变

    /**
     * Rect constructor.
     *
     * @param int $start_x
     * @param int $start_y
     * @param int $end_x
     * @param int $end_y
     *
     * @param bool $keep_size 保持box的size不变
     *
     * @throws \Exception
     */
    function __construct($start_x = 0,  $start_y = 0,  $end_x = 0,  $end_y = 0, $keep_size = TRUE) {
        if ($start_y > $end_y || $start_x > $end_x) {
            throw new \Exception("无法创建", 422);
        }
        $this->start_x = $start_x;
        $this->start_y = $start_y;
        $this->end_x = $end_x;
        $this->end_y =  $end_y;

        $this->keep_size = $keep_size;
    }

    public function setPaddingLeft($num = 0)
    {
        $this->padding_left = $num;
        return $this;
    }

    public function setPaddingTop($num = 0)
    {
        $this->padding_top = $num;
        return $this;
    }

    public function setPaddingRight($num = 0)
    {
        $this->padding_right = $num;
        return $this;
    }

    public function setPaddingBottom($num = 0)
    {
        $this->padding_bottom = $num;
        return $this;
    }

    /**
     * 获取内边距
     * @return array
     */
    public function getPadding()
    {
        return [$this->padding_left, $this->padding_top, $this->padding_right, $this->padding_bottom];
    }

    /**
     * 设置水平方向的内边距
     * @param int $padding
     *
     * @return $this
     */
    public function setHorizonPadding($padding = 0)
    {
        $this->padding_left = $this->padding_right = $padding;
        return $this;
    }

    /**
     * 设置垂直方向的内边距
     * @param int $padding
     *
     * @return $this
     */
    public function setVerticalPadding($padding = 0)
    {
        $this->padding_top = $this->padding_bottom = $padding;
        return $this;
    }


    /**
     * 设置内边距
     * @param int $left
     * @param int $top
     * @param int $right
     * @param int $bottom
     *
     * @return $this
     */
    public function setPadding($left = 0, $top = 0, $right = 0, $bottom = 0)
    {
        $this->padding_left = $left;
        $this->padding_top = $top ? : $left;
        $this->padding_bottom = $bottom ? : $left;
        $this->padding_right = $right ? : $left;
        return $this;
    }




    public function setMarginLeft($num = 0)
    {
        $this->margin_left = $num;
        return $this;
    }

    public function setMarginTop($num = 0)
    {
        $this->margin_top = $num;
        return $this;
    }

    public function setMarginRight($num = 0)
    {
        $this->margin_right = $num;
        return $this;
    }

    public function setMarginBottom($num = 0)
    {
        $this->margin_bottom = $num;
        return $this;
    }

    /**
     * 获取内边距
     * @return array
     */
    public function getMargin()
    {
        return [$this->margin_left, $this->margin_top, $this->margin_right, $this->margin_bottom];
    }

    /**
     * 设置水平方向的外边距
     * @param int $padding
     *
     * @return $this
     */
    public function setHorizonMargin($padding = 0)
    {
        $this->margin_left = $this->margin_right = $padding;
        return $this;
    }

    /**
     * 设置垂直方向的外边距
     * @param int $padding
     *
     * @return $this
     */
    public function setVerticalMargin($padding = 0)
    {
        $this->margin_top = $this->margin_bottom = $padding;
        return $this;
    }


    /**
     * 设置外边距，设置外边距时默认影响端点位置
     * @param int  $left
     * @param int  $top
     * @param int  $right
     * @param int  $bottom
     *
     * @return $this
     */
    public function setMargin($left = 0, $top = 0, $right = 0, $bottom = 0)
    {
        $this->margin_left = $left;
        $this->margin_top = $top ? : $left;
        $this->margin_bottom = $bottom ? : $left;
        $this->margin_right = $right ? : $left;


        $this->start_x = $this->start_x + $this->margin_left;
        $this->start_y = $this->start_y + $this->margin_top;
        if (!$this->keep_size) {//自动调整情况，根据外边框调整box的size
            $this->end_x = $this->end_x - $this->margin_right;
            $this->end_y = $this->end_y - $this->margin_bottom;
        } else {
            $this->end_x = $this->end_x + $this->margin_left + $this->margin_right;
            $this->end_y = $this->end_x + $this->margin_top + $this->margin_bottom;
        }

        return $this;
    }




    /**
     * 设置边框宽度
     * @param int $left
     * @param int $top
     * @param int $right
     * @param int $bottom
     *
     * @return $this
     */
    public function setBorderWidth($left = 0, $top = 0, $right = 0, $bottom = 0)
    {
        $this->border_left = $left;
        $this->border_top = $top ? : $left;
        $this->border_bottom = $bottom ? : $left;
        $this->border_right = $right ? : $left;
        return $this;
    }


    /**
     * 获取边框宽度
     * @return array
     */
    public function getBorderWidth()
    {
        return [$this->border_left, $this->border_top, $this->border_bottom, $this->border_right];
    }

    /**
     * 获取端点
     * @return array
     */
    public function getPoints()
    {
        return [$this->start_x, $this->start_y, $this->end_x, $this->end_y];
    }


    /**
     * 获取内边距的顶点
     * @return array
     */
    public function getPaddingPoints()
    {

        return [
            $this->start_x + $this->padding_left,
            $this->start_y + $this->padding_top,
            $this->end_x - $this->padding_right,
            $this->end_y - $this->padding_bottom
        ];
    }


    /**
     * 获取外边距的顶点
     * @return array
     */
    public function getMarginPoints()
    {
        return [
            $this->start_x - $this->margin_left,
            $this->start_y - $this->margin_top,
            $this->end_x + $this->margin_right,
            $this->end_y + $this->margin_bottom
        ];
    }

    /**
     * 获取水平方向的宽度
     * @return int
     */
    public function getWidth()
    {
        return $this->end_x - $this->start_x;
    }

    /**
     * 获取带外边距的高度
     * @return int
     */
    public function getWidthWithMargin()
    {
        return ($this->end_x - $this->start_x) + ($this->margin_left + $this->margin_right);
    }

    /**
     * 获取水平方向不带内边距的宽度
     * @return int
     */
    public function getWidthWithoutPadding()
    {
        return ($this->end_x - $this->start_x) - ($this->padding_left + $this->padding_right);
    }



    /**
     * 获取垂直方向的高度
     * @return int
     */
    public function getHeight()
    {
        return $this->end_y - $this->start_y;
    }

    /**
     * 获取水平方向不带内边距的高度
     * @return int
     */
    public function getHeightWithoutPadding()
    {
        return ($this->end_y - $this->start_y) - ($this->padding_top + $this->padding_bottom);
    }

    /**
     * 获取带外边距的高度
     * @return int
     */
    public function getHeightWithMargin()
    {
        return ($this->end_y - $this->start_y) + ($this->margin_top + $this->margin_bottom);
    }


}