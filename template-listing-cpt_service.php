<?php
/*
 * Template Name: Services Listing
 */
?>

<?php get_header(); ?>

<?php get_template_part( 'part', 'hero' ); ?>

<?php
	$args = array(
		'post_type'      => 'cpt_service',
		'posts_per_page' => -1
	);
	$services = new WP_Query( $args );
?>
<main id="main">
	<div class="container">
		<div class="row">

			<div class="col-md-8">
				<?php if ( $services->have_posts() ) : while ( $services->have_posts() ) : $services->the_post(); ?>
					<?php
						$current_ID = get_the_ID();
						$fi_layout  = get_post_meta( get_the_ID(), 'ci_cpt_service_fi_pos', true );
					?>
					<article id="entry-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
						<?php if ( has_post_thumbnail() and $fi_layout == 'top' ) : ?>
							<figure class="entry-thumb">
								<a href="<?php echo ci_get_featured_image_src('large'); ?>" data-rel="prettyPhoto">
									<?php the_post_thumbnail( 'ci_blog_thumb' ); ?>
								</a>
							</figure>
						<?php endif; ?>

						<h2 class="entry-title"><?php the_title(); ?></h2>
						
						<?php if ( has_post_thumbnail() and $fi_layout !== 'top' ) : ?>
							<figure class="entry-thumb-content-<?php echo $fi_layout; ?>">
								<a href="<?php echo ci_get_featured_image_src('large'); ?>" data-rel="prettyPhoto">
									<?php the_post_thumbnail( 'thumbnail' ); ?>
								</a>
							</figure>
						<?php endif; ?>
						
						<?php the_content(); ?>
					</article>
					<?php break; // We only want the first post in this section, so break out of the loop. ?>
				<?php endwhile; endif; ?>
			</div>

			<?php $services->rewind_posts(); ?>

			<div class="col-md-4">
				<div id="sidebar">
					<?php if ( $services->have_posts() ) : ?>
						<ul class="item-nav">
							<?php while ( $services->have_posts() ) : $services->the_post(); ?>
								<?php $icon = get_post_meta( $post->ID, 'ci_cpt_service_icon', true ); ?>
								<li <?php echo ( get_the_ID() == $current_ID ? 'class ="active"' : '' ); ?>>
									<a href="<?php the_permalink(); ?>">
										<?php
											 if ( ! empty( $icon ) ) {
												 echo '<i class="fa '. esc_attr( $icon ) . '"></i>';
											 }
										?>
										<?php the_title(); ?>
									</a>
								</li>
							<?php endwhile; ?>
						</ul>
					<?php endif; wp_reset_postdata(); ?>
				</div>
			</div>

		</div>
	</div>
</main>

<?php get_footer(); ?>