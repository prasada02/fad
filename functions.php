<?php
add_action( 'after_setup_theme', 'fad_setup' );
function fad_setup()
{
load_theme_textdomain( 'fad', get_template_directory() . '/languages' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
global $content_width;
if ( ! isset( $content_width ) ) $content_width = 640;
register_nav_menus(
array( 'main-menu' => __( 'Main Menu', 'fad' ) )
);
}
add_action( 'wp_enqueue_scripts', 'fad_load_scripts' );
function fad_load_scripts()
{
wp_enqueue_script( 'jquery' );
wp_register_script( 'fad-videos', get_template_directory_uri() . '/js/videos.js' );
wp_enqueue_script( 'fad-videos' );
}
add_action( 'wp_head', 'fad_print_custom_scripts', 99 );
function fad_print_custom_scripts()
{
if ( !is_admin() ) {
?>
<script type="text/javascript">
jQuery(document).ready(function($){
$("#wrapper").vids();
});
</script>
<?php
}
}
add_action( 'comment_form_before', 'fad_enqueue_comment_reply_script' );
function fad_enqueue_comment_reply_script()
{
if ( get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}
add_filter( 'the_title', 'fad_title' );
function fad_title( $title ) {
if ( $title == '' ) {
return '&rarr;';
} else {
return $title;
}
}
add_filter( 'wp_title', 'fad_filter_wp_title' );
function fad_filter_wp_title( $title )
{
return $title . esc_attr( get_bloginfo( 'name' ) );
}
add_action( 'widgets_init', 'fad_widgets_init' );
function fad_widgets_init()
{
register_sidebar( array (
'name' => __( 'Sidebar Widget Area', 'fad' ),
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => "</li>",
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
function fad_custom_pings( $comment )
{
$GLOBALS['comment'] = $comment;
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php 
}
add_filter( 'get_comments_number', 'fad_comments_number' );
function fad_comments_number( $count )
{
if ( !is_admin() ) {
global $id;
$comments_by_type = &separate_comments( get_comments( 'status=approve&post_id=' . $id ) );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}