<?php
/*
Template Name: Blog
*/

include ('local_functions.php');

global $wpdb;

$posts = get_posts ("numberposts=-1&category=".get_cat_id ('Blog'));

?>

<?php get_header() ?>

    <script type="text/javascript">
    <!--
    var post_count = <?php echo sizeof ($posts); ?>;
    var curr_page = 0;

    function searchKey(evt) { 
        var evt = (evt) ? evt : ((event) ? event : null); 
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
        //alert (evt.keyCode+" "+node.id);
        if ((evt.keyCode == 13) && (node.id == "searchtext"))  {
            google_search();
        }
    } 

    /*function hide_object_tag (page) {
        var e=document.getElementsByTagName("object");
        for (var i=0;i<e.length;i++) { e[i].style.backgroundColor = nextColor; }
    }*/

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

    function google_search() {
        var searchbox = document.getElementById ('searchtext');
        var searchtext = searchbox.value;
        window.location = "http://www.google.co.uk/#q="+searchtext.replace(" ", "+", searchtext)+"+site:http://wewillraakyou.com";
    }

    function expand(tab) {
        if (tab == "mostviewed") {
            document.getElementById('whitebox_secondary_body_mostviewed').style.display = 'block';
            document.getElementById('whitebox_secondary_body_category').style.display = 'none';
            document.getElementById('whitebox_secondary_tabs_mostviewed').className = 'whitebox_secondary_tab active';
            document.getElementById('whitebox_secondary_tabs_category').className = 'whitebox_secondary_tab';
        } else {
            document.getElementById('whitebox_secondary_body_category').style.display = 'block';
            document.getElementById('whitebox_secondary_body_mostviewed').style.display = 'none';
            document.getElementById('whitebox_secondary_tabs_mostviewed').className = 'whitebox_secondary_tab';
            document.getElementById('whitebox_secondary_tabs_category').className = 'whitebox_secondary_tab active';
        }
    }

    document.onkeypress = searchKey; 

    //-->
    </script>
	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="whitebox_big">
                <div id="whitebox_big_top"></div>
                <div id="whitebox_big_body">
                    <span id="whitebox_big_body_title"><h1>Our Blog</h1></span>
                    <span id="whitebox_big_body_search"><a onclick="javascript: google_search();">Search</a><input type="text" id="searchtext" /></span>
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
                            <span id="whitebox_primary_body_footer_prev_text" class="whitebox_primary_body_footer_item<?php if (sizeof($posts) > 2) { echo " active"; } ?>">Previous</span>
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
            <div id="bluebox">
                <div id="bluebox_tab">
                    <div id="bluebox_tab_top"></div>
                    <div id="bluebox_tab_body">Other Posts</div>
                </div><!-- #bluebox_tab -->
                <div id="bluebox_top"></div>
                <div id="bluebox_body">
                    <div id="bluebox_body_mustread">
                        <div id="bluebox_body_mustread_title">Must Reads</div>
                        <ul>
<?php
$posts = get_posts ("numberposts=5&category=".get_cat_id ('Must read'));

foreach ($posts as $post) {
?>
                            <li><a href="<?php echo get_permalink ($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
<?php
}
?>
                        </ul>
                        <div id="bluebox_body_mustread_footer">
<?php
$cat_link = get_bloginfo ('url')."/category/".get_category(get_cat_id('Must read'))->slug."/";
?>
                            <span class="bluebox_body_mustread_link_text"><a href="<?php echo $cat_link; ?>" rel="nofollow">More</a></span><span class="bluebox_body_mustread_link_arrow"><a href="<?php echo $cat_link; ?>" rel="nofollow">&#9660;</a></span>
                        </div>
                    </div><!-- #bluebox_body_mustread -->
                    <hr />
                    <div id="bluebox_body_worthalook">
                        <div id="bluebox_body_worthalook_title">Worth a look</div>
                        <ul>
<?php
$posts = get_posts ("numberposts=3&category=".get_cat_id ('Worth a look'));

foreach ($posts as $post) {
?>
                            <li><a href="<?php echo get_permalink ($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
<?php
}
?>
                        </ul>
                        <div id="bluebox_body_worthalook_footer">
<?php
$cat_link = get_bloginfo ('url')."/category/".get_category(get_cat_id('Worth a look'))->slug."/";
?>
                            <span class="bluebox_body_worthalook_link_text"><a href="<?php echo $cat_link; ?>" rel="nofollow">More</a></span><span class="bluebox_body_worthalook_link_arrow"><a href="<?php echo $cat_link; ?>" rel="nofollow">&#9660;</a></span>
                        </div>
                    </div><!-- #bluebox_body_worthalook -->
                    <div id="bluebox_body_inspiration">
                        <div id="bluebox_body_inspiration_title">The RAAKonteur</div>
                        <ul>
