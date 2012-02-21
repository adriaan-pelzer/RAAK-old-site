<?php
/*
Template Name: Twitter Registrations
*/
class TwitterState {
    const START = 0;
    const INIT = 1;
    const AUTH = 2;
    const RESP = 3;
    const ERR = 4;
}

$twitter_status = TwitterState::START;

if (current_user_can('manage_options')) {
    $enable_see_yourself = true;
} else {
    $enable_see_yourself = false;
}

if ($enable_see_yourself && !empty($_REQUEST['screen_name'])) {
    require_once (dirname(__FILE__)."/tmhOAuth.php");

    $twitter_status = TwitterState::INIT;

    $twitter = new tmhOAuth(array(
        'consumer_key' => 'j3ipoBsLRTURQKsClTw1Q',
        'consumer_secret' => 'c4zwwzhuTJNBWfRzEHKwHY8ESowH2Zb52e3SwjL3kM',
        'user_token' => '169026281-8AagsypAkgOpKPyM5SA8MFTeosfYQ2lMyAEfIfDi',
        'user_secret' => 'oPZgstaEPsQzul2q3d0CVOs096vXd30lMonG7w9c'
    ));

    if ($twitter) {
        $twitter_status = TwitterState::AUTH;

        $code = $twitter->request('GET', $twitter->url('1/users/lookup'), array('screen_name' => $_REQUEST['screen_name']));

        if ($code == 200) {
            $user = json_decode($twitter->response['response']);
            $twitter_status = TwitterState::RESP;
        } else {
            if ($code > 499) {
                $error = "Something is broken at Twitter right now, please try again in a while";
            } else if ($code == 400) {
                $error = "There are too many of you! We are being rate limited by Twitter. Please try again in a while";
            } else if ($code == 401) {
                $error = "I think we might have been blocked by Twitter. Oops!";
            } else if ($code = 404) {
                $error = "There is no such user. Please check if you've typed your name correctly";
            } else if ($code = 406) {
                $error = "Twitter returned a \"not acceptable\" error. My God, they're weird ...";
            }

            $twitter_status = TwitterState::ERR;
        }
    } else {
        $error = "Cannot init twitter";
        $twitter_status = TwitterState::ERR;
    }
}
?>
<?php get_header() ?>
<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();

        $maxid = 0;

        if ($msql = mysql_connect("localhost", "twats_twats", "tW4Ts!")) {
            if (mysql_select_db("twats_twats", $msql)) {
                if ($resource = mysql_query("SELECT MAX(`id`) FROM `users`;", $msql)) {
                    if ($row = mysql_fetch_array($resource)) {
                        $maxid = $row[0];
                    }
                }

                if ($resource = mysql_query("SELECT `time` FROM `users` WHERE `id`=".$maxid.";", $msql)) {
                    if ($row = mysql_fetch_array($resource)) {
                        $maxtime = $row[0];
                    }
                }

                if ($resource = mysql_query("SELECT MIN(`id`) FROM `users`;", $msql)) {
                    if ($row = mysql_fetch_array($resource)) {
                        $minid = $row[0];
                    }
                }

                if ($resource = mysql_query("SELECT `time` FROM `users` WHERE `id`=".$minid.";", $msql)) {
                    if ($row = mysql_fetch_array($resource)) {
                        $mintime = $row[0];
                    }
                }
            }
        }

        ob_start();
        the_content();
        $content = ob_get_contents();
        ob_end_clean();

        if ($enable_see_yourself) {
            $html_to_replace = '<div id="see_yourself_form">';
            $html_to_replace .= empty($error)?'':'<p class="error">'.$error.'</p>';
            $html_to_replace .= '<p>';
            $html_to_replace .= 'Do you want to see where you are on the graph below? Type your twitter name in the box below and submit.';
            $html_to_replace .= '</p>';
            $html_to_replace .= '<p>';
            $html_to_replace .= '<form action="" method="get">';
            $html_to_replace .= '<input type="text" name="screen_name" style="margin-right: 20px;" />';
            $html_to_replace .= '<input type="submit" name="submit_screen_name" value="See Yourself on the Graph" />';
            $html_to_replace .= '</form>';
            $html_to_replace .= '</p>';
            $html_to_replace .= '</div>';

            $content = str_replace("[see_yourself]", $html_to_replace, $content);
        } else {
            $content = str_replace("[see_yourself]", "", $content);
        }
?>
	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="whitebox_big">
                <div id="whitebox_big_top">
                </div><!-- #whitebox_big_top -->
                <div id="whitebox_big_body">
                <div id="whitebox_big_body_title"><?php echo $maxid?"Twitter now has ".$maxid." registered users.":"Twitter Users"; ?></div>
                    <div class="whitebox_big_body_nav">
                    </div><!-- whitebox_big_body_nav -->
                    <hr />
                    <?php echo $content; ?>
                </div><!-- #whitebox_big_body -->
                <div id="whitebox_big_bottom">
                </div><!-- #whitebox_big_bottom -->
            </div><!-- #whitebox_big -->
		</div><!-- #content -->
        <?php get_sidebar() ?>
        <div id="container_bottom"></div>
	</div><!-- #container -->
<?php
        if ($twitter_status == TwitterState::RESP) {
            $text_size = 15;
            $width = 1700;
            $height = 1200;
            $margintop = 50;
            $marginbottom = 50;
            $marginleft = 150;
            $marginright = 50;

            $nwidth = 740;
            $nheight = 522;
            $wratio = $nwidth/$width;
            $hratio = $nheight/$height;
            $ntext_size = $text_size * $hratio;
            $nmargintop = $margintop * $hratio;
            $nmarginbottom = $marginbottom * $hratio;
            $nmarginleft = $marginleft * $wratio;
            $nmarginright = $marginright * $wratio;
            $usery = $nheight - $nmarginbottom - (($user[0]->id - $minid)/($maxid - $minid))*($nheight - $nmarginbottom - $nmargintop);
            $userx = $nmarginleft + ((strtotime($user[0]->created_at) - $mintime)/($maxtime - $mintime))*($nwidth - $nmarginright - $nmarginleft);
?>
    <script>
    $(document).ready(function() {
        var graph = new Image();
        var imgsrc = $('#whitebox_big_body img').attr('src');
        var ctx;

        graph.src = imgsrc;
        $('#whitebox_big_body img').replaceWith('<canvas width="740" height="522" id="graph"></canvas>');
        ctx = document.getElementById('graph').getContext('2d');
        ctx.drawImage(graph, 0, 0, 740, 522);
        ctx.fillStyle = "#0b0";
        ctx.fillRect(<?php echo ($userx - 2); ?>, <?php echo ($usery - 2); ?>, 4, 4);
        ctx.fillStyle = "#000";
        ctx.textBaseline = 'bottom';
        ctx.textAlign = 'right';
        ctx.fillText('<?php echo $_REQUEST['screen_name']; ?>', <?php echo ($userx - 2); ?>, <?php echo ($usery - 2); ?>);
    });
    </script>
<?php
        }
    }
}
?>
<?php get_footer() ?>
