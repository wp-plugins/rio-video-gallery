<?php
/**
 * Plugin Name: Rio Video Gallery
 * Plugin URI: http://riosis.com/themes/rio-video-gallery/
 * Description: A powerful Video Gallery plugin that allows you to embed videos from YouTube, Vimeo and Daily motion through categories.
 * Version: 1.0
 * Author: Riosis Web Team
 * Author URI: http://web.riosis.com
 */
 ?>
<?php
/*
|--------------------------------------------------------------------------
| FILTERS
|--------------------------------------------------------------------------
*/

//include video gallery single template from plugin direcotory
add_filter( "single_template", "get_video_gallery_type_template" ) ;
add_filter( 'archive_template', 'get_rio_gallery_archive_template' ) ;

/*
|--------------------------------------------------------------------------
| Custom post type  'video-gallery'
|--------------------------------------------------------------------------
*/
function codex_custom_video_gallery() {
  $labels = array(
    'name' => 'Video Gallery',
    'singular_name' => 'Video Gallery',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New',
    'edit_item' => 'Edit Video',
    'new_item' => 'New Video',
    'all_items' => 'All Videos',
    'view_item' => 'View Video',
    'search_items' => 'Search Video',
    'not_found' =>  'No Videos found',
    'not_found_in_trash' => 'No Videos found in Trash', 
    'parent_item_colon' => '',
    'menu_name' => 'Video Gallery'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => 'video-gallery' ),
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null,
	'menu_icon' => plugins_url().'/rio-video-gallery/img/video_sb_icon.png',
    'supports' => array( 'title', 'editor', 'thumbnail','comments')
  ); 

  register_post_type( 'video-gallery', $args );
  
  register_taxonomy("video-categories", array("video-gallery"), array("hierarchical" => true,'show_admin_column'   => true, "label" => "Video Categories", "singular_label" => "Video Categories", "rewrite" => array( 'slug' => 'videos' )));
}
add_action( 'init', 'codex_custom_video_gallery' );


//for adding metabox to video post...
add_action( 'admin_init', 'fun_add_video_metaBox' );

function fun_add_video_metaBox() {

    add_meta_box( 'video_gallery_metabox',
        'Video Gallery Options',
        'fun_video_gallery_metabox_display',
        'video-gallery', 'normal', 'high'
    );

}

function fun_video_gallery_metabox_display( $video_gallery ) 
{
	$post_order_res = get_post_meta($video_gallery->ID,'video_post_order', true );
	$provider_res= get_post_meta($video_gallery->ID,'video_provider',true);
	$video_id_res = esc_html( get_post_meta( $video_gallery->ID, 'video_id', true ) );
	$_custom_video_size_width = esc_html( get_post_meta( $video_gallery->ID, '_custom_video_size_width', true ) );
	if(empty($_custom_video_size_width))
	{
		$_custom_video_size_width=600;
	}
	$_custom_video_size_height = esc_html( get_post_meta( $video_gallery->ID, '_custom_video_size_height', true ) );
	if(empty($_custom_video_size_height))
	{
		$_custom_video_size_height=400;
	}
	$video_post_id = $video_gallery->ID;
?>

<div class="inside">
  <table width="100%">
    <tr>
      <td width="178" align="right"><b>Post Order</b></td>
      <td width="4">:</td>
      <td><input size="10" value="<?php if(!empty($post_order_res)) { echo $post_order_res;}else { echo 0; }?>" name="video_post_order" type="text" class="widther" />
        &nbsp;&nbsp;Post order for your video gallery </td>
      <td rowspan="4" width="230px" align="right"><?php if(!empty($provider_res) && $provider_res == 'youtube') {?>
        <iframe width="200" height="120" src="//www.youtube.com/embed/<?php echo $video_id_res;?>?controls=0&showinfo=0" frameborder="0" allowfullscreen></iframe>
        <?php } else if(!empty($provider_res) && $provider_res == 'vimeo') {?>
        <iframe src="//player.vimeo.com/video/<?php echo $video_id_res;?>" width="200" height="120" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        <?php } else if(!empty($provider_res) && $provider_res == 'dailymotion') {?>
        <iframe frameborder="0" width="200" height="120" src="http://www.dailymotion.com/embed/video/<?php echo $video_id_res;?>"></iframe>
        <?php }?></td>
    </tr>
    <tr>
      <td align="right"><b>Select your video provider</b></td>
      <td>:</td>
      <td><select name="video_provider" id="video_provider">
          <option <?php if(!empty($provider_res) && $provider_res == '') { echo 'selected="selected"'; }?> value="">-Select-</option>
          <option <?php if(!empty($provider_res) && $provider_res == 'youtube') { echo 'selected="selected"'; }?> value="youtube">YouTube</option>
          <option <?php if(!empty($provider_res) && $provider_res == 'vimeo') { echo 'selected="selected"'; }?> value="vimeo">Vimeo</option>
          <option <?php if(!empty($provider_res) && $provider_res == 'dailymotion') { echo 'selected="selected"'; }?> value="dailymotion">Dailymotion</option>
        </select>
        &nbsp;&nbsp; Your video provider.</td>
    </tr>
    <tr>
      <td align="right"><b>Video ID</b>&nbsp; <a href="javascript:void(0);" title="Enter video id from video provider" class="tooltip" > <img src="<?php echo plugins_url();?>/rio-video-gallery/img/help.png" /> </a></td>
      <td>:</td>
      <td><input size="40" value="<?php if(!empty($video_id_res)) { echo $video_id_res;}?>" name="video_id" type="text" class="widther" /></td>
    </tr>
     <tr>
      <td align="right"><b>Custom Video Player size</b></td>
      <td>:</td>
      <td colspan="2">
      <input size="10" name="_custom_video_size_width" type="text" value='<?php echo $_custom_video_size_width;?>'> x <input size="10" name="_custom_video_size_height" type="text" value='<?php echo $_custom_video_size_height;?>'>&nbsp;&nbsp;<em>Size of the video player in pixels</em>&nbsp;&nbsp;&nbsp;</td>
    </tr>
    <tr>
      <td align="right"><b>Short code</b></td>
      <td>:</td>
      <td colspan="2"><input size="30" type="text" onfocus="this.select();" readonly value='[videopost id="<?php echo $video_post_id;?>"]'></td>
    </tr>
  </table>
</div>
<?php } 
//for inserting values into postmeta table..
add_action( 'save_post','save_video_meta_values', 10, 2 ); 
function save_video_meta_values( $video_postId,$video_gallery ) 
{
	if ( $video_gallery->post_type == 'video-gallery' ) 
	{
		if($_POST)
		{
			if(empty($_POST['video_post_order']))
			{
				$postOrder = 0;
			}
			else
			{
				$postOrder = $_POST['video_post_order'];
			}
			update_post_meta( $video_postId, 'video_post_order',$postOrder);
			update_post_meta( $video_postId, 'video_provider',$_POST['video_provider'] );
			update_post_meta( $video_postId, 'video_id',$_POST['video_id'] );
			update_post_meta( $video_postId, '_custom_video_size_width',$_POST['_custom_video_size_width'] );
			update_post_meta( $video_postId, '_custom_video_size_height',$_POST['_custom_video_size_height'] );
		}
	}
}

