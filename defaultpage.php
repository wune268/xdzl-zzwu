<?php

/**
 * Template Name: 普通模板
 *
 */

get_header(); ?>

<div id="container">
<div id="current">
<table><tbody><tr><td class="con_hight"></td></tr>
  <tr><td>
  <div class="current_zi"><strong>当前位置:</strong> <a href="<?php bloginfo('url'); ?>">首页</a> &gt; <a href="<?php echo get_page_link();?>"><?php echo get_the_title();?></a> &gt; </div>
  </td></tr></tbody></table>
</div>

<div id="sidepage">
<div class="title"><span style="float: left;"><?php echo get_the_title();?></span></div>
    
    <div class="side1">

      <ul class="subtitle">

          <?php zzwu_get_page_class(get_the_title()); ?>
      </ul>
    </div>
  </div>



 <div id="mainpage">
     <?php $numberposts = 25; $offset = isset($_GET['pagination']) ? ($_GET['pagination'] - 1) * $numberposts:'';  $previous_posts = isset($_GET['pagination']) ? zzwu_get_post($_GET['page_id'], $numberposts, $offset) : zzwu_get_post($_GET['page_id']);?>

     <div style="width: 100%; height: 25px;"></div>
        <div class="list">
            <!-- 文章列表 -->
          <div>
          <ul class="article_list" id="article_list">
          <?php if(is_array($previous_posts)){
                foreach($previous_posts as $post){
                    setup_postdata($post);
          ?>
            <li><div class="fl"><a href="<?php echo $post->guid; ?>"><?php echo zzwu_substr($post->post_title, $post->post_date, 35); ?></a></div><div class="fr"><?php echo zzwu_time_since($post); ?></div></li>
                <?php } }?>
          </ul>
          </div>
          <div style="clear:both;"></div>


            <div class="dede_pages">
                <ul class="pagelist">
            <?php
            $pageurl = get_bloginfo( 'url', 'display' ).'?page_id='.$_GET['page_id'];
            $pagination = isset($_GET['pagination']) ? $_GET['pagination'] : '1';
            $nextpage = count($previous_posts) >= 25 ? ($pagination + 1) : $pagination;
            $lastpage = $pagination > 1 ? ($pagination - 1) : '1';
            if($_GET['pagination'] > 1 || count($previous_posts) >= 25)
            {?>
            <!-- /listbox -->
                    <li><a href="<?php echo $pageurl;?>">首页</a></li>
                    <li><a href="<?php echo ($pageurl.'&pagination='.$lastpage);?>">上一页</a></li>
                    <li><a href="<?php echo ($pageurl.'&pagination='.$nextpage);?>">下一页</a></li>
<!--                    <li><select name="sldd" style="width:36px" onchange="location.href=this.options[this.selectedIndex].value;">-->
<!--                            <option value="--><?php //echo ($pageurl.'&pagination=3');?><!--" selected="">1</option>-->
<!--                            <option value="--><?php //echo ($pageurl.'&pagination=4');?><!--">2</option>-->
<!--                        </select></li>-->

            <?php }?>
                </ul>
            </div>

        </div>
  </div>
  <div style="clear: both;"></div>
  <div style="height: 20px;"></div>

</div>


<?php get_footer(); ?>

<script>
    function js_method(obj)
    {
        jQuery(document).ready(function($){
            var data={
                action:"say",
                classid:obj
            }
            $.post("<?php echo admin_url('admin-ajax.php');  ?>", data, function(response) {
                if(response=="0"){
                }
                else{
                    $("#article_list").children().remove();
                    $.each(response, function(commentIndex, comment){
                        var guid = comment["guid"];
                        var post_date = comment["post_date"];
                        var post_title = comment["post_title"];
                        $('#article_list').append('<li><div class="fl"><a href="'+guid+'">'+post_title+'</a></div><div class="fr">'+post_date+'</div></li>');
                    });
                }
            });
        });
    }
</script>
