<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2016/5/14
 * Time: 13:54
 */
/**
 * 添加输出菜单描述的 Walker 类
 */
class zzwu_description_walker extends Walker_Nav_Menu
{
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= "\n<ul class=\"dropdown-menu\">\n";
    }


    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $li_attributes = $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if ( $args->has_children ) {
            $classes[] = ( 1 > $depth) ? 'dropdown': 'dropdown-submenu';
            $li_attributes .= ' data-dropdown="dropdown"';
        }

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';


        $attributes	=	$item->attr_title	? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes	.=	$item->target		? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes	.=	$item->xfn			? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes	.=	$item->url			? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes	.=	$args->has_children	? ' class="dropdown-toggle" data-toggle="dropdown"' : '';

        $item_output	=	$args->before . '<a' . $attributes . '>';
        $item_output	.=	$args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output	.=	( $args->has_children AND 1 > $depth ) ? ' <b class="caret"></b>' : '';
        $item_output	.=	'</a>' . $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }


    function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

        if ( ! $element )
            return;

        $id_field = $this->db_fields['id'];

        //display this element
        if ( is_array( $args[0] ) )
            $args[0]['has_children'] = (bool) ( ! empty( $children_elements[$element->$id_field] ) AND $depth != $max_depth - 1 );
        elseif ( is_object(  $args[0] ) )
            $args[0]->has_children = (bool) ( ! empty( $children_elements[$element->$id_field] ) AND $depth != $max_depth - 1 );

        $cb_args = array_merge( array( &$output, $element, $depth ), $args );
        call_user_func_array( array( &$this, 'start_el' ), $cb_args );

        $id = $element->$id_field;

        // descend only when the depth is right and there are childrens for this element
        if ( ( $max_depth == 0 OR $max_depth > $depth+1 ) AND isset( $children_elements[$id] ) ) {

            foreach ( $children_elements[ $id ] as $child ) {

                if ( ! isset( $newlevel ) ) {
                    $newlevel = true;
                    //start the child delimiter
                    $cb_args = array_merge( array( &$output, $depth ), $args );
                    call_user_func_array( array( &$this, 'start_lvl' ), $cb_args );
                }
                $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
            }
            unset( $children_elements[ $id ] );
        }

        if ( isset( $newlevel ) AND $newlevel ) {
            //end the child delimiter
            $cb_args = array_merge( array( &$output, $depth ), $args );
            call_user_func_array( array( &$this, 'end_lvl' ), $cb_args );
        }

        //end this element
        $cb_args = array_merge( array( &$output, $element, $depth ), $args );
        call_user_func_array( array( &$this, 'end_el' ), $cb_args );
    }
}