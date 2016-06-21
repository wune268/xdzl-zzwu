<?php
/**
 * 验证码生成类
 */
class ZW_Recaptcha
{
    private $charset = 'abcdefghijkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';//随机因子
    private $code;//验证码
    private $codelen = 4;//验证码长度
    private $width = 130;//宽度
    private $height = 30;//高度
    private $img;//图形资源句柄
    private $font;//指定的字体
    private $fontsize = 20;//指定字体大小
    private $fontcolor;//指定字体颜色

    public function __construct($fontpath = '', $height = 30, $width = 130)
    {
        $this->font = $fontpath;
        $this->height = $height;
        $this->width = $width;
    }

    private function createCode()
    {
        $len = strlen($this->charset)-1;
        for($i = 0; $i < $this->codelen; $i ++)
        {
            $this->code .= $this->charset[mt_rand(0, $len)];
        }
    }

    private function createBackGround()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(200,255), mt_rand(200,255), mt_rand(200,255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    //生成文字
    private function createFont() {
        $x = $this->width / $this->codelen;
        for ($i=0;$i<$this->codelen;$i++) {
            $this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
        }
    }
    //生成线条、雪花
    private function createLine() {
        //线条
        for ($i=0;$i<5;$i++) {
            $color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
            imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
        }
        //雪花
        for ($i=0;$i<50;$i++) {
            $color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
            imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
        }
    }
    //输出
    private function outPutimage() {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }
    //对外生成
    public function createImage() {
        $this->createBackGround();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPutimage();
    }
    //获取验证码
    public function getCode() {
        return strtolower($this->code);
    }
}