function reg_video_set_submenu(){
//for registering submenu settings under video gallery post type..
add_submenu_page(
    'edit.php?post_type=video-gallery',
    'Settings', /*page title*/
    'Settings', /*menu title*/
    'manage_options', /*roles and capabiliyt needed*/
    'video_gallery_settings',
    'video_gallery_settings_fun' /*replace with your own function*/
);
}

add_action( 'admin_menu', 'reg_video_set_submenu' );

function video_gallery_settings_fun(){
	
	if($_POST)
	{
		/*..Video post starts from here...*/
		if(!empty($_POST['hid_video_post']))
		{
			$vposted_date_display = $_POST['vposted_date_display'];
			$vpost_order = $_POST['vpost_order'];
			$vrelated_posts = $_POST['vrelated_posts'];
			$vpost_orderby = $_POST['vpost_orderby'];
			$video_count = stripslashes($_POST['video_count']);
			
			$video_thumb_height = stripslashes($_POST['video_thumb_height']);
			$video_thumb_width = stripslashes($_POST['video_thumb_width']);
			$video_sthumb_height = stripslashes($_POST['video_sthumb_height']);
			$video_sthumb_width = stripslashes($_POST['video_sthumb_width']);
			
			$video_layout = $_POST['video_layout'];
			
			$data = array('vposted_date_display' => $vposted_date_display,'vpost_order' =>$vpost_order,'vrelated_posts' => $vrelated_posts,'vpost_orderby' => $vpost_orderby,'video_layout' => $video_layout,'video_thumb_width' => $video_thumb_width,'video_thumb_height' => $video_thumb_height,'video_count' => $video_count,'video_sthumb_width' => $video_sthumb_width,'video_sthumb_height' => $video_sthumb_height);
			//update video gallery option 
			$updated_vgallery = update_option('video_gallery_settings',$data);
		}
		/*..Video post ends here...*/
	}
	
	$data_results = get_option('video_gallery_settings'); //get video gallery all settings
	$vposted_date_display_res = $data_results['vposted_date_display'];

	$vpost_order_res = $data_results['vpost_order'];
	$vpost_orderby_res = $data_results['vpost_orderby'];
	$video_layout_res = $data_results['video_layout'];
	$video_thumb_width_res = $data_results['video_thumb_width'];
	$video_thumb_height_res = $data_results['video_thumb_height'];
	
	$video_sthumb_width_res = $data_results['video_sthumb_width'];
	if(empty($video_sthumb_width_res))
	{
		$video_sthumb_width_res=600;
	}
	$video_sthumb_height_res = $data_results['video_sthumb_height'];
	if(empty($video_sthumb_height_res))
	{
		$video_sthumb_height_res=400;
	}
	
	$video_count_res = $data_results['video_count'];
	
	
?>
<div class="wrap">
  <div id="icon-video-gallery" class="icon32"><br>
  </div>
  <h2>Video Settings</h2>
  <br/>
  <?php if(!empty($updated_vgallery)) {?>
  <div class="updated below-h2" id="message">
    <p>Options updated.</p>
  </div>
  <?php }?>
  <!-- Basic Settings post box starts here..-->
  <div class="postbox">
  <div class="inside">
  <table>
    <tr>
      <th align="left">Basic Settings</th>
    </tr>
  </table>
  <form action="" method="post" name="frm_vid_gallery_settings">
    <table>
      <tr>
        <td align="right" width="174px">Number of videos</td>
        <td>:</td>
        <td><input size="8px" type="text" name="video_count" id="video_count" value="<?php if(!empty($video_count_res)) { echo $video_count_res;} else { echo '5';}?>"></td>
        <td class="desc">&nbsp;&nbsp;Number of videos display (Default display is 5).</td>
      </tr>
    </table>
    <table>
      <tr>
        <td align="right">Show total views &amp; posted date </td>
        <td>:</td>
        <td><input type="checkbox" value="1" id="vposted_date_display" name="vposted_date_display" <?php if(!empty($vposted_date_display_res)) { echo 'checked="checked"';}?>></td>
        <td class="desc">&nbsp;&nbsp;If enabled, total views and published date will display.</td>
      </tr>
      <tr>
        <td align="right">Display 'Related posts' in single page</td>
        <td>:</td>
        <td><input type="checkbox" value="1" id="vrelated_posts" name="vrelated_posts" <?php if(!empty($vrelated_posts)) { echo 'checked="checked"';}?>></td>
        <td class="desc">&nbsp;&nbsp;If enabled, Will show related posts in single page.</td>
      </tr>
      <tr>
        <td align="right">Enable post order</td>
        <td>:</td>
        <td><input type="checkbox" value="1" id="vpost_order" name="vpost_order" <?php if(!empty($vpost_order_res)) { echo 'checked="checked"';}?>></td>
        <td class="desc">&nbsp;&nbsp;If enabled, videos will display according to the post order entered.</td>
      </tr>
    </table>
    <table id="tbl_vpostorder_settings">
      <tr>
        <td width="190px">&nbsp;</td>
        <td><input type="radio" <?php if(!empty($vpost_orderby_res) && $vpost_orderby_res == 'asc') { echo 'checked="checked"';} elseif(empty($vpost_orderby_res)) {echo 'checked="checked"';}?> value="asc" name="vpost_orderby" >
          &nbsp;Ascending</td>
        <td><input type="radio" <?php if(!empty($vpost_orderby_res) && $vpost_orderby_res == 'desc') { echo 'checked="checked"';}?> value="desc" name="vpost_orderby">
          &nbsp;Descending</td>
        <td class="desc">&nbsp;Videos will displays ascending or descending order.</td>
      </tr>
    </table>
    </div>
    </div>
    <!-- Basic Settings post box ends here..--> 
    <!-- Layout post box starts here..-->
    <div class="postbox">
      <div class="inside">
        <table>
          <tr>
            <th align="left">Gallery Layout</th>
          </tr>
          <tr>
            <td align="right" width="152px">Choose Layout</td>
            <td>:</td>
            <td><input type="radio" <?php if(!empty($video_layout_res) && $video_layout_res == '1') { echo 'checked="checked"';} elseif(empty($video_layout_res)) { echo 'checked="checked"';}?> value="1" name="video_layout">
              <img src="<?php echo plugins_url();?>/rio-video-gallery/img/video_layout_1.png" alt="layout_1" style="vertical-align:middle;"></td>
            <td width="50px"></td>
            <td><input type="radio" <?php if(!empty($video_layout_res) && $video_layout_res == '2') { echo 'checked="checked"';}?> value="2" name="video_layout">
              <img src="<?php echo plugins_url();?>/rio-video-gallery/img/video_layout_2.png" alt="layout_2" style="vertical-align:middle;"></td>
            <td class="desc">&nbsp;Choose layout for your video gallery.</td>
          </tr>
        </table>
        <table>
          <tr>
            <td align="right">Video image thumbnail size</td>
            <td>:</td>
            <td>Width : &nbsp;
              <input size="8px" type="text" name="video_thumb_width" id="video_thumb_width" value="<?php if(!empty($video_thumb_width_res)) { echo $video_thumb_width_res;}else{ echo 230;}?>"></td>
            <td>Height : &nbsp;
              <input size="8px" type="text" id="video_thumb_height" name="video_thumb_height" value="<?php if(!empty($video_thumb_height_res)) { echo $video_thumb_height_res;}else{ echo 160;}?>"></td>
            <td class="desc">&nbsp;The image width and height in pixels.</td>
          </tr>
          <tr>
            <td align="right">Video player size</td>
            <td>:</td>
            <td>Width : &nbsp;
              <input size="8px" type="text" name="video_sthumb_width" id="video_sthumb_width" value="<?php if(!empty($video_sthumb_width_res)) { echo $video_sthumb_width_res;}?>"></td>
            <td>Height : &nbsp;
              <input size="8px" type="text" id="video_sthumb_height" name="video_sthumb_height" value="<?php if(!empty($video_sthumb_height_res)) { echo $video_sthumb_height_res;}?>"></td>
            <td class="desc">&nbsp;Player width and height in pixels, displays in single page.</td>
          </tr>
        </table>
      </div>
    </div>
    <!-- Layout post box ends here..--> 
    <!-- Short code post box starts here..-->
    <div class="postbox">
      <div class="inside">
        <?php 
	 $args = array(
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'hide_empty'               => 1,
	'taxonomy'                 => 'video-categories',
	'pad_counts'               => true );
	 //returns all category under custom taxonomy 'video-categories'..
	 $videocategories =  get_categories($args);
	 ?>
        <table>
          <tr>
            <th align="left">Short codes</th>
          </tr>
          <tr>
            <td align="right">Select video category : &nbsp;
              <select name="sel_shortcode" id="sel_shortcode" >
                <option value="all">All</option>
                <?php foreach ( $videocategories as $vcategory ) {
				$category_name = $vcategory->name;
				$category_parent = $vcategory->parent; 
				$category_slug = $vcategory->slug; 
			?>
                <?php if(empty($category_parent)) {?>
                <option value="<?php echo $category_slug;?>"><?php echo $category_name;?></option>
                <?php } // if category_parent checking close.?>
                <?php } //foreach close..?>
              </select></td>
            <td><input size="50" type="text" onfocus="this.select();" id="shortcodeDisplay" readonly value='[videogallery view="all"]'></td>
            <td class="desc">&nbsp;&nbsp;Use this shortcode in your page.</td>
          </tr>
        </table>
      </div>
    </div>
    <!-- Short code post box ends here..-->
    <table>
      <tr>
        <td class="spaciuosCells"><input type="hidden" name="hid_video_post" value="true">
          <input name="video_submit"  type="submit" value="Update options" class="widther button-primary" /></td>
      </tr>
    </table>
  </form>
</div>
<?php 
//for enable scripts.
wp_register_script('video-custom-script', plugins_url(). '/rio-video-gallery/js/video-gallery-script.js', array('jquery', 'jquery-ui-core', 'jquery-ui-tabs'),'1.0.0', true);
wp_enqueue_script('video-custom-script');

 //for enabling style.
 $css_path = plugins_url().'/rio-video-gallery/css/video-gallery-style.css';
	 wp_register_style( 'video-custom-style', $css_path);
	 wp_enqueue_style( 'video-custom-style' );
?>
<?php } ?>
<?php
/*
|--------------------------------------------------------------------------
| short code function
|--------------------------------------------------------------------------
*/
function fun_video_gallery_shortcode($atts) 
{
   extract(shortcode_atts(array('view' => ''), $atts));
   $view_status = $view; // returns short code attribute id..
   
	$data_results = get_option('video_gallery_settings');//video gallery settings option
	$vposted_date_display_gshort = $data_results['vposted_date_display'];

	$vpost_order_gshort = $data_results['vpost_order'];
	$vpost_orderby_gshort = $data_results['vpost_orderby'];
	$video_layout_gshort = $data_results['video_layout'];
	$video_thumb_width_gshort = $data_results['video_thumb_width'];
	if(empty($video_thumb_width_gshort))
	{
		$video_thumb_width_gshort = 230;
	}
	$video_thumb_height_gshort = $data_results['video_thumb_height'];
	if(empty($video_thumb_height_gshort))
	{
		$video_thumb_height_gshort = 160;
	}
	$video_count_gshort = $data_results['video_count'];
	if(empty($video_count_gshort))
	{
		$video_count_gshort = 5;
	}
?>
<div class="rio-video-container">
  <?php
  	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	if(!empty($view_status) && $view_status == 'all') // all categories under video gallery post type.. 
	{
		
		if(!empty($vpost_order_gshort)) //with post order
			{
				$exclude_terms = array(
				'post_type' => 'video-gallery',
				'posts_per_page' => $video_count_gshort,
				'meta_key' => 'video_post_order',
				'orderby' => 'meta_value_num',
				'paged' => $paged,
				'order' => $vpost_orderby_gshort
				);
			}
			else //without post order
			{
				
				$exclude_terms = array(
				'post_type' => 'video-gallery',
				'paged' => $paged,
				'posts_per_page' => $video_count_gshort
				);
			}
		
	}
	else //filtered category only under video-categories..
	{
			
			if(!empty($vpost_order_gshort)) //with post order
			{
				$exclude_terms = array(
				'post_type' => 'video-gallery',
				'posts_per_page' => $video_count_gshort,
				'paged' => $paged,
				'meta_key' => 'video_post_order',
				'orderby' => 'meta_value_num',
				'order' => $vpost_orderby_gshort,
				'tax_query' => array(array(
				'taxonomy' => 'video-categories',
				'field' => 'slug',
				'terms' => array($view_status)))
				);
			}
			else //without post order
			{
				
				$exclude_terms = array(
				'post_type' => 'video-gallery',
				'posts_per_page' => $video_count_gshort,
				'paged' => $paged,
				'tax_query' => array(array(
				'taxonomy' => 'video-categories',
				'field' => 'slug',
				'terms' => array($view_status)))
				);
			}
	}
	$the_query = new WP_Query( $exclude_terms );
	if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
    //if(have_posts()):while(have_posts()):the_post();
	$postid = get_the_ID();
	$provider_shortres= get_post_meta($postid,'video_provider',true); // returns video provider from post..
	$video_id_shortres = get_post_meta($postid, 'video_id', true ); //returns curresponding video id..
    ?>
  <?php if(!empty($video_id_shortres)) {?>
  <?php if(empty($video_layout_gshort) || $video_layout_gshort == 1) {?>
  <article itemscope itemtype="http://schema.org/VideoObject">
    <figure> <a href="<?php the_permalink();?>">&nbsp;</a>
      <?php if(!empty($provider_shortres) && $provider_shortres == 'youtube') {?>
      <img src="http://img.youtube.com/vi/<?php echo $video_id_shortres;?>/0.jpg" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width_gshort; ?>" height="<?php echo $video_thumb_height_gshort; ?>" itemprop="thumbnail">
      <?php } else if(!empty($provider_shortres) && $provider_shortres == 'vimeo') {?>
      <img src="<?php echo getVimeoThumb($video_id_shortres);?>" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width_gshort; ?>" height="<?php echo $video_thumb_height_gshort; ?>" itemprop="thumbnail">
      <?php } else if(!empty($provider_shortres) && $provider_shortres == 'dailymotion') {?>
      <img src="http://www.dailymotion.com/thumbnail/video/<?php echo $video_id_shortres;?>" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width_gshort; ?>" height="<?php echo $video_thumb_height_gshort; ?>" itemprop="thumbnail">
      <?php }?>
    </figure>
  </article>
  <?php } //layout condition 1 close here... ?>
  <?php if(!empty($video_layout_gshort) && $video_layout_gshort == 2) {?>
  <article itemscope itemtype="http://schema.org/VideoObject">
    <figure> <a href="<?php the_permalink();?>">&nbsp;</a>
      <?php if(!empty($provider_shortres) && $provider_shortres == 'youtube') {?>
      <img src="http://img.youtube.com/vi/<?php echo $video_id_shortres;?>/0.jpg" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width_gshort; ?>" height="<?php echo $video_thumb_height_gshort; ?>" itemprop="thumbnail">
      <?php } else if(!empty($provider_shortres) && $provider_shortres == 'vimeo') {?>
      <img src="<?php echo getVimeoThumb($video_id_shortres);?>" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width_gshort; ?>" height="<?php echo $video_thumb_height_gshort; ?>" itemprop="thumbnail">
      <?php } else if(!empty($provider_shortres) && $provider_shortres == 'dailymotion') {?>
      <img src="http://www.dailymotion.com/thumbnail/video/<?php echo $video_id_shortres;?>" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width_gshort; ?>" height="<?php echo $video_thumb_height_gshort; ?>" itemprop="thumbnail">
      <?php }?>
    </figure>
    <header>
      <h1 itemprop="name"> <a href="<?php the_permalink();?>">
        <?php $title = get_the_title(); echo substr($title,0,27);?>
        </a> </h1>
      <?php if(!empty($vposted_date_display_gshort)) {?>
      <p><span itemprop="datePublished">Posted <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';?></span><span itemprop="playCount"><?php echo getPostViews($postid);?> Views</span></p>
      <?php } ?>
    </header>
  </article>
  <?php } //layout condition 2 close here... ?>
  <?php } //checking $video_id_shortres exist close..?>
  <?php endwhile; else : echo 'No Videos found'; endif;wp_reset_postdata(); //wp_reset_query(); ?>
  <div class="clearFixer">&nbsp;</div>
  <p class="pagination">
    <?php
	if(function_exists('wp_pagenavi')){ wp_pagenavi(); }
	else
	{
$big = 999999999; // need an unlikely integer
echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'total' => $the_query->max_num_pages
) ); 
	}
