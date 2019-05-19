## 这是一个自用库，由PHP GD拓展写成，其中借鉴和使用部分来自网上的代码

> 由于工作中收到的任务是要求生成一个分享图，在网上查找了一些图形库，未得到完全能满足需求的功能。如裁切出一张圆形的图片边缘透明。
所以我就使用原生的API写了一个库。

### 基础构成

* 画布对象ShareImage，保存图形基础信息和画布资源，采用链式设计
* 字体库对象Font，一个可有可无的类，大家可以直接使用字符串来选择字体库
* 矩形布局对象Rect，这是一个尝试性的对象设计，借鉴了前端CSS的盒模型设计，在一个基础矩形框上增加了外边距、内边距和边框，
我在实际使用时，主要用作辅助布局，免去了一些位置换算的工作
* 文本处理对象Text，主要是用来对文字的进行一些排版工作，也没啥可说的
* 色彩对象Color，主要是用来生成颜色对象，也没啥可说的

### API

* ShareImage
    - ``getImgByUrl()`` 通过URL获取图片并创建一个图像资源
    - ``reset()`` 重置这个画布的内容
    - ``getH()`` 获取画布的高
    - ``getW()`` 获取画布的宽
    - ``getType()`` 获取画布生成的类型，png、jpg、jpeg
    - ``getImg()`` 获取画布的图像资源
    - ``outputImg()`` 输出一张图片
    - ``drawRing()`` 绘制一个圆环
    - ``linearGradient()`` 对指定区域进行渐变渲染
    - ``setBackground()`` 设置背景颜色
    - ``setText()`` 绘制文本内容
    - ``drawRect()`` 绘制一个矩形，填充体积还是绘制边可选，注意，边框绘制为矩形外侧绘制设置边的宽度
    - ``drawLine()`` 绘制一条线，可以理解为填充一个矩形
    - ``repeat()`` 在一个矩形内平铺图片，水平、垂直（已完成）、铺满（太懒了，未实现）
    - ``copy()``  复制一个图像到当前画布对象
    - ``copyAlign()`` 复制一个图像到当前画布对象并对其，对齐方式 lt、t、rt、l、r、lb、b、rb、c => 左上、上、右上、左、右、左下、下、右下、中心
    - ``crop()`` 裁切成圆形，就是为了这个方法才写的这个库。。。
    - ``radius()``  裁切圆角
    - ``scale()`` 按比例变换图形
    - ``convolution() `` 一个3x3卷积矩阵对图像进行变换，一些库的特效如锐化等效果估计是使用该方法完成，自己尝试吧，我也不太会，不过挺好玩的
    
* Rect
    - ``setPaddingLeft()`` 设置内边距，左
    - ``setPaddingTop()`` 设置内边距，顶
    - ``setPaddingRight()`` 设置内边距，右
    - ``setPaddingBottom()`` 设置内边距，底
    - ``setHorizonPadding()``  设置内边距，左、右
    - ``setVerticalPadding()`` 设置内边距，顶、底
    - ``setPadding()`` 设置内边距
    - ``getPadding()``  获取内边距的list
    - ``setMarginLeft()`` 设置外边距，左
    - ``setMarginTop()`` 设置外边距，顶
    - ``setMarginRight()`` 设置外边距，右
    - ``setMarginBottom()`` 设置外边距，底
    - ``setHorizonMargin()`` 设置外边距，左、右
    - ``setVerticalMargin()`` 设置外边距，顶、底
    - ``setMargin()`` 设置外边距
    - ``getMargin()`` 获取外边距列表
    - ``setBorderWidth()`` 设置边的宽度
    - ``getBorderWidth()`` 获取边的宽度
    - ``getPoints()`` 获取顶点的信息
    - ``getPaddingPoints()`` 获取内边距的顶点
    - ``getMarginPoints()`` 获取外边距的顶点信息
    - ``getWidth()`` 获取盒子的宽度
    - ``getWidthWithMargin()`` 获取盒子外边距的宽度
    - ``getWidthWithoutPadding()`` 获取盒子内边距的宽度
    - ``getHeight()`` 获取盒子的高度
    - ``getHeightWithMargin()`` 获取盒子外边距的高度
    - ``getHeightWithoutPadding()`` 获取盒子内边距的高度

* Text
    - ``formatText()`` 根据一个Rect来调整文字的换行
    - ``getTextWidth()`` 获取文字的宽度
    - ``getTextHeight()`` 获取文字的高度
    - ``getText()`` 获取文本
    - ``getAngle()`` 获取文本角度
    - ``getFont()`` 获取文本字体
    - ``getSize()``  获取文本尺寸
    - ``getTextSize()`` 获取文本的box
    
### 不足

* emoji字符的支持，这个我暂时还没有找到合适的办法，还是说GD本身就不支持UNICODE的字符集，还有待验证，或者说哪位朋友有解决办法或者知道原因的麻烦不吝赐教

* 为了赶时间，并没有太多的功能加入，但是还算满足大部分场景的需求的开发。未来如果还会继续维护这个库的话😏，先挖个坑。
    - filter 也就是滤镜功能，这个是个可以尝试的内容
    - rotate 这个也是个基础需求，但是我没用上就没写上，后面会加上
    - radius 圆角功能裁出的边锯齿还比较严重，所以我还是希望能改善一下
    - cropRect 根据Rect来裁切图片，目前只有一个切圆，当然该功能可以通过其他操作代替，还是有一个更方便
    - 其他，比较狂拽酷炫吊炸天的功能，哈哈哈哈哈哈哈哈哈嗝
    
* 盒模型的完善，在完成功能需求的层面上来看还ok，但是总觉的有点不够fashion。 后面可能会考虑重写(；′⌒`)

