<?php

session_start();

add_action( 'after_setup_theme', 'zzwu_theme_setup' );

function zzwu_theme_setup() {
    global $content_width;

    /* 为嵌入的视频等设置$content_width */
    if ( !isset( $content_width ) )
        $content_width = 600;

    /* 使主题支持自动feed链接 */
//    add_theme_support( 'automatic-feed-links' );

    /* 使主题支持文章缩略图（推荐图片） */
    add_theme_support( 'post-thumbnails' );

    /* 向 'init' 动作hook添加导航菜单功能 */
//    add_action( 'init', 'zzwu_register_menus' );
//
//    /* 向 'widgets_init' 动作hook添加侧栏功能 */
//    add_action( 'widgets_init', 'zzwu_register_sidebars' );
//
//    /* 在 'template_rediret' 动作hook上加载JS文件 */
//    add_action( 'template_redirect', 'zzwu_load_scripts' );

    //删除WordPress更新提示
    if (!current_user_can('edit_users')) {
        add_action('init', create_function('$a', "remove_action('init', 'wp_version_check');"), 2);
        add_filter('pre_option_update_core', create_function('$a', "return null;"));
    }
}

require_once (TEMPLATEPATH.'/includes/walker.php');

include_once(TEMPLATEPATH.'/includes/post-type.php');

/* 获取新闻 */
function zzwu_get_post($pageid, $numberposts = 30, $offset = 0)
{
    $args = array(
        'numberposts'     => $numberposts,
        'offset'          => $offset,
//        'category'        => '5',
        'orderby'         => 'post_date',
        'order'           => 'DESC',
        'meta_key'        => 'forbes',
        'meta_value'      => $pageid,
        'post_type'       => array('news', 'post'),
        'post_status'     => 'publish' );

    $previous_posts = get_posts($args);
    return $previous_posts;
}

//菜单默认显示首页
add_filter('wp_page_menu_args', 'zzwu_page_menu_args');
if (!function_exists('zzwu_page_menu_args')) {
    function zzwu_page_menu_args($args) {
        $args['show_home'] = true;

        return $args;
    }
}

// 获得轮播图
function zzwu_get_banner_slide(){
    $args = array('numberposts' => 5,'orderby' => 'post_date','order' => 'DESC','meta_key' => 'homescrol','meta_value' => '1','post_type' => array('news', 'post'));
    $previous_posts = get_posts($args);
    $str = "";
    if(is_array($previous_posts)){
        foreach($previous_posts as $post){
            setup_postdata($post);
            $thumbnail_img = zzwu_get_post_thumbnail_url($post->ID);
            $str .= '<li style="position: absolute; width: 1903px; left: 0px; top: 0px; display: none; background: url(&quot;'.$thumbnail_img.'&quot;) 50% 50% no-repeat;">
           <div class="pith">
            <h3 class="wow bounceInLeft" style="visibility: visible; animation-name: bounceInLeft;">——'.$post->post_title.'——</h3>
            <p class="wow bounceInRight" style="visibility: visible; animation-name: bounceInRight;">'.$post->post_content.'</p>
            <a data-wow-delay="0.5s" href="'.get_permalink($post->ID).'" class="v-more mtll  wow bounceInUp" target="_blank" style="visibility: visible; animation-delay: 0.5s; animation-name: bounceInUp;">查看更多</a>
           </div> </li> ';
        }
    }
    echo $str;
}

//获取文章特色图片
function zzwu_get_post_thumbnail_url($post_id){
    $post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
    $thumbnail_id = get_post_thumbnail_id($post_id);
    if($thumbnail_id ){
        $thumb = wp_get_attachment_image_src($thumbnail_id, 'full');
        return $thumb[0];
    }else{
        return false;
    }
}

// 获取当前页面下的所有分类
function zzwu_get_page_class($pagename)
{
    global $wpdb;
    $str = '';

    $pageclass = $wpdb->get_results(sprintf('SELECT a.id, a.termid, a.classifyname FROM zw_classify AS a JOIN zw_posts AS b ON a.pageid = b.ID WHERE b.post_title = "%s" ORDER BY a.termid ASC ', $pagename));
    if(count($pageclass))
    {
        foreach($pageclass as $pageclassvalue)
        {
            $str .= '<li ><div ><a href="javascript:void(0);" onclick="js_method('.$pageclassvalue->termid.')">'.$pageclassvalue->classifyname.'</a></div></li>';
        }
    }
    echo $str;
}

