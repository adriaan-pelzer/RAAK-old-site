<?php
function patch_dimensions ($code, $width, $height) {
    $widthtexts = array();
    $heighttexts = array();
    $oldwidth = -1;
    $oldheight = -1;

    if (preg_match ("/width=\"([^\"]+)\"/", $code, $widthtexts)) {
        $oldwidth = $widthtexts[1];
    }

    if (preg_match ("/height=\"([^\"]+)\"/", $code, $heighttexts)) {
        $oldheight = $heighttexts[1];
    }

    if ($width && $height) {
        $patchwidth = TRUE;
        $patchheight = TRUE;
    } else if ($height) {
        $patchwidth = FALSE;
        $patchheight = TRUE;
    } else if ($width) {
        $patchwidth = TRUE;
        $patchheight = FALSE;
    } else {
        $patchwidth = FALSE;
        $patchheight = FALSE;
    }

    if ($patchwidth && $patchheight) {
        if ($oldwidth == -1) {
            $code = preg_replace ("/\/?>$/", " width=\"".$width."\" />", $code);
        } else {
            $code = preg_replace ("/width=\"[^\"]+\"/", " width=\"".$width."\"", $code);
        }
        if ($oldheight == -1) {
            $code = preg_replace ("/\/?>$/", " height=\"".$height."\" />", $code);
        } else {
            $code = preg_replace ("/height=\"[^\"]+\"/", "height=\"".$height."\"", $code);
        }
    } else if ($patchwidth) {
        if ($oldwidth == -1) {
            $code = preg_replace ("/\/?>$/", " width=\"".$width."\" />", $code);
        } else {
            $code = preg_replace ("/width=\"[^\"]+\"/", " width=\"".$width."\"", $code);
            if ($oldheight != -1) {
                $code = preg_replace ("/height=\"[^\"]+\"/", "height=\"".$width*$oldheight/$oldwidth."\"", $code);
            }
        }
    } else if ($patchheight) {
        if ($oldheight == -1) {
            $code = preg_replace ("/\/?>$/", " height=\"".$height."\" />", $code);
        } else {
            $code = preg_replace ("/height=\"[^\"]+\"/", "height=\"".$height."\"", $code);
            if ($oldwidth != -1) {
                $code = preg_replace ("/width=\"[^\"]+\"/", "width=\"".$height*$oldwidth/$oldheight."\"", $code);
            }
        }
    }

    return $code;
}

function mine_gallery ($id) {
    global $wpdb;

    $records = $wpdb->get_results ("SELECT path FROM ".$wpdb->nggallery." WHERE gid=".$id);

    $path = $records[0]->path;

    $records = $wpdb->get_results ("SELECT filename FROM ".$wpdb->nggpictures." WHERE galleryid=".$id." ORDER BY `sortorder` LIMIT 1");
    $filename = $records[0]->filename;

    $returncode = "<img alt=\"".$records[0]->alttext."\" src=\"".get_bloginfo('url')."/".$path."/".$filename."\" ";

    if ($width) {
        $returncode .= "width=\"".$width."\" ";
    }

    if ($height) {
        $returncode .= "height=\"".$height."\" ";
    }

    $returncode .= "/>";

    return $returncode;
}

function get_image ($post_content, $width=NULL, $height=NULL) {
    $returncode = NULL;
    $gallerytext = array();
    $matches_img = array();
    $matches_gal = array();

    if (preg_match ("/<img[^>]+>/", $post_content, $matches_img, PREG_OFFSET_CAPTURE)) {
        if (preg_match ("/\[nggallery id=(\d+)\]/", $post_content, $matches_gal, PREG_OFFSET_CAPTURE) && ($matches_gal[0][1] < $matches_img[0][1])) {
            return patch_dimensions (mine_gallery ($matches_gal[1][0]), $width, $height);
        } else {
            $returncode = $matches_img[0][0];

            $returncode = patch_dimensions ($returncode, $width, $height);

            $returncode = preg_replace ("/ class=\"[^\"]*\"/", "", $returncode);
        }
    } else if (preg_match ("/\[nggallery id=(\d+)\]/", $post_content, $matches_gal, PREG_OFFSET_CAPTURE)) {
        return patch_dimensions (mine_gallery ($matches_gal[1][0]), $width, $height);
    }

    return $returncode;
}

function get_image_or_video ($post_content, $width=NULL, $height=NULL) {
    $returncode = NULL;
    $gallerytext = array();
    $videotext = array();
    $imagetext = array();

    $gallerymatch = preg_match ("/\[nggallery id=(\d+)\]/", $post_content, $gallerytext, PREG_OFFSET_CAPTURE);
    $videomatch = preg_match ("/<object.*<\/object>/", $post_content, $videotext, PREG_OFFSET_CAPTURE);
    $imagematch = preg_match ("/<img[^>]+>/", $post_content, $imagetext, PREG_OFFSET_CAPTURE);

    if ($gallerymatch && $videomatch && $imagematch) {
        if (($gallerytext[0][1] < $videotext[0][1]) && ($gallerytext[0][1] < $imagetext[0][1])) {
            return patch_dimensions (mine_gallery ($gallerytext[1][0]), $width, $height);
        } else if (($videotext[0][1] < $gallerytext[0][1]) && ($videotext[0][1] < $imagetext[0][1])) {
            return patch_dimensions ($videotext[0][0], $width, $height);
        } else {
            return preg_replace ("/ class=\"[^\"]*\"/", "", patch_dimensions ($imagetext[0][0], $width, $height));
        }
    } else if ($gallerymatch && $videomatch) {
        if ($gallerytext[0][1] < $videotext[0][1]) {
            return patch_dimensions (mine_gallery ($gallerytext[1][0]), $width, $height);
        } else {
            return patch_dimensions ($videotext[0][0], $width, $height);
        }
    } else if ($gallerymatch && $imagematch) {
        if ($gallerytext[0][1] < $imagetext[0][1]) {
            return patch_dimensions (mine_gallery ($gallerytext[1][0]), $width, $height);
        } else {
            return preg_replace ("/ class=\"[^\"]*\"/", "", patch_dimensions ($imagetext[0][0], $width, $height));
        }
    } else if ($videomatch && $imagematch) {
        if ($videotext[0][1] < $imagetext[0][1]) {
            return patch_dimensions ($videotext[0][0], $width, $height);
        } else {
            return preg_replace ("/ class=\"[^\"]*\"/", "", patch_dimensions ($imagetext[0][0], $width, $height));
        }
    } else if ($gallerymatch) {
        return patch_dimensions (mine_gallery ($gallerytext[1][0]), $width, $height);
    } else if ($videomatch) {
        return patch_dimensions ($videotext[0][0], $width, $height);
    } else if ($imagematch) {
        return preg_replace ("/ class=\"[^\"]*\"/", "", patch_dimensions ($imagetext[0][0], $width, $height));
    } else {
        return null;
    }
}

function get_post_by_name($page_name) {
    global $wpdb;

    $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE `post_name` = '%s' AND `post_type`='post'", $page_name ));
    if ( $post )
        return get_post($post);

    return null;
}

function get_post_by_title($page_title) {
    global $wpdb;

    $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE `post_title` = '%s' AND `post_type`='post'", $page_title ));
    if ( $post )
        return get_post($post);

    return null;
}

function get_ip () {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
?>