?>
  </p>
</div>
<?php
} 
//shortcode function close here..
/*

|--------------------------------------------------------------------------
| create short code
|--------------------------------------------------------------------------
*/
add_shortcode('videogallery', 'fun_video_gallery_shortcode');
//videogallery shortcode ends here...
?>
<?php 
//for enabling session in wordpress..
function kana_init_session()
{
  session_start();
}
add_action('init', 'kana_init_session', 1);
?>
<?php
//function for getting vimeo video imagethumb url..
function getVimeoThumb($id) {
    $data = file_get_contents("http://vimeo.com/api/v2/video/$id.json");
    $data = json_decode($data);
    return $data[0]->thumbnail_medium;
}
?>
<?php
// function to display number of posts.
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if(empty($count)){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}

// function to count views.
function setPostViews() {
	if ( is_single() ) 
	{
		global $post;
		$postID = $post->ID;		
		$count_key = 'post_views_count';
		
		//for checking there is any viewed ids exist in viewed session array..
		if(!empty($_SESSION['viewed_IDs']))
		{
		   $viewed_IDs = $_SESSION['viewed_IDs']; // for getting all viewed_IDs by current user..
		}
		else
		{
			$viewed_IDs = ''; // viewed ids set as empty....
		}
		
		$count = get_post_meta($postID, $count_key, true);
		if($count=='')
		{
			$count = 0;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
		}
		else
		{
			if (@in_array($postID, $viewed_IDs)) //check this post id exist in viewed_IDs array..
			{
				//'Do nothing...'
			}
			else
			{
				$count++;
				update_post_meta($postID, $count_key, $count);
			}
		}
		
		if(!empty($viewed_IDs)) //if session is empty..
		{
			$videoPostIds = $viewed_IDs; // current ids...
			array_push($videoPostIds,$postID); // add new postID to the array..
		}
		else
		{
			$videoPostIds = array(); // create an array...
			array_push($videoPostIds,$postID); // add new postID to this array..
		}
		$_SESSION['viewed_IDs'] = $videoPostIds;
	}
}
add_action('wp_head','setPostViews');
?>
<?php
//for generating shortcode for video post...
//for site title shortcode..
function fun_video_post_shortcode($atts) 
{
   extract(shortcode_atts(array('id' => ''), $atts));
   $videoPost_id = $id;	
	$data_results = get_option('video_gallery_settings');//video gallery settings option
			
	$vposted_date_display_pshort = $data_results['vposted_date_display'];

	/*$video_sthumb_width_pres = $data_results['video_sthumb_width'];
	$video_sthumb_height_pres = $data_results['video_sthumb_height'];*/
?>
<?php if(!empty($videoPost_id)) {?>
<?php 
//return post with given id from shortcode..
$getpost = get_post($videoPost_id); 
$posttitle = $getpost->post_title; //returns post title..
$postcontent = $getpost->post_content; //returns post content..
$posttitle = $getpost->post_title; //returns post title..

$provider_pres= get_post_meta($videoPost_id,'video_provider',true); // returns video provider from post..
$video_id_pres = get_post_meta($videoPost_id, 'video_id', true ); //returns corresponding video id..
$video_sthumb_width_pres=get_post_meta($videoPost_id, '_custom_video_size_width', true ); //returns video custom width value
if(empty($video_sthumb_width_pres))
{
	$video_sthumb_width_pres=$data_results['video_sthumb_width'];
}
$video_sthumb_height_pres=get_post_meta($videoPost_id, '_custom_video_size_height', true ); //returns video custom height value
if(empty($video_sthumb_height_pres))
{
	$video_sthumb_height_pres=$data_results['video_sthumb_height'];
}
?>
<?php if(!empty($video_id_pres)) {?>
<figure>
  <?php if(!empty($provider_pres) && $provider_pres == 'youtube') {?>
  <iframe width="<?php echo $video_sthumb_width_pres; ?>" height="<?php echo $video_sthumb_height_pres; ?>" src="//www.youtube.com/embed/<?php echo $video_id_pres;?>" frameborder="0" allowfullscreen> </iframe>
  <?php } else if(!empty($provider_pres) && $provider_pres == 'vimeo') {?>
  <iframe src="//player.vimeo.com/video/<?php echo $video_id_pres;?>" width="<?php echo $video_sthumb_width_pres; ?>" height="<?php echo $video_sthumb_height_pres; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
  <?php } else if(!empty($provider_pres) && $provider_pres == 'dailymotion') {?>
  <iframe frameborder="0" width="<?php echo $video_sthumb_width_pres; ?>" height="<?php echo $video_sthumb_height_pres; ?>" src="http://www.dailymotion.com/embed/video/<?php echo $video_id_pres;?>"></iframe>
  <?php }?>
</figure>
<?php }?>
<article>
  <header>
  <?php if(!empty($posttitle)) {?>
  <h3><?php echo $posttitle;?></h3>
  <?php }?>
  <?php if(!empty($vposted_date_display_pshort)) {?>
  <p><span>Views <?php echo getPostViews($videoPost_id);?></span><span><?php echo human_time_diff( get_the_time('U',$videoPost_id), current_time('timestamp') ) . ' ago';?></span></p>
  <?php } ?>
  <header>
  <?php echo wpautop($postcontent);?> </article>
<?php } //checking videoPost_id exist or not close.. ?>
<?php
}
add_shortcode('videopost', 'fun_video_post_shortcode');
?>
<?php 
add_action( 'admin_head', 'wpt_video_icons' );
function wpt_video_icons() {
    ?>
<style type="text/css" media="screen">
    #icon-edit.icon32-posts-video-gallery {background: url(<?php echo plugins_url();?>/rio-video-gallery/img/video_page_icon.png) no-repeat;}
    </style>
<?php } ?>
<?php 
/*
|--------------------------------------------------------------------------
| Widget section 
|--------------------------------------------------------------------------
*/
class video_gallery extends WP_Widget
{
  function video_gallery()
  {
    $widget_ops = array('classname' => 'video_gallery', 'description' => 'Use this widget to display video gallery corresponding to your shortcode' );
    $this->WP_Widget('video_gallery', 'Video Gallery', $widget_ops);
  }
 
