<?php
/*
Template Name: Blog Archive
*/

include ('local_functions.php');

$ppp = 20;

if (isset ($_GET['page'])) {
    $this_page = $_GET['page'];
    $offset = $_GET['page'] * $ppp;
} else {
    $this_page = 0;
    $offset = 0;
}

$url_add = "";

if (isset ($_GET['thistag'])) {
    $url_add .= "thistag=".$_GET['thistag']."&";
    $subquery = "tag=".$_GET['thistag']."&";
} else {
    $subquery = "";
}

$subquery .= "category=".get_cat_id ('Blog');
$fullquery = "numberposts=-1&".$subquery;
$pagequery = "numberposts=".$ppp."&offset=".$offset."&".$subquery;
if (isset ($_GET['authorid'])) {
    $url_add .= "authorid=".$_GET['authorid']."&";
    $posts_full = get_posts ($fullquery);
    $posts_auth = array();

    foreach ($posts_full as $post) {
        if ($post->post_author == $_GET['authorid']) {
            array_push ($posts_auth, $post);
        }
    }

    $number_of_posts = sizeof ($posts_auth);
    $posts = array_slice ($posts_auth, $offset, $ppp);
} else {
    $number_of_posts = sizeof (get_posts ($fullquery));
    $posts = get_posts ($pagequery);
}
?>

<?php get_header() ?>
	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="whitebox_big">
                <div id="whitebox_big_top"></div>
                <div id="whitebox_big_body">
                    <span id="whitebox_big_body_title"><h1>Blog Archive</h1></span>
                </div><!-- #whitebox_big_body -->
                <div id="whitebox_big_bottom"></div>
            </div><!-- #whitebox_big -->
            <div id="whitebox_primary">
                <div id="whitebox_primary_top">
                </div><!-- #whitebox_primary_top -->
                <div id="whitebox_primary_body">
<?php
$i = 0;

foreach ($posts as $post) {
    if (in_category ('Blog', $post)) {
        $author = get_userdata ($post->post_author);
?>
                    <div id="whitebox_primary_body_post_<?php echo $i; ?>" class="whitebox_primary_body_post">
                        <div id="whitebox_primary_body_content"><a href="<?php echo get_permalink ($post->ID); ?>"><?php echo $post->post_title; ?></a></div>
                        <hr />
                        <div class="whitebox_primary_body_attr">
                            <span id="whitebox_primary_body_attr_author" class="whitebox_primary_body_attr_item">Posted by <a href="<?php echo get_bloginfo ('url'); ?>/about/the-founders/<?php echo $author->user_login; ?>/"><?php echo the_author_meta ('display_name', $post->post_author); ?></a></span>
                            <span class="seperator">|</span>
                            <span id="whitebox_primary_body_attr_date" class="whitebox_primary_body_attr_item"><?php echo strftime ('%e %h %Y', strtotime ($post->post_date)); ?></span>
                            <span class="seperator">|</span>
                            <span id="whitebox_primary_body_attr_commenticon" class="whitebox_primary_body_attr_item"><img src="<?php echo get_bloginfo ('template_directory'); ?>/images/whitebox_primary_body_attr_comment_icon.png" /></span>
                            <span id="whitebox_primary_body_attr_comments" class="whitebox_primary_body_attr_item"><?php echo $post->comment_count." comment".(($post->comment_count == 1)?"":"s"); ?></span>
                        </div><!-- .whitebox_primary_body_attr -->
                        <hr class="solid" />
                    </div>
<?php
    $i++;
    }
}
?>
                    <div class="whitebox_primary_body_footer">
                        <span id="whitebox_primary_body_footer_archive" class="whitebox_primary_body_footer_item"><a href="<?php echo get_bloginfo ('url'); ?>/blog-archive/">All blog posts</a></span>
<?php
if ((($this_page+1)*$ppp) < $number_of_posts) {
?>
                        <a href="?<?php echo $url_add; ?>page=<?php echo ($this_page + 1); ?>">
<?php
}
?>
                            <span id="whitebox_primary_body_footer_prev_arrow" class="whitebox_primary_body_footer_item">&#9668;</span>
                            <span id="whitebox_primary_body_footer_prev_text" class="whitebox_primary_body_footer_item">Previous</span>
<?php
if ((($this_page+1)*$ppp) < $number_of_posts) {
?>
                        </a>
<?php
}
?>
                        <span class="seperator">|</span>
<?php
if ($this_page > 0) {
?>
                        <a href="?<?php echo $url_add; ?>page=<?php echo ($this_page - 1); ?>">
<?php
}
?>
                            <span id="whitebox_primary_body_footer_next_text" class="whitebox_primary_body_footer_item">Next</span>
                            <span id="whitebox_primary_body_footer_next_arrow" class="whitebox_primary_body_footer_item">&#9658;</span>
<?php
if ($this_page > 0) {
?>
                        </a>
<?php
}
?>
                    </div><!-- .whitebox_primary_body_footer -->
                </div><!-- #whitebox_primary_body -->
                <div id="whitebox_primary_bottom"></div>
            </div><!-- #whitebox_primary -->
            <div id="bluebox">
                <div id="bluebox_tab">
                    <div id="bluebox_tab_top"></div>
                    <div id="bluebox_tab_body">Tags</div>
                </div><!-- #bluebox_tab -->
                <div id="bluebox_top"></div>
                <div id="bluebox_body">
<?php
ob_start();
wp_tag_cloud("number=60");
$tag_cloud = ob_get_contents();
ob_end_clean();

//echo str_replace ("/'", "'", str_replace ("tag/", "blog-archive/?thistag=", $tag_cloud));
echo $tag_cloud;
?>
                </div><!-- bluebox_body -->
                <div id="bluebox_bottom"></div>
            </div><!-- #bluebox -->
            <div id="whitebox_secondary">
                <div id="whitebox_secondary_tabs">
                    <div id="whitebox_secondary_tabs_mostviewed" class="whitebox_secondary_tab active">
                        <div class="whitebox_secondary_tab_top"></div>
                        <div class="whitebox_secondary_tab_body">Authors</div>
                    </div>
                </div><!-- #whitebox_secondary_tabs -->
                <div id="whitebox_secondary_top"></div>
                <div id="whitebox_secondary_body">
                    <ul>
<?php
global $wpdb;
 
$authors = $wpdb->get_results("SELECT ID,user_login from $wpdb->users ORDER BY display_name");
 
foreach($authors as $author) {
    ob_start();
    the_author_meta ('display_name', $author->ID);
    $display_name = ob_get_contents();
    ob_end_clean();

    if ($display_name != "admin") {
?>
                        <!--li><?php //the_author_posts_link(); ?></li-->      
                        <li><a href="<?php echo get_bloginfo ('url'); ?>/author/<?php echo $author->user_login; ?>/"><?php echo $display_name; ?></a></li>      
<?php
    }
}
?>
                    </ul>
                </div><!-- #whitebox_secondary_body -->
                <div id="whitebox_secondary_bottom"></div>
            </div><!-- #whitebox_secondary -->
		</div><!-- #content -->
        <?php get_sidebar() ?>
        <div id="container_bottom"></div>
	</div><!-- #container -->

<?php get_footer() ?>
