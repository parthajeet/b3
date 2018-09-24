<?php get_header(); ?>

<?php get_template_part( 'part', 'hero' ); ?>

<main id="main">
	<div class="container">
		<div class="row">

			<div class="col-md-8">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<article id="entry-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="entry-meta">
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo get_the_date(); ?></time> &bull; <?php the_author(); ?> &bull; <?php the_category( ', ' ); ?>
						</div>

						<?php if ( ci_has_image_to_show() ) : ?>
							<figure class="entry-thumb">
								<a href="<?php echo ci_get_featured_image_src('large'); ?>" data-rel="prettyPhoto">
									<?php ci_the_post_thumbnail(); ?>
								</a>
							</figure>
						<?php endif; ?>

						<div class="entry-content">
							<?php the_content(); ?>
							<?php wp_link_pages(); ?>
						</div>

						<?php if ( ci_setting( 'disable_author_info' ) !== 'on' AND get_the_author_meta( 'description' ) ) : ?>
							<div class="author-info">
								<figure class="author-avatar">
									<?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?>
								</figure>

								<div class="author-content">
									<h5><?php _e( 'About the author', 'ci_theme' ); ?></h5>

									<?php the_author_meta( 'description' ); ?>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ci_setting( 'disable_posts_related' ) !== 'on' ) : ?>
							<?php
								$term_list = array();
								$tax_query = array();
								$terms = get_the_terms( get_the_ID(), 'category');
		
								if ( is_array($terms) and count($terms) > 0 ) {
									$term_list = wp_list_pluck( $terms, 'slug' );
									$term_list = array_values( $term_list );
									if ( !empty($term_list) ) {
										$tax_query = array(
											'tax_query' => array(
												array(
													'taxonomy' => 'category',
													'field'    => 'slug',
													'terms'    => $term_list
												)
											)
										);
									}
								}
		
								$args = array(
									'post_type'      => 'post',
									'posts_per_page' => 2,
									'post_status'    => 'publish',
									'post__not_in'   => array( $post->ID ),
									'orderby'        => 'rand'
								);
		
								$related = new WP_Query( array_merge( $args, $tax_query ) );
							?>
							<?php if ( $related->have_posts() ) : ?>
								<section class="related">
									<h2 class="section-title"><?php _e( 'Related Articles', 'ci_theme' ); ?></h2>
		
									<div class="row">
										<?php while ( $related->have_posts() ) : $related->the_post(); ?>
											<div class="col-sm-6">
												<?php get_template_part( 'content-item', get_post_type() ); ?>
											</div>
										<?php endwhile; ?>
									</div>
								</section>
							<?php endif; wp_reset_postdata(); ?>
		
						<?php endif; // 'disable_posts_related' ?>

						<?php //comments_template(); ?>
					</article>
				<?php endwhile; endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>

<?php dynamic_sidebar( 'bottom-widgets' ); ?>

<?php get_footer(); ?>