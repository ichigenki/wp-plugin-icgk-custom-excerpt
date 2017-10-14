<?php
/*
Plugin Name: ICGK Custom Excerpt
Plugin URI: 
Description: 投稿の抜粋の文字数と「...more（リンク付き）」を指定して表示する。get_custom_excerpt($n, $text, $class) ＜$n:抜粋の文字数（必須）、$text:「詳細はこちら」などのリンクテキスト（空欄でリンクなし）、$class:aタグのクラス名＞
Version: 1.0.1
Author: ICHIGENKI
Author URI: 
License: GPL2
*/

function get_custom_excerpt($n=72, $text='', $class='') {
  $str = get_the_content();
  $str = strip_tags($str); // HTMLタグを取り除く
  $str = str_replace(array("\r\n","\n","\r"), '', $str); // 改行を取り除く
  $str = preg_replace('/\[[a-zA-Z0-9]*[ ]?[a-zA-Z0-9]*\]/i', '', $str); // ショートコードを取り除く
  $count = mb_strlen( $str ); // 文字数をカウント
  $str = mb_substr( $str , 0, $n ); // 指定数を抜き出す
  echo $str;
  if( $count > $n ) echo ' ...';
  if( $text ) echo '<span class="post-more more"> <a class="read_more '.$class.'" href="'.esc_url(the_permalink()).'">'.$text.'</a></span>';
}
?>


