<?php get_header();

$obj = get_post_type_object( 'video-gallery' );
$singular_name = $obj->labels->singular_name;

function pagination_bar() {
    global $wp_query;
 
    $total_pages = $wp_query->max_num_pages;
 
    if ($total_pages > 1){
        $current_page = max(1, get_query_var('paged'));
 
        echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '/page/%#%',
            'current' => $current_page,
            'total' => $total_pages,
        ));
    }
}

function limit_text($string, $repl, $limit) {
  if(strlen($string) > $limit) 
  {
    return strip_tags(substr($string, 0, $limit) . $repl); 
  }
  else 
  {
    return strip_tags($string);
  }
}
 

?>
<?php
//get video_settings options in serialized format...
$data_results = get_option('video_gallery_settings');

$videog_main_title_res = $data_results['videog_main_title'];
$video_thumb_width = $data_results['video_thumb_width'];
$video_thumb_height = $data_results['video_thumb_height'];
if(empty($video_thumb_width) || empty($video_thumb_height))
{
$video_thumb_width = 300;
$video_thumb_height = 200;
}
// $video_count_gshort = $data_results['video_count'];
if(empty($data_results['video_count']))
{
$video_count = 5;
}

?>

<h1><?php echo $singular_name;?></h1>
<section id="rio-video-gallery-container-archive"> 
 <!-- //Single news item -->
 <?php
			 $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $qargs = array(
	'posts_per_page' => $video_count,
	'post_type' => 'video-gallery',
	'paged' => $paged,
	'meta_key' => 'video_post_order',
	'orderby' => 'meta_value_num',
	'order' => 'asc'
);

		$the_query = new WP_Query( $qargs );
		if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
			$video_provider = get_post_meta(get_the_ID(), 'video_provider', true );
			$video_id = get_post_meta(get_the_ID(), 'video_id', true );
			?>
 <article itemscope itemtype="http://schema.org/VideoObject">
  <figure> <a href="<?php the_permalink(); ?>">
   <?php if(!empty($video_provider) && $video_provider == 'youtube') {?>
   <img alt="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>" src="http://img.youtube.com/vi/<?php echo $video_id; ?>/0.jpg" itemprop="thumbnail">
   <?php } else if(!empty($video_provider) && $video_provider == 'vimeo') {
	   
			 	$imgid = $video_id;			
			$thumb = getVimeoInfo_details($imgid);
			if(empty($thumb))
			{
				$thumb=plugins_url().'/rio-video-gallery/img/video-failed.png';
			}
	  ?>     
   <img alt="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>" src="<?php echo $thumb; ?>" itemprop="thumbnail">
   <?php } else if(!empty($video_provider) && $video_provider == 'dailymotion') {
	  ?>
   <img alt="<?php the_title();?>" width="<?php echo $video_thumb_width; ?>" height="<?php echo $video_thumb_height; ?>" src="http://www.dailymotion.com/thumbnail/video/<?php echo $video_id;?>" itemprop="thumbnail">
   <?php }?>
   </a></figure>
  <section>
   <header>
    <h1><a href="<?php the_permalink();?>" itemprop="name">
     <?php $title = get_the_title(); echo substr($title,0,50);?>
     </a></h1>
    <p>
     <?php if(!empty($video_provider)) { ?>
     <span class="video_provider"><?php echo $video_provider; ?></span>
     <?php } ?>
     <span itemprop="playCount"><?php echo getPostViews($post->ID);?> Views</span><span itemprop="datePublished"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';?></span></p>
   </header>
   <p itemprop="description">
    <?php $content = get_the_content(); echo limit_text($content, "...", 200); ?>
   </p>
  </section>
 </article>
 <?php endwhile; else : echo 'No videos found'; endif; wp_reset_postdata();?>
</section>
<div class="clearFixer">&nbsp;</div>
<p class="pagination">
 <?php
	if(function_exists('wp_pagenavi')){ wp_pagenavi(); }
	else
	{
 pagination_bar(); 

	}

?>
</p>
<?php wp_reset_query(); ?>
<!-- //sidebar -->
<?php get_footer(); ?>

