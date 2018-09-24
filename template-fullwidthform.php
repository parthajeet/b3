<?php
/*
 * Template Name: Form Page
 */
?>

<?php get_header(); ?>

<?php get_template_part( 'part', 'hero' ); ?>

<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<article id="entry-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
						<?php if ( ci_has_image_to_show() ) : ?>
							<figure class="entry-thumb">
								<a href="<?php echo ci_get_featured_image_src('large'); ?>" data-rel="prettyPhoto">
									<?php ci_the_post_thumbnail_full(); ?>
								</a>
							</figure>
						<?php endif; ?>

						<div class="entry-content">
							<?php the_content(); ?>
							<?php wp_link_pages(); ?>
						</div>

						<div class="row">
							<div class="col-lg-6 col-md-7">
								<?php comments_template(); ?>
							</div>
						</div>
					</article>
				<?php endwhile; endif; ?>
			</div>
		</div>
	</div>
</main>

<?php dynamic_sidebar( 'bottom-widgets' ); ?>

<?php get_footer(); ?>