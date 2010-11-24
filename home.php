<?php
/*
Template Name: Home
*/

include ('local_functions.php');

$blogposts = get_posts ("numberposts=-1&category=".get_cat_id ('Blog'));
?>

<?php get_header() ?>

    <script type="text/javascript">
<?php
$work_categories = get_categories (array ('child_of'=>get_cat_id ('RAAK projects'), 'orderby'=>'slug', 'order'=>'desc'));
?>
    <!--
    var post_count = <?php echo sizeof ($blogposts); ?>;
    var curr_page = 0;

    function expand (id) {
        var elements_to_hide = new Array();
<?php
$i = 0;

foreach ($work_categories as $c) {
?>
        elements_to_hide[<?php echo $i; ?>] = '<?php echo $c->slug; ?>';
<?php
    $i++;
}
?>

        var element_to_expand = document.getElementById (id);
        var menu_item_to_activate = document.getElementById ("bluebox_body_nav_"+id);
        var element_to_hide;

        for (i = 0; i < <?php echo $i; ?>; i++) {
            element_to_hide = document.getElementById(elements_to_hide[i]);
            menu_item_to_deactivate = document.getElementById("bluebox_body_nav_"+elements_to_hide[i]);
            if (element_to_hide.style.display != 'none') {
                element_to_hide.style.display = 'none';
            }
            if (menu_item_to_deactivate.className == "bluebox_body_nav_item active") {
                menu_item_to_deactivate.className = "bluebox_body_nav_item";
            }
        }

        element_to_expand.style.display = 'block';
        menu_item_to_activate.className = "bluebox_body_nav_item active";
    }

    function previous () {
        if ((post_count - curr_page) > 2) {
            var element_to_hide = document.getElementById ("whitebox_primary_body_post_"+curr_page);
            curr_page += 2;
            var element_to_show = document.getElementById ("whitebox_primary_body_post_"+curr_page);
            element_to_hide.style.display = 'none';
            element_to_show.style.display = 'block';
            if ((post_count - curr_page) > 2) {
                document.getElementById('whitebox_primary_body_footer_prev_text').className = "whitebox_primary_body_footer_item active";
            } else {
                document.getElementById('whitebox_primary_body_footer_prev_text').className = "whitebox_primary_body_footer_item";
            }
            if (curr_page > 1) {
                document.getElementById('whitebox_primary_body_footer_next_text').className = "whitebox_primary_body_footer_item active";
            } else {
                document.getElementById('whitebox_primary_body_footer_next_text').className = "whitebox_primary_body_footer_item";
            }
        }
    }

    function next () {
        if (curr_page > 1) { 
            var element_to_hide = document.getElementById ("whitebox_primary_body_post_"+curr_page);
            curr_page -= 2;
            var element_to_show = document.getElementById ("whitebox_primary_body_post_"+curr_page);
            element_to_hide.style.display = 'none';
            element_to_show.style.display = 'block';
            if ((post_count - curr_page) > 2) {
                document.getElementById('whitebox_primary_body_footer_prev_text').className = "whitebox_primary_body_footer_item active";
            } else {
                document.getElementById('whitebox_primary_body_footer_prev_text').className = "whitebox_primary_body_footer_item";
            }
            if (curr_page > 1) {
                document.getElementById('whitebox_primary_body_footer_next_text').className = "whitebox_primary_body_footer_item active";
            } else {
                document.getElementById('whitebox_primary_body_footer_next_text').className = "whitebox_primary_body_footer_item";
            }
        }
    }

    //-->
    </script>
	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="logox_counter">
                <div id="logox_counter_top">CURRENT LOGO ITERATIONS</div>
<?php
global $wpdb;

$lettercount = array ();
$lettercount['R'] = 0;
$lettercount['A'] = 0;
$lettercount['K'] = 0;

$uploads = $wpdb->get_results ('SELECT * FROM `wp_logo_uploads` ORDER BY `timestamp` DESC');

