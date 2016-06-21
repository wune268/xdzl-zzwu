<?php
get_header(); ?>

    <div id="container">
        <div id="current">
            <table><tbody><tr><td class="con_hight"></td></tr>
                <tr><td>
                        <div class="current_zi"><strong>当前位置:</strong> <a href="<?php bloginfo('url'); ?>">首页</a> &gt; <a href="<?php echo get_the_guid();?>"><?php echo the_title();?></a> &gt; </div>
                    </td></tr></tbody></table>
        </div>


<?php if (have_posts()) : while (have_posts()) : the_post(); zzwu_setPostViews(get_the_ID()); ?>
        <div style="width: 700px;background-color: #fdfdfb; float: left; margin-top: 10px; text-align:center; min-height: 950px;" >

                <div class="yuanjiao">
                </div>
                <div class="main_content">
                    <div class="content_title"><?php the_title() ?></div>
                    <div class="info">时间：<?php the_time('Y-m-d'); ?>&nbsp;&nbsp; 来源作者：<?php the_author(); ?> &nbsp;&nbsp; 点击：<?php echo zzwu_getPostViews(get_the_ID()); ?>次
                    </div>
                    <div class="hr"></div>
                    <div class="article">
                        <?php the_content(); ?>
                    </div>
                </div>


                <div class="yuanjiao2">
                </div>
        </div>
<?php endwhile; ?>
<?php endif;
$gonggao = zzwu_get_post_pagename_class('公告通知', 9);
?>

        <div style="margin-top: 10px; width: 270px; float: right">
            <!-- 公告通知 -->
            <div class="first_r">
                <div class="first_r_con" style="width: 95%">
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
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="con_hight"></td>
                        </tr>
                        <tr>
                            <td class="con_hight"></td>
                        </tr>

                        <?php if(count($gonggao->posts))
                        {
                            $i = 1;
                            $str = '';
                            foreach($gonggao->posts as $gonggaovalue)
                            {
                                $i ++;
                                if($i == 5)
                                {
                                    $str .= '<tr><td height="10px"></td></tr>';
                                }
                                $str .= '<tr><td class="arc_title"><a href="'.$gonggaovalue->guid.'">'.zzwu_substr($gonggaovalue->post_title, $gonggaovalue->post_date, 15).'</a></td></tr>';
                            }
                            echo $str;
                        }?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 资料下载 -->
            <div class="second_r" style="margin-top: 10px; ">
                <div class="first_r_con" style="width: 95%">
                    <table>
                        <tbody><tr><td class="con_hight"></td></tr>
                        <tr><td>
                                <div style="width: 80px; float: left; font-size: 15px; font-weight: bold;">
                                    <span>资料下载</span>
                                </div>
                                <div class="more" style="border-bottom:#009966 1px solid;">
                                    <a href="<?php echo $gonggao->class[0]->guid; ?>">更多&gt;&gt;</a>
                                </div>
                            </td>
                        </tr>
                        <tr><td class="con_hight"></td></tr>
                        <tr><td class="con_hight"></td></tr>

                        <?php $attachmentresult = zzwu_attachment_news(20);
                        if(count($attachmentresult))
                        {
                            foreach($attachmentresult as $attachmentresultvalue)
                            {?>
                                <tr><td class="arc_title"><a href="<?php echo $attachmentresultvalue->guid; ?>"><?php echo $attachmentresultvalue->post_title; ?></a></td></tr>
                        <?php } }?>
                        </tbody></table>
                </div>
            </div>
        </div>

    </div>

<div style="clear: both; height: 20px;"></div>


<?php get_footer(); ?>