// 获取分会页面下的所有分类
function zzwu_get_page_class_fenhui($pagename, $limit = 10, $type = null)
{
    global $wpdb;
    $str = '';

    $fenhui = new stdClass();

    $pageclass = $wpdb->get_results(sprintf('SELECT a.id, a.termid, a.classifyname, b.guid FROM zw_classify AS a JOIN zw_posts AS b ON a.pageid = b.ID WHERE b.post_title = "%s" ORDER BY a.termid ASC LIMIT %s ', $pagename, $limit));
    if(isset($type))
    {
        return $pageclass;
    }
    else
    {
        if(count($pageclass))
        {
            $fenhui->url = $pageclass[0]->guid;
            foreach($pageclass as $pageclassvalue)
            {
                $str .= '<li><a href="'.$pageclassvalue->guid.'&wuclass='.$pageclassvalue->termid.'">'.zzwu_substr_not_hot($pageclassvalue->classifyname, 7).'</a></li>';
            }
        }
        $fenhui->str = $str;
        return $fenhui;
    }
}

/** 微博时间格式化显示 **/
function zzwu_time_since($post) {
    $time_diff = current_time('timestamp') - get_the_time('U', $post);
    if($time_diff <= 60){
        return '刚刚';
    }else{
        if( $time_diff >= 60 && $time_diff <= 172800 ){  //七天之内
            return human_time_diff(get_the_time('U',$post), current_time('timestamp')).'之前';    //显示格式 OOXX 之前
        }else{
            return apply_filters( 'the_time', get_the_time( 'Y-m-d', $post), 'Y-m-d' );    //显示格式 XX.XX.XX
        }
    }
}

// 通过页面名称获取页面文章数据
function zzwu_get_post_pagename_class($pagename, $postnum, $classnum = null)
{
    global $wpdb;
    if($classnum)
    {
//        获取页面分类
        $sql = sprintf('SELECT a.id, a.termid, a.classifyname, b.guid, a.pageid FROM zw_classify AS a JOIN zw_posts AS b ON a.pageid = b.ID WHERE b.post_title = "%s" LIMIT %s ',$pagename, $classnum);
        $postnameid = $wpdb->get_results($sql);
        $postvalue = new stdClass();
        if(count($postnameid))
        {
            $postarray = array();
            foreach($postnameid as $postnameidvalue)
            {
                $posts = new stdClass();
                $posts->class = $postnameidvalue->classifyname;
                $posts->guid = $postnameidvalue->guid;
//                获取该分类下文章信息
                $post = $wpdb->get_results(sprintf('SELECT b.ID, b.post_title, b.post_content, b.post_date, b.guid FROM zw_classifypost AS a JOIN zw_posts AS b ON a.postid = b.ID WHERE b.post_status = "publish" AND a.classifyid = "%s" ORDER BY post_date DESC LIMIT %s', $postnameidvalue->termid, $postnum));
                if(count($post))
                {
                    $posts->posts = $post;
                    $postarray[] = $posts;
                }
            }
            $postvalue->postarray = $postarray;
        }
    }
    else
    {
        $sql = sprintf('SELECT ID, guid FROM zw_posts WHERE post_title = "%s" AND post_status = "publish" AND post_type = "page"',$pagename);
        $postnameid = $wpdb->get_results($sql);
        $postvalue = new stdClass();
        if(count($postnameid))
        {
            $postvalue->class = $postnameid;
            $posts = zzwu_get_post($postnameid[0]->ID, $postnum);
            if(count($posts))
            {
                $postvalue->posts = $posts;
            }
        }
    }
    return $postvalue;
}

//获取文章第一张图片
function zzwu_catch_that_image($post_content) {
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches);
    $first_img = $matches [1] [0];
    return $first_img;
}

function zzwu_getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "1";
    }
    return $count;
}

function zzwu_setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 1;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, $count);
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function zzwu_remove_menus() {
    global $menu;
    $restricted = array(__('Dashboard'), __('Posts'), __('Links'), __('Appearance'), __('Plugins'), __('Tools'), __('Comments'));
//      $restricted = array(__('Dashboard'), __('Posts'), __('Media'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Users'), __('Settings'), __('Comments'), __('Plugins'));
    end ($menu);
    while (prev($menu)){
        $value = explode(' ',$menu[key($menu)][0]);
        if(strpos($value[0], '<') === FALSE) {
            if(in_array($value[0] != NULL ? $value[0]:"" , $restricted)){
                unset($menu[key($menu)]);
            }
        }
        else {
            $value2 = explode('<', $value[0]);
            if(in_array($value2[0] != NULL ? $value2[0]:"" , $restricted)){
                unset($menu[key($menu)]);
            }
        }
    }
}


add_action('admin_menu', 'zzwu_remove_menus');

add_action('admin_menu','zzwu_hide_nag');

// 隐藏更新提示信息
function zzwu_hide_nag() {
    remove_action( 'admin_notices', 'update_nag', 3 );
}

function zzwu_login_redirect($redirect_to, $request){
    if( empty( $redirect_to ) || $redirect_to == 'wp-admin/' || $redirect_to == admin_url() )
        return admin_url().'profile.php';
//        return home_url("/wp-admin/edit.php?post_type=news");profile.php

    else
        return $redirect_to;
}

add_filter("login_redirect", "zzwu_login_redirect", 10, 3);

//remove_action('wp_head', 'wp_generator');

