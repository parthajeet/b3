<!doctype html>
<!--[if IE 8]> <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); } ?>
<?php do_action('after_open_body_tag'); ?>

<div id="page">

	<div id="mobile-bar">
		<a class="menu-trigger" href="#mobilemenu"><i class="fa fa-bars"></i></a>
		<p class="mob-title"><?php bloginfo( 'name' ); ?></p>
	</div>

	<header id="header">
		<div class="pre-head">
			<div class="container">
				<div class="row">
					<div class="col-sm-5">
						<span class="header-callout"><?php ci_e_setting( 'header_message' ); ?></span>
					</div>

					<?php //if ( is_active_sidebar( 'header-sidebar' ) OR woocommerce_enabled() ) : ?>
						<div class="col-sm-7 side-head">

							<?php //if( have_rows('social_media', 'option') ): ?>

							    <!-- <ul>

							    <?php while( have_rows('social_media', 'option') ): the_row(); ?>

							        <li><a href="<?php echo get_sub_field('url'); ?>" class="social-icon" target="_blank" title="<?php echo get_sub_field('name'); ?>"><?php echo get_sub_field('icon'); ?></a></li>

							    <?php endwhile; ?>

							    </ul> -->

							<?php //endif; ?>
							
							
							<?php 
							if ( is_active_sidebar( 'header-sidebar' )) {
								dynamic_sidebar( 'header-sidebar' ); 
							}
							?>

							<?php if ( woocommerce_enabled() ) : ?>
								<div class="cart-head">
									<span><?php _e('Shopping Cart', 'ci_theme'); ?>: <?php echo WC()->cart->get_cart_total(); ?></span>
									<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php echo sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'ci_theme' ), WC()->cart->get_cart_contents_count() ); ?></a>
								</div>
							<?php endif; // woocommerce_enabled() ?>
						</div>
					<?php //endif; ?>
				</div>
			</div>
		</div> <!-- .pre-head -->

		<div class="mast-head">
			<div class="container">
				<div class="row">
					<div class="col-md-3 u-logo-section">
						<?php 
						if ( is_home() || is_front_page()) {
							ci_e_logo( '<h1 class="site-logo ' . get_logo_class() . '">', '</h1>' );
						} else {
							ci_e_logo( '<p class="site-logo ' . get_logo_class() . '">', '</p>' );
						 
						}
						?>
						<?php ci_e_slogan( '<span class="site-tagline">', '</span>' ); ?>
					</div>
					<div class="col-md-9">
						<nav id="nav">
							<?php wp_nav_menu( array(
								'theme_location' => 'ci_main_menu',
								'fallback_cb'    => '',
								'container'      => '',
								'menu_id'        => 'navigation',
								'menu_class'     => 'group'
							) ); ?>
						</nav>
						<div id="mobilemenu"></div>
					</div>
				</div>
			</div>
		</div>
	</header>
