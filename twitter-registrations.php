<?php
/*
Template Name: Twitter Registrations
*/

?>
<?php get_header() ?>
<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();

        $maxid = 0;

        if ($msql = mysql_connect("localhost", "twats_twats", "tW4Ts!")) {
            if (mysql_select_db("twats_twats", $msql)) {
                if ($resource = mysql_query("SELECT MAX(`id`) FROM `users`;", $msql)) {
                    if ($row = mysql_fetch_array($resource)) {
                        $maxid = $row[0];
                    }
                }
            }
        }

        ob_start();
        the_content();
        $content = ob_get_contents();
        ob_end_clean();
?>
	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="whitebox_big">
                <div id="whitebox_big_top">
                </div><!-- #whitebox_big_top -->
                <div id="whitebox_big_body">
                <div id="whitebox_big_body_title"><?php echo $maxid?"Twitter now has ".$maxid." registered users.":"Twitter Users"; ?></div>
                    <div class="whitebox_big_body_nav">
                    </div><!-- whitebox_big_body_nav -->
                    <hr />
                    <?php echo $content; ?>
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