// 处理ajax请求
function say(){
    global $wpdb;
    header( "Content-Type: application/json" );
    if(isset($_POST['classifyid']))
    {
        $classifyid = $_POST['classifyid'];
        $classifyresult = $wpdb->get_results(sprintf('SELECT id, termid, classifyname FROM zw_classify WHERE pageid = %s', $classifyid));
        if(count($classifyresult))
        {
            echo json_encode($classifyresult);
        }
        else
        {
            echo '0';
        }
    }
    elseif(isset($_POST['classid']))
    {
        $classid = $_POST['classid'];
        $classresult = $wpdb->get_results(sprintf('SELECT b.ID, b.post_title, b.post_date, b.guid FROM zw_classifypost AS a JOIN zw_posts AS b ON a.postid = b.ID WHERE a.classifyid = %s AND b.post_status = "publish" ORDER BY post_date DESC ', $classid));
        if(count($classresult))
        {
            $classresultarray = array();
            foreach($classresult as $classresultvalue)
            {
                $classresultvalue->post_title = zzwu_substr($classresultvalue->post_title, $classresultvalue->post_date, 35);
                $classresultvalue->post_date = zzwu_time_since($classresultvalue);
                $classresultarray[] = $classresultvalue;
            }
            echo json_encode($classresultarray);
        }
        else
        {
            echo '0';
        }
    }
    elseif(isset($_POST['type']) && $_POST['type']=='post_tourist')
    {
//        $wpdb->insert('zw_posts', array('post_author'=>'99','post_content'=>$_POST['post_content'], 'post_title'=>$_POST['post_title'], 'post_type'=>'news', 'ping_status'=>'closed', 'comment_status'=>'closed', 'post_status'=>'pending', 'post_date'=>current_time('mysql')));
//
//        $wpdb->get_results(sprintf('SELECT ID FROM zw_posts WHERE post_title = "%s" AND post_content = "%s"', $_POST['post_title'], $_POST['post_content']));
//
//        $insertid = $wpdb->insert_id;
//        $url = home_url().'/?post_type=news&p='.$insertid;
//        $wpdb->query(sprintf('UPDATE zw_posts SET guid = "%s" WHERE ID = "%s"', $url, $insertid));


        $last_post = $wpdb->get_var("SELECT `post_date` FROM `$wpdb->posts` WHERE post_author = 2 ORDER BY `post_date` DESC LIMIT 1");
        // 博客当前最新文章发布时间与要投稿的文章至少间隔120秒。
        if ( current_time('timestamp') - strtotime($last_post) < 120 ) {
            echo '0';
            die();
        }

        $tougao = array(
            'post_title' => $_POST['post_title'],
            'post_content' => $_POST['post_content'],
            'post_author' => 2,
            'post_type' => 'news',
            'post_status' => 'pending'
        );
        // 将文章插入数据库
        $status = wp_insert_post( $tougao );
        if($status != 0)
        {
            echo '1';
        }
        $insertid = $wpdb->insert_id;


        $wpdb->insert('zw_classifypost', array('postid'=>$insertid, 'classifyid'=>$_POST['zzwu_classid']));

        $classify_key = 'classify';

        $forbes_key = 'forbes';
        // 更新数据
        update_post_meta( $insertid, $forbes_key, $_POST['zzwu_forbesid']);
        // 更新子栏目
        update_post_meta( $insertid, $classify_key, $_POST['zzwu_classid']);
        echo '1';
    }
    elseif(isset($_POST['forbesweibo']))
    {
        $forbesweibo = $_POST['forbesweibo'];
        $weiboresult = $wpdb->get_results(sprintf('SELECT id, weiboshow FROM zw_weiboshow WHERE itemid = %s', $forbesweibo));
        if(count($weiboresult))
        {
          echo json_encode($weiboresult);
        }
        else
        {
            echo '0';
        }
    }
    elseif(isset($_POST['zzwuforbesid']))
    {
        $zzwuweibo = $_POST['zzwuweibo'];
        $zzwuforbesname = $_POST['zzwuforbesname'];
        $zzwuforbesid = $_POST['zzwuforbesid'];
        $result = false;
        $weiboresult = $wpdb->get_results(sprintf('SELECT id FROM zw_weiboshow WHERE itemid = %s', $zzwuforbesid));
        if(count($weiboresult))
        {
//            $wpdb->update('zw_weiboshow', array('weiboshow'=>$zzwuweibo, 'itemid'=>$zzwuforbesid, 'classname'=>$zzwuforbesname));
            foreach($weiboresult as $weiboresultvalue)
            {
                $result = $wpdb->query(sprintf('UPDATE zw_weiboshow SET weiboshow = "%s" , itemid = %s , classname = "%s" WHERE id = %s', $zzwuweibo, $zzwuforbesid, $zzwuforbesname, $weiboresultvalue->id));
            }
        }
        else
        {
            $result = $wpdb->query(sprintf('INSERT INTO zw_weiboshow (classname, itemid, weiboshow) VALUES ("%s", "%s", "%s")', $zzwuforbesname, $zzwuforbesid, $zzwuweibo));
//            $wpdb->insert('zw_weiboshow', array('weiboshow'=>$zzwuweibo, 'itemid'=>$zzwuforbesid, 'classname'=>$zzwuforbesname));
        }
        if($result)
        {
            echo '1';
        }
        else
        {
            echo '0';
        }
    }
    elseif(isset($_POST['codeimage']))
    {
//        $s = $_SESSION;
        if(strtolower($_POST['codeimage']) == strtolower($_SESSION['authnum_code']))
        {
            echo '1';
        }
        else
        {
            echo '0';
        }
    }
    else
    {
        echo '0';
    }


    die();
}

