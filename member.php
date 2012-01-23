<?php
/*
Template Name: Member
*/

include ('local_functions.php');

?>

<?php get_header() ?>

    <script type="text/javascript">
    <!--
    function expand (id) {
        var elements_to_hide = new Array();
        elements_to_hide[0] = 'what-we-do';
        elements_to_hide[1] = 'who-we-are';

        var element_to_expand = document.getElementById (id);
        var menu_item_to_activate = document.getElementById ("whitebox_primary_body_nav_"+id);
        var element_to_hide;

        for (i = 0; i < 2; i++) {
            element_to_hide = document.getElementById(elements_to_hide[i]);
            menu_item_to_deactivate = document.getElementById("whitebox_primary_body_nav_"+elements_to_hide[i]);
            if (element_to_hide.style.display != 'none') {
                element_to_hide.style.display = 'none';
            }
            if (menu_item_to_deactivate.className == "whitebox_primary_body_nav_item active") {
                menu_item_to_deactivate.className = "whitebox_primary_body_nav_item";
            }
        }

        element_to_expand.style.display = 'block';
        menu_item_to_activate.className = "whitebox_primary_body_nav_item active";
        if (id == 'what-we-do') {
            expand_excerpt ('what-we-do');
            document.getElementById('bluebox_body_nav').innerHTML = "What we do";
        } else {
            expand_person ('gerrie');
            document.getElementById('bluebox_body_nav').innerHTML = "Who we are";
        }
    }

    function expand_person (id) {
        var elements_to_hide = new Array();
        elements_to_hide[0] = 'adriaan';
        elements_to_hide[1] = 'gerrie';
        elements_to_hide[2] = 'wessel';

        var element_to_expand = document.getElementById ("whitebox_primary_body_content_"+id);
        var menu_item_to_activate = document.getElementById ("whitebox_primary_body_content_nav_"+id);
        var element_to_hide;

        for (i = 0; i < 3; i++) {
            element_to_hide = document.getElementById("whitebox_primary_body_content_"+elements_to_hide[i]);
            menu_item_to_deactivate = document.getElementById("whitebox_primary_body_content_nav_"+elements_to_hide[i]);
            if (element_to_hide.style.display != 'none') {
                element_to_hide.style.display = 'none';
            }
            if (menu_item_to_deactivate.className == "whitebox_primary_body_content_nav_item active") {
                menu_item_to_deactivate.className = "whitebox_primary_body_content_nav_item";
            }
        }

        element_to_expand.style.display = 'block';
        menu_item_to_activate.className = "whitebox_primary_body_content_nav_item active";
        expand_excerpt (id);
    }

    function expand_excerpt (id) {
        var elements_to_hide = new Array();
        elements_to_hide[0] = 'adriaan';
        elements_to_hide[1] = 'gerrie';
        elements_to_hide[2] = 'wessel';
        elements_to_hide[3] = 'what-we-do';

        var element_to_expand = document.getElementById ("bluebox_body_content_"+id);
        var element_to_hide;

        for (i = 0; i < 4; i++) {
            element_to_hide = document.getElementById("bluebox_body_content_"+elements_to_hide[i]);
            if (element_to_hide.style.display != 'none') {
                element_to_hide.style.display = 'none';
            }
            if (elements_to_hide[i] == 'what-we-do') {
                document.getElementById('twitter').style.display = 'none';
            } else {
                document.getElementById('twitter_'+elements_to_hide[i]).style.display = 'none';
            }
        }

        if (id == 'what-we-do') {
            document.getElementById('twitter').style.display = 'block';
        } else {
            document.getElementById('twitter_'+id).style.display = 'block';
        }
        element_to_expand.style.display = 'block';
    }
    //-->
    </script>
	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="whitebox_primary">
                <div id="whitebox_primary_top">
                </div><!-- #whitebox_primary_top -->
                <div id="whitebox_primary_body">
                    <div id="whitebox_primary_body_title">About</div>
                    <div class="whitebox_primary_body_nav">
                        <span id="whitebox_primary_body_nav_what-we-do" class="whitebox_primary_body_nav_item<?php if ($post->post_title == "What we do") { echo " active"; } ?>"><a onclick="javacsript: expand('what-we-do');">What we do</a></span>
                        <span class="seperator">|</span>
                        <span id="whitebox_primary_body_nav_who-we-are" class="whitebox_primary_body_nav_item<?php if ($post->post_title != "What we do") { echo " active"; } ?>"><a onclick="javacsript: expand('who-we-are');">Who we are</a></span>
                    </div><!-- whitebox_primary_body_nav -->
                    <hr />
