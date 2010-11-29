<?php
/*
Template Name: Logo
*/

include ('local_functions.php');

$width = 700;
$height = 840;

$error_messages = array (
    'upload_letter'=>"Please choose a letter to upload",
    'upload_email'=>"Please enter a valid email address",
    'upload_url'=>"Please enter a valid url",
    'upload_name'=>"Please enter your name",
    'upload_agree'=>"Please tick to agree to the terms & conditions",
    'upload_file'=>"Please select a file to upload",
    'upload_file_type'=>"Picture type should be jpg or png",
    'upload_file_size'=>"Picture size too big",
    'upload_file_dim'=>"Picture dimensions wrong - it should be ".$width."x".$height,
    'upload_file_copy'=>"Picture can not be copied",
    'upload_db_insert'=>"Picture cannot be inserted into the database",
    'upload_db_update'=>"Picture confirmation state cannot be updated"
);

$error = array();
$state = 0;

if (isset ($_POST['upload_submit'])) {
    /* required fields */
    foreach (array ('upload_letter', 'upload_email', 'upload_name', 'upload_agree') as $errkey) {
        if (!(isset ($_POST[$errkey])) || ($_POST[$errkey] == "")) {
            array_push ($error, $errkey);
        }
    }
    /* /required fields */

    /* validation */
    $regex_url = "((https?|ftp)\:\/\/)?"; // SCHEME 
    $regex_url .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
    $regex_url .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
    $regex_url .= "(\:[0-9]{2,5})?"; // Port 
    $regex_url .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
    $regex_url .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
    $regex_url .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 

    if ((isset ($_POST['upload_url']) && ($_POST['upload_url'] != "")) && (!(preg_match ("/^".$regex_url."$/", $_POST['upload_url'])))) {
        array_push ($error, 'upload_url');
    }

    $regex_email = "([a-z0-9-.]*)\@([a-z0-9-.]*)";

    if ((isset ($_POST['upload_email']) && ($_POST['upload_email'] != "")) && (!(preg_match ("/^".$regex_email."$/", $_POST['upload_email'])))) {
        array_push ($error, 'upload_email');
    }
    /* /validation */

    if (($_FILES['upload_file']["name"] == "") && !(isset ($_POST['uploaded_file']))) {
        array_push ($error, 'upload_file');
    }

    if ($_POST['upload_agree'] != 'on') {
        array_push ($error, 'upload_agree');
    }

    if ($_FILES['upload_file']["name"] != "") {
        unset ($_POST['index']);
        unset ($_POST['uploaded_file']);
        unset ($_POST['filename']);

        $imagesize = getimagesize ($_FILES["upload_file"]["tmp_name"]);

        if (($_FILES["upload_file"]["type"] != "image/jpeg") && ($_FILES["upload_file"]["type"] != "image/pjpeg") && ($_FILES["upload_file"]["type"] != "image/png") && ($_FILES["upload_file"]["type"] != "image/x-png")) {
            array_push ($error, 'upload_file_type');
        } else if ($_FILES['upload_file']['size'] > $_POST['MAX_FILE_SIZE']) {
            array_push ($error, 'upload_file_size');
        } else if (!(($imagesize[0] == $width) && ($imagesize[1] == $height))) {
            array_push ($error, 'upload_file_dim');
        } else {
            $filename = md5 ($_FILES["upload_file"]["name"].time()).((($_FILES["upload_file"]["type"] == "image/jpeg") || ($_FILES["upload_file"]["type"] == "image/pjpeg"))?".jpg":".png");
            if (!(move_uploaded_file ($_FILES["upload_file"]["tmp_name"], "wp-content/themes/RAAK/logo_uploads/".$filename))) {
                array_push ($error, 'upload_file_copy');
            } else {
                $uploaded_file = $filename;

                if (sizeof ($error) == 0) {
                    global $wpdb;

                    $upload_url = ($_POST['upload_url'] == "")?NULL:$_POST['upload_url'];

                    $data = array ('ipaddress'=>get_ip(), 'username'=>$_POST['upload_name'], 'useremail'=>$_POST['upload_email'], 'userurl'=>$upload_url, 'filename'=>$uploaded_file, 'originalname'=>$_FILES["upload_file"]["name"], 'letter'=>$_POST["upload_letter"]);
                    if (!($wpdb->insert( "wp_logo_uploads", $data ))) {
                        array_push ($error, 'upload_db_insert');
                    } else {
                        $index = $wpdb->insert_id;
                        $state = 2;
                    }
                }
            }
        }
    } else {
        if (isset ($_POST['uploaded_file'])) {
            if (sizeof ($error) == 0) {
                global $wpdb;

                $uploaded_file = $_POST['uploaded_file'];
                $upload_url = ($_POST['upload_url'] == "")?NULL:$_POST['upload_url'];

                $data = array ('ipaddress'=>get_ip(), 'username'=>$_POST['upload_name'], 'useremail'=>$_POST['upload_email'], 'userurl'=>$upload_url, 'filename'=>$uploaded_file, 'originalname'=>(isset ($_POST['filename'])?$_POST['filename']:'unavailable (inserted on second try)'), 'letter'=>$_POST["upload_letter"]);
                if (!($wpdb->insert( "wp_logo_uploads", $data ))) {
                    array_push ($error, 'upload_db_insert');
                } else {
                    $index = $wpdb->insert_id;
                    $state = 2;
                }
            }
        }
    }
}