add_action('wp_ajax_say', 'say');
add_action('wp_ajax_nopriv_say', 'say');

/**
 * 自定义 WordPress 后台底部的版权和版本信息
 */
add_filter('admin_footer_text', 'zzwu_left_admin_footer_text');
function zzwu_left_admin_footer_text() {
// 左边信息
    $text = '欢迎访问广西大学生志愿者联合会网站';
    return $text;
}
add_filter('update_footer', 'zzwu_right_admin_footer_text', 11);
function zzwu_right_admin_footer_text() {
// 右边信息
    $text = "";
    return $text;
}

//移除Wordpress后台顶部左上角的W图标
function zzwu_annointed_admin_bar_remove() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('updates');
}
add_action('wp_before_admin_bar_render', 'zzwu_annointed_admin_bar_remove', 0);

add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
remove_action('admin_init', '_maybe_update_core');    // 禁止 WordPress 检查更新

// 同时删除head和feed中的WP版本号
function zzwu_remove_wp_version() {
    return '';
}
add_filter('the_generator', 'zzwu_remove_wp_version');
// 隐藏js/css附加的WP版本号
function zzwu_remove_wp_version_strings( $src ) {
    global $wp_version;
    parse_str(parse_url($src, PHP_URL_QUERY), $query);
    if ( !empty($query['ver']) && $query['ver'] === $wp_version ) {
        $src = str_replace($wp_version, $wp_version + 126.8, $src);
    }
    return $src;
}
add_filter( 'script_loader_src', 'zzwu_remove_wp_version_strings' );
add_filter( 'style_loader_src', 'zzwu_remove_wp_version_strings' );

require_once (TEMPLATEPATH.'/includes/page-quick-edit.php');

// 获取栏目信息
function zzwu_get_forbes()
{
    global $wpdb;
    $forbesresult = $wpdb->get_results("SELECT ID, post_title FROM zw_posts WHERE post_type = 'page' AND post_status = 'publish' ORDER BY menu_order");
    $str = '';
    if($forbesresult)
    {
        foreach($forbesresult as $value)
        {
            if($value->post_title == '新闻投稿')
            {
                continue;
            }

            $str .= sprintf('<option value="%s">%s</option>', $value->ID, $value->post_title);
        }
    }
    return $str;
}

// 获取附件
function zzwu_attachment_news($num)
{
    global $wpdb;
    $attachmentresult = $wpdb->get_results('SELECT ID, post_date, guid, post_title FROM zw_posts WHERE post_parent != "0" AND post_type = "attachment" AND post_mime_type LIKE "application%" ORDER BY post_date DESC LIMIT '.$num);
    if(count($attachmentresult))
    {
        return $attachmentresult;
    }else
    {
        return '';
    }
}

function zzwu_remove_open_sans_from_wp_core() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans','');
}
add_action( 'init', 'zzwu_remove_open_sans_from_wp_core' );

//修改后台登录链接
add_filter('login_headerurl', 'zzwu_login_headerurl');
function zzwu_login_headerurl(){
    return home_url();
}

// 修改后台登录链接标题
add_filter('login_headertitle', 'zzwu_login_headertitle');
function zzwu_login_headertitle(){
    return get_bloginfo('name');
}

// 修改后台登录图片
add_action('login_head', 'zzwu_login_head');
function zzwu_login_head() {
    ?>
    <style type="text/css">
        .login h1 a { width:150px; height:150px;background-size: 150px 150px; background-image: url("<?php bloginfo('template_url'); ?>/resource/images/logo_img.gif") !important;}
    </style>
    <?php
}

//添加后台登录界面底下文字
add_action('login_footer','zzwu_login_footer');
function zzwu_login_footer() {
    ?>
    <p style="text-align: center; margin-top: 10em;"><a>欢迎使用大学生联合志愿者服务网站</a>
    </p>
    <?php
}

add_filter( 'avatar_defaults', 'zzwu_newgravatar' );

function zzwu_newgravatar ($avatar_defaults) {
    $myavatar = get_bloginfo('template_directory') . '/resource/images/logo_img.gif';
    $avatar_defaults[$myavatar] = "默认头像";
    return $avatar_defaults;
}

