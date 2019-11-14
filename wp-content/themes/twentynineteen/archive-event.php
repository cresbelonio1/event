<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

get_header();


function my_pre_get_posts( $query ) {
	
	// do not modify queries in the admin
	if( is_admin() ) {
		
		return $query;
		
	}
	

	// only modify queries for 'event' post type
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'event' ) {
		
		$query->set('orderby', 'meta_value');	
		$query->set('meta_key', 'event_date');	 
		$query->set('order', 'DESC'); 
		
	}
	

	// return
	return $query;

}

add_action('pre_get_posts', 'my_pre_get_posts');

$args = array(
		'post_type' => 'event',
	);
$event_posts = new WP_Query($args);

?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( $event_posts->have_posts() ) : ?>

			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
				?>
			</header><!-- .page-header -->

			<?php
			// Start the Loop.
			while ( $event_posts->have_posts() ) :
				$event_posts->the_post();
				$title = get_the_title();
				$edate = get_field('event_date');
				$elocation = get_field('event_location');
				$eurl = get_field('url');
				$onlydate = date('Ymd', strtotime($edate) );
				$onlytime = date('His', strtotime($edate) );
				$ledate = $onlydate.'T'.$onlytime.'Z/'.$onlydate.'T'.$onlytime.'Z';
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php
						if ( is_sticky() && is_home() && ! is_paged() ) {
							printf( '<span class="sticky-post">%s</span>', _x( 'Featured', 'post', 'twentynineteen' ) );
						}
						if ( is_singular() ) :
							the_title( '<h1 class="entry-title">', '</h1>' );
						else :
							the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
						endif;
						?>
					</header><!-- .entry-header -->

					<div class="entry-content">
						Date : <?php echo $edate; ?>
					</div>

					<div class="entry-content">
						Location : <?php echo $elocation; ?>
					</div>

					<div class="entry-content">
						URL : <?php echo $eurl; ?>
					</div>

					<div class="entry-content">
						<?php $link = 'https://www.google.com/calendar/render?action=TEMPLATE&text='.$title.'&dates='.$ledate.'&details=For+details,+link+here:+'.$eurl.'&location='.$elocation.'&sf=true&output=xml' ?>

						<a href="<?php echo $link; ?>" target="_blank"> <button>Add to Calendar</button> </a>
					</div>

					<div class="entry-content">
						<?php
						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . __( 'Pages:', 'twentynineteen' ),
								'after'  => '</div>',
							)
						);
						?>
					</div><!-- .entry-content -->

				</article><!-- #post-<?php the_ID(); ?> -->

				<?php 
				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				get_template_part( 'template-parts/content/content', 'excerpt' );
				 */

				// End the loop.
			endwhile;

			// Previous/next page navigation.
			twentynineteen_the_posts_navigation();

			// If no content, include the "No posts found" template.
		else :
			get_template_part( 'template-parts/content/content', 'none' );

		endif;
		?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
