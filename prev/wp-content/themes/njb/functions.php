<?php


remove_filter('the_content', 'wpautop');
remove_filter('the_excerpt', 'wpautop');

// WordPress管理画面にサムネイル機能のインターフェース追加
if ( function_exists( 'add_theme_support' ) )
add_theme_support( 'post-thumbnails' );

function disable_visual_editor_in_page(){
  global $typenow;
  if( $typenow == 'page' ){
    add_filter('user_can_richedit', 'disable_visual_editor_filter');
  }
}
function disable_visual_editor_filter(){
  return false;
}
add_action( 'load-post.php', 'disable_visual_editor_in_page' );
add_action( 'load-post-new.php', 'disable_visual_editor_in_page' );

//記事本文の文字数の指定抜出し関数
function len_excerpt($length) {
global $post;
$content =  mb_substr(strip_tags($post->post_content),0,$length);
return $content;
}

 
function Include_my_php($params = array()) {
    extract(shortcode_atts(array(
        'file' => 'default'
    ), $params));
    ob_start();
    include(get_theme_root() . '/' . get_template() . "/$file.php");
    return ob_get_clean();
}
 
add_shortcode('myphp', 'Include_my_php');


//ショートコード
//ディレクトリーパス　呼び出し：[tplpass]
function tplDirectryPass() {
	return get_bloginfo('template_directory');
}
add_shortcode('tplpass', 'tplDirectryPass');


register_sidebar(array(
     'name' => 'Footer' ,
     'id' => 'footer' ,
));