function zzwu_get_https_avatar($avatar) {
    //~ 替换为 https 的域名
    $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "secure.gravatar.com", $avatar);
    //~ 替换为 https 协议
    $avatar = str_replace("http://", "https://", $avatar);
    return $avatar;
}
add_filter('get_avatar', 'zzwu_get_https_avatar');


if( is_admin() ) {
    /*  利用 admin_menu 钩子，添加菜单 */
    add_action('admin_menu', 'zzwu_display_weiboshow_menu');
}

function zzwu_display_weiboshow_menu() {
    /* 页名称，菜单名称，访问级别，菜单别名，点击该菜单时的回调函数（用以显示设置页面） */
    add_options_page('设置首页微博', '设置首页微博', 'administrator','weiboshow', 'zzwu_display_weiboshow_html_page');
}

function zzwu_display_weiboshow_html_page() {
    ?>

    <script>
        function saveweiboshow()
        {

            jQuery(document).ready(function($){

                var forbesvalue=document.getElementById("forbes-weibo");
                var index=forbesvalue.selectedIndex ;
                var classifyid = forbesvalue.options[index].value;
                var classifytext = forbesvalue.options[index].text;
                var weiboshow = document.getElementById("weiboshow").value;

                var data={
                    action:"say",
                    zzwuweibo:weiboshow,
                    zzwuforbesname: classifytext,
                    zzwuforbesid:classifyid
                }
                $.post("<?php echo admin_url('admin-ajax.php');  ?>", data, function(response) {
                    if(response=="1"){
                        $('#message').css({display: 'block'});
                    }
                    else{
                    }
                });
            });

        }

        function weibochange(){
            var forbesvalue=document.getElementById("forbes-weibo");
            var index=forbesvalue.selectedIndex ;
            var classifyid = forbesvalue.options[index].value;

            jQuery(document).ready(function($){
                var data={
                    action:"say",
                    forbesweibo:classifyid
                }
                $.post("<?php echo admin_url('admin-ajax.php');  ?>", data, function(response) {
                    if(response=="0"){
                    }
                    else{
                        $.each(response, function(commentIndex, comment){
                            $("#weiboshow").val(comment["weiboshow"]);
                        });
                    }
                });
            });
        };
    </script>

    <div style="margin-left: 50px; width: 600px; margin-top: 50px;">
        <div class="updated fade" id="message" style="display: none"><p>保存成功</p></div>
        <h1>设置微博</h1>
<!--        <form method="post" action="options.php">-->
            <?php /* 下面这行代码用来保存表单中内容到数据库 */ ?>
<!--            --><?php //wp_nonce_field('update-options'); ?>
        <p>
            分会微博：<select name="forbes_weibo" id="forbes-weibo" <!--onchange="weibochange()"-->><option value="0">请选择栏目</option>
            <?php $fenhui = zzwu_get_page_class_fenhui('各地分会', 30, 'noformat');
            if(count($fenhui))
            {
                foreach($fenhui as $fenhuivalue)
                { ?>
                    <option value="<?php echo $fenhuivalue->termid; ?>"><?php echo $fenhuivalue->classifyname; ?></option>
                <?php
                }
            }
            ?>
            </select>

            </p>

            <p>
            <textarea name="weiboshow"
                    id="weiboshow"
                    cols="100"
                    rows="6"></textarea>
            </p>

            <p>
                <input type="submit" value="保存" class="button-primary" onclick="saveweiboshow()" />
            </p>
<!--        </form>-->
    </div>
    <?php
}

function zzwu_get_weiboshow($limit)
{
    global $wpdb;
    $weiboshow = $wpdb->get_results(sprintf('SELECT id, classname, weiboshow FROM zw_weiboshow WHERE itemid != 0 ORDER BY id LIMIT %s', $limit));
    $str = '';
    if(count($weiboshow))
    {
        $i = 0;
        foreach($weiboshow as $weiboshowvalue)
        {
            if($i == 0)
            {
                $style = 'style="display: block;"';
            }
            else
            {
                $style = '';
            }
            $i ++;
            $str .= '<p class="flip">'.$weiboshowvalue->classname.'的微博</p><div class="panel" '.$style.' >'.$weiboshowvalue->weiboshow.'
    </div>';
        }
    }
    return $str;
}

remove_action( 'wp_head', 'feed_links_extra', 3 ); //去除评论feed
remove_action( 'wp_head', 'feed_links', 2 ); //去除文章feed
remove_action( 'wp_head', 'rsd_link' ); //针对Blog的远程离线编辑器接口
remove_action( 'wp_head', 'wlwmanifest_link' ); //Windows Live Writer接口
remove_action( 'wp_head', 'index_rel_link' ); //移除当前页面的索引
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); //移除后面文章的url
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); //移除最开始文章的url
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );//自动生成的短链接
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); ///移除相邻文章的url
remove_action( 'wp_head', 'wp_generator' ); // 移除版本号

