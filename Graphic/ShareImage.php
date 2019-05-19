<?php
/**
 *
 * ShareImage.php Created by ldb at 2019/5/9T10:51 AM
 */
namespace Liudb\Graphic;


class ShareImage
{
    private $w;
    private $h;
    private $type;
    private $img;

    function __construct($type = 'png', $w = 750, $h = 300) {
        if (!in_array($type, ['png', 'jpeg', 'jpg'])) {
            throw new \Exception('错误的类型', 422);
        };
        $this->w = $w;
        $this->h = $h;
        $this->type = $type;
        $this->img = imagecreatetruecolor($w,$h);
        imagesavealpha($this->img, TRUE);
    }


    /**
     * @param string $url
     *
     * @return ShareImage
     * @throws \Exception
     */
    private function getImgByUrl($url = '')
    {
        return imagecreatefromstring(file_get_contents($url));
    }

    /**
     * 重置图像资源
     * @param $img
     *
     * @return $this
     */
    public function reset($img)
    {
        if (is_string($img)) {
            if (preg_match("/^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?/i",$img)) {
                $img = $this->getImgByUrl($img);
            }
            $img = imagecreatefromstring($img);
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $new_img = imagecreatetruecolor($this->w, $this->h);
        imagesavealpha($new_img, TRUE);
        imagecopy($new_img, $img, 0,0,0,0, $w, $h);

        $this->img = $new_img;
        $this->w = $w;
        $this->h = $h;
        $this->type = 'png';

        return $this;
    }

    /**
     * @return int
     */
    public function getH()
    {
        return $this->h;
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @return int
     */
    public function getW()
    {
        return $this->w;
    }


    public function __destruct()
    {
        if (is_resource($this->img))
        {
            imagedestroy($this->img);
        }
    }

    /**
     * @return resource
     */
    public function getImg()
    {
        return $this->img;
    }


    /**
     * 输出图像或者路径
     * @param string $dir
     * @param string $key
     *
     * @return resource|string
     */
    public function outputImg($dir = '', $key = '')
    {
        if ($dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            $fpath = $dir . $key;
        }
        switch ($this->type) {
            case 'png':
                isset($fpath) ? imagepng($this->img, $fpath) : imagepng($this->img);
                break;
            case 'jpeg':
            case 'jpg':
                isset($fpath) ? imagejpeg($this->img, $fpath) : imagejpeg($this->img);
                break;
            default:
                isset($fpath) ? image2wbmp($this->img, $fpath) : image2wbmp($this->img);
        }
        imagedestroy($this->img);
        return isset($fpath) ? $fpath : $this->img;
    }

    /**
     * @param       $cx
     * @param       $cy
     * @param int   $radius
     * @param int   $thickness
     * @param Color $color
     *
     * @return $this
     */
    public function drawRing($cx, $cy, $radius = 0, $thickness = 5, Color $color)
    {
        for ($i = 0; $i < $thickness; $i++) {
            imagefilledarc($this->img, $cx, $cy, $radius + $i, $radius + $i, 0, 360, $color->getColor($this->img), IMG_ARC_NOFILL);
        }
        return $this;
    }

    /**
     * 指定矩形框内线性渐变
     * @param Color  $from_color
     * @param Color  $to_color
     * @param Rect   $rect
     * @param string $direct vertical/horizon 横向，纵向渐变
     *
     * @return $this
     */
    public function linearGradient(Color $from_color, Color $to_color, Rect $rect, $direct = 'vertical')
    {
        list($s_x, $s_y, $e_x, $e_y) = $rect->getPoints();

        if ($direct == 'horizon') {
            $step = Color::getStep($from_color, $to_color, $rect->getWidth());
            for ($i = 0; $i < $rect->getWidth(); $i++) {
                $color = $from_color->addStep($step);
                imagefilledrectangle($this->img, $s_x + $i, $s_y, $s_x + $i + 1, $e_y, $color->getColor($this->img));
            }
        } else if ($direct == 'vertical') {
            $step = Color::getStep($from_color, $to_color, $rect->getHeight());
            for ($i = 0; $i < $rect->getHeight(); $i++) {
                $color = $from_color->addStep($step);
                imagefilledrectangle($this->img, $s_x, $s_y + $i, $e_x, $s_y + $i + 1, $color->getColor($this->img));
            }
        }

        return $this;
    }


    /**
     * 设置背景颜色
     * @param Color $color
     *
     * @return $this
     */
    public function setBackground(Color $color)
    {
        imagesavealpha($this->img, TRUE);

        $color = $color->setColor($this->img);
        imagefill($this->img, 0, 0, $color);

        return $this;
    }


    /**
     * @param Text  $text
     * @param Color $color
     * @param Rect  $rect
     * @param bool  $is_force  是否强制在矩形框内渲染
     *
     * @return $this
     */
    public function setText(Text $text, Color $color, Rect $rect, $is_force = FALSE)
    {
        $color = $color->setColor($this->img);

        list($s_x, $s_y, $e_x, $e_y) = $rect->getPoints();
        list($p_l, $p_t) = $rect->getMargin();

        $size = $text->getSize();
        imagettftext($this->img, $size, 0, $s_x + $p_l, $s_y + $p_t + $size, $color, $text->getFont(), $text->getText());

        return $this;
    }


    /**
     * @param Rect  $rect
     * @param Color $color
     * @param bool  $is_fill
     *
     * @return $this
     */
    public function drawRect(Rect $rect, Color $color, $is_fill = TRUE)
    {
        list($m_l, $m_t, $m_r, $m_b) = $rect->getMargin();
        list($ps_x, $ps_y, $pe_x, $pe_y) = $rect->getPoints();

        $fill = $color->getColor($this->img);
        if ($is_fill) {
            $w = $rect->getWidth();
            $h = $rect->getHeight();
            imagefilledrectangle($this->img, $ps_x + $m_l, $ps_y + $m_t, $ps_x + $w, $ps_y + $h, $fill);
        } else {
            list($l, $t, $r, $b) = $rect->getBorderWidth();
            //左边
            imagefilledrectangle($this->img, $m_l + $ps_x - $l, $m_t + $ps_y - $t, $m_l + $m_r + $ps_x, $m_t + $m_b + $pe_y + $b, $fill);
            //上边
            imagefilledrectangle($this->img, $m_l + $ps_x, $m_t + $ps_y - $t, $m_l + $m_r + $pe_x, $m_t + $m_b + $ps_y, $fill);
            //右边
            imagefilledrectangle($this->img, $m_l + $pe_x, $m_t + $ps_y - $t, $m_l + $m_r + $pe_x + $r, $m_t + $m_b + $pe_y + $b, $fill);
            //底边
            imagefilledrectangle($this->img, $m_l + $ps_x, $m_t + $pe_y, $m_l + $m_r + $pe_x, $m_t + $m_b + $pe_y + $b, $fill);
        }

        return $this;
    }


    /**
     * @param Rect  $rect
     * @param Color $color
     *
     * @return $this
     */
    public function drawLine(Rect $rect, Color $color)
    {
        list($ps_x, $ps_y, $pe_x, $pe_y) = $rect->getPoints();
        //左边
        imagefilledrectangle($this->img, $ps_x, $ps_y, $pe_x, $pe_y, $color->getColor($this->img));

        return $this;
    }


    /**
     * @param        $res
     * @param Rect   $rect
     * @param string $mode horizon/vertical/full => 水平、垂直、铺满
     *
     * @return $this
     */
    public function repeat($res, Rect $rect, $mode = 'horizon')
    {
        $res_w = imagesx($res);
        $res_h = imagesy($res);


        $rect_h = $rect->getHeightWithoutPadding();
        $rect_w = $rect->getWidthWithoutPadding();
        list($ps_x, $ps_y, $pe_x, $pe_y) = $rect->getPaddingPoints();
        $temp = imagecreatetruecolor($rect_w, $rect_h);
        imagesavealpha($temp, TRUE);
        imagefill($temp, 0, 0, Color::alpha()->getColor($temp));

        if ($mode == 'vertical') {
            $repeat_time = ceil($rect_h / $res_h);
            for($i = 0; $i < $repeat_time; $i++) {
                imagecopy($temp, $res, 0,$i * $res_h,  0, 0, $res_w, $res_h);
            }
        }else if ($mode == 'horizon') {
            $repeat_time = ceil($rect_w / $res_w);
            for($i = 0; $i < $repeat_time; $i++) {
                imagecopy($temp, $res, $i * $res_w, 0,0, 0, $res_w, $res_h);
            }
        }

        imagecopy($this->img, $temp, $ps_x, $ps_y, 0, 0, $rect_w, $rect_h);

        return $this;
    }


    /**
     * 合并图片
     * @param        $src
     * @param        $dst_x
     * @param        $dst_y
     * @param        $src_x
     * @param        $src_y
     * @param int    $point_w
     * @param int    $point_h
     *
     * @return $this
     */
    public function copy($src, $dst_x, $dst_y, $src_x, $src_y, $point_w = 0, $point_h = 0)
    {
        //获取点图片的宽高
        $point_w = $point_w ? : imagesx($src);
        $point_h = $point_h ? : imagesy($src);

        imagecopy($this->img, $src, $dst_x, $dst_y, $src_x, $src_y, $point_w, $point_h);
        return $this;
    }

    /**
     * @param string $align 对齐方式 lt、t、rt、l、r、lb、b、rb、c => 左上、上、右上、左、右、左下、下、右下、中心
     * @param        $src
     * @param        $src_x
     * @param        $src_y
     * @param string $align
     *
     * @return $this
     */
    public function copyAlign($src, $src_x, $src_y, $align = 'c')
    {
        //获取点图片的宽高
        $point_w = imagesx($src);
        $point_h = imagesy($src);

        $offset_x = 0;
        $offset_y = 0;
        switch ($align) {
            case 't'://上
                $offset_x = ceil(($this->w - $point_w) / 2);
                break;
            case 'rt'://右上
                $offset_x = $this->w - $point_w;
                break;
            case 'l'://左
                $offset_y = ceil(($this->h - $point_h) / 2);
                break;
            case 'r'://右
                $offset_x = $this->w - $point_w;
                $offset_y = ceil(($this->h - $point_h) / 2);
                break;
            case 'lb'://左下
                $offset_y = $this->h - $point_h;
                break;
            case 'b'://下
                $offset_x = ceil(($this->w - $point_w) / 2);
                $offset_y = $this->h - $point_h;
                break;
            case 'rb'://右下
                $offset_x = $this->w - $point_w;
                $offset_y = $this->h - $point_h;
                break;
            case 'c'://中心
                $offset_x = ceil(($this->w - $point_w) / 2);
                $offset_y = ceil(($this->h - $point_h) / 2);
            default:
        }

        imagecopy($this->img, $src, $offset_x, $offset_y, $src_x, $src_y, $point_w, $point_h);
        return $this;
    }

    /**
     * 截圆行图
     * @return $this
     */
    public function crop()
    {
        // Intializes destination image
        // Create a black image with a transparent ellipse, and merge with destination
        $mask = imagecreatetruecolor($this->w, $this->h);
        $forAlpha = Color::forAlpha();

        $maskTransparent = $forAlpha->getColor($this->img);
        imagecolortransparent($mask, $maskTransparent);

        imagefilledellipse($mask, $this->w / 2, $this->h / 2, $this->w, $this->h, $maskTransparent);
        imagecopymerge($this->img, $mask, 0, 0, 0, 0, $this->w, $this->h, 100);

        imagefill($this->img, 0, 0, $maskTransparent);
        imagefill($this->img, $this->w - 1, 0, $maskTransparent);
        imagefill($this->img, 0, $this->h - 1, $maskTransparent);
        imagefill($this->img, $this->w - 1, $this->h - 1, $maskTransparent);
        imagecolortransparent($this->img, $maskTransparent);

        return $this;
    }


    /**
     * 截圆角
     * @param int $radius
     *
     * @return $this
     */
    public function radius($radius = 0)
    {
        $radius = $radius == 0 ? (min($this->w, $this->h) / 2) : $radius;
        // lt(左上角)
        $lt_corner	= $this->getRounderCorner($radius);
        imagecopymerge($this->img, $lt_corner, 0, 0, 0, 0, $radius, $radius, 100);
        // lb(左下角)
        $lb_corner	= imagerotate($lt_corner, 90, 0);
        imagecopymerge($this->img, $lb_corner, 0, $this->h - $radius, 0, 0, $radius, $radius, 100);
        // rb(右上角)
        $rb_corner	= imagerotate($lt_corner, 180, 0);
        imagecopymerge($this->img, $rb_corner, $this->w - $radius, $this->h - $radius, 0, 0, $radius, $radius, 100);
        // rt(右下角)
        $rt_corner	= imagerotate($lt_corner, 270, 0);
        imagecopymerge($this->img, $rt_corner, $this->w - $radius, 0, 0, 0, $radius, $radius, 100);

        $fgcolor	= Color::forAlpha()->getColor($this->img);
        imagecolortransparent($this->img, $fgcolor);

        return $this;
    }

    /**
     * 获取圆角图形
     * @param int $radius
     *
     * @return resource
     */
    private function getRounderCorner($radius = 0)
    {
        $img	 = imagecreatetruecolor($radius, $radius);	// 创建一个正方形的图像
        $bgcolor	= Color::forAlpha()->getColor($this->img);
        $fgcolor	= Color::white()->getColor($this->img);
        imagefill($img, 0, 0, $bgcolor);
        // fgcolor：指定颜色
        imagefilledarc($img, $radius, $radius, $radius*2, $radius*2, 180, 270, $fgcolor, IMG_ARC_PIE);
        // 将弧角图片的颜色设置为透明
        imagecolortransparent($img, $fgcolor);

        return $img;
    }

    /**
     * 缩略图
     * @param     $new_w
     * @param int $new_h
     *
     * @return $this
     */
    public function scale($new_w, $new_h = 0)
    {
        $new_h = $new_h ? :  ($this->h / $this->w) * $new_w;

        $new_img = imagecreatetruecolor($new_w, $new_h);
        imagecopyresampled($new_img,
                           $this->img,
                           0, // Center the image horizontally
                           0, // Center the image vertically
                           0, 0,
                           $new_w, $new_h,
                           $this->w, $this->h);

        imagedestroy($this->img);
        $this->img = $new_img;

        $this->w = $new_w;
        $this->h = $new_h;
        return $this;
    }


    /**
     * 使用卷积矩阵对图片进行变换
     * @param $matrix
     *
     * @return $this
     */
    public function convolution($matrix)
    {
        imageconvolution($this->img, $matrix, 1, 127);
        return $this;
    }
}