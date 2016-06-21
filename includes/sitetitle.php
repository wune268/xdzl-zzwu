<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2016/6/9
 * Time: 9:18
 */
?>
<?php if ( is_home() ) { ?><title><?php bloginfo('name'); ?> | <?php bloginfo('description'); ?></title><?php } ?>
<?php if ( is_single() ) { ?><title><?php echo trim(wp_title('',0)); ?> | <?php bloginfo('name'); ?></title><?php } ?>
<?php if ( is_page() ) { ?><title><?php echo trim(wp_title('',0)); ?> | <?php bloginfo('name'); ?></title><?php } ?>
<?php if ( is_404() ) { ?><title><?php echo"未找到指定的页面" ?> | <?php bloginfo('name'); ?></title><?php } ?>