<?php
ob_start();
function zzwu_register_custom_taxonomy(){

    //自定义分类法名
    $name = 'pageclassify';

    //自定义分类法的文章类型
    $post_type = 'page';

    //设置关键文本
    $labels = array(
        'name'                  => '发表栏目',
        'singular_name'         => 'classify',
        'search_items'          => '搜索栏目',
        'popular_items'         => '热门栏目',
        'all_items'             => '全部栏目',
        'parent_item'           => '父级栏目',
        'parent_item_colon'     => '父级栏目：',
        'edit_item'             => '编辑栏目',
        'update_item'           => '更新栏目',
        'add_new_item'          => '新建栏目',
        'new_item_name'         => '栏目名称',
        'add_or_remove_items'   => '添加或删除栏目',
        'choose_from_most_used' => '从经常使用的栏目中选择',
        'menu_name'             => '栏目'
    );

    //详细配置自定义分类法
    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => true,
        'show_admin_column' => true
    );

    //使用 register_taxonomy() 函数注册自定义分类法
    register_taxonomy( $name, $post_type, $args );

}
add_action( 'init', 'zzwu_register_custom_taxonomy' );//必须把操作函数挂载到 init 钩子上

function zzwu_custom_post_news() {
    $labels = array(
        'name'               => _x( '新闻', 'post type 名称' ),
        'singular_name'      => _x( '新闻', 'post type 单个 item 时的名称，因为英文有复数' ),
        'add_new'            => _x( '发表新闻', '添加新内容的链接名称' ),
        'add_new_item'       => __( '新建一个新闻' ),
        'edit_item'          => __( '编辑新闻' ),
        'new_item'           => __( '新新闻' ),
        'all_items'          => __( '所有新闻' ),
        'view_item'          => __( '查看新闻' ),
        'search_items'       => __( '搜索新闻' ),
        'not_found'          => __( '没有找到有关新闻' ),
        'not_found_in_trash' => __( '回收站里面没有相关新闻' ),
        'parent_item_colon'  => '',
        'menu_name'          => '新闻',
    );
    $args = array(
        'labels'        => $labels,
        'description'   => '我们网站的新闻动态',
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array( 'title', 'editor', 'thumbnail'),
        'has_archive'   => true
    );
    register_post_type( 'news', $args );
}
add_action( 'init', 'zzwu_custom_post_news' );

add_action( 'add_meta_boxes', 'zzwu_news_director' );

function zzwu_news_director($post_type) {
    // 需要哪些post type添加Meta Box
    $types = array( 'news', 'post' );

    foreach ( $types as $type ) {
        add_meta_box(
            'zzwu_forbes_meta_box_id', // Meta Box在前台页面中的id，可通过JS获取到该Meta Box
            '发表栏目', // 显示的标题
            'zzwu_forbes_meta_box', // 回调方法，用于输出Meta Box的HTML代码
            $type, // 在哪个post type页面添加
            'side', // 在哪显示该Meta Box
            'default' // 优先级
        );
    }
}