foreach ($uploads as $upload) {
    if ($upload->confirmed == 1) {
        $lettercount[$upload->letter]++;
    }
}
?>
                <div id="logox_counter_number"><?php echo $lettercount['R']*$lettercount['A']*$lettercount['A']*$lettercount['K']; ?></div>
                <div id="logox_counter_body">
                    <hr />
                    <a href="<?php echo get_bloginfo ('url'); ?>/2009/07/the-story-behind-our-crowd-sourced-raak-logo/">Read the story behind our logo</a>
                    <hr />
                </div>
                <div id="logox_counter_bottom"><a href="<?php echo get_bloginfo ('template_directory'); ?>/logo-project/">Upload a letter</a></div>
            </div><!-- #logox_counter -->
            <div id="bluebox_home">
                <div id="bluebox_home_top"></div>
                <div id="bluebox_home_body">
                <div id="bluebox_home_body_header"><a href="<?php echo get_bloginfo ('url'); ?>/our-work/">Our Work</a></div>
                    <hr />
                    <div id="bluebox_home_body_content"><?php the_post(); the_content(); ?></div>
                </div>
                <div id="bluebox_home_bottom"></div>
            </div><!-- #bluebox_home -->
            <div id="bluebox">
                <div id="bluebox_top"></div>
                <div id="bluebox_body">
                    <div id="bluebox_body_nav">
<?php
$seperator = FALSE;
$project_types = array();

$random = rand (0, (sizeof ($work_categories) - 1));

foreach ($work_categories as $c) {
    if ($seperator) {
?>
                        <span class="seperator">|</span>
<?php
    }
?>
                        <span id="bluebox_body_nav_<?php echo $c->slug; ?>" class="bluebox_body_nav_item<?php if ($c->slug == $work_categories[$random]->slug) { echo " active"; } ?>"><a onclick="javascript: expand('<?php echo $c->slug; ?>');"><h3><?php echo $c->name; ?></h3></a></span>
<?php
    $seperator = TRUE;
    $project_types[$c->slug] = $c->name;
}
?>
                    </div>
<?php
/*$project_types = array(
    "social-media"=>"Social Media",
    "digital"=>"Digital",
    "strategy"=>"Strategy",
    "concepts"=>"Concepts"
);*/

foreach ($project_types as $project_id=>$project_category) {
    $posts = get_posts ('numberposts=1&category='.get_cat_id ($project_category));
?>
                    <div class="bluebox_body_content" id="<?php echo $project_id; ?>"<?php if ($project_id != $work_categories[$random]->slug) { echo " style=\"display: none\""; } ?>>
                        <div class="bluebox_body_content_picture">
                            <a href="<?php echo get_permalink ($posts[0]->ID); ?>">
<?php
    echo get_image_or_video ($posts[0]->post_content, 315);
?>
                            </a>
                        </div>
<?php
$client = get_post_meta ($posts[0]->ID, 'Client', true);
?>
                        <div class="bluebox_body_content_sub">
                            <span class="bluebox_body_content_sub_title">Client:</span>
                            <span class="bluebox_body_content_sub_text"><?php echo $client; ?></span>
                        </div>
<?php
$project = get_post_meta ($posts[0]->ID, 'Project', true);
?>
                        <div class="bluebox_body_content_sub">
                            <span class="bluebox_body_content_sub_title">Project:</span>
                            <span class="bluebox_body_content_sub_text"><?php echo $project; ?></span>
                        </div>
<?php
$overview = get_post_meta ($posts[0]->ID, 'Overview', true);
?>
                        <div class="bluebox_body_content_sub">
                            <span class="bluebox_body_content_sub_title">Overview:</span>
                            <span class="bluebox_body_content_sub_text bluebox_body_content_sub_overview"><?php echo $overview; ?></span>
                        </div>
                        <div class="bluebox_body_content_link">
                            <span class="bluebox_body_content_link_text"><a href="<?php echo get_bloginfo ('url'); ?>/our-work/?category=<?php echo $project_id; ?>" rel="nofollow">More Projects</a></span><span class="bluebox_body_content_link_arrow"><a href="<?php echo get_bloginfo ('url'); ?>/our-work/?category=<?php echo $project_id; ?>" rel="nofollow">&#9660;</a></span>
                        </div>
                    </div>
<?php
}
?>
                </div>
                <div id="bluebox_bottom"></div>
            </div><!-- #bluebox -->
            <div id="whitebox_primary">
                <div id="whitebox_primary_tab">
                    <div id="whitebox_primary_tab_top">
                    </div><!-- #whitebox_primary_tab_top -->
                    <div id="whitebox_primary_tab_body">
                        Latest Posts
                    </div><!-- #whitebox_primary_tab_body -->
                </div><!-- #whitebox_primary_tab -->
                <div id="whitebox_primary_top">
                </div><!-- #whitebox_primary_top -->
                <div id="whitebox_primary_body">
