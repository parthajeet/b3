<?php
	$slides = new WP_Query( array(
		'post_type'      => 'cpt_slider',
		'posts_per_page' => -1
	) );
?>
<?php if ( $slides->have_posts() ) : ?>
	<div class="home-slider flexslider loading">
		<ul class="slides">
			<?php while ( $slides->have_posts() ) : $slides->the_post(); ?>
				<?php $video_url = get_post_meta( $post->ID, 'ci_cpt_slider_video_url', true ); ?>
				<li style="background:url('<?php echo ci_get_featured_image_src('ci_slider_full'); ?>') no-repeat top center">
					<?php if ( ! empty ( $video_url ) ) : ?>
						<div class="slide-video-wrap">
							<?php echo wp_oembed_get( esc_url( $video_url ), array( 'height' => 390 ) ); ?>
						</div>
					<?php else : ?>
						<div class="slide-content">
							<div class="container">
								<div class="row">
									<div class="col-md-10 col-md-offset-1">
										<?php $slide_url = get_post_meta( $post->ID, 'ci_cpt_slider_url', true ); ?>
										<h3 class=""><a class="slide-title white" href="<?php echo esc_url( $slide_url ); ?>"><?php the_title(); ?></a></h3>
										<?php the_content(); ?>
										
										<?php if ( ! empty( $slide_url ) ) : ?>
											<a class="btn white transparent" href="<?php echo esc_url( $slide_url ); ?>"><?php _e( 'Learn More', 'ci_theme' ); ?></a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					<?php endif; // if $video_url empty ?>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
<?php endif; wp_reset_postdata(); ?>