  function form($instance) // Dashboard area..
  {
    // Check values
	if($instance) {
	$short_code = $instance['video_short_code'];
	} else {
	$short_code = '';
	}
?>
<p>
  <label for="<?php echo $this->get_field_id('video_short_code'); ?>">Short code :</label>
  <input class="widefat" id="<?php echo $this->get_field_id('video_short_code'); ?>" name="<?php echo $this->get_field_name('video_short_code'); ?>" type="text" value='<?php echo $short_code; ?>' />
</p>
<?php
  }
 
  function update($new_instance, $old_instance) //update function..
  {
	$instance = $old_instance;
	// Fields
	$instance['video_short_code'] = $new_instance['video_short_code'];
    return $instance;
  }
 
  function widget($args, $instance) //output funtion...
  {
    extract($args, EXTR_SKIP);
    $short_code = $instance['video_short_code'];
    // WIDGET CODE GOES HERE 
	echo do_shortcode($short_code);
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("video_gallery");') );//widget?>
<?php
/*
|--------------------------------------------------------------------------
| Include custom template from plugin directory
|--------------------------------------------------------------------------
*/
function get_video_gallery_type_template($single_template) {
 global $post;

 if ($post->post_type == 'video-gallery') {
      $single_template = dirname( __FILE__ ) . '/includes/template/single-video-gallery.php';
 }
 return $single_template;
}
//archive
function get_rio_gallery_archive_template($single_template) {
global $post;
if ($post->post_type == 'video-gallery') {
     $single_template = dirname( __FILE__ ) . '/includes/template/archive-video-gallery.php';
}
return $single_template;
}
/*
|--------------------------------------------------------------------------
| Hook css to wp_head
|--------------------------------------------------------------------------
*/
add_action('wp_enqueue_scripts', 'rio_video_style_hook');
function rio_video_style_hook() {
 //for enabling style.
 $css_path = plugins_url().'/rio-video-gallery/css/video-gallery-style.css';
	 wp_register_style( 'video-custom-style', $css_path);
	 wp_enqueue_style( 'video-custom-style' );
}
/*
|--------------------------------------------------------------------------
| short content function
|--------------------------------------------------------------------------
*/
function video_short_content($num) {
$limit = $num+1;
	$content = str_split(get_the_content());
	$length = count($content);
	if ($length>=$num)
	{
		$content = array_slice( $content, 0, $num);
		$content = implode("",$content)."...";
		echo $content;
	}
	else
	{
		the_content();
	} 
} 

/*
|--------------------------------------------------------------------------
| Help Section
|--------------------------------------------------------------------------
*/
function video_gallery_help($contextual_help, $screen_id, $screen) {
$CurrentPostType = $screen->post_type; //returns post type..
if ($CurrentPostType == 'video-gallery') {



// Add my_help_tab if current screen is video-gallery

$screen->add_help_tab( array(

'id' => 'about_video_gallery',

'title' => __('Overview'),

'content' => video_overview()

) );

// Add category

$screen->add_help_tab(array(

'id' => 'video_gallery_category',

'title' => 'Add a Video Category',

'content' => video_add_category()

));

// Add POST

$screen->add_help_tab(array(

'id' => 'video_gallery_post',

'title' => 'Add a Video Post',

'content' => video_add_post()

));


// Settings

$screen->add_help_tab(array(

'id' => 'settings_video',

'title' => 'Settings',

'content' => video_settings()

));

// Youtube Video ID

$screen->add_help_tab(array(

'id' => 'getting_youtube_id',

'title' => 'Get Youtube Id',

'content' => get_youtube_id()

));
// Vimeo Video ID

$screen->add_help_tab(array(

'id' => 'getting_vimeo_id',

'title' => 'Get Vimeo Id',

'content' => get_vimeo_id()

));
// Vimeo Video ID

$screen->add_help_tab(array(

'id' => 'getting_dialymotion_id',

'title' => 'Get Dailymotion Id',

'content' => get_dialymotion_id()

));



}
}
add_filter('contextual_help', 'video_gallery_help', 10, 3);
?>
<?php function video_overview(){
$output = '<h3>Video Gallery</h3>
<p>This is the Video gallery section, here you can manage your video posts and do basic actions such as Add, Edit, View, Trash and setting up your videos.</p>
<h3>Managing Video Gallery</h3>
<ul>
<li>
<strong>Creating a new video post:</strong>
Click on the "Add New" button on the top of the page to create a new video post.
<p>Here, you need to add the following information.</p>
<ul>
<li><strong>Post Title:</strong> This is the title for your video post.</li>
<li><strong>Post Content:</strong> This is the content for your video post.</li>
<li><strong>Video Gallery Options:</strong> This section has the following field.
<ol>
<li><strong>Post Order:</strong>Post order specifies the ordering for this post. The post will show in ascending order corresponding to the numerical value of this field.</li>
<li><strong>Select your video provider:</strong> You can choose anyone from the three providers, such as YouTube, Vimeo, Dailymotion.</li>
<li><strong>Video ID:</strong> The video ID corresponding to the provider.</li>
<li><strong>Short code:</strong> To get this post in a specific page or post, you need to edit a page or post and insert its shortcode into the WordPress text editor.</li>
</ol>
</li>
</ul>
</li>
<li>
<strong>Modifying a video post:</strong>
Click on the name of the video post or the "Edit" button to jump to the edit page.
</li>
<li>
<strong>Adding Video Categories:</strong>
If you want to post a video under a specific category, you need to add a video category through "Video Categories" option.
</li>
<li>
<strong>Settings of Video Gallery:</strong>
The complete video gallery settings are under "Settings" options. Here you can manage the following options.
<ul>
<li><strong>Basic Settings:</strong> Here you can manage Main Title of the video gallery and can set up some listing options such as Number of videos display, Enable view all button, Show total views & posted date and Enable post order options. </li>
<li><strong>Gallery Layout: </strong>
You can choose video layout such as thumbnail only or by title.</li>
<li><strong>Short codes:</strong> You can place your video into pages and posts with their shortcodes. You can find the shortcode for each video category or all video. To insert the video shortcode, edit a page or post and insert its shortcode into the WordPress text editor.
<p>Please note that you have to update your video options before leaving from the settings page.</p>
</li>
</ul>
</li>
</ul>';
return $output;
}?>
<?php function video_add_category(){
$output = '<h3>Add a Video Category</h3>
<p>
<ul>
<li> For adding video categories: Click on “Video Categories” link.</li>
</ul>
<p><img src="'.plugins_url().'/rio-video-gallery/help/images/Rio-video-add-category.png" alt="" /><p>
</p>';
return $output;
}?>
<?php function video_add_post(){
$output = '<h3>Add a Video Post</h3>
<p>
<ul>
<li>For adding video post: Click on “Add new” link.</li>
</ul>
<p><img src="'.plugins_url().'/rio-video-gallery/help/images/Rio-video-add.png" alt="" /><p>
</p>
<p>
<ul>
<li> Enter the video title, contentPost Order, Video provider, Video ID and Short code.</li>
</ul>
<p><img src="'.plugins_url().'/rio-video-gallery/help/images/Rio-video-add-new.png" alt="" /></p>
</p>';
return $output;
}?>
<?php function video_settings(){
$output = '<h3>Video Gallery Settings</h3>
<p>
<ul>
<li> Click on “Settings” link.</li>
</ul>
<p>Here you can manage the following options.<p>
</p>
<p>
<ul>
<li><strong>Basic Settings:</strong> Here you can set up some listing options such as number of videos display, enable view all button, show total views &amp; posted date, enable/disable related videos on single page and enable post order for ordering posts. </li>
<li><strong>Gallery Layout: </strong>
You can choose video layout such as thumbnail only or by title.</li>
<li><strong>Short codes:</strong> You can place your video into pages and posts with their shortcodes. You can find the shortcode for each video category or all video. To insert the video shortcode, edit a page or post and insert its shortcode into the WordPress text editor.
<p>Please note that you have to update your video options before leaving from the settings page. These settings options only seems in shortcode used pages and posts.</p>
</li>
</ul>
<p><img src="'.plugins_url().'/rio-video-gallery/help/images/Rio-video-gallery-settings-page.png" alt="" /></p>

</p>
';
return $output;
}?>
<?php function get_youtube_id(){
$output = '<h3>Getting Youtube Video Id</h3>
<p><img src="'.plugins_url().'/rio-video-gallery/help/images/get-youtube-video-id.png" alt="" /><p>
';
return $output;
}?>
<?php function get_vimeo_id(){
$output = '<h3>Getting Vimeo Video Id</h3>
<p><img src="'.plugins_url().'/rio-video-gallery/help/images/get-vimeo-id-1.png" alt="" /><p>
<p><img src="'.plugins_url().'/rio-video-gallery/help/images/get-vimeo-id-2.png" alt="" /><p>
';
return $output;
}?>
<?php function get_dialymotion_id(){
$output = '<h3>Getting Dialymotion Video Id</h3>
<p><img src="'.plugins_url().'/rio-video-gallery/help/images/get-dialymotion-id.png" alt="" /><p>
';
return $output;
}?>