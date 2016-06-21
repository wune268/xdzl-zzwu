<?php
/**
 * Created by PhpStorm.
 * User: zzwu
 * Date: 2016/5/20
 * Time: 11:27
 */

add_filter('manage_news_posts_columns', 'zzwu_add_new_news_columns');

function zzwu_add_new_news_columns($book_columns) {

    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['title'] = '标题';
    $new_columns['author'] = __('Author');

    $new_columns['menu'] = __('菜单');

    $new_columns['forbes'] = __('栏目');

    $new_columns['date'] = _x('Date', 'column name');

    return $new_columns;
}

add_action('manage_news_posts_custom_column', 'zzwu_manage_news_columns', 10, 2);

function zzwu_manage_news_columns($column_name, $id) {
    global $wpdb;
    $forbesresult = $wpdb->get_results(sprintf('SELECT a.id, c.post_title, b.classifyname FROM zw_classifypost AS a JOIN zw_classify AS b ON a.classifyid = b.termid JOIN zw_posts AS c ON c.ID = b.pageid WHERE a.postid ="%s" ',$id));
    switch ($column_name) {
        case 'id':
            echo $id;
            break;

        case 'forbes':
            if(count($forbesresult))
            {
                foreach($forbesresult as $forbesresultvalue)
                {
                    $str = $forbesresultvalue->classifyname;
                }
                echo $str;
            }
            else
            {
                echo '未分类栏目';
            }
            break;
        case 'menu':
            if(count($forbesresult))
            {
                foreach($forbesresult as $forbesresultvalue)
                {
                    $str = $forbesresultvalue->post_title;
                }
                echo $str;
            }
            else
            {
                echo '未分类菜单';
            }
            break;
        default:
            break;
    }
}