if (isset ($_POST['preview_submit']) && isset ($_POST['index']) && isset ($_POST['uploaded_file'])) {
    global $wpdb;

    $data = array ('confirmed'=>1);
    $where = array ('index'=>$_POST['index']);

    if (!($wpdb->update( "wp_logo_uploads", $data, $where))) {
        array_push ($error, 'upload_db_update');
    } else {
        $state = 3;
    }
}

if (sizeof ($error) > 0) {
    $state = 1;
     if (in_array ('upload_letter', $error)) {
         $state = 0;
     }
}

?>

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
<?php get_header() ?>
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

        function cycle_letter (display_letter) {
            switch (display_letter) {
            case 'R':
                var letter = 'R';
                var default_letter = 'ar.jpg';
                break;
            case 'A1':
            case 'A2':
                var letter = 'A';
                var default_letter = 'ay1.jpg';
                break;
            case 'K':
                var letter = 'K';
                var default_letter = 'kay.jpg';
                break;
            }

            $("#preview_letter_"+display_letter+" img").fadeTo('slow', 0, function () {
                if (logo_letters[letter].length == 0) {
                    $("#preview_letter_"+display_letter+" img").attr('src', "<?php echo get_bloginfo('template_directory'); ?>/images/"+default_letter);
                } else {
                    $("#preview_letter_"+display_letter+" img").attr('src', logo_letters[letter][Math.floor(Math.random()*logo_letters[letter].length)].src);
                }

                $("#preview_letter_"+display_letter+" img").fadeTo('slow', 1);
            });

            setTimeout ("cycle_letter('"+display_letter+"');", 3000 + Math.floor(Math.random()*2000));
        }

        $(document).ready( function () {
            $("#bluebox_body_content_A").hide();

            $("#bluebox_body_content_K").hide();

<?php
foreach (array ('R', 'A', 'K') as $letter) {
?>
            $("#letter_<?php echo $letter; ?> a img").fadeTo (0, <?php if ((isset ($_GET['upload_letter']) && ($_GET['upload_letter'] == $letter)) || (!(isset ($_GET['upload_letter'])) && ($letter == 'R'))) { ?>1<?php } else { ?>0.3<?php } ?>);
<?php
}
?>

            $("#again").click ( function () {
                $("#filename").val ('');
                $("#index").val ('');
                $("#uploaded_file").val ('');
                $("#upload_file").val ('');
                $("#upload_letter").val ('R');

                location.reload ();
            });

            $("#upload_file").change ( function () {
                $("#dummy_file_text").html ($("#upload_file").val());
            });

            $("#whitebox_secondary_body_upload_next a").click ( function () {
                $("#whitebox_secondary_tabs_upload").removeClass ("active");
                $("#whitebox_secondary_body_upload").fadeOut ('fast', function () {
                    $("#whitebox_secondary_body_submit").fadeIn ('fast', function () {
                        $("#whitebox_secondary_tabs_submit").addClass ("active");
                    });
                });
            });

            $("#whitebox_secondary_body_submit_back a").click ( function () {
                $("#whitebox_secondary_tabs_submit").removeClass ("active");
                $("#whitebox_secondary_body_submit").fadeOut ('fast', function () {
                    $("#whitebox_secondary_body_upload").fadeIn ('fast', function () {
                        $("#whitebox_secondary_tabs_upload").addClass ("active");
                    });
                });
            });

            $("#whitebox_secondary_body_preview_back a").click ( function () {
                $("#whitebox_secondary_tabs_preview").removeClass ("active");
                $("#whitebox_secondary_body_preview").fadeOut ('fast', function () {
                    $("#whitebox_secondary_body_submit").fadeIn ('fast', function () {
                        $("#whitebox_secondary_tabs_submit").addClass ("active");
                    });
                });
            });

            $("#expand_R").click ( function () {
                $("#bluebox_body_content_"+active_logos).fadeOut ('fast', function () {
                    $("#bluebox_body_content_R").fadeIn ('fast', function () {
                        active_logos = 'R';
                    });
                });
            });

            $("#expand_A").click ( function () {
                $("#bluebox_body_content_"+active_logos).fadeOut ('fast', function () {
                    $("#bluebox_body_content_A").fadeIn ('fast', function () {
                        active_logos = 'A';
                    });
                });
            });

            $("#expand_K").click ( function () {
                $("#bluebox_body_content_"+active_logos).fadeOut ('fast', function () {
                    $("#bluebox_body_content_K").fadeIn ('fast', function () {
                        active_logos = 'K';
                    });
                });
            });

            $("#letter_R a").click ( function () {
                if ($("#upload_letter").val () != 'R') {
                    $("#letter_R a img").fadeTo ('fast', 1, function () {
                        $("#letter_"+$("#upload_letter").val ()+" a img").fadeTo ('fast', 0.3, function () {
                            $("#upload_letter").val ('R');
                        });
                    });
                }
            });

            $("#letter_A a").click ( function () {
                if ($("#upload_letter").val () != 'A') {
                    $("#letter_A a img").fadeTo ('fast', 1, function () {
                        $("#letter_"+$("#upload_letter").val ()+" a img").fadeTo ('fast', 0.3, function () {
                            $("#upload_letter").val ('A');
                        });
                    });
                }
            });

            $("#letter_K a").click ( function () {
                if ($("#upload_letter").val () != 'K') {
                    $("#letter_K a img").fadeTo ('fast', 1, function () {
                        $("#letter_"+$("#upload_letter").val ()+" a img").fadeTo ('fast', 0.3, function () {
                            $("#upload_letter").val ('K');
                        });
                    });
                }
            });

<?php
if ($state == 2) {
    if ($my_letter != 'R') {
?>
            setTimeout ("cycle_letter('R');", 3000 + Math.floor(Math.random()*2000));
<?php
    }

    if ($my_letter != 'A') {
?>
            setTimeout ("cycle_letter('A1');", 3000 + Math.floor(Math.random()*2000));
<?php
    }
?>
            setTimeout ("cycle_letter('A2');", 3000 + Math.floor(Math.random()*2000));
<?php
    if ($my_letter != 'K') {
?>
            setTimeout ("cycle_letter('K');", 3000 + Math.floor(Math.random()*2000));
<?php
    }
}
?>
            setTimeout ("cycle_logo_letter('R');", 3000 + Math.floor(Math.random()*2000));
            setTimeout ("cycle_logo_letter('A1');", 3000 + Math.floor(Math.random()*2000));
            setTimeout ("cycle_logo_letter('A2');", 3000 + Math.floor(Math.random()*2000));
            setTimeout ("cycle_logo_letter('K');", 3000 + Math.floor(Math.random()*2000));
        });


    //-->
    </script>
	<div id="container">
        <div id="container_top"></div>
		<div id="content">
            <div id="whitebox_primary">
                <div id="whitebox_primary_top">
                </div><!-- #whitebox_primary_top -->
                <div id="whitebox_primary_body">
                    <?php the_content(); ?>
                </div><!-- #whitebox_primary_body -->
                <div id="whitebox_primary_bottom">
                </div><!-- #whitebox_primary_bottom -->
            </div><!-- #whitebox_primary -->
            <div id="bluebox">
                <div id="bluebox_top"></div>
                <div id="bluebox_body">
                    <div id="bluebox_body_title">
                        Latest Uploads
                    </div>
                    <hr />
                    <div id="bluebox_body_nav">
                        <span class="bluebox_body_nav_item">
                            <span id="expand_R" class="bluebox_body_nav_item_left"><a>R</a></span>
                            <span class="bluebox_body_nav_seperator">|</span>
                            <span class="bluebox_body_nav_item_right"><a href="<?php the_permalink(); ?>/logo-archive/?letter=R">View All</a></span>
                        </span>
                        <span class="bluebox_body_nav_item">
                            <span id="expand_A" class="bluebox_body_nav_item_left"><a>A</a></span>
                            <span class="bluebox_body_nav_seperator">|</span>
                            <span class="bluebox_body_nav_item_right"><a href="<?php the_permalink(); ?>/logo-archive/?letter=A">View All</a></span>
                        </span>
                        <span class="bluebox_body_nav_item">
                            <span id="expand_K" class="bluebox_body_nav_item_left"><a>K</a></span>
                            <span class="bluebox_body_nav_seperator">|</span>
                            <span class="bluebox_body_nav_item_right"><a href="<?php the_permalink(); ?>/logo-archive/?letter=K">View All</a></span>
                        </span>
                    </div>
                    <hr class="solid" />