<?php
$i = 0;

foreach ($blogposts as $post) {
    $author = get_userdata ($post->post_author);

    if (($i % 2) == 0) {
?>
                    <div id="whitebox_primary_body_post_<?php echo $i; ?>" class="whitebox_primary_body_post"<?php if ($i != 0) { echo " style=\"display: none;\""; } ?>>
<?php
    }
?>
                        <div id="whitebox_primary_body_title"><a href="<?php echo get_permalink ($post->ID); ?>"><?php echo $post->post_title; ?></a></div>
                        <hr />
                        <div class="whitebox_primary_body_attr">
                            <span id="whitebox_primary_body_attr_author" class="whitebox_primary_body_attr_item">Posted by <a href="<?php echo get_bloginfo ('url'); ?>/about/the-founders/<?php echo $author->user_login; ?>/"><?php echo $author->first_name." ".$author->last_name; ?></a></span>
                            <span class="seperator">|</span>
                            <span id="whitebox_primary_body_attr_date" class="whitebox_primary_body_attr_item"><?php echo strftime ('%e %h %Y', strtotime ($post->post_date)); ?></span>
                            <span class="seperator">|</span>
                            <!--span id="whitebox_primary_body_attr_time" class="whitebox_primary_body_attr_item"><?php echo strftime ('%H:%M', strtotime ($post->post_date)); ?></span>
                            <span class="seperator">|</span-->
                            <span id="whitebox_primary_body_attr_commenticon" class="whitebox_primary_body_attr_item"><img src="<?php echo get_bloginfo ('template_directory'); ?>/images/whitebox_primary_body_attr_comment_icon.png" /></span>
                            <span id="whitebox_primary_body_attr_comments" class="whitebox_primary_body_attr_item"><?php echo $post->comment_count." comment".(($post->comment_count == 1)?"":"s"); ?></span>
                        </div><!-- .whitebox_primary_body_attr -->
                        <div class="whitebox_primary_body_content">
                            <div class="whitebox_primary_body_content_left">
                                <?php echo $post->post_excerpt; ?>
                            </div><!-- .whitebox_primary_body_content_left -->
                            <div class="whitebox_primary_body_content_right">
                                <div class="whitebox_primary_body_content_right_image">
                                    <a href="<?php echo get_permalink ($post->ID); ?>"><?php echo get_image_or_video ($post->post_content, 162, 104); ?></a>
                                </div><!-- .whitebox_primary_body_content_right_image -->
                                <div class="whitebox_primary_body_content_right_link">
                                    <a href="<?php echo get_permalink ($post->ID); ?>">More &#9658;</a>
                                </div><!-- .whitebox_primary_body_content_right_link -->
                            </div><!-- .whitebox_primary_body_content_right -->
                        </div><!-- .whitebox_primary_body_content -->
                        <hr class="solid" />
<?php
    if (($i % 2) == 1) {
?>
                    </div>
<?php
    }

    $i++;
}

