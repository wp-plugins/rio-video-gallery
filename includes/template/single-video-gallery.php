<?php get_header(); the_post(); ?>
<?php
$current_post_id = $post->ID;
$current_cat = get_taxonomy('video-categories');
$obj = get_post_type_object('video-gallery');
$singular_name = $obj->labels->singular_name;
 //for getting the feartured image...
 $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
 $provider_shortres= get_post_meta($post->ID,'video_provider',true); // returns video provider from post..
 $video_id_shortres = get_post_meta($post->ID, 'video_id', true ); //returns curresponding video id..
 $post_ID =  $post->ID;
 $data_results = get_option('video_gallery_settings'); //get video gallery all settings
$video_sthumb_width_res = $data_results['video_sthumb_width'];//video player width 
if(empty($video_sthumb_width_res))
{
	$video_sthumb_width_res=600;
}
$video_sthumb_height_res = $data_results['video_sthumb_height'];//video player height
if(empty($video_sthumb_height_res))
{
	$video_sthumb_height_res=400;
}
$display_related_posts = $data_results['vrelated_posts'];

$video_thumb_width = $data_results['video_thumb_width'];//video thumb width for related posts
if(empty($video_thumb_width))
{
	$video_thumb_width = 230;
}
$video_thumb_height = $data_results['video_thumb_height'];//video thumb height for related posts
if(empty($video_thumb_height))
{
	$video_thumb_height = 160;
	}
?>

<div class="rio-video-single" itemscope itemtype="http://schema.org/VideoObject">
  <h1><?php echo $singular_name;?></h1>
  <article>
  <figure>
    <?php if(!empty($provider_shortres) && $provider_shortres == 'youtube') {?>
    <iframe width="<?php echo $video_sthumb_width_res;?>" height="<?php echo $video_sthumb_height_res;?>" src="//www.youtube.com/embed/<?php echo $video_id_shortres;?>" frameborder="0" allowfullscreen></iframe>
    <?php } else if(!empty($provider_shortres) && $provider_shortres == 'vimeo') {?>
    <iframe src="//player.vimeo.com/video/<?php echo $video_id_shortres;?>" width="<?php echo $video_sthumb_width_res;?>" height="<?php echo $video_sthumb_height_res;?>"></iframe>
    <?php } else if(!empty($provider_shortres) && $provider_shortres == 'dailymotion') {?>
    <iframe frameborder="0" width="<?php echo $video_sthumb_width_res;?>" height="<?php echo $video_sthumb_height_res;?>" src="http://www.dailymotion.com/embed/video/<?php echo $video_id_shortres;?>"></iframe>
    <?php }?>
    
  </figure>
  <header>
    <h1 itemprop="name">
      <?php the_title() ?>
    </h1>
    <p><span><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';?></span><span><?php echo getPostViews($post_ID);?> Views</span></p>
    </header>
    <?php the_content(); ?>
    <?php //for enabling comment form through theme options..
		   $comment_status = get_option('comment_status');	//returns 1 if checkbox is checkd from theme option CMS tab..
		      if(!empty($comment_status) && $comment_status == 1)
			  {
		   		 comments_template(); 
			  }
			
			?>
  </article>
  <?php if(!empty($display_related_posts) && $display_related_posts == 1)
		{?>
</div>
<div class="rio-video-gallery-related-posts">
  <h3>Related Videos</h3>
  <?php $terms_slug = wp_get_post_terms($current_post_id, 'video-categories',array("fields" => "slugs")); //current taxonomi slug from post id?>
<?php $current_video_category=$terms_slug[0]; //current portfolio cateogry?>
  <?php 
            $args = array('post_type' => 'video-gallery','video-categories' => $current_video_category,'posts_per_page' => 3,'post__not_in' => array($post_ID));
            // The Query
            $query = new WP_Query( $args );
            if($query->have_posts()):while($query->have_posts()):$query->the_post();
            $provider_res= get_post_meta($post->ID,'video_provider',true); // returns video provider from post..
 			$video_id_res = get_post_meta($post->ID, 'video_id', true ); //returns curresponding video id..
            ?>
  <article itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
    <figure> <a href="<?php the_permalink();?>">&nbsp;</a>
      <?php if(!empty($provider_res) && $provider_res == 'youtube') {?>
      <img width="<?php echo $video_thumb_width;?>" height="<?php echo $video_thumb_height;?>" src="http://img.youtube.com/vi/<?php echo $video_id_res; ?>/0.jpg" alt="" itemprop="thumbnail">
      <?php } else if(!empty($provider_res) && $provider_res == 'vimeo') {
			$imgid = $video_id_res;
			$hash = unserialize(@file_get_contents("http://vimeo.com/api/v2/video/$imgid.php"));
	  ?>
      <img width="<?php echo $video_thumb_width;?>" height="<?php echo $video_thumb_height;?>" src="<?php echo $hash[0]['thumbnail_medium']; ?>" alt="" itemprop="thumbnail">
      <?php } else if(!empty($provider_res) && $provider_res == 'dailymotion') {
	  ?>
      <img width="<?php echo $video_thumb_width;?>" height="<?php echo $video_thumb_height;?>" src="http://www.dailymotion.com/thumbnail/video/<?php echo $video_id_res;?>" alt="" itemprop="thumbnail">
      <?php }?>
    </figure>
    <header>
      <h1><a href="<?php the_permalink();?>" itemprop="name">
        <?php $title = get_the_title(); echo substr($title,0,27);?>
        </a></h1>
      <p><span itemprop="playCount">Views <?php echo getPostViews($post->ID);?></span><span itemprop="datePublished"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';?></span></p>
    </header>
  </article>
  <?php endwhile; else : echo 'No other posts found'; endif; wp_reset_postdata();?>
  <?php } //end if condition?>
</div>
<?php get_footer(); ?>