//后台登陆数学验证码
function zzwu_plugin_add_login_fields() {
//获取两个随机数, 范围0~9
    $num1=rand(1,9);
    $num2=rand(1,9);
//最终网页中的具体内容
    echo "<p><label for='math' class='small'>验证码</label><br /> $num1 + $num2 = ?<input type='text' name='sum' class='input' value='' size='25' tabindex='4'>"
        ."<input type='hidden' name='num1' value='$num1'>"
        ."<input type='hidden' name='num2' value='$num2'></p>";
}
add_action('login_form','zzwu_plugin_add_login_fields');
function zzwu_login_val() {
    $sum=$_POST['sum'];//用户提交的计算结果
    switch($sum){
//得到正确的计算结果则直接跳出
        case $_POST['num1']+$_POST['num2']:break;
//未填写结果时的错误讯息
        case null:wp_die('错误: 请输入验证码.');break;
//计算错误时的错误讯息
        default:wp_die('错误: 验证码错误,请重试.');
    }
}
add_action('login_form_login','zzwu_login_val');

//修改默认的邮件名字
function zzwu_new_from_name($email){
    $wp_from_name = get_option('blogname');
    return $wp_from_name;
}

function zzwu_new_from_email($email) {
    $wp_from_email = get_option('admin_email');
    return $wp_from_email;
}

add_filter('wp_mail_from_name', 'zzwu_new_from_name');
add_filter('wp_mail_from', 'zzwu_new_from_email');

// 修改后台登录地址
//add_action('login_enqueue_scripts','zzwu_login_protection');
function zzwu_login_protection(){
    if($_GET['type'] != 'xdzl')header('Location: '.home_url());
}

//  删除子菜单
function zzwu_remove_submenu() {
    remove_submenu_page( 'options-general.php', 'options-permalink.php' );
    remove_submenu_page( 'options-general.php', 'options-reading.php' );
    remove_submenu_page( 'options-general.php', 'options-discussion.php' );
    remove_submenu_page( 'options-general.php', 'options-writing.php' );
}

add_action('admin_init','zzwu_remove_submenu');

//隐藏防止暴露管理员用户名
add_filter('author_link','my_author_link');

function zzwu_author_link()
{
    return home_url();
}


// 添加一个新栏目“新闻”
function zzwu_add_news_column( $columns ) {
    $columns['zzwu_news'] = '新闻';
    return $columns;
}
add_filter( 'manage_users_columns', 'zzwu_add_news_column' );

function zzwu_add_news_column_value( $value, $column_name, $user_id ) {
    if ( 'zzwu_news' == $column_name ) {
        $value      =  '0';
        $result = zzwu_num_of_author_posts($user_id);
        if($result)
        {
            $value = $result;
        }
    }
    return $value;
}
add_action( 'manage_users_custom_column', 'zzwu_add_news_column_value', 10, 3 );

// 用户注册时间 Start
add_filter('manage_users_columns','zzwu_add_users_column_reg_time');
function zzwu_add_users_column_reg_time($column_headers){
    $column_headers['reg_time'] = '注册时间';
    return $column_headers;
}

add_filter('manage_users_custom_column', 'zzwu_show_users_column_reg_time',10,3);
function zzwu_show_users_column_reg_time($value, $column_name, $user_id){
    if($column_name=='reg_time'){
        $user = get_userdata($user_id);
        return get_date_from_gmt($user->user_registered);
    }else{
        return $value;
    }
}

add_filter( "manage_users_sortable_columns", 'zzwu_users_sortable_columns' );
function zzwu_users_sortable_columns($sortable_columns){
    $sortable_columns['reg_time'] = 'reg_time';
    return $sortable_columns;
}

add_action( 'pre_user_query', 'zzwu_users_search_order' );
function zzwu_users_search_order($obj){
    if(!isset($_REQUEST['orderby']) || $_REQUEST['orderby']=='reg_time' ){
        if( !in_array($_REQUEST['order'],array('asc','desc')) ){
            $_REQUEST['order'] = 'desc';
        }
        $obj->query_orderby = "ORDER BY user_registered ".$_REQUEST['order']."";
    }
}
// 用户注册时间 end

// 创建一个新字段存储用户登录时间 Start
function zzwu_insert_last_login( $login ) {
    $user = get_user_by( 'login', $login );
    update_user_meta( $user->ID, 'last_login', time() );
}
add_action( 'wp_login', 'zzwu_insert_last_login' );

// 添加一个新栏目“上次登录”
function zzwu_add_last_login_column( $columns ) {
    $columns['last_login'] = '上次登录';
    return $columns;
}
add_filter( 'manage_users_columns', 'zzwu_add_last_login_column' );