<?php
$title = $post->post_title;
$old_post = $post;
$posts = get_posts ("numberposts=-1&post_type=page");
$founder_posts = array();

foreach ($posts as $post) {
    if ($post->post_title == "What we do") {
        $what_post = $post;
    } else if ($post->post_title == "Adriaan Pelzer") {
        $founder_posts['adriaan'] = $post;
    } else if ($post->post_title == "Gerrie Smits") {
        $founder_posts['gerrie'] = $post;
    } else if ($post->post_title == "Wessel van Rensburg") {
        $founder_posts['wessel'] = $post;
    }
}
?>
                <div id="what-we-do" class="whitebox_primary_body_content"<?php if ($title != "What we do") { echo " style=\"display: none;\""; } ?>>
                        <?php echo $what_post->post_content; ?>
                    </div><!-- whitebox_primary_body_content -->
                    <div id="who-we-are" class="whitebox_primary_body_content"<?php if ($title == "What we do") { echo " style=\"display: none;\""; } ?>>
                        <div class="whitebox_primary_body_content_nav">
                            <span id="whitebox_primary_body_content_nav_gerrie" class="whitebox_primary_body_content_nav_item<?php if ($title == "Gerrie Smits") { echo " active"; } ?>"><a onclick="javascript: expand_person('gerrie');">Gerrie Smits</a></span>
                            <span class="seperator">|</span>
                            <span id="whitebox_primary_body_content_nav_wessel" class="whitebox_primary_body_content_nav_item<?php if ($title == "Wessel van Rensburg") { echo " active"; } ?>"><a onclick="javascript: expand_person('wessel');">Wessel van Rensburg</a></span>
                            <span class="seperator">|</span>
                            <span id="whitebox_primary_body_content_nav_adriaan" class="whitebox_primary_body_content_nav_item<?php if ($title == "Adriaan Pelzer") { echo " active"; } ?>"><a onclick="javascript: expand_person('adriaan');">Adriaan Pelzer</a></span>
                        </div><!-- whitebox_primary_body_content_nav -->
                        <hr class="solid" />
<?php
switch ($title) {
case 'Adriaan Pelzer':
    $shortname = 'adriaan';
    break;
case 'Gerrie Smits':
    $shortname = 'gerrie';
    break;
case 'Wessel van Rensburg':
    $shortname = 'wessel';
    break;
default:
    $shortname = 'gerrie';
    break;
}