if (($i % 2) == 1) {
?>
                    </div>
<?php
}
?>
                    <div class="whitebox_primary_body_footer">
                        <span id="whitebox_primary_body_footer_archive" class="whitebox_primary_body_footer_item"><a href="<?php echo get_bloginfo ('url'); ?>/blog-archive/">All blog posts</a></span>
                        <a onclick="javascript: previous();">
                            <span id="whitebox_primary_body_footer_prev_arrow" class="whitebox_primary_body_footer_item">&#9668;</span>
                            <span id="whitebox_primary_body_footer_prev_text" class="whitebox_primary_body_footer_item<?php if (sizeof($blogposts) > 2) { echo " active"; } ?>">Previous</span>
                        </a>
                        <span class="seperator">|</span>
                        <a onclick="javascript: next();">
                            <span id="whitebox_primary_body_footer_next_text" class="whitebox_primary_body_footer_item">Next</span>
                            <span id="whitebox_primary_body_footer_next_arrow" class="whitebox_primary_body_footer_item">&#9658;</span>
                        </a>
                    </div><!-- .whitebox_primary_body_footer -->
                </div><!-- #whitebox_primary_body -->
                <div id="whitebox_primary_bottom"></div>
            </div><!-- #whitebox_primary -->
            <div id="whitebox_secondary">
                <div id="whitebox_secondary_tab">
                    <div id="whitebox_secondary_tab_top">
                    </div><!-- #whitebox_secondary_tab_top -->
                    <div id="whitebox_secondary_tab_body">
                        Other Posts
                    </div><!-- #whitebox_secondary_tab_body -->
                </div><!-- #whitebox_secondary_tab -->
                <div id="whitebox_secondary_top">
                </div><!-- #whitebox_secondary_top -->
                <div id="whitebox_secondary_body">
                    <div id="whitebox_secondary_body_header">
                        Must Reads
                    </div>
                    <div id="whitebox_secondary_body_content_one">
                        <ul>
<?php
$posts = get_posts ('numberposts=5&category='.get_cat_id ('Must Read'));

foreach ($posts as $post) {
?>
                            <li><a href="<?php echo get_permalink ($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
<?php
}
?>
                        </ul>
                        <div id="whitebox_secondary_body_content_one_footer">
<?php
$cat_link = get_bloginfo ('url')."/category/".get_category(get_cat_id('Must read'))->slug."/";
?>
                            <span class="bluebox_body_content_link_text"><a href="<?php echo $cat_link; ?>" rel="nofollow">More</a></span><span class="bluebox_body_content_link_arrow"><a href="<?php echo $cat_link; ?>" rel="nofollow">&#9660;</a></span>
                        </div>
                    </div><!-- #whitebox_secondary_body_content_one -->
                    <div id="whitebox_secondary_body_content_two">
                        <div id="whitebox_secondary_body_header">
                            Worth a Look
                        </div>
                        <ul>
<?php
$posts = get_posts ('numberposts=3&category='.get_cat_id ('Worth a look'));

foreach ($posts as $post) {
?>
                            <li><a href="<?php echo get_permalink ($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
<?php
}
?>
                        </ul>
                        <div id="whitebox_secondary_body_content_two_footer">
<?php
$cat_link = get_bloginfo ('url')."/category/".get_category(get_cat_id('Worth a look'))->slug."/";
?>
                            <span class="bluebox_body_content_link_text"><a href="<?php echo $cat_link; ?>" rel="nofollow">More</a></span><span class="bluebox_body_content_link_arrow"><a href="<?php echo $cat_link; ?>" rel="nofollow">&#9660;</a></span>
                        </div>
                    </div><!-- #whitebox_secondary_body_content_two -->
                    <div id="whitebox_secondary_body_content_three">
                        <div id="whitebox_secondary_body_header">
                            The RAAKonteur
                        </div>
                        <ul>
<?php
$posts = get_posts ('numberposts=3&category='.get_cat_id ('RAAKonteur'));

foreach ($posts as $post) {
?>
                            <li><a href="<?php echo get_permalink ($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
<?php
}
?>
                        </ul>
                        <div id="whitebox_secondary_body_content_three_footer">
<?php
$cat_link = get_bloginfo ('url')."/category/".get_category(get_cat_id('RAAKonteur'))->slug."/";
?>
                            <span class="bluebox_body_content_link_text"><a href="<?php echo $cat_link; ?>" rel="nofollow">More</a></span><span class="bluebox_body_content_link_arrow"><a href="<?php echo $cat_link; ?>" rel="nofollow">&#9660;</a></span>
                        </div>
                    </div><!-- #whitebox_secondary_body_content_three -->
                </div><!-- #whitebox_secondary_body -->
                <div id="whitebox_secondary_bottom">
                </div><!-- #whitebox_secondary_bottom -->
            </div><!-- #whitebox_secondary -->
		</div><!-- #content -->
        <?php get_sidebar() ?>
        <div id="container_bottom"></div>
	</div><!-- #container -->

<?php get_footer() ?>