<?php
foreach (array ('R', 'A', 'K') as $letter) {
?>
                    <div id="bluebox_body_content_<?php echo $letter; ?>" class="bluebox_body_content">
<?php
    $i = 0;

    for ($i = 0; $i < 6; $i++) {
        if (sizeof ($letters[$letter]) > $i) {
            $upload = $letters[$letter][$i];
        } else {
            $upload = null;
        }

        switch ($i) {
        case 0:
            $bigbreak = "top";
            $bigbreakend = null;
            $position = "left_top";
            break;
        case 1:
            $bigbreak = null;
            $bigbreakend = null;
            $position = "center_top";
            break;
        case 2:
            $bigbreak = null;
            $bigbreakend = TRUE;
            $position = "right_top";
            break;
        case 3:
            $bigbreak = "bottom";
            $bigbreakend = null;
            $position = "left_bottom";
            break;
        case 4:
            $bigbreak = null;
            $bigbreakend = null;
            $position = "center_bottom";
            break;
        case 5:
            $bigbreak = null;
            $bigbreakend = TRUE;
            $position = "right_bottom";
            break;
        }

        if ($bigbreak != null) {
?>
                        <div id="bluebox_body_<?php echo $bigbreak; ?>">
<?php
        }
?>
                            <div id="bluebox_body_<?php echo $position; ?>">
<?php
        if ($upload) {
?>
                                <div id="bluebox_body_<?php echo $position; ?>_image">
                                    <img src="<?php echo get_bloginfo('template_directory'); ?>/resize.php?filename=logo_uploads/<?php echo $upload->filename; ?>&width=70&height=82" />
                                </div>
                                <div id="bluebox_body_<?php echo $position; ?>_blurp">
                                    Submitted by
                                </div>
                                <div id="bluebox_body_<?php echo $position; ?>_name">
<?php
            if (($upload->userurl) && (!(preg_match ("/^((https?|ftp)\:\/\/)/", $upload->userurl)))) {
                $userurl = "http://".$upload->userurl;
            }
?>
                                    <a href="<?php echo $userurl; ?>"><?php echo $upload->username; ?></a>
                                </div>
<?php
        } else {
?>
                                <!--div id="bluebox_body_<?php echo $position; ?>_image">
                                    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/def.jpg" />
                                </div>
                                <div id="bluebox_body_<?php echo $position; ?>_blurp">
                                </div>
                                <div id="bluebox_body_<?php echo $position; ?>_name">
                                </div-->
<?php
        }
?>
                            </div>
<?php
        if ($bigbreakend) {
?>
                        </div>
<?php
        }
    }
?>
                    </div><!-- bluebox_body_content -->
<?php
}
?>
                </div><!-- bluebox_body -->
                <div id="bluebox_bottom"></div>
            </div><!-- #bluebox -->
            <div id="whitebox_extra">
                <div id="whitebox_extra_tabs">
                    <div id="whitebox_extra_tabs_download">
                        <div class="whitebox_extra_tab_top"></div>
                        <div class="whitebox_extra_tab_body">Downloads</div>
                    </div>
                </div>
                <div id="whitebox_extra_top"></div>
                <div id="whitebox_extra_body">
                    <ul>
                        <li><a href="http://wewillraakyou.com/RAAK-letter-Illustrator-template.zip">Illustrator Template</a></li>
                        <li><a href="http://wewillraakyou.com/raak-letter-PS-template.zip">Photoshop Template</a></li>
                    </ul>
                </div>
                <div id="whitebox_extra_bottom"></div>
            </div>
            <div id="whitebox_secondary">
                <div id="whitebox_secondary_tabs">
                    <div id="whitebox_secondary_tabs_upload" class="whitebox_secondary_tab<?php if ($state == 0) { ?> active<?php } ?>">
                        <div class="whitebox_secondary_tab_top"></div>
                        <div class="whitebox_secondary_tab_body">Upload a letter</div>
                    </div>
                    <div id="whitebox_secondary_tabs_submit" class="whitebox_secondary_tab<?php if ($state == 1) { ?> active<?php } ?>">
                        <div class="whitebox_secondary_tab_top"></div>
                        <div class="whitebox_secondary_tab_body">Submit</div>
                    </div>
                    <div id="whitebox_secondary_tabs_preview" class="whitebox_secondary_tab<?php if ($state == 2) { ?> active<?php } ?>">
                        <div class="whitebox_secondary_tab_top"></div>
                        <div class="whitebox_secondary_tab_body">Preview</div>
                    </div>
                    <div id="whitebox_secondary_tabs_finish" class="whitebox_secondary_tab<?php if ($state == 3) { ?> active<?php } ?>">
                        <div class="whitebox_secondary_tab_top"></div>
                        <div class="whitebox_secondary_tab_body">Finish</div>
                    </div>
                </div><!-- #whitebox_secondary_tabs -->
                <div id="whitebox_secondary_top"></div>
                <div id="whitebox_secondary_body">
