<?php get_header(); 
$obj = get_post_type_object( 'video-gallery' );
$singular_name = $obj->labels->singular_name;
?>
<?php
//get video_settings options in serialized format...
$video_settings = get_option('video_gallery_settings');

$data_results = unserialize($video_settings); //unserialize the $video_settings array..

$videog_main_title_res = $data_results['videog_main_title'];//main title
$vposted_date_display_res = $data_results['vposted_date_display'];//posted date and view count
$video_thumb_width = $data_results['video_thumb_width'];//video thumb widtg
$video_thumb_height = $data_results['video_thumb_height'];//video thunb height
$video_layout = $data_results['video_layout'];//video layout
if(empty($video_thumb_width) || empty($video_thumb_height))
{
	$video_thumb_width = 300;
	$video_thumb_height = 200;
}
?>
<section id="content-area"> 
  <!-- //Left column -->
  <div class="left-column rio-video-container" id="rio-video-category">
    <h1><?php if(!empty($videog_main_title_res)){ echo $videog_main_title_res;}else{ echo $singular_name;}?></h1>
    <!-- //Single news item -->
   <?php
			 $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $qargs = array(
	'posts_per_page' => 10,
	'post_type' => 'video-gallery',
	'paged' => $paged,
	'meta_key' => 'video_post_order',
	'orderby' => 'meta_value_num',
	'order' => 'asc'
);

		$the_query = new WP_Query( $qargs );
		if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
			$postid = get_the_ID();
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
			$video_provider = get_post_meta(get_the_ID(), 'video_provider', true );
			$video_id = get_post_meta(get_the_ID(), 'video_id', true );
			?>
    <?php if(empty($video_layout) || $video_layout == 1) {?>
<article>
  <figure> <a href="<?php the_permalink();?>">&nbsp;</a>
    <?php if(!empty($video_provider) && $video_provider == 'youtube') {?>
    <img src="http://img.youtube.com/vi/<?php echo $video_id;?>/0.jpg" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>">
    <?php } else if(!empty($video_provider) && $video_provider == 'vimeo') {?>
    <img src="<?php echo getVimeoThumb($video_id);?>" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>">
    <?php } else if(!empty($video_provider) && $video_provider == 'dailymotion') {?>
    <img src="http://www.dailymotion.com/thumbnail/video/<?php echo $video_id;?>" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>">
    <?php }?>
     </figure>
  <header>
    <?php if(!empty($vposted_date_display_res)) {?>
    <p><span>Views <?php echo getPostViews($postid);?></span><span><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';?></span></p>
    <?php } ?>
  </header>
</article>
<?php } //layout condition 1 close here... ?>
<?php if(!empty($video_layout) && $video_layout == 2) {?>
<article>
  <figure> <a href="<?php the_permalink();?>">&nbsp;</a>
    <?php if(!empty($video_provider) && $video_provider == 'youtube') {?>
    <img src="http://img.youtube.com/vi/<?php echo $video_id;?>/0.jpg" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>">
    <?php } else if(!empty($video_provider) && $video_provider == 'vimeo') {?>
    <img src="<?php echo getVimeoThumb($video_id);?>" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>">
    <?php } else if(!empty($video_provider) && $video_provider == 'dailymotion') {?>
    <img src="http://www.dailymotion.com/thumbnail/video/<?php echo $video_id;?>" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>">
    <?php }?>
     </figure>
  <header> <a href="<?php the_permalink();?>">
    <h1>
      <?php $title = get_the_title(); echo substr($title,0,25);?>
    </h1>
    </a>
    <?php if(!empty($vposted_date_display_res)) {?>
    <p><span>Views <?php echo getPostViews($postid);?></span><span><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';?></span></p>
    <?php } ?>
  </header>
</article>
<?php } //layout condition 2 close here... ?>
    <?php endwhile; else : echo '<p>No videos found</p>'; endif; wp_reset_postdata();?>
    <p class="pagination"><?php
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
  <!-- //sidebar -->
 <?php get_sidebar(); ?>
</section>
<?php get_footer(); ?>