<?php
$posts = get_posts ("numberposts=3&category=".get_cat_id ('RAAKonteur'));

foreach ($posts as $post) {
?>
                            <li><a href="<?php echo get_permalink ($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
<?php
}
?>
                        </ul>
                        <div id="bluebox_body_inspiration_footer">
<?php
$cat_link = get_bloginfo ('url')."/category/".get_category(get_cat_id('RAAKonteur'))->slug."/";
?>
                            <span class="bluebox_body_inspiration_link_text"><a href="<?php echo $cat_link; ?>" rel="nofollow">More</a></span><span class="bluebox_body_inspiration_link_arrow"><a href="<?php echo $cat_link; ?>" rel="nofollow">&#9660;</a></span>
                        </div>
                    </div><!-- #bluebox_body_inspiration -->
                </div><!-- bluebox_body -->
                <div id="bluebox_bottom"></div>
            </div><!-- #bluebox -->
            <div id="whitebox_secondary">
                <div id="whitebox_secondary_tabs">
                    <div id="whitebox_secondary_tabs_mostviewed" class="whitebox_secondary_tab active">
                        <div class="whitebox_secondary_tab_top"></div>
                        <div class="whitebox_secondary_tab_body"><a onclick="javascript: expand('mostviewed');">Most Viewed</a></div>
                    </div>
                    <div id="whitebox_secondary_tabs_category" class="whitebox_secondary_tab">
                        <div class="whitebox_secondary_tab_top"></div>
                        <div class="whitebox_secondary_tab_body"><a onclick="javascript: expand('category');">Category</a></div>
                    </div>
                </div><!-- #whitebox_secondary_tabs -->
                <div id="whitebox_secondary_top"></div>
                <div id="whitebox_secondary_body">
                    <div id="whitebox_secondary_body_mostviewed">
                        <ul>
<?php
$posts = get_posts ("numberposts=-1&category=".get_cat_id ('Blog'));
$post_visits = array();
$options = array ("show"=>"posts", "posts"=>25, "days"=>30);

if (function_exists('wpcomstats_most_visited')) {
    ob_start();
    wpcomstats_most_visited($options);
    $visits = ob_get_contents();
    ob_end_clean();
}

$mv_posts = explode ("<br />", $visits);

$i = 0;

foreach ($mv_posts as $post) {
    if ($post != "") {
        $post = str_replace ("&raquo;", "", $post);
        $title = preg_replace ("/<[^>]+>/", "", $post);
        $post_id = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE `post_title`='".$title."'"));
        if (in_category ('Blog', $post_id)) {
            echo "                            <li>".$post."</li>\n";
            $i++;
        }
        if ($i > 5) {
            break;
        }
    }
}
/*foreach ($posts as $post) {
    ob_start();
    wpcomstats_visits("", "", $post->ID, 0);
    $visits = ob_get_contents();
    ob_end_clean();
    $visits = ($visits == "No data yet!")?0:$visits;
    $this_post['visits'] = $visits;
    $this_post['post'] = $post;
    array_push ($post_visits, $this_post);
}

function cmp ($a, $b) {
    $c = (int) $a['visits'];
    $d = (int) $b['visits'];
    return ($c == $d)?0:(($c < $d)?1:-1);
}

usort ($post_visits, 'cmp');

for ($i = 0; $i < 8; $i++) {
    echo "<li>";
    echo "<a href=\"".get_permalink($post_visits[$i]['post']->ID)."\">";
    echo $post_visits[$i]['post']->post_title;
    echo "</a>";
    echo "</li>";
    echo "\n";
}*/
?>
                        </ul>
                    </div><!-- #whitebox_secondary_body_mostviewed -->
                    <div id="whitebox_secondary_body_category" style="display: none">
                        <ul>
<?php
$categories = get_categories ("child_of=".get_cat_ID('Blog'));

foreach ($categories as $category) {
?>
                            <li><a href="<?php echo get_bloginfo ('url'); ?>/category/<?php echo $category->cat_name; ?>/"><?php echo $category->cat_name; ?></a></li>
<?php
}
?>
                        </ul>
                    </div><!-- #whitebox_secondary_body_category -->
                </div><!-- #whitebox_secondary_body -->
                <div id="whitebox_secondary_bottom"></div>
            </div><!-- #whitebox_secondary -->
		</div><!-- #content -->
        <?php get_sidebar() ?>
        <div id="container_bottom"></div>
	</div><!-- #container -->

<?php get_footer() ?>
