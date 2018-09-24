<div <?php post_class( 'item' ); ?>>
	<?php $icon = get_post_meta( get_the_ID(), 'ci_cpt_service_icon', true ); ?>
	<span class="item-icon">
		<a class="" href="<?php the_permalink(); ?>"><i class="fas <?php echo esc_attr( $icon ); ?>"></i></a>
	</span>
	<p class="item-title"><a class="home_service_icon_title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
	<div class="item-desc">
		<?php the_excerpt(); ?>
	</div>
	<a class="item-more" href="<?php the_permalink(); ?>"><?php _e( 'Learn More', 'ci_theme' ); ?> <i class="fa fa-chevron-right"></i></a>
</div>