<?php
/*
Template Name: Work
*/

include ('local_functions.php');

?>

<?php get_header() ?>

    <script type="text/javascript">
    <!--
    var current_page = 1;

    function mouse_action (category, id, state) {
        if (state == 'over') {
            picstate = 'none';
            ovwstate = 'block';
        } else {
            picstate = 'block';
            ovwstate = 'none';
        }
        document.getElementById('post_'+category+'_'+id+'_picture').style.display=picstate;
        document.getElementById('post_'+category+'_'+id+'_overview').style.display=ovwstate;
    }

    function page (slug, page, maxpage) {
        if (page <= maxpage) {
            var element_to_show = document.getElementById('whitebox_big_body_'+slug+'_'+page);
            var element_to_hide;

            for (var p = 1; p <= maxpage; p++) {
                document.getElementById('whitebox_nav_'+p).className = 'whitebox_big_body_category_page_item';
                element_to_hide = document.getElementById('whitebox_big_body_'+slug+'_'+p);
                element_to_hide.style.display = 'none';
            }

            element_to_show.style.display = 'block';
            current_page = page;
            document.getElementById('whitebox_nav_'+page).className = 'whitebox_big_body_category_page_item active';
        }
    }

    function expand (id) {
        var elements_to_hide = new Array();

        elements_to_hide[0] = 'all-projects';
<?php
$work_categories = get_categories (array ('child_of'=>get_cat_id ('RAAK projects'), 'orderby'=>'slug', 'order'=>'desc'));

$i = 1;

$project_types = array();
$project_types['all-projects'] = "RAAK projects";

foreach ($work_categories as $c) {
    $project_types[$c->slug] = $c->name;
?>
        elements_to_hide[<?php echo $i; ?>] = '<?php echo $c->slug; ?>';
<?php
    $i++;
}
?>

        var element_id = 'whitebox_big_body_'+id;
        var menu_id = 'whitebox_big_body_nav_'+id;
        var element_to_expand = document.getElementById (element_id);
        var menu_item_to_activate = document.getElementById (menu_id);
        var element_to_hide;

        for (i = 0; i < <?php echo $i; ?>; i++) {
            element_to_hide = document.getElementById('whitebox_big_body_'+elements_to_hide[i]);
            menu_item_to_deactivate = document.getElementById("whitebox_big_body_nav_"+elements_to_hide[i]);
            element_to_hide.style.display = 'none';
            menu_item_to_deactivate.className = "whitebox_big_body_nav_item";
        }

        element_to_expand.style.display = 'block';
        menu_item_to_activate.className = "whitebox_big_body_nav_item active";
    }
    //-->
    </script>
	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="whitebox_big">
                <div id="whitebox_big_top">
                </div><!-- #whitebox_big_top -->
                <div id="whitebox_big_body">
                    <div id="whitebox_big_body_title">Our Work</div>
                    <div class="whitebox_big_body_nav">
<?php
$seperator = FALSE;

if (isset ($_GET['category'])) {
    $showslug = $_GET['category'];
} else {
    $showslug = "all-projects";
}

foreach ($project_types as $slug=>$project_type) {
    if ($slug == $showslug) {
        $classadd = " active";
    } else {
        $classadd = "";
    }

    if ($slug == 'all-projects') {
        $project_type = "All Projects";
    }

    if ($seperator) {
?>
                        <span class="seperator">|</span>
<?php
    }

    $seperator = TRUE;
?>
                        <span id="whitebox_big_body_nav_<?php echo $slug; ?>" class="whitebox_big_body_nav_item<?php echo $classadd; ?>"><a onclick="javascript: expand('<?php echo $slug; ?>');"><?php echo $project_type; ?></a></span>
<?php
}
?>
                    </div><!-- whitebox_big_body_nav -->
                    <hr />
<?php
reset ($project_types);
$project_posts = array();