<?php
/*echo "POST: ";
print_r ($_POST);
echo "<br />FILES: ";
print_r ($_FILES);
echo "<br />ERR: ";
print_r ($error);
echo "<br />";*/
?>
                    <form method="post" enctype="multipart/form-data" action="">
<?php
if (isset ($_POST['filename'])) {
?>
                    <input id="filename" type="hidden" name="filename" value="<?php echo $_POST['filename']; ?>" />
<?php
} else if (isset ($_FILES["upload_file"]["name"]) && ($_FILES["upload_file"]["name"] != "")) {
?>
                    <input id="filename" type="hidden" name="filename" value="<?php echo $_FILES["upload_file"]["name"]; ?>" />
<?php
}

if (isset ($index) && ($index != 0)) {
?>
                    <input id="index" type="hidden" name="index" value="<?php echo $index; ?>" />
<?php
} else if (isset ($_POST['index'])) {
?>
                    <input id="index" type="hidden" name="index" value="<?php echo $_POST['index']; ?>" />
<?php
}

if (isset ($uploaded_file) && ($uploaded_file != "")) {
?>
                    <input id="uploaded_file" type="hidden" name="uploaded_file" value="<?php echo $uploaded_file; ?>" />
<?php
} else if (isset ($_POST['uploaded_file'])) {
?>
                    <input id="uploaded_file" type="hidden" name="uploaded_file" value="<?php echo $_POST['uploaded_file']; ?>" />
<?php
}
?>
                    <div id="whitebox_secondary_body_upload"<?php if ($state != 0) { ?> style="display: none;"<?php } ?>>
                            <p>Choose the letter you've designed</p>
