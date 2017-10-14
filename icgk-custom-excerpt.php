<?php
/*
Plugin Name: ICGK Custom Excerpt
Plugin URI: 
Description: 投稿の抜粋の文字数と「...more（リンク付き）」を指定して表示する。get_custom_excerpt($n, $text) ＜$n:抜粋の文字数（必須）、$text:「詳細はこちら」などのリンクテキスト（空欄でリンクなし）＞
Version: 1.0.0
Author: ICHIGENKI
Author URI: 
License: GPL2
*/
$page_title = 'ICGK Custom Excerpt';
$menu_title = 'ICGK Custom Excerpt';


// 管理メニューに追加するフック
add_action('admin_menu', 'icgk_custom_excerpt_menu');

// 上のフックに対する action 関数
function icgk_custom_excerpt_menu() {
  // 「設定」下に新しいサブメニューを追加
  add_options_page('ICGK Custom Excerpt', 'ICGK Custom Excerpt', 'manage_options', 'icgk-custom-excerpt', 'icgk_custom_excerpt_options' );
}

// メニュー項目をクリックした際に表示されるページ、または画面の HTML 出力を作成
// mt_settings_page() は Test Settings サブメニューのページコンテンツを表示
function icgk_custom_excerpt_options() {

  // ユーザーが必要な権限を持つか確認する必要がある
  if ( !current_user_can('manage_options') ) {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }

  // フィールドとオプション名の変数
  $option_name = 'icgk-custom-excerpt';
  $hidden_field_name = 'mt_submit_hidden';
  global $option_data;
  // データベースから既存のオプション値を取得
  if ( get_option( $option_name ) ) {
    $option_data = get_option( $option_name );
  } else {
    $option_data = array();
  }

  // ユーザーが何か情報を POST したかどうかを確認
  // POST していれば、隠しフィールドに 'Y' が設定されている
  if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
    // POST されたデータを取得
    $option_data = $_POST[ 'icgk_excerpt' ];
    // POST された値をデータベースに保存
    update_option( $option_name, $option_data );
    // 画面に「設定は保存されました」メッセージを表示
    $saved = 'settings saved.'
    //_e($saved, 'menu-test' );
?>
<div class="updated"><p><strong>設定は保存されました</strong></p></div>
<?php
  }

  // ここで設定編集画面を表示
  echo '<div class="wrap">';
  // ヘッダー
  echo "<h2>" . __( 'ICGK Custom Excerpt', 'menu-test' ) . "</h2>";
  // 設定用フォーム
?>
<br />
<hr />

<form name="form1" method="post" action="">
<!--<form method="post" action="options.php">-->
  <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
  <?php settings_fields( 'icgk-custom-excerpt' ); ?>

  <h3>カスタム抜粋</h3>
  <table class="form-table">
    <tr valign="top">
    <th scope="row">「...」のテキスト</th>
    <td><input type="text" name="icgk_excerpt[more]" value="<?php echo $data['more']; ?>" /></td>
    </tr>
    <tr valign="top">
    <th scope="row">リンク</th>
    <td><label for="icgk_excerpt_link_y"><input type="radio" name="icgk_excerpt[link]" id="icgk_excerpt_link_y" value="y"<?php if($option_data['link'] == 'y') echo ' checked="checked"'; ?> /> あり</label>　　<label for="icgk_excerpt_link_n"><input type="radio" name="icgk_excerpt[link]" id="icgk_excerpt_link_n" value="n"<?php if($option_data['link'] == 'n') echo ' checked="checked"'; ?> /> なし</label></td>
    </tr>
    <tr valign="top">
    <th scope="row">リンクテキスト</th>
    <td><input type="text" name="icgk_excerpt[linktext]" value="<?php echo $data['linktext']; ?>" /></td>
    </tr>
  </table>
    <p class="submit"<?php echo $submit_style; ?>><input type="submit" name="submit" id="submit" class="button button-primary" value="保存" /></p>
</form>
</div>
<?php
}
// 管理画面の設定ここまで


function get_custom_excerpt($n, $text) {
  if( !$n ) $n = 72; // 指定がない場合に抜き出す文字数をセット
  $str = get_the_content();
  $str = strip_tags($str); // HTMLタグを取り除く
  $str = str_replace(array("\r\n","\n","\r"), '', $str); // 改行を取り除く
  $str = preg_replace('/\[[a-zA-Z0-9]*[ ]?[a-zA-Z0-9]*\]/i', '', $str); // ショートコードを取り除く
  $count = mb_strlen( $str ); // 文字数をカウント
  $str = mb_substr( $str , 0, $n ); // 指定数を抜き出す
  $more = ' ...';
  if( $count <= $n ) $more = '';
  echo '<p class="post-excerpt">'.$str.$more;
  if( $text ) {
    echo '<span class="post-more more"> <a class="read_more" href="'.esc_url(the_permalink()).'">'.$text.'</a></span>';
  }
  echo '</p>'."\n";
}
?>


