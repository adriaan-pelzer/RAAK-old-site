<?php
/*
Single Post Template: Product
*/
?>

<?php get_header() ?>

	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="whitebox_big">
                <div id="whitebox_big_top"></div>
                <div id="whitebox_big_body">
                    <div id="whitebox_big_body_title">Our Products</div>
                    <div class="whitebox_big_body_nav">
<?php the_post() ?>
<?php
$work_categories = get_categories (array ('child_of'=>get_cat_id ('RAAK products'), 'orderby'=>'slug', 'order'=>'desc'));

$product_types = array();
$product_types['all-products'] = "RAAK products";

foreach ($work_categories as $c) {
    $product_types[$c->slug] = $c->name;
}

$seperator = FALSE;

foreach ($product_types as $slug=>$product_type) {
    $classadd = "";

    if ($slug == 'all-products') {
        $product_type = "All Products";
    }

    foreach ((get_the_category()) as $category) {
        if ($category->name == $product_type) {
            $classadd = " active";
        }
    }

    if ($seperator) {
?>
                        <span class="seperator">|</span>
<?php
    }

    $seperator = TRUE;
?>
                        <span id="whitebox_big_body_nav_<?php echo $slug; ?>" class="whitebox_big_body_nav_item<?php echo $classadd; ?>"><a href="<?php echo get_bloginfo ('url'); ?>/our-work/?category=<?php echo $slug; ?>"><?php echo $product_type; ?></a></span>
<?php
}
?>
                    </div><!-- #whitebox_big_body_nav -->
                    <hr />
                </div><!-- #whitebox_big_body -->
                <div id="whitebox_big_bottom"></div>
            </div><!-- #whitebox_big -->
            <div id="whitebox_primary">
                <div id="whitebox_primary_top_outward"><!-- for the sake of the outward-facing corner on the right --></div>
                <div id="whitebox_primary_body">
<?php
$seperator = FALSE;

foreach (array("project"=>"Product", "solution"=>"Descript") as $section_slug=>$section_title) {
    if ($seperator) {
?>
                    <hr class="solid">
<?php
    }
?>
                    <div id="whitebox_primary_body_<?php echo $section_slug; ?>" class="whitebox_primary_body_section">
                        <span class="whitebox_primary_body_section_title"><?php echo $section_title; ?>:</span>
                        <span class="whitebox_primary_body_section_content"><?php echo get_post_meta ($post->ID, $section_title, true); ?></span>
                    </div><!-- .whitebox_primary_body_section -->
<?php
    $seperator = TRUE;
}
?>
                </div><!-- #whitebox_primary_body -->
                <div id="whitebox_primary_bottom"></div>
            </div><!-- #whitebox_primary -->
            <div id="bluebox">
                <div id="bluebox_top"></div>
                <div id="bluebox_body">
                    <div id="bluebox_body_bigpic"></div>
<?php
if (($homepage = get_post_meta ($post->ID, 'homepage', true)) != '') {
?>
                    <div id="bluebox_body_homepage">
                        <a href="<?php echo $homepage; ?>">Website home page</a>
                    </div>
<?php
}
?>
                    <hr class="solid" />
                    <div id="bluebox_body_thumbpic">
                        <?php the_content(); ?>
                    </div>
                </div><!-- #bluebox_body -->
                <div id="bluebox_bottom"></div>
            </div><!-- #bluebox -->
		</div><!-- #content -->
        <?php get_sidebar() ?>
        <div id="container_bottom"></div>
	</div><!-- #container -->
    <script type="text/javascript">
    <!--
        function showImage_worker (imgsrc) {
            var bigpic = document.getElementById('bluebox_body_bigpic');
            bigpic.innerHTML = "<img width='315px' height='203px' src='"+imgsrc+"' />";
        }

        function showImage () {
            showImage_worker (this.id);
            this.removeAttribute("href");
        }

        function showImage_initial () {
            var aTags = document.getElementsByTagName ("div");
            var displayed = 0;

            for (var i = 0; i < aTags.length; i++) {
                if (aTags[i].className == 'ngg-gallery-thumbnail') {
                    for (var j = 0; j < aTags[i].childNodes.length; j++) {
                        var elm = aTags[i].childNodes[j];
                        if (elm.tagName == 'A') {
                            elm.id = elm.href;
                            elm.className = "custom_thumbnail";
                            elm.onmouseover = showImage;
                            elm.removeAttribute("href");
                            if (displayed == 0) {
                                showImage_worker (elm.id);
                                displayed = 1;
                            }
                        }
                    }
                } else if (aTags[i].className == 'piclenselink') {
                    aTags[i].style.display = 'none';
                }
            }

            //showImage_worker ('http://test.wewillraakyou.com/wp-content/gallery/guided-collective/guided_community.png');
            
            var aTags = document.getElementsByTagName ("div");

            /*var piclense = document.getElementsByClassName ('piclenselink', document.body);
            piclense[0].style.display = 'none';*/
        }

        window.onload = showImage_initial;
    //-->
    </script>
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
