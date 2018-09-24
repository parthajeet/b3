<?php get_header(); ?>

<?php get_template_part( 'part', 'hero' ); ?>

<main id="main">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<article class="entry">
					<h1 class="entry-title"><?php _e('Page not found', 'ci_theme'); ?></h1>
					<div class="entry-content">
						<p><?php _e('Oops! That page can’t be found.It looks like nothing was found at this location. Maybe try a search?', 'ci_theme'); ?></p>
						<?php get_search_form(); ?>
					</div>
				</article>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>

<?php dynamic_sidebar( 'bottom-widgets' ); ?>

<?php get_footer(); ?>