<?php
if (in_array ('upload_letter', $error)) {
?>
                            <div class="error">
                                <?php echo $error_messages ['upload_letter']; ?>
                            </div>
<?php
}
?>
                            <div id="whitebox_secondary_body_upload_letters">
                                <input id="upload_letter" type="hidden" name="upload_letter" value="<?php echo (isset ($_POST['upload_letter'])?$_POST['upload_letter']:'R'); ?>" />
                                <span id="letter_R"><a><img alt="R" src="<?php echo get_bloginfo('template_directory'); ?>/images/ar.jpg" /></a></span>
                                <span id="letter_A"><a><img alt="A" src="<?php echo get_bloginfo('template_directory'); ?>/images/ay1.jpg" /></a></span>
                                <span id="letter_K"><a><img alt="K" src="<?php echo get_bloginfo('template_directory'); ?>/images/kay.jpg" /></a></span>
                            </div>
                            <div id="whitebox_secondary_body_upload_next">
                                <a>Next &#9658;</a>
                            </div>
                        </div><!-- #whitebox_secondary_body_upload -->
                        <div id="whitebox_secondary_body_submit"<?php if ($state != 1) { ?> style="display: none;"<?php } ?>>
<?php
foreach ($error as $errkey) {
    if (preg_match ("/upload_db/", $errkey)) {
?>
                            <div class="error">
                                <?php echo $error_messages [$errkey]; ?>
                            </div>
<?php
    }
}
?>
<?php
if (in_array ('upload_name', $error)) {
?>
                            <div class="error">
                                <?php echo $error_messages ['upload_name']; ?>
                            </div>
<?php
}
?>
                            <div id="whitebox_secondary_body_submit_name">
                                <label for="upload_name">Your Name</label>
                                <input id="upload_name" name="upload_name"<?php if (isset ($_POST['upload_name'])) { ?> value="<?php echo $_POST['upload_name']; ?>"<?php } ?> type="text" maxlength="40" />
                            </div>
