<?php
/*
Template Name: Twitter Registrations
*/

?>
<?php get_header() ?>
<?php
if (has_posts()) {
    while (has_posts()) {
        the_post();
?>
	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="whitebox_big">
                <div id="whitebox_big_top">
                </div><!-- #whitebox_big_top -->
                <div id="whitebox_big_body">
                <div id="whitebox_big_body_title"><?php the_title(); ?></div>
                    <div class="whitebox_big_body_nav">
                    </div><!-- whitebox_big_body_nav -->
                    <hr />
<?php the_content(); ?>
                </div><!-- #whitebox_big_body -->
                <div id="whitebox_big_bottom">
                </div><!-- #whitebox_big_bottom -->
            </div><!-- #whitebox_big -->
		</div><!-- #content -->
        <?php get_sidebar() ?>
        <div id="container_bottom"></div>
	</div><!-- #container -->
<?php
    }
}
?>
<?php get_footer() ?>
