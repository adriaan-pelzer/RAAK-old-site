<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
<?php
ob_start ();
wp_title();
$wptitle = ob_get_contents ();
ob_end_clean ();
?>
    <title><?php wp_title( '-', true, 'right' ); echo wp_specialchars( get_bloginfo('name'), 1 ) ?><?php if ($wptitle == "") { echo " - "; bloginfo('description'); } ?></title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>" />
    <!--[if IE]>
	<link rel="stylesheet" type="text/css" href="<?php echo str_replace ("style.css", "style_ie.css", get_bloginfo('stylesheet_url')); ?>" />
    <![endif]-->
    <!--[if IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo str_replace ("style.css", "style_ie_8.css", get_bloginfo('stylesheet_url')); ?>" />
    <![endif]-->
    <!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo str_replace ("style.css", "style_ie_7.css", get_bloginfo('stylesheet_url')); ?>" />
    <![endif]-->
<?php
if (preg_match ("/Opera/", $_SERVER['HTTP_USER_AGENT'])) {
?>
	<link rel="stylesheet" type="text/css" href="<?php echo str_replace ("style.css", "style_opera.css", get_bloginfo('stylesheet_url')); ?>" />
<?php
}
?>

<?php wp_head() // For plugins ?>
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php printf( __( '%s latest posts', 'sandbox' ), wp_specialchars( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'sandbox' ), wp_specialchars( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
<?php
global $wpdb;

$letters = array();
$letters["R"] = array();
$letters["A"] = array();
$letters["K"] = array();

$uploads = $wpdb->get_results ('SELECT * FROM `wp_logo_uploads` ORDER BY `timestamp` DESC');


foreach ($uploads as $upload) {
    if (($upload->index == $index) || ($upload->index == $_POST['index'])) {
        $my_upload = $upload->filename;
        $my_letter = $upload->letter;
    } else if ($upload->confirmed == 1) {
        array_push ($letters[$upload->letter], $upload);
    }
}
?>

    <script type="text/javascript" src="<?php echo get_bloginfo ('template_directory'); ?>/jquery.min.js"></script>
    <script type="text/javascript">
    <!--
        var active_logos = 'R';

        var logo_letters = new Array();
<?php
foreach ($letters as $display_letter=>$sletter) {
?>
        logo_letters['<?php echo $display_letter; ?>'] = new Array();
<?php
    $i = 0;

    foreach ($sletter as $letter) {
?>
        logo_letters['<?php echo $display_letter; ?>'][<?php echo $i; ?>] = new Image();
        logo_letters['<?php echo $display_letter; ?>'][<?php echo $i++; ?>].src = "<?php echo get_bloginfo('template_directory'); ?>/resize.php?filename=logo_uploads/<?php echo $letter->filename; ?>&width=35&height=42";
<?php
    }
}
?>
        function cycle_logo_letter (display_letter) {
            switch (display_letter) {
            case 'R':
                var letter_id = 'logo_letter_r1';
                var letter = 'R';
                break;
            case 'A1':
                var letter_id = 'logo_letter_a2';
                var letter = 'A';
                break;
            case 'A2':
                var letter_id = 'logo_letter_a3';
                var letter = 'A';
                break;
            case 'K':
                var letter_id = 'logo_letter_k4';
                var letter = 'K';
                break;
            }

            $("#"+letter_id+" a img").fadeTo('slow', 0, function () {
                if (logo_letters[letter].length != 0) {
                    $("#"+letter_id+" a img").attr('src', logo_letters[letter][Math.floor(Math.random()*logo_letters[letter].length)].src);
                }

                $("#"+letter_id+" a img").fadeTo('slow', 1);
            });

            setTimeout ("cycle_logo_letter('"+display_letter+"');", 5000 + Math.floor(Math.random()*5000));
        }
<?php
if (!(is_page_template ('logo.php'))) {
?>
        $(document).ready( function () {
            setTimeout ("cycle_logo_letter('R');", 5000 + Math.floor(Math.random()*5000));
            setTimeout ("cycle_logo_letter('A1');", 5000 + Math.floor(Math.random()*5000));
            setTimeout ("cycle_logo_letter('A2');", 5000 + Math.floor(Math.random()*5000));
            setTimeout ("cycle_logo_letter('K');", 5000 + Math.floor(Math.random()*5000));
        });
<?php
}
?>
    //-->
    </script>
</head>

<body class="<?php sandbox_body_class() ?>">
<div id="wrapper" class="hfeed">
    <div id="header">
        <div id="header_top"></div>
        <div id="blog-title">
            <div id="logo_letter_r1" class="logo_letter">
                <a href="<?php bloginfo('home') ?>/" title="<?php echo wp_specialchars( get_bloginfo('name'), 1 ) ?>" rel="home">
<?php
if (sizeof ($letters['R']) == 0) {
?>
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/torn1.jpg" />
<?php
} else {
    $letter = $letters['R'][rand(0, (sizeof ($letters['R']) - 1))];
?>
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/resize.php?filename=logo_uploads/<?php echo $letter->filename; ?>&width=35&height=42" />
<?php
}
?>
                </a>
            </div>
            <div id="logo_letter_a2" class="logo_letter">
                <a href="<?php bloginfo('home') ?>/" title="<?php echo wp_specialchars( get_bloginfo('name'), 1 ) ?>" rel="home">
<?php
if (sizeof ($letters['A']) == 0) {
?>
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/torn2.jpg" />
<?php
} else {
    $letter = $letters['A'][rand(0, (sizeof ($letters['A']) - 1))];
?>
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/resize.php?filename=logo_uploads/<?php echo $letter->filename; ?>&width=35&height=42" />
<?php
}
?>
                </a>
            </div>
            <div id="logo_letter_a3" class="logo_letter">
                <a href="<?php bloginfo('home') ?>/" title="<?php echo wp_specialchars( get_bloginfo('name'), 1 ) ?>" rel="home">
<?php
if (sizeof ($letters['A']) == 0) {
?>
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/torn3.jpg" />
<?php
} else {
    $letter = $letters['A'][rand(0, (sizeof ($letters['A']) - 1))];
?>
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/resize.php?filename=logo_uploads/<?php echo $letter->filename; ?>&width=35&height=42" />
<?php
}
?>
                </a>
            </div>
            <div id="logo_letter_k4" class="logo_letter">
                <a href="<?php bloginfo('home') ?>/" title="<?php echo wp_specialchars( get_bloginfo('name'), 1 ) ?>" rel="home">
<?php
if (sizeof ($letters['K']) == 0) {
?>
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/torn4.jpg" />
<?php
} else {
    $letter = $letters['K'][rand(0, (sizeof ($letters['K']) - 1))];
?>
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/resize.php?filename=logo_uploads/<?php echo $letter->filename; ?>&width=35&height=42" />
<?php
}
?>
                </a>
            </div>
        </div>
		<div id="blog-description"><?php bloginfo('description') ?></div>
        <div id="access">
		    <div class="skip-link"><a href="#content" title="<?php _e( 'Skip to content', 'sandbox' ) ?>"><?php _e( 'Skip to content', 'sandbox' ) ?></a></div>
		    <?php sandbox_globalnav() ?>
	    </div><!-- #access -->
        <div id="header_bottom"></div>
	</div><!--  #header -->