<?php
if (in_array ('upload_email', $error)) {
?>
                            <div class="error">
                                <?php echo $error_messages ['upload_email']; ?>
                            </div>
<?php
}
?>
                            <div id="whitebox_secondary_body_submit_email">
                                <label for="upload_email">Email</label>
                                <input id="upload_email" name="upload_email"<?php if (isset ($_POST['upload_email'])) { ?> value="<?php echo $_POST['upload_email']; ?>"<?php } ?> type="text" maxlength="255" />
                            </div>
<?php
if (in_array ('upload_url', $error)) {
?>
                            <div class="error">
                                <?php echo $error_messages ['upload_url']; ?>
                            </div>
<?php
}
?>
                            <div id="whitebox_secondary_body_submit_url">
                                <label for="upload_url">URL</label>
                                <input id="upload_url" name="upload_url"<?php if (isset ($_POST['upload_url'])) { ?> value="<?php echo $_POST['upload_url']; ?>"<?php } ?> type="text" maxlength="255" />
                            </div>
<?php
foreach ($error as $errkey) {
    if (preg_match ("/upload_file/", $errkey)) {
        if ($errkey == "upload_file_type") {
?>
                            <div class="error">
                                <?php echo $error_messages [$errkey].": file type: ".$_FILES["upload_file"]["type"]; ?>
                            </div>
<?php
        } else {
?>
                            <div class="error">
                                <?php echo $error_messages [$errkey]; ?>
                            </div>
<?php
        }
    }
}
?>
                            <div id="whitebox_secondary_body_submit_file">
                                <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                                <label for="upload_file">Browse for file</label>
                                <span id="file_replace"><input id="upload_file" name="upload_file" type="file" /><p id="dummy_file_text"><?php if (isset ($_FILES["upload_file"]["name"])) { echo $_FILES["upload_file"]["name"]; } ?></p></span>
                            </div>
