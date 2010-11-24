<?php get_header() ?>
    <script type="text/javascript">
    <!--
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
    //-->
    </script>

	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="whitebox_big">
                <div id="whitebox_big_top"></div>
                <div id="whitebox_big_body">
                    <span id="whitebox_big_body_title"><h1>Oops ... you've hit a 404!</h1></span>
                    <span id="whitebox_big_body_search"><a onclick="javascript: google_search();">Search</a><input type="text" id="searchtext" /></span>
                </div><!-- #whitebox_big_body -->
                <div id="whitebox_big_bottom"></div>
            </div><!-- #whitebox_big -->
            <div id="whitebox_primary">
                <div id="whitebox_primary_top">
                </div><!-- #whitebox_primary_top -->
                <div id="whitebox_primary_body">
                    <div id="post-0" class="whitebox_primary_body_post post error404 not-found">
                        <div id="whitebox_primary_body_title">Did you know?</div>
                        <hr />
                        <div class="whitebox_primary_body_content">
                            <p>'RAAK' means 'Spot-on' in Afrikaans. This time, though, it's not really applicable. The page you were looking for, is nowhere to be found. Hmmm ... we're feeling a bit uncomfortable now. What are we going to say next?</p>
                            <p>Ah! How about a random fortune?</p>
                            <blockquote>
<?php
$ch = curl_init ("http://www.fortunefortoday.com/getfortuneonly.php");
curl_exec($ch);
?>
                            </blockquote>
                        </div><!-- .whitebox_primary_body_content -->
                        <hr class="solid" />
                    </div>
                    <div class="whitebox_primary_body_footer">
                        <span id="whitebox_primary_body_footer_archive" class="whitebox_primary_body_footer_item"><a href="<?php echo get_bloginfo ('url'); ?>/blog-archive/">All blog posts</a></span>
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
                    </div><!-- #bluebox_body_mustread -->
                    <hr />
                    <div id="bluebox_body_worthalook">
                        <div id="bluebox_body_worthalook_title">Worth a look</div>
                        <ul>
<?php
$posts = get_posts ("numberposts=5&category=".get_cat_id ('Worth a look'));

foreach ($posts as $post) {
?>
                            <li><a href="<?php echo get_permalink ($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
<?php
}
?>
                        </ul>
                    </div><!-- #bluebox_body_worthalook -->
                    <div id="bluebox_body_inspiration">
                        <div id="bluebox_body_inspiration_title">The RAAKonteur</div>
                        <ul>
<?php
$posts = get_posts ("numberposts=5&category=".get_cat_id ('RAAKonteur'));

foreach ($posts as $post) {
?>
                            <li><a href="<?php echo get_permalink ($post->ID); ?>"><?php echo $post->post_title; ?></a></li>
<?php
}
?>
                        </ul>
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
$options = array ("show"=>"posts", "posts"=>25);

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
