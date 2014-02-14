<?php get_header(); 
$obj = get_post_type_object( 'video-gallery' );
$singular_name = $obj->labels->singular_name;
?>
<?php
//get video_settings options in serialized format...
$data_results = get_option('video_gallery_settings');

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
//video link target
	$_video_link_target = $data_results['_video_link_target'];

	if(empty($_video_link_target))
	{
	$_video_link_target='popup';
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
  <figure>
    <?php if(!empty($video_provider) && $video_provider == 'youtube') {?>
     <a <?php if($_video_link_target == 'popup'){?> href="http://www.youtube.com/watch?v=<?php echo $video_id;?>" rel="prettyPhoto" <?php }else{?> href="<?php the_permalink();?>"<?php } ?>  title="<?php the_title();?>">&nbsp;</a>
    <img src="http://img.youtube.com/vi/<?php echo $video_id;?>/0.jpg" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>">
    <?php } else if(!empty($video_provider) && $video_provider == 'vimeo') {?>
     <a <?php if($_video_link_target == 'popup'){?> href="http://vimeo.com/<?php echo $video_id;?>" rel="prettyPhoto" <?php }else{?> href="<?php the_permalink();?>"<?php } ?> title="<?php the_title();?>">&nbsp;</a>
    <img src="<?php echo getVimeoThumb($video_id);?>" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>">
    <?php } else if(!empty($video_provider) && $video_provider == 'dailymotion') {?>
    <a <?php if($_video_link_target == 'popup'){?> href="http://www.dailymotion.com/embed/video/<?php echo $video_id;?>?iframe=true&width=500&height=344" rel="prettyPhoto" <?php }else{?> href="<?php the_permalink();?>"<?php } ?> title="<?php the_title();?>">&nbsp;</a>
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
  <figure>
    <?php if(!empty($video_provider) && $video_provider == 'youtube') {?>
     <a <?php if($_video_link_target == 'popup'){?> href="http://www.youtube.com/watch?v=<?php echo $video_id;?>" rel="prettyPhoto" <?php }else{?> href="<?php the_permalink();?>"<?php } ?>  title="<?php the_title();?>">&nbsp;</a>
    <img src="http://img.youtube.com/vi/<?php echo $video_id;?>/0.jpg" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>">
    <?php } else if(!empty($video_provider) && $video_provider == 'vimeo') {?>
    <a <?php if($_video_link_target == 'popup'){?> href="http://vimeo.com/<?php echo $video_id;?>" rel="prettyPhoto" <?php }else{?> href="<?php the_permalink();?>"<?php } ?> title="<?php the_title();?>">&nbsp;</a>
    <img src="<?php echo getVimeoThumb($video_id);?>" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>">
    <?php } else if(!empty($video_provider) && $video_provider == 'dailymotion') {?>
    <a <?php if($_video_link_target == 'popup'){?> href="http://www.dailymotion.com/embed/video/<?php echo $video_id;?>?iframe=true&width=500&height=344" rel="prettyPhoto" <?php }else{?> href="<?php the_permalink();?>"<?php } ?> title="<?php the_title();?>">&nbsp;</a>
    <img src="http://www.dailymotion.com/thumbnail/video/<?php echo $video_id;?>" alt="<?php the_title();?>" title="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>">
    <?php }?>
     </figure>
  <header> <h1 itemprop="name"> 
      <?php if(!empty($video_provider) && $video_provider == 'youtube')
	  { ?>
       <a <?php if($_video_link_target == 'popup'){?> href="http://www.youtube.com/watch?v=<?php echo $video_id;?>" rel="prettyPhoto" <?php }else{?> href="<?php the_permalink();?>"<?php } ?>  title="<?php the_title();?>">
        <?php $title = get_the_title(); echo substr($title,0,27);?>
        </a> 
      <?php }elseif(!empty($video_provider) && $video_provider == 'vimeo')
	  {?>
       <a <?php if($_video_link_target == 'popup'){?> href="http://vimeo.com/<?php echo $video_id;?>" rel="prettyPhoto" <?php }else{?> href="<?php the_permalink();?>"<?php } ?> title="<?php the_title();?>">
        <?php $title = get_the_title(); echo substr($title,0,27);?>
        </a> 
      <?php }elseif(!empty($video_provider) && $video_provider == 'dailymotion')
	  {?>
      <a <?php if($_video_link_target == 'popup'){?> href="http://www.dailymotion.com/embed/video/<?php echo $video_id;?>?iframe=true&width=500&height=344" rel="prettyPhoto" <?php }else{?> href="<?php the_permalink();?>"<?php } ?> title="<?php the_title();?>">
        <?php $title = get_the_title(); echo substr($title,0,27);?>
        </a> 
      <?php }else{?>
      <a href="<?php the_permalink();?>">
        <?php $title = get_the_title(); echo substr($title,0,27);?>
        </a> 
        <?php } ?>
        </h1>
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