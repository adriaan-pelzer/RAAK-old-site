<?php
include ('local_functions.php');
?>
<?php get_header() ?>
    <script type="text/javascript">
    <!--
    function searchKey(evt) { 
        var evt = (evt) ? evt : ((event) ? event : null); 
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
        //alert (evt.keyCode+" "+node.id);
        if ((evt.keyCode == 13) && (node.id == "searchtext"))  {
            google_search();
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
                    <div class="whitebox_primary_body_post">
                        <div id="whitebox_primary_body_title"><h1><?php the_title(); ?></h1></div>
                        <hr />
                        <div class="whitebox_primary_body_attr">
<?php
ob_start();
the_author();
$authorname = ob_get_contents();
ob_end_clean();
ob_start();
the_author_login();
$authorlogin = ob_get_contents();
ob_end_clean();
$postedbyhtml = "Posted by <a href=\"".get_bloginfo ('url')."/about/the-founders/".$authorlogin."/\">".$authorname."</a>";
?>
                            <div id="whitebox_primary_body_attr_author" class="whitebox_primary_body_attr_item"><?php echo $postedbyhtml; ?></div>
                            <div id="whitebox_primary_body_attr_date" class="whitebox_primary_body_attr_item"><?php echo strftime ('%e %B %Y', strtotime ($post->post_date)); ?></div>
                            <div id="whitebox_primary_body_attr_time" class="whitebox_primary_body_attr_item"><?php echo strftime ('%H:%M', strtotime ($post->post_date)); ?></div>
                            <div id="whitebox_primary_body_attr_comment" class="whitebox_primary_body_attr_item"><span id="whitebox_primary_body_attr_commenticon"><img src="<?php echo get_bloginfo ('template_directory'); ?>/images/whitebox_primary_body_attr_comment_icon.png" /></span><span id="whitebox_primary_body_attr_comments"><?php echo $post->comment_count." comment".(($post->comment_count == 1)?"":"s"); ?></span></div>
                        </div><!-- .whitebox_primary_body_attr -->
                        <div class="whitebox_primary_body_share">
<?php
$authorpage = get_page_by_title ($authorname);

$twittername = str_replace ("http://www.twitter.com/", "", get_post_meta ($authorpage->ID, 'twitter', true));
$twittername = str_replace ("http://twitter.com/", "", $twittername);
?>
                            <a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="<?php echo $twittername; ?>" data-related="RAAKonteurs" data-text="<?php the_title(); ?> &#9733; RAAK">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<?php
/**
 * Include this code on your theme for single blog posts (for example, in your single.php file)
 * or on your blogs main page to include a Facebook "Like" iframe
 */
if (function_exists('the_opengraphprotocoltools_like_code')):
    ob_start();
	the_opengraphprotocoltools_like_code();
    $likecode = ob_get_contents();
    ob_end_clean();

    //echo str_replace ("layout=standard", "layout=button_count&amp;send=true", $likecode);
    echo str_replace ("layout=standard", "layout=button_count", $likecode);
else:
	echo "<!-- opengraphprotocoltools is not activated -->";
endif;
?>
                            <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:send font=""></fb:send>
                            <div id="plusone"><!--iframe allowtransparency="true" frameborder="0" hspace="0" id="I1_1307012621585" marginheight="0" marginwidth="0" name="I1_1307012621585" scrolling="no" src="https://plusone.google.com/u/0/_/+1/button?hl=en-US&amp;jsh=s%3Bplusone%3Agoogleapis.client%4021550740_8d71de52%2Fclient%3Bgoogleapis.proxy%4021550740_8d71de52%2Fproxy%3Bplusone%3Agoogleapis.client%3Aiframes-styles-bubble%4021550740_8d71de52%2Fbubble%3Biframes-styles-bubble!plusone%3Agoogleapis.client%4021550740_8d71de52%2Fbubble_only%3Bplusone-unsupported%4021550740_8d71de52%2Funsupported#url=<?php echo urlencode (php_self()); ?>&amp;size=medium&amp;count=true&amp;id=I1_1307012621585&amp;parent=<?php echo urlencode (get_bloginfo ('url')); ?>&amp;rpctoken=982298531&amp;_methods=_ready%2C_close%2C_open%2C_resizeMe" style="width: 82px; height: 20px; position: static; left: 0px; top: 0px; visibility: visible; " tabindex="-1" vspace="0" width="100%"></iframe--><g:plusone size="medium"></g:plusone></div>
                        </div><!-- .whitebox_primary_body_share -->
                        <hr class="solid" />
                        <div class="whitebox_primary_body_content">
                            <?php the_content(); ?>
                        </div><!-- .whitebox_primary_body_content -->
                        <div class="whitebox_primary_body_flwbtn">
                            <p><span><em><?php echo $postedbyhtml; ?></em></span><span><a href="<?php echo get_post_meta ($authorpage->ID, 'twitter', true); ?>" class="twitter-follow-button" data-show-count="true">Follow @<?php echo $twittername; ?></a></span></p>
                            <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
                        </div>
                    </div><!-- .whitebox_primary_body_post -->
                    <div id="whitebox_primary_body_comments">
<?php
//ob_start();
comments_template();
//$comment_text = ob_get_contents();
//ob_end_clean();

//echo str_replace ("<h3><span>One</span>", "<h3><span>1</span>", $comment_text);
?>
                    </div><!-- #whitebox_primary_body_comments -->
                </div><!-- #whitebox_primary_body -->
                <div id="whitebox_primary_bottom"></div>
            </div><!-- #whitebox_primary -->
            <div id="bluebox">
                <div id="bluebox_tab">
                    <div id="bluebox_tab_top"></div>
                    <div id="bluebox_tab_body">Related Posts</div>
                </div><!-- #bluebox_tab -->
                <div id="bluebox_top"></div>
<?php
ob_start();
wp_related_posts();
$related_posts = ob_get_contents();
ob_end_clean();

$related_posts = str_replace ("<ul class=\"related_post\"><li><a href=\"", "", $related_posts);
$related_posts = str_replace ("</a></li></ul>", "", $related_posts);

$r_posts = array();

foreach (explode ("</a></li><li><a href=\"", $related_posts) as $post_carcass) {
    list ($href, $post_carcass) = explode ("\" title=\"", $post_carcass, 2);
    list ($alt, $title) = explode ("\">", $post_carcass, 2);
    foreach (explode ('/', $href) as $slug) {
        if ($slug != '') {
            $page_name = $slug;
        }
    }
    array_push ($r_posts, get_post_by_name ($page_name));
}
?>
                <div id="bluebox_body">
                    <div id="bluebox_body_top">
<?php
if (sizeof ($r_posts) > 0) {
?>
                        <div id="bluebox_body_left_top">
                            <div id="bluebox_body_left_top_image">
                                <a href="<?php echo get_permalink($r_posts[0]->ID); ?>"><?php echo get_image_or_video ($r_posts[0]->post_content, 120, 85); ?></a>
                            </div>
                            <div id="bluebox_body_left_top_title">
                                <a href="<?php echo get_permalink($r_posts[0]->ID); ?>"><?php echo $r_posts[0]->post_title; ?></a>
                            </div>
                        </div>
<?php
}

if (sizeof ($r_posts) > 1) {
?>
                        <div id="bluebox_body_right_top">
                            <div id="bluebox_body_right_top_image">
                                <a href="<?php echo get_permalink($r_posts[1]->ID); ?>"><?php echo get_image_or_video ($r_posts[1]->post_content, 120, 85); ?></a>
                            </div>
                            <div id="bluebox_body_right_top_title">
                                <a href="<?php echo get_permalink($r_posts[1]->ID); ?>"><?php echo $r_posts[1]->post_title; ?></a>
                            </div>
                        </div>
<?php
}
?>
                    </div>
<?php
if (sizeof ($r_posts) > 2) {
?>
                    <hr id="hrleft" class="solid" />
                    <hr id="hrright" class="solid" />
<?php
}
?>
                    <div id="bluebox_body_bottom">
<?php
if (sizeof ($r_posts) > 2) {
?>
                        <div id="bluebox_body_left_bottom">
                            <div id="bluebox_body_left_bottom_image">
                                <a href="<?php echo get_permalink($r_posts[2]->ID); ?>"><?php echo get_image_or_video ($r_posts[2]->post_content, 120, 85); ?></a>
                            </div>
                            <div id="bluebox_body_left_bottom_title">
                                <a href="<?php echo get_permalink($r_posts[2]->ID); ?>"><?php echo $r_posts[2]->post_title; ?></a>
                            </div>
                        </div>
<?php
}

if (sizeof ($r_posts) > 3) {
?>
                        <div id="bluebox_body_right_bottom">
                            <div id="bluebox_body_right_bottom_image">
                                <a href="<?php echo get_permalink($r_posts[3]->ID); ?>"><?php echo get_image_or_video ($r_posts[3]->post_content, 120, 85); ?></a>
                            </div>
                            <div id="bluebox_body_right_bottom_title">
                                <a href="<?php echo get_permalink($r_posts[3]->ID); ?>"><?php echo $r_posts[3]->post_title; ?></a>
                            </div>
                        </div>
<?php
}
?>
                    </div>
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
if ( function_exists('stats_get_csv') && $top_posts = stats_get_csv('postviews', 'days=0&limit=25') ) {
    $i = 0;

    foreach ($top_posts as $post) {
        $title = htmlspecialchars_decode ($post["post_title"], ENT_QUOTES);
        $post_id = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE `post_title`='".mysql_real_escape_string ($title)."' AND `post_status`='publish'"));
        if (in_category ('Blog', $post_id)) {
            echo "                            <li><a href=\"".$post["post_permalink"]."\">".$post["post_title"]."</a></li>\n";
            $i++;
        }
        if ($i > 5) {
            break;
        }
    }
}
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
<!-- Newscurve Beta Code :: Adriaan Pelzer -->
<script type="text/javascript">
var _nct = _nct || [];

(function() {
var nc = document.createElement('script'); nc.type = 'text/javascript'; nc.async = true;
nc.src = 'http://t.newscurve.com/nct.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(nc, s);
})();
</script>
<!-------------------------------------------->
<?php get_footer() ?>