<?php
if (in_array ('upload_agree', $error)) {
?>
                            <div class="error">
                                <?php echo $error_messages ['upload_agree']; ?>
                            </div>
<?php
}
?>
                            <div id="whitebox_secondary_body_submit_agree">
                            <label for="upload_agree">I agree to the <a href="<?php bloginfo ('url'); ?>/logo-project/terms-and-conditions/">terms & conditions</a></label>
                                <input id="upload_agree" name="upload_agree" type="checkbox" />
                                <input name="upload_submit" type="submit" value="Submit &#9658;" />
                            </div>
                            <div class="whitebox_secondary_back" id="whitebox_secondary_body_submit_back">
                                <a>&#9668; Go back</a>
                            </div>
                        </div><!-- #whitebox_secondary_body_submit -->
                        <div id="whitebox_secondary_body_preview"<?php if ($state != 2) { ?> style="display: none;"<?php } ?>>
                            <div id="whitebox_secondary_body_preview_letters">
<?php
$firstdone = FALSE;

foreach (array ('R'=>'ar.jpg', 'A'=>'ay1.jpg', 'A2'=>'ay1.jpg', 'K'=>'kay.jpg') as $letter=>$default) {
    if ($letter == 'A') {
        $displayletter = 'A1';
    } else if ($letter == 'A2') {
        $firstdone = TRUE;
        $letter = 'A';
        $displayletter = 'A2';
    } else {
        $displayletter = $letter;
    }

    if (($my_letter == $letter) && (!($firstdone))) {
?>
                                <span id="preview_letter_<?php echo $displayletter; ?>"><img alt="<?php echo $letter; ?>" src="<?php echo get_bloginfo('template_directory'); ?>/resize.php?filename=logo_uploads/<?php echo $my_upload; ?>&width=70&height=82" /></span>
<?php
    } else if (sizeof ($letters[$letter]) == 0) {
?>
                                <span id="preview_letter_<?php echo $displayletter; ?>"><img alt="<?php echo $letter; ?>" src="<?php echo get_bloginfo('template_directory'); ?>/resize.php?filename=images/<?php echo $default; ?>&width=70&height=82" /></span>
<?php
    } else {
        $random_index = rand (0, (sizeof ($letters[$letter]) - 1));
?>
                                <span id="preview_letter_<?php echo $displayletter; ?>"><img alt="<?php echo $letter; ?>" src="<?php echo get_bloginfo('template_directory'); ?>/resize.php?filename=logo_uploads/<?php echo $letters[$letter][$random_index]->filename; ?>&width=70&height=82" /></span>
<?php
    }

    $firstdone = FALSE;
}
?>
                            </div>
<?php
switch ($my_letter) {
case 'R':
?>
                            <div id="whitebox_secondary_body_preview_marker" class="first">
                               &#9650; 
                            </div>
<?php
    break;
case 'A':
?>
                            <div id="whitebox_secondary_body_preview_marker" class="second">
                               &#9650; 
                            </div>
<?php
    break;
case 'K':
?>
                            <div id="whitebox_secondary_body_preview_marker" class="fourth">
                               &#9650; 
                            </div>
<?php
    break;
}
?>
                            <div id="whitebox_secondary_body_preview_submit">
                                <input name="preview_submit" type="submit" value="HAPPY? Then SUBMIT your letter &#9658;" />
                            </div>
                            <div class="whitebox_secondary_back" id="whitebox_secondary_body_preview_back">
                                <a>&#9668; Go back</a>
                            </div>
                        </div><!-- #whitebox_secondary_body_preview -->
                        <div id="whitebox_secondary_body_finish"<?php if ($state != 3) { ?> style="display: none;"<?php } ?>>
                            <h3>THANKS for taking part!</h3>
                            <p>Your letter is now part of the loop.</p>
                            <button id="again">Upload another letter</button>
                        </div><!-- #whitebox_secondary_body_finish -->
                    </form>
                </div><!-- #whitebox_secondary_body -->
                <div id="whitebox_secondary_bottom"></div>
            </div><!-- #whitebox_secondary -->
		</div><!-- #content -->
        <?php get_sidebar() ?>
        <div id="container_bottom"></div>
	</div><!-- #container -->

<?php get_footer() ?>
