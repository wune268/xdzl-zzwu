<?php

/**
 * Template Name: 新闻投稿模板
 *
 */

get_header();?>

<div id="container">
<div id="current">
<table><tbody><tr><td class="con_hight"></td></tr>
  <tr><td>
  <div class="current_zi"><strong>当前位置:</strong> <a href="<?php bloginfo('url'); ?>">首页</a> &gt; <a href="<?php echo get_page_link();?>"><?php echo get_the_title();?></a> &gt; </div>
</td></tr></tbody></table>
</div>
    <div style="width: 1000px; height: 250px; margin: 0 auto; margin-top: 10px; background: #fdfdfb;">

        <div style="float: left; margin-top: 10px; width: 100%; height: 30px;">
           <p style="float: left; margin-left: 10px; font-weight:bold; ">新闻投稿 - 欢迎大家积极投稿</p>
        </div>
        <div style="float: left; margin-top: 10px; width: 100%; ">
            <p><label style="float: left; margin-top: 20px; margin-left: 10px;">标题内容：</label>
            <input id="post_title" type="text" placeholder="请输入标题 * " style="margin-left: 10px; float: left; height: 50px; width: 80%;" onblur="if(value ==''){style='margin-left: 10px; float: left; height: 50px; width: 80%; border:solid 2px #FF0000;'} else{style='margin-left: 10px; float: left; height: 50px; width: 80%;'}"/>
        </div>
        <div style="float: left; width: 100%; margin-top: 10px; height: 30px;">
            <p><label style="float: left; margin-top: 25px; margin-left: 10px;">隶属栏目：</label>
                <select id="forbesid" size="1" style="float: left; margin-top: 20px; margin-left: 10px; width: 150px; height: 30px;" onchange="change()">
                    <option value="0" selected="">请选择栏目...</option>
                    <?php echo zzwu_get_forbes(); ?>
                </select>
            </p>

        </div>
        <div style="float: left; width: 100%; margin-top: 10px; height: 30px;">
            <p><label style="float: left; margin-top: 25px; margin-left: 10px;">次级栏目：</label>
                <select id="classid" size="1" style="float: left; margin-top: 20px; margin-left: 10px; width: 150px; height: 30px;">
                    <option value="0" selected="">请选择栏目...</option>
                </select>
            </p>

        </div>
        <div style="margin-top: 30px; float: left; margin-right: 10px; height: 30px; width: 150px; cursor:pointer">
            <img src="<?php bloginfo('template_url'); ?>/zwrecaptcha.class.php" title="看不清，换一个" onclick="this.src='<?php bloginfo('template_url'); ?>/zwrecaptcha.class.php?'+Math.random();">：
        </div>
        <div style="margin-top: 30px; float: left; margin-right: 50px; height: 30px; width: 150px;">
            <input type="text" id="codeimage" style="height: inherit; width: inherit" placeholder="点击输入左侧验证码 * " onblur="checkcode();"/>
        </div>
        <div style="margin-top: 20px; float: right; margin-right: 50px; height: 50px; width: 100px;">
            <input type="button" id="sendpost" value="提交" disabled="disabled" onclick="send_post();" style="font-size:20px; color: #fbfbfb; font-weight:bold; cursor: pointer; background: #33CC99; height: inherit; width: inherit">
        </div>
    </div>
        <div style="width: 1000px; margin: 0 auto; background: #fdfdfb;">
            <div style="float: left; margin-top: 10px; width: 100%; height: 30px; border-bottom:#009966 1px solid;">
                <h3 style="float: left; margin-left: 10px; font-weight:bold; ">详细内容</h3>
            </div>
<?php
wp_editor('', 'myeditor',
    $settings = array(
        'tinymce'=>0,
        'media_buttons'=>0
    )
);

?>

</div>

<!--    <div style="clear: both;"></div>-->
    <div style="height: 10px;"></div>

</div>

<?php get_footer('home'); ?>

<script>

    function checkcode()
    {
        var codeimage = jQuery('#codeimage').val();
        var data={
            action:"say",
            codeimage:codeimage
        }
        jQuery.post("<?php echo admin_url('admin-ajax.php');  ?>", data, function(response) {
            if(response=="1"){
                jQuery('#codeimage').css({border:'2px inset'});
                jQuery('#sendpost').removeAttr("disabled");
            }
            else{
                jQuery('#codeimage').css({border:'solid 2px #FF0000'});
                jQuery('#sendpost').attr({disabled:"disabled"});
            }
        });
    }

    function send_post()
    {
        jQuery(document).ready(function($){
            var forbesid = $('#forbesid option:selected') .val();
            var classid = $('#classid option:selected') .val();
            var post_title = $('#post_title').val();
            var post_content = $('#myeditor').val();

            var data={
                action:"say",
                zzwu_forbesid:forbesid,
                post_title:post_title,
                post_content:post_content,
                zzwu_classid:classid,
                type:'post_tourist'
            };

            $.post("<?php echo admin_url('admin-ajax.php');  ?>", data, function(response) {
                if(response=="0"){
                    alert("投稿失败，服务器繁忙，请稍后再试");
                }
                else{
                    alert("投稿成功，正在等候管理员审核");
                    window.location.href = "<?php echo home_url();?>";
                }
            });
        });
    }

    function change(){
        var forbesvalue=document.getElementById("forbesid");
        var index=forbesvalue.selectedIndex ;
        var classifyid = forbesvalue.options[index].value;

        jQuery(document).ready(function($){
            var data={
                action:"say",
                classifyid:classifyid
            }
            $.post("<?php echo admin_url('admin-ajax.php');  ?>", data, function(response) {
                if(response=="0"){
                }
                else{
                    $("#classid").children().remove();
                    $.each(response, function(commentIndex, comment){
                        var classifyid = comment["termid"];
                        var classifyname = comment["classifyname"];
                        $("#classid").append("<option value='"+classifyid+"'>"+classifyname+"</option>")
                    });
                }
            });

        });
    }
</script>