// 显示登录时间到新增栏目
function zzwu_add_last_login_column_value( $value, $column_name, $user_id ) {
    if ( 'last_login' == $column_name ) {
        $value      =  '从未登录';
        $last_login = (int) get_user_meta( $user_id, 'last_login', true );

        if ( $last_login ) {
            $format = apply_filters( 'wpll_date_format', get_option( 'date_format' ) );
            $value  = date_i18n( $format, $last_login );
        }
    }
    return $value;
}
add_action( 'manage_users_custom_column', 'zzwu_add_last_login_column_value', 10, 3 );
// 创建一个新字段存储用户登录时间 end

/* 获得用户自己发表文章总数 */
function zzwu_num_of_author_posts($authorID=''){ //根据作者ID获取该作者的文章数量
    if ($authorID) {
        global $wpdb;
        $result = $wpdb->get_results(sprintf('SELECT ID FROM zw_posts WHERE post_status = "publish" AND post_type = "news" AND post_author = %s', $authorID));
        if(count($result))
        {
            return count($result);
        }
        else
        {
            return false;
        }
    }
    return false;
}

// 隐藏用户列表文章字段
function zzwu_users_columns_filter( $columns ) {
    unset($columns['posts']);
    return $columns;
}
add_filter( 'manage_users_columns', 'zzwu_users_columns_filter',10, 1 );

// 隐藏页面列表文章字段
function zzwu_pages_columns_filter( $columns ) {
    unset($columns['comments']);
    return $columns;
}
add_filter( 'manage_pages_columns', 'zzwu_pages_columns_filter',10, 1 );


//  媒体库只显示自己上传的
//在文章编辑页面的[添加媒体]只显示用户自己上传的文件
function zzwu_upload_media( $wp_query_obj ) {
    global $current_user, $pagenow;
    if( !is_a( $current_user, 'WP_User') )
        return;
    if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
        return;
    if( !current_user_can( 'manage_options' ) && !current_user_can('manage_media_library') )
        $wp_query_obj->set('author', $current_user->ID );
    return;
}
add_action('pre_get_posts','zzwu_upload_media');

//在[媒体库]只显示用户上传的文件
function zzwu_media_library( $wp_query ) {
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false ) {
        if ( !current_user_can( 'manage_options' ) && !current_user_can( 'manage_media_library' ) ) {
            global $current_user;
            $wp_query->set( 'author', $current_user->id );
        }
    }
}
add_filter('parse_query', 'zzwu_media_library' );

// 隐藏媒体列表文章字段
function zzwu_media_columns_filter( $columns ) {
    unset($columns['comments']);
    return $columns;
}
add_filter( 'manage_media_columns', 'zzwu_media_columns_filter',10, 1 );


//禁止上传的文件类型（不允许上传视频和音频）
add_filter('upload_mimes', 'zzwu_upload_mimes');
function zzwu_upload_mimes( $existing_mimes=array() ) {
    unset( $existing_mimes['asf'] );
    unset( $existing_mimes['avi'] );
    unset( $existing_mimes['divx'] );
    unset( $existing_mimes['flv'] );

    unset( $existing_mimes['mov|qt'] );
    unset( $existing_mimes['mpeg|mpg|mpe'] );
    unset( $existing_mimes['mp4|m4v'] );
    unset( $existing_mimes['ogv'] );

    unset( $existing_mimes['mkv'] );
    unset( $existing_mimes['mp3|m4a|m4b'] );
    unset( $existing_mimes['ra|ram'] );
    unset( $existing_mimes['wav'] );

    unset( $existing_mimes['ogg|oga'] );
    unset( $existing_mimes['mid|midi'] );
    unset( $existing_mimes['wma'] );
    unset( $existing_mimes['mka'] );

    return $existing_mimes;
}

// 添加热度文章置顶
function zzwu_add_title_icon($title, $post_date, $length)
{
    $current_time=current_time('timestamp');
    $diff=($current_time-strtotime($post_date))/3600;
    $title_icon_new=get_bloginfo('template_directory').'/resource/images/new.gif';
    if($diff < 24)
    {
        if(strlen($title) > ($length - 2)){
            $title =  wp_trim_words( $title, $length - 2, $more = '' );
        }
        $title .= '<img src="'.$title_icon_new.'" />';
    }
    else
    {
        if(strlen($title) > $length){
            $title =  wp_trim_words( $title, $length, $more = '' );
        }
    }
    return $title;
}

function zzwu_substr($string, $post_date, $length = 20) {
    $string = zzwu_add_title_icon($string, $post_date, $length);
    return $string;
}

function zzwu_substr_not_hot($string, $length = 20) {
    if(strlen($string)>$length){
        return wp_trim_words( $string, $length, $more = '' );
    }else{
        return $string;
    }
}

