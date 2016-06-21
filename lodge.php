<?php

/**
 * Template Name: 分会模板
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

<div style="width: 290px; float: left;">
<div id="sidepage" style="min-height: 350px;">
<div class="title"><span style="float: left;"><?php echo get_the_title();?></span></div>
    
    <div class="side1">

      <ul class="subtitle">

        <?php zzwu_get_page_class(get_the_title()); ?>
      </ul>
    </div>
  </div>
  <div style="clear: both;">
<!--    <select name="forbes_weibo" id="forbes-weibo"><option value="0">请选择栏目</option></select>-->
    </div>
  <!-- 微博秀 -->
  <div class="weiboshow" >
    <iframe width="100%" height="537" class="share_self"  frameborder="0" scrolling="no" src="http://widget.weibo.com/weiboshow/index.php?language=&width=0&height=563&fansRow=1&ptype=1&speed=0&skin=1&isTitle=0&noborder=0&isWeibo=1&isFans=1&uid=5055077436&verifier=08118085&colors=d6f3f7,fdfdfb,666666,33CC99,fdfdfb&dpc=1"></iframe>
  </div>
  <div style="clear: both;">     
    </div>
    <div style="height: 20px"></div>
</div>

 <div id="mainpage" style="min-height: 900px;">

   <?php if(isset($_GET['wuclass']))
   {
      global $wpdb; $previous_posts = $wpdb->get_results((sprintf('SELECT b.ID, b.post_title, b.post_date, b.guid FROM zw_classifypost AS a JOIN zw_posts AS b ON a.postid = b.ID WHERE a.classifyid = %s ORDER BY post_date DESC ', $_GET['wuclass'])));
   }
   else
   {
     $numberposts = 25; $offset = isset($_GET['pagination']) ? ($_GET['pagination'] - 1) * $numberposts:'';  $previous_posts = isset($_GET['pagination']) ? zzwu_get_post($_GET['page_id'], $numberposts, $offset) : zzwu_get_post($_GET['page_id']);
   }?>
   <div style="width: 100%; height: 25px;"></div>
   <div class="list">
            <!-- 文章列表 -->
      <div>
        <ul class="article_list" id="article_list">
          <?php  if(is_array($previous_posts)){
            foreach($previous_posts as $post){
              ?>
              <li><div class="fl"><a href="<?php echo $post->guid; ?>"><?php echo zzwu_substr($post->post_title, $post->post_date, 35); ?></a></div><div class="fr"><?php echo zzwu_time_since($post);?></div></li><?php } }?>
        </ul>
      </div>
      <div style="clear:both;"></div>


     <!-- /listbox -->
     <div class="dede_pages">
       <ul class="pagelist">

     <?php
     $pageurl = get_bloginfo( 'url', 'display' ).'?page_id='.$_GET['page_id'];
     $pagination = isset($_GET['pagination']) ? $_GET['pagination'] : '1';
     $nextpage = count($previous_posts) >= 25 ? ($pagination + 1) : $pagination;
     $lastpage = $pagination > 1 ? ($pagination - 1) : '1';
     if($_GET['pagination'] > 1 || count($previous_posts) >= 25)
     {?>
       <li><a href="<?php echo $pageurl;?>">首页</a></li>
       <li><a href="<?php echo ($pageurl.'&pagination='.$lastpage);?>">上一页</a></li>
       <li><a href="<?php echo ($pageurl.'&pagination='.$nextpage);?>">下一页</a></li>
     <?php }?>
         </ul>
       </div>

    </div>

  
  </div>

</div>


<?php get_footer(); ?>


<script>
  function js_method(obj)
  {
    weibochange(obj);
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

  function weibochange(obj){

    jQuery(document).ready(function($){
      var data={
        action:"say",
        forbesweibo:obj
      }
      $.post("<?php echo admin_url('admin-ajax.php');  ?>", data, function(response) {
        if(response=="0"){
//          $(".weiboshow").append();
        }
        else{
//          alert('fffffffffff');
		  $(".weiboshow").children().remove();
          $.each(response, function(commentIndex, comment){
            $(".weiboshow").append(comment["weiboshow"]);

          });
        }
      });
    });
  }
</script>
