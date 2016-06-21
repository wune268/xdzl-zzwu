<?php
get_header(); ?>

<div id="container">
    <div id="current">
        <table><tbody><tr><td class="con_hight"></td></tr>
            <tr><td>
                    <div class="current_zi"><strong>当前位置:</strong> <a href="<?php bloginfo('url'); ?>">首页</a> &gt; <a href="">404</a> &gt; </div>
                </td></tr></tbody></table>
    </div>
    <div style="width: 1000px; min-height: 600px; margin: 0 auto; margin-top: 10px; background: #ffffff;">
        <div style="height: 50px"><br /><h1>对不起，找不到你要的东西，也许去当志愿者了吧</h1></div>
            <div style="margin-top: 10px;">

                <img src="<?php bloginfo('template_directory'); ?>/resource/images/404.jpg" width="100%"/>
            </div>
        <div style="height: 60px">
            <a href="<?php bloginfo('siteurl'); ?>/" style="font-size: 40px">点此返回首页</a>
        </div>
    </div>
</div>
<div style="height: 10px" ></div>

<?php get_footer(); ?>

