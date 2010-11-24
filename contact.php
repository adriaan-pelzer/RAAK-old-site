<?php
/*
Template Name: Contact
*/

include ('local_functions.php');
?>
<?php get_header() ?>
	<div id="container">
        <div id="container_top"></div>
		<div id="content">

            <div id="whitebox_primary">
                <div id="whitebox_primary_top">
                </div><!-- #whitebox_primary_top -->
                <div id="whitebox_primary_body">
                    <div class="whitebox_primary_body_post">
                        <div id="whitebox_primary_body_title"><h1><?php the_title(); ?></h1></div>
                        <hr />
                        <div class="whitebox_primary_body_content">
                            <?php the_content(); ?>
                        </div><!-- .whitebox_primary_body_content -->
                    </div><!-- .whitebox_primary_body_post -->
                </div><!-- #whitebox_primary_body -->
                <div id="whitebox_primary_bottom"></div>
            </div><!-- #whitebox_primary -->
            <div id="bluebox">
                <div id="bluebox_top"></div>
                <div id="bluebox_body">
                    <div id="bluebox_body_title">
                        <span id="bluebox_body_title_title">Where we are</span><!--span id="bluebox_body_title_print"><a href="">Print</a></span><span class="blue"><a href="">&#9658;</a></span-->
                    </div>
                    <div id="bluebox_body_print">
                        
                    </div>
                    <hr />
                    <div id="bluebox_body_map">
                    <a target="_blank" href="http://maps.google.com/maps?hl=en&q=<?php echo get_post_meta ($post->ID, 'latitude', true); ?>,<?php echo get_post_meta ($post->ID, 'longitude', true); ?>&ie=UTF8&z=14"><!--img id="gimg" src="http://maps.google.com/maps/api/staticmap?center=<?php echo get_post_meta ($post->ID, 'latitude', true); ?>,<?php echo get_post_meta ($post->ID, 'longitude', true); ?>&zoom=14&size=315x315&sensor=false" /--><img alt="map to RAAK" id="gimg" src="<?php echo get_bloginfo ('template_directory'); ?>/images/map.png" /></a>
                    </div>
                </div><!-- bluebox_body -->
                <div id="bluebox_bottom"></div>
            </div><!-- #bluebox -->
		</div><!-- #content -->
        <?php get_sidebar() ?>
        <div id="container_bottom"></div>
	</div><!-- #container -->
<?php get_footer() ?>
