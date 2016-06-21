<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <?php include(TEMPLATEPATH . '/includes/sitetitle.php'); ?>
    <meta name="description" content="">
    <meta name="keywords" content="志愿者">
    <link href="<?php bloginfo('template_url'); ?>/style.css" rel="stylesheet" media="screen" type="text/css">

    <script src="<?php bloginfo('template_url'); ?>/resource/js/jquery.min.js" type="text/javascript"></script>
</head>

<body>
<div class="header_top">
    <div class="w1000 center">
        <span id="time" class="time">志愿服务，助我成才！</span>
        <span><?php echo zzwu_printf_IPAddress(); ?></span>
        <div class="toplinks">
            <?php if ( is_user_logged_in() ) {
                global $current_user, $display_name , $user_email;
                wp_get_current_user();  ?>
                <a href="<?php echo admin_url().'profile.php'; ?>" target="_blank">欢迎您，<?php echo $current_user->display_name; ?></a>
            <?php  }else{ ?>
                <span class="time">欢迎您的访问！</span>
            <?php  }?>
        </div>
    </div>
</div>

<div id="Header" class="Header">
    <div id="head_line1">
        <div class="head_l">
            <div class="logo_img">
                <a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('template_url'); ?>/resource/images/logo_img.gif"></a>
            </div>
            <div class="logo_zi">
                <img src="<?php bloginfo('template_url'); ?>/resource/images/logo_zi.gif">
            </div>
        </div>
        <div class="head_r">
            <img src="<?php bloginfo('template_url'); ?>/resource/images/head_img.gif">
        </div>
    </div>
    <?php
    wp_nav_menu(
        array(
            'theme_location' => 'primary',
            'menu_id' => 'head_menu',
            'wa;ker' => new zzwu_description_walker()
        )
    );
    ?>

</div>