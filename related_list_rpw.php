<?php ob_start();
//define all variables the needed alot
include 'the_globals.php';
$postimage = '';
$dont_show_image = '';
?>
<?php
	$rpw_related_posts_settings = rpw_read_options();
	$limit = (stripslashes($rpw_related_posts_settings['rpw_posts_limit']));
	$searches = get_rpw_searches(rpw_get_taglist());
	$counter = 1;

	if($searches){
	echo '<div id="related_posts_rpw"><ul>';
		foreach($searches as $search) {
		$categorys = get_the_category($search->ID);	//Fetch categories of the plugin
		$p_in_c = false;	// Variable to check if post exists in a particular category
		$title = get_the_title($search->ID);
		$title = rpw_title_shorter($title,10);
		//---------------------------------
		echo '<li style="background-image: none">';
	if($rpw_related_posts_settings['rpw_show_thumbs'] == 'Yes'){
    	$out_post_thumbnail = '<div class="related_posts_rpw_main_image"><a href="'.get_permalink($search->ID).'" rel="related" title="'.$title.'">';
    if ((function_exists('get_the_post_thumbnail')) && (has_post_thumbnail($search->ID))) {
				$out_post_thumbnail .= get_the_post_thumbnail( $search->ID, 'rpw-thumb', array('title' => $title,'alt' => $title,'class' => 'rpw_image'));
		} else {
			$postimage = get_post_meta($search->ID, 'image' , true);
			$dont_show_image = 'No';
			if (!$postimage)
				{
				preg_match_all( $reg_exp, get_post($search->ID)->post_content, $matches );
				// any image there?
				if( isset( $matches ) && $matches[1][0] ) {
					$postimage = $matches[1][0]; // this give the first image only
					preg_match_all( $new_reg_exp, get_post($search->ID)->post_content, $matches2 );
					$new_img_src = $matches2[1][0];
					//echo $new_img_src;
					//will resize the image here
				}}
		$site_url = site_url();
		$postimage = str_replace("../",$site_url."/",$postimage);
			$out_post_thumbnail .= '<img src="'.$postimage.'" title="'.$title.'" class="rpw_image" />';
		if (!$postimage) {$dont_show_image = 'Yes';}
		}//of line 27
		//$out_post_thumbnail .= '<span id="entry-meta-span" class="entry-meta-span">'. get_the_time('M j, Y',$search->ID) .'</span>';
		$out_post_thumbnail .= '</a></div>';
		if ($dont_show_image == 'Yes') $out_post_thumbnail = '';
    }else{//for line 19 if
		$out_post_thumbnail = '';
	}
	echo $out_post_thumbnail;
	$rpw_Style = $rpw_related_posts_settings['rpw_Style'];
	if($rpw_Style != "Just_Thumbs" && $rpw_Style != "CSS-Just_Thumbs"){
	    echo '<div class="related_posts_rpw_main_content">';
	    echo '<p><a href="'; echo get_permalink($search->ID); echo '" rel="related" title="'; the_title(); echo '">'; echo $title; echo '</a></p>';
	    $rpw_show_excerpt_temp = $rpw_related_posts_settings['rpw_show_excerpt'];
	    if ($rpw_show_excerpt_temp == 'Yes'){echo "<p>". rpw_excerpt($search->ID,$rpw_related_posts_settings['rpw_excerpt_length']) . "</p>";}
        global $wpdb;
        $postID=$search->ID;
        $my_data = $wpdb->get_var( $wpdb->prepare(
            "
		SELECT (meta_value)
		FROM `wp_postmeta`
		WHERE post_id = '$postID'
        AND meta_key = '_kksr_casts'
	",1,1
        ));
        echo "<div class='help'><span></span>".$my_data."</div>";
        global $wpdb;
        $postID=$search->ID;
        $my_data_2 = $wpdb->get_var( $wpdb->prepare(
            "
		SELECT (meta_value)
		FROM `wp_postmeta`
		WHERE post_id = '$postID'
        AND meta_key = '_kksr_avg'
	",1,1
        ));
        echo "<div class='rescue'><span></span>".((number_format((float)($my_data_2/5)*100)))."%"."</div>";
        echo "</div>";

    }
    echo "</li>";

	if ($search_counter == $limit) break;	// End loop when related posts limit is reached
		} //end of foreach loop
	echo '</ul></div>';
	}//end of searches if statement
	else{
		echo '<p>No related posts!</p>';
	}
?><?php
$out = ob_get_clean();
return $out;
?>