foreach ($project_types as $slug=>$project_type) {
    $project_posts[$slug] = get_posts ("numberposts=-1&category=".get_cat_id ($project_type));
    $number_of_pages = (int)(sizeof($project_posts[$slug])/9) + 1;
?>
                    <div id="whitebox_big_body_<?php echo $slug; ?>" class="whitebox_big_body_category"<?php if ($slug != $showslug) { echo " style=\"display: none\""; } ?>>
                        <div class="whitebox_big_body_category_page">
<?php
    //echo "project type: ".$slug."<br />";
    //echo "number of posts: ".sizeof($project_posts[$slug])."<br />";
    //echo "number of pages: ".$number_of_pages;
    for ($page_number=1; $page_number<=$number_of_pages; $page_number++) {
?>
                            <span id="whitebox_nav_<?php echo $page_number; ?>" class="whitebox_big_body_category_page_item<?php if ($page_number == 1) { echo " active"; } ?>">
                                <a onclick="javascript: page('<?php echo $slug; ?>', <?php echo $page_number; ?>, <?php echo $number_of_pages; ?>);"><?php echo $page_number; ?></a>
                            </span>
<?php
    }
?>
                            <span class="whitebox_big_body_category_page_item_arrow">
                                <a onclick="javascript: page('<?php echo $slug; ?>', current_page + 1, <?php echo $number_of_pages; ?>);">&#9658;</a>
                            </span>
                        </div><!-- .whitebox_big_body_category_page -->
                        <hr class="solid" />
<?php
    for ($page_number=1; $page_number<=$number_of_pages; $page_number++) {
?>
    <div id="whitebox_big_body_<?php echo $slug; ?>_<?php echo $page_number; ?>" class="whitebox_big_body_category_body"<?php if ($page_number != 1) { echo " style=\"display: none;\""; } ?>>
<?php
        for ($row_number=1; $row_number<=3; $row_number++) {
?>
                            <div id="whitebox_big_body_category_body_row<?php echo $row_number; ?>" class="whitebox_big_body_category_body_row">
<?php
            for ($column_number=1; $column_number<=3; $column_number++) {
                $post_number = (($page_number-1)*9)+(($row_number-1)*3)+($column_number - 1);
                if ($post_number < sizeof ($project_posts[$slug])) {
                    $this_post = $project_posts[$slug][$post_number];
                } else {
                    $this_post = NULL;
                }
?>
                                <div class="whitebox_big_body_category_body_entry">
                                    <div class="whitebox_big_body_category_body_entry_title">
                                    <span class="whitebox_big_body_category_body_entry_title_title"><?php if ($this_post) { echo "Client:"; } ?></span>
                                        <span class="whitebox_big_body_category_body_entry_title_body"><?php if ($this_post) { echo get_post_meta ($this_post->ID, 'Client', true); } ?></span>
                                    </div><!-- .whitebox_big_body_category_body_entry_title -->
                                    <?php if ($this_post) { echo "<hr class=\"solid\" />"; } ?>
                                    <div class="whitebox_big_body_category_body_entry_body">
<?php
                if ($this_post) {
?>
                                        <a href="<?php echo get_permalink ($this_post->ID); ?>" onmouseover="javascript: mouse_action ('<?php echo $slug; ?>', <?php echo $post_number; ?>, 'over');" onmouseout="javascript: mouse_action ('<?php echo $slug; ?>', <?php echo $post_number; ?>, 'out');">
                                            <div id="post_<?php echo $slug; ?>_<?php echo $post_number; ?>_picture" class="whitebox_big_body_category_body_entry_body_picture">
                                                <?php echo get_image ($this_post->post_content, 220, 142); ?>
                                            </div><!-- #post_<?php echo $post_number; ?>_picture -->
                                            <div id="post_<?php echo $slug; ?>_<?php echo $post_number; ?>_overview" class="whitebox_big_body_category_body_entry_body_overview" style="width: 220px; height: 142px; display: none;">
                                                <?php echo get_post_meta ($this_post->ID, 'Overview', true); ?>
                                            </div><!-- #post_<?php echo $post_number; ?>_overview -->
                                        </a>
<?php
                } else {
?>
                                        <!--div id="post_<?php echo $post_number; ?>_picture" class="whitebox_big_body_category_body_entry_body_picture">
                                            <img src="<?php echo get_bloginfo('template_directory'); ?>/images/default_post.jpg" />
                                        </div-->
<?php
                }
?>
                                    </div><!-- .whitebox_big_body_category_body_entry_body -->
                                </div><!-- .whitebox_big_body_category_body_entry -->
<?php
            }
?>
                            </div><!-- #whitebox_big_body_category_body_row<?php echo $row_number; ?> -->
<?php
        }
?>
                        </div><!-- #whitebox_big_body_<?php echo $slug; ?>_<?php echo $page_number; ?> -->
<?php
    }
?>
                    </div><!-- #whitebox_big_body_<?php echo $slug; ?> -->
<?php
}
?>
                </div><!-- #whitebox_big_body -->
                <div id="whitebox_big_bottom">
                </div><!-- #whitebox_big_bottom -->
            </div><!-- #whitebox_big -->
		</div><!-- #content -->
        <?php get_sidebar() ?>
        <div id="container_bottom"></div>
	</div><!-- #container -->

<?php get_footer() ?>
