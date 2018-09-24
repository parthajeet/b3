<?php
/*
* Template Name: Contact Page Template
*/
?>

<?php get_header(); ?>

<?php get_template_part( 'part', 'hero' ); ?>

<main id="main">
	<div class="container">
		<div class="row">

			<div class="col-md-8">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<article id="entry-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
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

						<?php comments_template(); ?>
					</article>
				<?php endwhile; endif; ?>
			</div>
			<div class="col-md-4">
				<div id="sidebar">
					<?php dynamic_sidebar( 'contactpage-sidebar' ); ?>
				</div>
			</div>
		</div>
	</div>
</main>


<?php get_footer(); ?>