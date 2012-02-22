<?php
require_once 'Browser.php';

$browser = new Browser ();
$ie678 = false;

/*switch ($browser->getPlatform()) {
case Browser::PLATFORM_IPHONE:
case Browser::PLATFORM_IPOD:
case Browser::PLATFORM_BLACKBERRY:
case Browser::PLATFORM_NOKIA:
case Browser::PLATFORM_ANDROID:
    if (!(isset ($_SESSION['desktop_on_mobile']))) {
        header ("Location: http://m.nirvana.raak.it");
        die();
    }
    break;
}*/

function is_ie678(){
    $browser = new Browser ();

    switch ($browser->getBrowser()) {
    case Browser::BROWSER_IE:
        if ($browser->getVersion() >= 9) {
            return FALSE;
        } else if ($browser->getVersion() >= 8) {
            return TRUE;
        } else if ($browser->getVersion() >= 7) {
            return TRUE;
        } else if ($browser->getVersion() >= 6) {
            return TRUE;
        } else {
            return TRUE;
        }
        break;
    default:
        return FALSE;
    }
}

function browser_specific_stylesheet(){
    $browser = new Browser ();

    switch ($browser->getBrowser()) {
    case Browser::BROWSER_OPERA:
        $stylesheet = 'style_op11.css';
        break;
    case Browser::BROWSER_FIREFOX:
        if ($browser->getVersion() >= 4) {
            $stylesheet = 'style_ff4.css';
        } else if ($browser->getVersion() >= 3.6) {
            $stylesheet = 'style_ff36.css';
        } else if ($browser->getVersion() >= 3.5) {
            $stylesheet = 'style_ff35.css';
        } else {
            $stylesheet = 'style_ff35.css'; /*!!*/
        }
        break;
    case Browser::BROWSER_IE:
        if ($browser->getVersion() >= 9) {
            $stylesheet = 'style_ie9.css';
        } else if ($browser->getVersion() >= 8) {
            $ie678 = true;
            $stylesheet = 'style_ie8.css';
        } else if ($browser->getVersion() >= 7) {
            $ie678 = true;
            $stylesheet = 'style_ie7.css';
        } else if ($browser->getVersion() >= 6) {
            $ie678 = true;
            $stylesheet = 'style_ie7.css'; /*!!*/
        } else {
            $ie678 = true;
            $found = FALSE;
        }
        break;
    case Browser::BROWSER_SAFARI:
        $stylesheet = 'style_sf5.css';
        break;
    case Browser::BROWSER_CHROME:
        if ($browser->getVersion() >= 12) {
            $stylesheet = 'style_ch10.css'; /*!!*/
        } else if ($browser->getVersion() >= 11) {
            $stylesheet = 'style_ch10.css'; /*!!*/
        } else if ($browser->getVersion() >= 10) {
            $stylesheet = 'style_ch10.css';
        } else if ($browser->getVersion() >= 9) {
            $stylesheet = 'style_ch10.css'; /*!!*/
        } else {
            $stylesheet = 'style_ch10.css'; /*!!*/
        }
        break;
    default:
        $found = FALSE;
        break;
    }

    if ($stylesheet) {
        return '<link rel="stylesheet" href="'.get_bloginfo ('template_url').'/'.$stylesheet.'?ver=1.0" /><!-- '.$browser->getUserAgent().' -->';
    } else {
        return '<!-- '.$browser->getUserAgent().' -->';
    }
}

?>
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
    <meta name="google-site-verification" content="gopVig1vD9ASr_RMvJGPk5w2Rk9dQ16tJY4v7-5E5dc" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>?ver=1.0" />
    <!--dynamic-cached-content--><?php echo browser_specific_stylesheet(); ?><!--/dynamic-cached-content-->
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
echo "<!--".$_SERVER['HTTP_USER_AGENT']."-->\n";
if (preg_match ("/iPhone/", $_SERVER['HTTP_USER_AGENT'])) {
?>
	<link rel="stylesheet" type="text/css" href="<?php echo str_replace ("style.css", "style_iphone.css", get_bloginfo('stylesheet_url')); ?>" />
<?php
}
?>
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
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-350036-10']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

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

