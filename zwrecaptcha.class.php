<?php

session_start();

require_once 'recaptcha.class.php';

$recaptcha = new ZW_Recaptcha('times.ttf');  //实例化一个对象
$recaptcha->createImage();
$_SESSION['authnum_code'] = $recaptcha->getCode();//验证码保存到SESSION中