add_action( 'register_form', 'zzwu_show_extra_register_fields' );
function zzwu_show_extra_register_fields(){
    ?>
    <p>
        <label for="password">密码<br/>
            <input id="password" class="input" type="password" tabindex="30" size="25" value="" name="password" />
        </label>
    </p>
    <p>
        <label for="repeat_password">重复输入密码<br/>
            <input id="repeat_password" class="input" type="password" tabindex="40" size="25" value="" name="repeat_password" />
        </label>
    </p>
    <p>
        <label for="are_you_human" style="font-size:11px">请输入验证码<br/>
            <input id="are_you_human" class="input" type="text" tabindex="40" size="25" value="" name="are_you_human" />
        </label>
        <div style="margin-top: 5px; margin-bottom: 10px;  margin-right: 10px; height: 40px; cursor:pointer">
            <img src="<?php bloginfo('template_url'); ?>/zwrecaptcha.class.php" title="看不清，换一个" onclick="this.src='<?php bloginfo('template_url'); ?>/zwrecaptcha.class.php?'+Math.random();">
        </div>
    </p>
    <?php
}
//2. 检查用户的输入，两次输入的密码是否一致

add_action( 'register_post', 'zzwu_check_extra_register_fields', 10, 3 );
function zzwu_check_extra_register_fields($login, $email, $errors) {
    if ( $_POST['password'] !== $_POST['repeat_password'] ) {
        $errors->add( 'passwords_not_matched', "<strong>错误</strong>: 两次密码不一样" );
    }
    if ( strlen( $_POST['password'] ) < 8) {
        $errors->add( 'password_too_short', "<strong>错误</strong>: 密码少于8位" );
    }
    if ( strtolower($_POST['are_you_human']) !== strtolower($_SESSION['authnum_code']) ) {
        $errors->add( 'not_human', "<strong>错误</strong>: 验证码不正确" );
    }
}
//3. 存储用户输入的密码，如果用户没有填写密码，什么也不做，让WordPress自动生成密码。

add_action( 'user_register', 'zzwu_register_extra_fields');
function zzwu_register_extra_fields( $user_id ){
    $userdata = array();

    $userdata['ID'] = $user_id;
    if ( $_POST['password'] !== '' ) {
        $userdata['user_pass'] = $_POST['password'];
    }
    wp_new_user_notification( $user_id, $_POST['user_pass'], 1 );
    wp_update_user( $userdata );
}

function zzwu_printf_IPAddress()
{
    require_once (TEMPLATEPATH.'/includes/IP.class.php');
    $IP = IP::getIPinstance();
    $result = $IP->printfIPandAddress();

    if($result != -1)
    {
        $str = '欢迎'.$result->region.$result->city.'的小伙伴';
        global $wpdb;
        $count = 1;
//        $IPresult =  $wpdb->query(sprintf(' SELECT id, IP, accesscount FROM zw_ipaccess WHERE IP = "%s"', $result->ip));
        $IPresult = $wpdb->get_results(sprintf(' SELECT id, IP, accesscount FROM zw_ipaccess WHERE IP = "%s"', $result->ip));
        if(count($IPresult))
        {
            foreach($IPresult as $IPresultvalue)
            {
                $count = $IPresultvalue->accesscount + 1;
            }
            $wpdb->query(sprintf('UPDATE zw_ipaccess SET accesscount = %s, accesstime = %s WHERE IP = "%s"', $count, time(), $result->ip));
        }
        else
        {
            $wpdb->query(sprintf('INSERT INTO zw_ipaccess (IP, address, accesscount, accesstime)VALUES("%s", "%s", %s, %s)', $result->ip, $result->region.$result->city.$result->county, $count, time()));
        }
        return $str;
    }
    else
    {
        return '';
    }
}



// 创建一个新字段存储用户登录次数 Start
function zzwu_insert_login_count( $login ) {
    $user = get_user_by( 'login', $login );
    $value = 0;
    $value = (int)get_user_meta( $user->ID, 'login_count', true );

    update_user_meta( $user->ID, 'login_count', $value+1 );
}
add_action( 'wp_login', 'zzwu_insert_login_count' );

// 添加一个新栏目“登录次数”
function zzwu_add_login_count_column( $columns ) {
    $columns['login_count'] = '登陆次数';
    return $columns;
}
add_filter( 'manage_users_columns', 'zzwu_add_login_count_column' );

// 显示登录次数到新增栏目
function zzwu_add_login_count_column_value( $value, $column_name, $user_id ) {
    if ( 'login_count' == $column_name ) {
        $value = (int) get_user_meta( $user_id, 'login_count', true );
        return '共登录 '.$value.' 次';
    }
    return $value;
}
add_action( 'manage_users_custom_column', 'zzwu_add_login_count_column_value', 10, 3 );
// 创建一个新字段存储用户登录次数 End

/**
 * WordPress 去除后台标题中的“—— WordPress”
 * http://www.wpdaxue.com/remove-wordpress-from-admin-title.html
 * 参考代码见 https://core.trac.wordpress.org/browser/tags/4.2.2/src/wp-admin/admin-header.php#L44
 */
add_filter('admin_title', 'zzwu_custom_admin_title', 10, 2);
function zzwu_custom_admin_title($admin_title, $title){
    return $title.' &lsaquo; '.get_bloginfo('name');
}

?>
