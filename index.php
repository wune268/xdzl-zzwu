<?php

if(is_file(get_stylesheet_directory().'./zzwuhome.html')&&(time() - filemtime(get_stylesheet_directory().'./zzwuhome.html')) < 60)
{
 require_once get_stylesheet_directory().'./zzwuhome.html';
 die();
}
else
{
 ob_start();
 get_header('home');

$jiaodian = zzwu_get_post_pagename_class('焦点新闻', 8);
$pinpai = zzwu_get_post_pagename_class('品牌项目', 7);
$gaoxiao = zzwu_get_post_pagename_class('高校动态', 9, 2);
$fengcai = zzwu_get_post_pagename_class('风采展播', 7, 3);
$gonggao = zzwu_get_post_pagename_class('公告通知', 9);
 ?>

<div class="fullSlide cl">
       <div id="portal_block_372_content" class="dxb_bc"> 
        <div class="bd"> 
         <ul style="position: relative; width: 1903px; height: 530px;">
          <?php zzwu_get_banner_slide();?>
         </ul> 
        </div> 
        <div class="hd"> 
         <ul> 
          <li class="on">1</li>
          <li class="">2</li>
          <li class="">3</li>
         </ul> 
        </div> 
        <a class="prev" href="javascript:void(0)">&lt;</a> 
        <a class="next" href="javascript:void(0)">&gt;</a>
    </div> 
   </div> 
  </div> 
  <!-- 滚动美图 --> 
  <script type="text/javascript">
jQuery(".fullSlide").slide({titCell:".hd ul",  mainCell:".bd ul", effect:"fold",  autoPlay:true, mouseOverStop:false, autoPage:true });
jQuery(".wonderfula").slide({ mainCell:".bd ul", effect:"leftLoop",easing:"easeInOutQuint",delayTime:500, vis:4, scroll:1,autoPlay:true });
jQuery(".talk_box").slide({ mainCell:".bd ul", titCell:".hd ul",autoPlay:true,autoPage:true, easing:"easeInCubic"});
</script>	

<div style="height: 20px"></div>

<div id="container">

<div id="PageBody"> 
   <div id="first"> 
    <div class="first_l"> 
     <div class="con_hight1"> 
      <div class="sec_content"> 
       <div class="title">
        焦点新闻
       </div> 
       <div id="more_nav1">
        <div class="more" style="border-bottom:#009966 1px solid;">
         <a href="<?php echo $jiaodian->class[0]->guid; ?>">更多&gt;&gt;</a>
        </div>
       </div> 
      </div> 
     </div> 
     <div class="first_l_l">
     <?php if(count($jiaodian->posts))
        {
         $i = 0;
            $str = '';
            $image = null;
            foreach($jiaodian->posts as $jiaodianvalue)
            {
                if($i == 4)
                {
                   $str .= '<tr><td height="10px"></td></tr>';
                }
                $i ++;
                if(empty($image))
                {
                    $image = zzwu_catch_that_image($jiaodianvalue->post_content);
                }
                $str .= '<tr><td class="arc_title"><a href="'.$jiaodianvalue->guid.'">'.zzwu_substr($jiaodianvalue->post_title, $jiaodianvalue->post_date).'</a></td></tr>';
            }
        ?>
      <img src="<?php if(!empty($image)) echo $image;?>" border="0" width="320" height="250" />

     </div> 
     <!-- 最新文章 --> 
     <div class="first_l_r"> 
      <table> 
       <tbody>
       <?php echo $str; }?>
       </tbody>
      </table> 
     </div> 
    </div> 
    <!-- 公告通知 --> 
    <div class="first_r"> 
     <div class="first_r_con" style="width: 270px">
      <table> 
       <tbody>
        <tr>
         <td class="con_hight"></td>
        </tr> 
        <tr>
         <td> 
         <div style="width: 80px; float: left; font-size: 15px; font-weight: bold;">
         	<span>公告通知</span>
         </div>       
          <div class="more" style="border-bottom:#009966 1px solid;">
           <a href="<?php echo $gonggao->class[0]->guid; ?>">更多&gt;&gt;</a>
          </div></td>
        </tr> 
        <tr>
         <td class="con_hight"></td>
        </tr>

        <?php if(count($gonggao->posts))
        {
            $i = 0;
            $str = '';
            foreach($gonggao->posts as $gonggaovalue)
            {
                if($i == 5)
                {
                   $str .= '<tr><td height="10px"></td></tr>';
                }
                $i ++;
                $str .= '<tr><td class="arc_title"><a href="'.$gonggaovalue->guid.'">'.zzwu_substr($gonggaovalue->post_title, $gonggaovalue->post_date, 18).'</a></td></tr>';
            }
            echo $str;
        }?>

       </tbody>
      </table> 
     </div> 
    </div> 
   </div>
   <!--first end--> 
   <div id="second"> 
    <div class="second_l"> 
     <div class="sec_content"> 
      <div class="title">
       高校动态
      </div> 
      <div id="more_nav1">
       <div class="more">
        <a href="<?php echo $gaoxiao->postarray[0]->guid; ?>">更多&gt;&gt;</a>
       </div>
      </div> 
     </div> 
     <!-- 区内高校动态 --> 
     <div class="third_l_l"> 
      <table>
       <tbody>
        <tr>
         <td> 
          <div class="third_title"> 
           <a href="<?php echo $gaoxiao->postarray[0]->guid; ?>"><?php echo $gaoxiao->postarray[0]->class; ?></a>
          </div> </td>
        </tr> 
        <tr>
         <td class="con_hight"></td>
        </tr>

        <?php if(count($gaoxiao->postarray[0]->posts))
       {
            foreach($gaoxiao->postarray[0]->posts as $gaoxiaopost)
            {?>
                <tr><td class="arc_title"><a href="<?php echo $gaoxiaopost->guid; ?>"><?php echo zzwu_substr($gaoxiaopost->post_title, $gaoxiaopost->post_date); ?></a></td></tr>
           <?php }
       }?>

       </tbody>
      </table> 
     </div> 
     <!-- 区外高校动态 --> 
     <div class="third_l_r"> 
      <table>
       <tbody>
        <tr>
         <td> 
          <div class="third_title"> 
           <a href="<?php echo $gaoxiao->postarray[1]->guid; ?>"><?php echo $gaoxiao->postarray[1]->class; ?></a>
          </div> </td>
        </tr> 
        <tr>
         <td class="con_hight"></td>
        </tr> 
        <tr>
         <?php if(count($gaoxiao->postarray[1]->posts))
       {
            foreach($gaoxiao->postarray[1]->posts as $gaoxiaopost)
            {?>
                <tr><td class="arc_title"><a href="<?php echo $gaoxiaopost->guid; ?>"><?php echo zzwu_substr($gaoxiaopost->post_title, $gaoxiaopost->post_date); ?></a></td></tr>
           <?php }
       }?>
       </tbody>
      </table> 
     </div> 
    </div> 
    <!-- 资料下载 --> 
    <div class="second_r">
    <!-- 微博秀 550 636 -->

    <?php echo zzwu_get_weiboshow(3); ?>

    </div>
   </div>
   <!--第三行 品牌项目--> 
   <div id="" style="width:1000px; height:276px; margin-top:10px; padding:0px;">
   <!-- 添加微博 -->
      <div class="second_l_l_l">

<div class="first_r_con"> 
      <table> 
       <tbody>
        <tr>
         <td class="con_hight"></td>
        </tr> 
        <tr>
         <td>
         <div style="width: 80px; float: left; font-size: 15px; font-weight: bold;">
         	<span>各地分会</span>
         </div>
         <?php $fenhui = zzwu_get_page_class_fenhui('各地分会', 14); ?>
          <div class="more" style="border-bottom:#009966 1px solid;">
           <a href="<?php echo $fenhui->url; ?>">更多&gt;&gt;</a>
          </div></td>
        </tr> 
        <tr>
         <td class="con_hight"></td>
        </tr>
        <tr>
         <td class="con_hight"></td>
        </tr> 
       </tbody>
      </table> 
      <div class="third_r_con"> 
       <ul> 
        <?php echo $fenhui->str; ?>
       </ul> 
      </div> 
     </div> 
    </div>
    <div class="second_l_c"> 
     <div class="sec_content_c"> 
      <div class="title" style="margin-bottom: 10px; width: 150px; float:left;">
       <span>品牌项目</span>
      </div>
      <div id="more_nav" style="margin-top: 10px; width: 150px; float: right">
       <div class="more">
        <a href="<?php echo $pinpai->class[0]->guid; ?>">更多&gt;&gt;</a>
       </div>
      </div>
     </div>
     <div style="border-bottom:#009966 1px solid; float:left:; width: 150px;" ></div>
     <div class="nTab"> 
     <div class="TabContent">
       <!-- 关爱农民工 --> 
       <div id="myTab_Content0"> 
        <div> 
         <div class="second_l_l_c"> 
          <table> 
           <tbody>
            <tr>
             <td class="con_hight"></td>
            </tr>

            <?php if(count($pinpai->posts))
        {
            $i = 1;
            $str = '';
            $images = array();
            foreach($pinpai->posts as $pinpaivalue)
            {

                if($i == 4)
                {
                   $str .= '<tr><td height="10px"></td></tr>';
                }
                $i ++;
                $imagev = zzwu_catch_that_image($pinpaivalue->post_content);

                if(!empty($imagev))
                {
                    $image = new stdClass();
                    $image->img = $imagev;
                    $image->url = $pinpaivalue->guid;
                    $images[] = $image;
                }
                $str .= '<tr><td class="arc_title"><a href="'.$pinpaivalue->guid.'">'.zzwu_substr($pinpaivalue->post_title, $pinpaivalue->post_date, 16).'</a></td></tr>';
            }
            echo $str;
        }?>
          </tbody>
          </table> 
         </div> 
         <div class="second_1_m_c">

         <?php if(count($images))
          {
              $i = 0;
              foreach($images as $imagevalue)
              {
                  if($i>=2)
                  {
                      break;
                  }
                  $i++;
                  ?>
                  <a href="<?php echo $imagevalue->url; ?>" title=""><img src="<?php echo $imagevalue->img; ?>" border="0" width="110" height="75" alt="" /></a>
              <?php }
          }?>
        </div>

        </div> 
       </div>

       <!--myTab_Content3--> 
      </div>
      <!--TabContent end--> 
     </div>
     <!--nTab end--> 
    </div>
   </div>
   <!--second end--> 
   <div id="fourth">
   <div class="fourth_r_title" style="width:1000px;">
   <div class="title" style="width: 300px; float: left;margin-left: 15px;">
       风采展播
      </div>

      <div id="more_nav4" style="float: right; margin-right: 15px;width: 200px">
       <div class="more">
        <a href="<?php echo $fengcai->postarray[0]->guid; ?>">更多&gt;&gt;</a>
       </div>
      </div>
     </div>
    <div class="fourth_l" style="width:320px; float:left;">
     <!-- 风采展播  -->
     <div class="fourth_l_content" style="width:320px;">
      <table style="width:320px;">
       <tbody>
        <tr>
         <td width="150"></td>
         <td width="248" class="con_hight"></td>
        </tr>
        <tr>
         <td colspan="2">
          <div class="fourth_title" style="width:320px;">
           <a href="<?php echo $fengcai->postarray[0]->guid; ?>/"><?php echo $fengcai->postarray[0]->class; ?></a>
          </div></td>
        </tr>
        <tr>
         <td width="150"></td>
         <td width="248" class="con_hight"></td>
        </tr>
         <?php if(count($fengcai->postarray[0]->posts))
       {
            $str = '';
            foreach($fengcai->postarray[0]->posts as $fengcaipost)
            {
                $imgstr = zzwu_catch_that_image($fengcaipost->post_content);
                if($imgstr)
                {?>
                    <tr>
                     <td rowspan="8" style="width:120px; font-size:14px;"> <a href="<?php echo $fengcaipost->guid; ?>" ><img src="<?php echo $imgstr; ?>" border="0" width="120" height="120"  /></a> </td>
                    </tr>
                <?php break;
                }
            }
            foreach($fengcai->postarray[0]->posts as $fengcaipost)
            {?>
                <tr><td class="arc_title" style="width:172px; font-size:14px;"><a href="<?php echo $fengcaipost->guid; ?>"><?php echo zzwu_substr($fengcaipost->post_title, $fengcaipost->post_date, 12); ?></a></td></tr>
           <?php }
       }?>

       </tbody>
      </table>
     </div>
    </div>
    <div class="fourth_r" style="width:320px; float:left; margin-left:10px;">
     <div class="fourth_r_content" style="width:300px;">
      <table style="width:300px;">
       <tbody>
        <tr>
         <td width="150"></td>
         <td width="248" class="con_hight"></td>
        </tr>
        <tr>
         <td colspan="2">
          <div class="fourth_title" style="width:300px;">
           <a href="<?php echo $fengcai->postarray[0]->guid; ?>"><?php echo $fengcai->postarray[1]->class; ?></a>
          </div></td>
        </tr>
        <tr>
         <td width="150"></td>
         <td width="248" class="con_hight"></td>
        </tr>
        <?php if(count($fengcai->postarray[1]->posts))
       {
            foreach($fengcai->postarray[1]->posts as $fengcaipost)
            {
                $imgstr = zzwu_catch_that_image($fengcaipost->post_content);
                if(!empty($imgstr))
                {?>
                    <tr>
                     <td rowspan="8" style="width:120px; font-size:14px;"> <a href="<?php echo $fengcaipost->guid; ?>" ><img src="<?php echo $imgstr; ?>" border="0" width="120" height="120"  /></a> </td>
                    </tr>
                <?php break;
                }
            }
            foreach($fengcai->postarray[1]->posts as $fengcaipost)
            {?>
                <tr><td class="arc_title" style="width:172px; font-size:14px;"><a href="<?php echo $fengcaipost->guid; ?>"><?php echo zzwu_substr($fengcaipost->post_title, $fengcaipost->post_date, 10); ?></a></td></tr>
           <?php }
       }?>
       </tbody>
      </table>
     </div>
    </div>
    <div class="fourth_r" style="width:320px; float:left; margin-left:10px;">
     <div class="fourth_r_content" style="width:280px;">
      <table style="width:280px;">
       <tbody>
        <tr>
         <td width="150"></td>
         <td width="248" class="con_hight"></td>
        </tr>
        <tr>
         <td colspan="2">
          <div class="fourth_title" style="width:280px;">
           <a href="<?php echo $fengcai->postarray[0]->guid; ?>" target="_blank"><?php echo $fengcai->postarray[2]->class; ?></a>
          </div></td>
        </tr>
        <tr>
         <td width="150"></td>
         <td width="248" class="con_hight"></td>
        </tr>
        <?php if(count($fengcai->postarray[2]->posts))
       {
            foreach($fengcai->postarray[0]->posts as $fengcaipost)
            {
                $imgstr = zzwu_catch_that_image($fengcaipost->post_content);
                if(!empty($imgstr))
                {?>
                    <tr>
                     <td rowspan="8" style="width:120px; font-size:14px;"> <a href="<?php echo $fengcaipost->guid; ?>" ><img src="<?php echo $imgstr; ?>" border="0" width="120" height="120"  /></a> </td>
                   </tr>
                <?php break;
                }
            }
            foreach($fengcai->postarray[2]->posts as $fengcaipost)
            {?>
                <tr><td class="arc_title" style="width:172px; font-size:14px;"><a href="<?php echo $fengcaipost->guid; ?>"><?php echo zzwu_substr($fengcaipost->post_title, $fengcaipost->post_date, 9); ?></a></td></tr>
           <?php }
       }?>
      </tbody>
      </table> 
     </div> 
    </div> 
   </div>
   <!--fourth end--> 
   <div id="fifth"> 
    <table>
     <tbody>
      <tr>
       <td class="con_hight"></td>
      </tr> 
      <tr>
       <td> 
        <div class="fifth_title">
          友情链接 
        </div> </td>
      </tr> 
     </tbody>
    </table> 
    <div class="fifth_content"> 
     <ul> 
      <li><a href="http://www.zgzyz.org.cn/" target="_blank">中国青年志愿者网</a> </li>
      <li><a href="http://www.cydf.org.cn/" target="_blank">中国青少年发展基金会</a> </li>
      <li><a href="http://gongyi.qq.com/" target="_blank">腾讯公益</a> </li>
      <li><a href="http://www.nnvolunteer.com/" target="_blank">南宁市青年志愿者协会</a> </li> 
     </ul> 
     <div style="clear:both;"></div> 
    </div> 
   </div>
   <!--fifth end--> 
  </div>
  <!--PageBody end-->

</div><!--container end-->

<?php get_footer('home');
file_put_contents(get_stylesheet_directory().'/zzwuhome.html', ob_get_contents());

}
 ?>