foreach ($founder_posts as $founder=>$founder_post) {
?>
                        <div id="whitebox_primary_body_content_<?php echo $founder; ?>" class="whitebox_primary_body_content_founder"<?php if ($founder != $shortname) { echo " style=\"display: none;\""; } ?>>
                            <div class="whitebox_primary_body_content_founder_info">
                                <div class="whitebox_primary_body_content_founder_name"><?php echo $founder_post->post_title; ?></div>
                                <hr />
                                <div class="whitebox_primary_body_content_founder_social">
                                    <div class="whitebox_primary_body_content_founder_social_title">Follow me&#8230;</div>
<?php
    if (get_post_meta ($founder_post->ID, 'linkedin', true)) {
?>
                                    <div class="whitebox_primary_body_content_founder_social_linkedin">
                                        <span class="whitebox_primary_body_content_founder_social_linkedin_icon"><a href="<?php echo get_post_meta ($founder_post->ID, 'linkedin', true); ?>"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/linked_in_icon.png" alt="LinkedIn" /></a></span>
                                        <span class="whitebox_primary_body_content_founder_social_linkedin_text"><a href="<?php echo get_post_meta ($founder_post->ID, 'linkedin', true); ?>">Linked In</a></span>
                                    </div><!-- whitebox_primary_body_content_founder_social_linkedin -->
<?php
    }

    if (get_post_meta ($founder_post->ID, 'facebook', true)) {
?>
                                    <div class="whitebox_primary_body_content_founder_social_facebook">
                                        <span class="whitebox_primary_body_content_founder_social_facebook_icon"><a href="<?php echo get_post_meta ($founder_post->ID, 'facebook', true); ?>"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/facebook_icon.png" alt="Facebook" /></a></span>
                                        <span class="whitebox_primary_body_content_founder_social_facebook_text"><a href="<?php echo get_post_meta ($founder_post->ID, 'facebook', true); ?>">Facebook</a></span>
                                    </div><!-- whitebox_primary_body_content_founder_social_facebook -->
<?php
    }

    if (get_post_meta ($founder_post->ID, 'twitter', true)) {
?>
                                    <div class="whitebox_primary_body_content_founder_social_twitter">
                                        <span class="whitebox_primary_body_content_founder_social_twitter_icon"><a href="<?php echo get_post_meta ($founder_post->ID, 'twitter', true); ?>"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/twitter_icon.png" alt="Twitter" /></a></span>
                                        <span class="whitebox_primary_body_content_founder_social_twitter_text"><a href="<?php echo get_post_meta ($founder_post->ID, 'twitter', true); ?>">Twitter</a></span>
                                    </div><!-- whitebox_primary_body_content_founder_social_twitter -->
<?php
    }

    if (get_post_meta ($founder_post->ID, 'gplus', true)) {
?>
                                    <div class="whitebox_primary_body_content_founder_social_gplus">
                                        <span class="whitebox_primary_body_content_founder_social_gplus_icon"><a rel="me" href="<?php echo get_post_meta ($founder_post->ID, 'gplus', true); ?>"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/gplus_icon.png" alt="Google+" /></a></span>
                                        <span class="whitebox_primary_body_content_founder_social_gplus_text"><a href="<?php echo get_post_meta ($founder_post->ID, 'gplus', true); ?>">Google+</a></span>
                                    </div><!-- whitebox_primary_body_content_founder_social_gplus -->
<?php
    }
?>
                                </div><!-- whitebox_primary_body_content_founder_social -->
                            </div><!-- whitebox_primary_body_content_founder_info -->
                            <div class="whitebox_primary_body_content_founder_picture">
                                <?php echo get_image_or_video ($founder_post->post_content, 200); ?>

                                <!--img alt="<?php echo $founder_post->post_title; ?>" src="<?php echo get_post_meta ($founder_post->ID, 'picture', true); ?>" /-->
                            </div><!-- whitebox_primary_body_content_founder_picture -->
                            <hr class="solid" />
                            <div class="whitebox_primary_body_content_founder_text">
                                <?php echo preg_replace ("/<a[^>]+><img[^>]+><\/a>/", "", $founder_post->post_content); ?>
                            </div><!-- .whitebox_primary_body_content_founder_text -->                                        </div><!-- whitebox_primary_body_content_founder -->
<?php
}

reset ($founder_posts);
?>
                    </div><!-- whitebox_primary_body_content -->
                </div><!-- #whitebox_primary_body -->
                <div id="whitebox_primary_bottom">
                </div><!-- #whitebox_primary_bottom -->
            </div><!-- #whitebox_primary -->
            <div id="bluebox">
                <div id="bluebox_top"></div>
                <div id="bluebox_body">
                    <div id="bluebox_body_nav">
                        What we do
                    </div>
                    <hr />
                    <div class="bluebox_body_content">
                        <div id="bluebox_body_content_what-we-do" class="bluebox_body_content_item"<?php if ($title != "What we do") { echo " style=\"display:none;\""; } ?>>
                            <?php echo get_post_meta ($what_post->ID, 'excerpt', true); ?>
                        </div><!-- bluebox_body_content_item -->
<?php
foreach ($founder_posts as $founder=>$founder_post) {
?>
                        <div id="bluebox_body_content_<?php echo $founder; ?>" class="bluebox_body_content_item"<?php if ($title != $founder_post->post_title) { echo " style=\"display:none;\""; } ?>>
                            <?php echo get_post_meta ($founder_post->ID, 'excerpt', true); ?>
                        </div><!-- bluebox_body_content_item -->
<?php
}
?>
                    </div><!-- bluebox_body_content -->
                </div><!-- bluebox_body -->
                <div id="bluebox_bottom"></div>
            </div><!-- #bluebox -->
		</div><!-- #content -->
        <?php $post = $old_post; ?>
        <?php get_sidebar() ?>
        <div id="container_bottom"></div>
	</div><!-- #container -->

<?php get_footer() ?>
