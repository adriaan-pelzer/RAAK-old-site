<?php
/*
Template Name: Logo Archive
*/
?>

<?php
global $wpdb;

$letters = array();
$letters["R"] = array();
$letters["A"] = array();
$letters["K"] = array();

$uploads = $wpdb->get_results ('SELECT * FROM `wp_logo_uploads` ORDER BY `timestamp` DESC');

foreach ($uploads as $upload) {
    if (isset ($_GET['user'])) {
        if ($_GET['user'] == $upload->useremail) {
            array_push ($letters[$upload->letter], $upload);
        }
    } else {
        array_push ($letters[$upload->letter], $upload);
    }
}
?>
<?php get_header() ?>
    <script type="text/javascript">
    <!--
<?php
if (isset ($_GET['letter']) && (($_GET['letter'] == 'R') || ($_GET['letter'] == 'K') || ($_GET['letter'] == 'A'))) {
    $active_letter = $_GET['letter'];
?>
        var active_logos = '<?php echo $_GET['letter']; ?>';
<?php
} else {
    $active_letter = 'R';
?>
        var active_logos = 'R';
<?php
}
?>

        $(document).ready( function () {
            if (active_logos != 'R') {
                $("#bluebox_big_body_content_R").hide();
            }

            if (active_logos != 'A') {
                $("#bluebox_big_body_content_A").hide();
            }

            if (active_logos != 'K') {
                $("#bluebox_big_body_content_K").hide();
            }

            $("#expand_R").click ( function () {
                $("#expand_"+active_logos).removeClass ('active');
                $("#expand_R").addClass ('active');
                $("#bluebox_big_body_content_"+active_logos).fadeOut ('fast', function () {
                    $("#bluebox_big_body_content_R").fadeIn ('fast', function () {
                        $("#expanded_letter").html ('Letter R');
                        active_logos = 'R';
                    });
                });
            });

            $("#expand_A").click ( function () {
                $("#expand_"+active_logos).removeClass ('active');
                $("#expand_A").addClass ('active');
                $("#bluebox_big_body_content_"+active_logos).fadeOut ('fast', function () {
                    $("#bluebox_big_body_content_A").fadeIn ('fast', function () {
                        $("#expanded_letter").html ('Letter A');
                        active_logos = 'A';
                    });
                });
            });

            $("#expand_K").click ( function () {
                $("#expand_"+active_logos).removeClass ('active');
                $("#expand_K").addClass ('active');
                $("#bluebox_big_body_content_"+active_logos).fadeOut ('fast', function () {
                    $("#bluebox_big_body_content_K").fadeIn ('fast', function () {
                        $("#expanded_letter").html ('Letter K');
                        active_logos = 'K';
                    });
                });
            });
        });
    //-->
    </script>
	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="bluebox_big">
                <div id="bluebox_big_top"></div>
                <div id="bluebox_big_body">
                    <div id="bluebox_big_body_title">
                        Logo Project
                    </div>
                    <div id="bluebox_big_body_nav">
                        <span id="expand_R" class="bluebox_big_body_nav_item<?php if ($active_letter == 'R') { echo " active"; } ?>"><a>R</a></span>
                        <span class="bluebox_big_body_nav_seperator">|</span>
                        <span id="expand_A" class="bluebox_big_body_nav_item<?php if ($active_letter == 'A') { echo " active"; } ?>"><a>A</a></span>
                        <span class="bluebox_big_body_nav_seperator">|</span>
                        <span id="expand_K" class="bluebox_big_body_nav_item<?php if ($active_letter == 'K') { echo " active"; } ?>"><a>K</a></span>
                        <span class="bluebox_big_body_nav_seperator">|</span>
                        <span class="bluebox_big_body_nav_item"><a href="<?php echo get_permalink ($post->post_parent); ?>">Back</a></span>
                    </div>
                    <hr />
                    <span id="expanded_letter">Letter <?php echo $active_letter; ?></span>
                    <hr class="solid" />
<?php
foreach (array ('R', 'A', 'K') as $sletter) {
?>
                    <div id="bluebox_big_body_content_<?php echo $sletter; ?>" class="bluebox_big_body_content">
<?php
    $i = 0;

    foreach ($letters[$sletter] as $letter) {
        if (($i%7) == 0) {
?>
                        <div class="bluebox_big_body_content_row">
<?php
        }
?>
                            <div class="bluebox_big_body_content_row_item">
                                <div id="bluebox_big_body_content_row_item_image">
                                    <img src="<?php echo get_bloginfo('template_directory'); ?>/resize.php?filename=logo_uploads/<?php echo $letter->filename; ?>&width=70&height=84" />
                                </div>
                                <div id="bluebox_big_body_content_row_item_blurp">
                                    Submitted by
                                </div>
                                <div id="bluebox_big_body_content_row_item_name">
<?php
            if (($letter->userurl) && ($letter->userurl != "") && (!(preg_match ("/^((https?|ftp)\:\/\/)/", $letter->userurl)))) {
                $userurl = "http://".$letter->userurl;
            }
?>
                                    <?php if ($userurl && ($userurl != "")) { ?><a href="<?php echo $userurl; ?>"><?php } ?><?php echo $letter->username; ?><?php if ($userurl && ($userurl != "")) { ?></a><?php } ?>
                                </div>
                            </div>
<?php
        $i++;
        if (($i%7) == 0) {
?>
                        </div><!-- .bluebox_big_body_content_row -->
<?php
        }
    }

    if (($i%7) != 0) {
?>
                        </div><!-- .bluebox_big_body_content_row -->
<?php
    }
?>
                    </div><!-- bluebox_big_body_content -->
<?php
}
?>
                </div><!-- bluebox_big_body -->
                <div id="bluebox_big_bottom"></div>
            </div><!-- #bluebox_big -->
		</div><!-- #content -->
        <?php get_sidebar() ?>
        <div id="container_bottom"></div>
	</div><!-- #container -->

<?php get_footer() ?>