function zzwu_forbes_meta_box($post) {
    // 添加 nonce 项用于后续的安全检查
    wp_nonce_field( 'forbes_nonce_action', 'forbes_nonce_name' );

    // 获取栏目的值
    $forbes_key = 'forbes';
    $classify_key = 'classify';
    $forbes_value = get_post_meta( $post->ID, $forbes_key, true );
    $forbes_value = (int)$forbes_value;
    $classify_value = get_post_meta($post->ID, $classify_key, true);
    $classify_value = (int)$classify_value;
    $html = '<select name="forbes_field" id="forbes" onchange="Change()"><option value="0">请选择栏目</option>';

    global $wpdb;
    $forbesresult = $wpdb->get_results("SELECT ID, post_title FROM zw_posts WHERE post_type = 'page' AND post_status = 'publish' ORDER BY menu_order");
    foreach($forbesresult as $value)
    {
        $selected = '';
        if ($value->ID == $forbes_value) {
            $selected = 'selected="selected"';
        }
        if($value->post_title == '新闻投稿')
        {
            continue;
        }
        $html .= sprintf('<option value="%s" %s>%s</option>', $value->ID, $selected, $value->post_title);
    }
    $html .= '</select>
<script>
function Change(){
        var forbesvalue=document.getElementById("forbes");
        var index=forbesvalue.selectedIndex ;
        var classifyid = forbesvalue.options[index].value;

        jQuery(document).ready(function($){
        var data={
            action:"say",
            classifyid:classifyid
        }
        $.post("'.admin_url('admin-ajax.php').'", data, function(response) {
                if(response=="0"){
                }
                else{
                    $("#classify").children().remove();
					$.each(response, function(commentIndex, comment){
						var classifyid = comment["termid"];
						var classifyname = comment["classifyname"];
						$("#classify").append(\'<option value="\' + classifyid + \'">\' + classifyname + \'</option>\')

					});
                }
        });
    });
};
</script>
';

    $html .= '<br><hr style="height:1px;border:none;border-top:1px dashed #0066CC;" /><span>选择子栏目</span><hr style="height:1px;border:none;border-top:1px dashed #0066CC;" /><select name="classify_field" id="classify">';

    $html .= sprintf('<option value="%s">默认栏目</option>', $classify_value);
    $html .= '</select>';

    echo $html;
}

//  保存自定义栏目子分类
function zzwu_save_page_data($post_id)
{
    if (!current_user_can('edit_post', $post_id )) {
        return $post_id;
    }

    $pageclassify = $_POST['tax_input']['pageclassify'];

    global $wpdb;
    $termdeletenamearray = array();
    $termdeletename = $wpdb->get_results(sprintf('SELECT id, termid FROM zw_classify WHERE pageid = %s', $_POST['ID']));
    $tag = false;
    if(count($pageclassify) == 1)
    {
        $tag = true;
    }
    foreach($pageclassify as $value)
    {
        if($value == '0')
        {
            continue;
        }
        $termid = $wpdb->get_results(sprintf('SELECT a.id, a.termid, a.pageid, b.`name` FROM zw_classify AS a JOIN zw_terms AS b ON a.termid = b.term_id WHERE termid = %s AND pageid = %s',$value, $_POST['ID']));
        if(count($termid))
        {
            foreach($termid as $termidvalue)
            {
                $termdeletenamearray[] = $value;
                $wpdb->update('zw_classify', array('pageid'=>$_POST['ID'], 'termid'=>$value, 'classifyname'=>$termidvalue->name, 'id'=>$termidvalue->id));
            }
        }
        else
        {
            $termname = $wpdb->get_results(sprintf('SELECT term_id, `name` FROM zw_terms WHERE term_id = %s', $value));
            foreach($termname as $termnamevalue)
            {
                $wpdb->insert('zw_classify', array('pageid'=>$_POST['ID'], 'termid'=>$value, 'classifyname'=>$termnamevalue->name));
            }
        }
    }
    if(count($termdeletenamearray)&&count($termdeletename) || $tag)
    {
        foreach($termdeletename as $termdeletenamevalue)
        {
            if(!in_array($termdeletenamevalue->termid, $termdeletenamearray))
            {
                $wpdb->delete('zw_classify', array('termid'=>$termdeletenamevalue->termid, 'pageid'=>$_POST['ID']));
            }
        }
    }
}

function zzwu_save_classify_data($post_id)
{
    if (!current_user_can('edit_post', $post_id )) {
        return $post_id;
    }

    $classify_value = (int)$_POST['classify_field'];
    global $wpdb;

    $classresult = $wpdb->get_results(sprintf('SELECT id, postid, classifyid FROM zw_classifypost WHERE postid = %s', $_POST['ID']));

    if(count($classresult))
    {
        foreach($classresult as $classresultvalue)
        {
            $classresultvalueid = $classresultvalue->id;
            $wpdb->query(sprintf('UPDATE zw_classifypost SET classifyid = %s WHERE postid = %s', $classify_value, $_POST['ID']));
        }
    }
    else
    {
        $wpdb->insert('zw_classifypost', array('postid'=>$_POST['ID'], 'classifyid'=>$classify_value));
    }
}

function zzwu_save_post_data( $post_id ) {
    // 如果是系统自动保存，则不操作
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    if($_POST['post_type'] == 'page')
    {
        zzwu_save_page_data($post_id);
    }

    if(isset($_POST['classify_field']))
    {
        zzwu_save_classify_data($post_id);
    }

    // 检查nonce是否设置
    if (!isset($_POST['forbes_nonce_name'])) {
        return $post_id;
    }
    $nonce = $_POST['forbes_nonce_name'];

    // 验证nonce是否正确
    if (!wp_verify_nonce( $nonce, 'forbes_nonce_action')) {
        return $post_id;
    }

    // 检查用户权限
    if ($_POST['post_type'] == 'post') {
        if (!current_user_can('edit_post', $post_id )) {
            return $post_id;
        }
    }

    $classify_key = 'classify';
    // 获取数据
    $classify_value = $_POST['classify_field'];

    $forbes_key = 'forbes';
    // 获取数据
    $forbes_value = $_POST['forbes_field'];

    // 更新数据
    update_post_meta( $post_id, $forbes_key, $forbes_value );
    // 更新子栏目
    update_post_meta( $post_id, $classify_key, $classify_value );

    $homescrol_key = 'homescrol';
    // 获取数据
    $homescrol_value = $_POST['homescrol_field'];

    // 更新数据
    update_post_meta( $post_id, $homescrol_key, $homescrol_value );
}
add_action( 'save_post', 'zzwu_save_post_data' );



if(current_user_can('level_10')){

//添加首页滚动图
    add_action( 'add_meta_boxes', 'zzwu_home_scrol_director' );
}


function zzwu_home_scrol_director($post_type) {
    // 需要哪些post type添加Meta Box
    $types = array('post', 'news');

    foreach ( $types as $type ) {
        add_meta_box(
            'zzwu_home_scrol_meta_box_id', // Meta Box在前台页面中的id，可通过JS获取到该Meta Box
            '首页滚动图', // 显示的标题
            'zzwu_home_scrol_meta_box', // 回调方法，用于输出Meta Box的HTML代码
            $type, // 在哪个post type页面添加
            'side', // 在哪显示该Meta Box
            'default' // 优先级
        );
    }
}

function zzwu_home_scrol_meta_box($post)
{
    // 添加 nonce 项用于后续的安全检查
    wp_nonce_field( 'home_scrol_nonce_action', 'home_scrol_nonce_name' );

    // 获取栏目的值
    $homescrol_key = 'homescrol';
    $homescrol_value = get_post_meta( $post->ID, $homescrol_key, true );
    $homescrol_value = (int)$homescrol_value;
    $html = '<select name="homescrol_field">';

    $homescrolarray = array();

    for($i = 0; $i <=1; $i ++)
    {
        $homescrol = new stdClass();
        $homescrol->id = $i;
        $homescrol->name = ($i == 1) ? '设为首页滚动图' : '默认';
        $homescrolarray[] = $homescrol;
    }

    foreach($homescrolarray as $homescrolvalue)
    {
        $selected = '';
        if ($homescrolvalue->id == $homescrol_value)
        {
            $selected = 'selected="selected"';
        }
        $html .= sprintf('<option value="%s" %s>%s</option>', $homescrolvalue->id, $selected, $homescrolvalue->name);
    }
    $html .= '</select>';
    echo $html;
}
