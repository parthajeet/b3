<?php
	//
	// Determine the correct header image
	//

	// These hold the panel settings. If no $custom values are set, the $default will be used.
	$default    = ci_setting( 'default_header_bg' );
	$default_id = ci_setting( 'default_header_bg_id' );

	// Means that an image from Media Manager is selected. Replace the URL with the correctly sized image.
	// $default non-empty and $default_id empty, means a URL was pasted, so don't overwrite it.
	if ( !empty( $default ) and !empty( $default_id ) ) {
		$default = ci_get_image_src( $default_id, 'ci_header_image' );
	}

	// These will hold page-specific values. If empty, the $defaults will be used.
	$custom    = false;
	$custom_id = false;
	$subtitle  = false;

	if ( is_page() ) {
		$tmp      = get_post_meta( $post->ID, 'header_image', true );
		$tmp_id   = get_post_meta( $post->ID, 'header_image_id', true );
		$subtitle = get_post_meta( $post->ID, 'header_text', true );

		if ( !empty( $tmp ) and !empty( $tmp_id ) ) {
			$custom    = $tmp;
			$custom_id = $tmp_id;
		}
	} elseif ( is_singular( 'post' ) ) {
		// See if there is a page assigned as the posts page, in Reading Options.
		if ( 'page' == get_option( 'show_on_front' ) ) {

			$blogpage = get_option( 'page_for_posts' );
			if ( !empty( $blogpage ) ) {

				$page = get_post( $blogpage );
				if ( !empty( $page ) ) {
					$tmp      = get_post_meta( $page->ID, 'header_image', true );
					$tmp_id   = get_post_meta( $page->ID, 'header_image_id', true );
					$subtitle = get_post_meta( $page->ID, 'header_text', true );
					if ( !empty( $tmp ) and !empty( $tmp_id ) ) {
						$custom    = $tmp;
						$custom_id = $tmp_id;
					}
				}
			}
		}
	} elseif ( is_single() ) {
		// Handle the rest of the post types. Includes CPTs. Excludes attachments and pages.
		$pages = new WP_Query( array(
			'post_type'      => 'page',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'template-listing-' . get_post_type() . '.php',
		) );

		while ( $pages->have_posts() ) {
			$pages->the_post();
			global $post;
			$tmp      = get_post_meta( $post->ID, 'header_image', true );
			$tmp_id   = get_post_meta( $post->ID, 'header_image_id', true );
			$subtitle = get_post_meta( $post->ID, 'header_text', true );
			if ( !empty( $tmp ) and !empty( $tmp_id ) ) {
				$custom    = $tmp;
				$custom_id = $tmp_id;
				break; // Stop at the first successful match (i.e. the page has an image defined).
			}
		}
		wp_reset_postdata();
	}

	// Just like $defaults above, if $custom_id is set then an image from Media Manager has been selected.
	if ( !empty( $custom_id ) ) {
		$custom = ci_get_image_src( $custom_id, 'ci_header_image' );
	}

	// Create our inline CSS rules. Prefer $custom over $default.
	$rule = 'style="background: url(\'%s\') no-repeat center top;"';
	$style = '';
	if ( !empty( $custom ) ) {
		$style = sprintf( $rule, esc_url( $custom ) );
	} elseif ( !empty( $default ) ) {
		$style = sprintf( $rule, esc_url( $default ) );
	} else {
		$style = '';
	}
?>
<?php
	//
	// Determine the correct title.
	//
	$title = '';
	if ( woocommerce_enabled() and is_woocommerce() ) {
		$title = woocommerce_page_title( false );
	} elseif ( is_page() ) {
		$title = single_post_title( '', false );
	} elseif ( is_category() ) {
		$title = single_term_title( '', false );
	} elseif ( is_month() ) {
		$title = single_month_title( ' ', false );
	} elseif ( is_year() ) {
		$title = get_the_date( 'Y' );
	} elseif ( is_day() ) {
		$title = get_the_date( get_option( 'date_format' ) );
	} elseif ( is_singular( 'post' ) or 'post' == get_post_type() ) {
		$title = __( 'From the blog', 'ci_theme' );
	} elseif ( is_singular() and in_array( get_post_type(), array(
			'cpt_partner',
			'cpt_portfolio',
			'cpt_service',
			'cpt_team',
			'cpt_feature',
		) )
	) {
		$pages = new WP_Query( array(
			'post_type'      => 'page',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'template-listing-' . get_post_type() . '.php',
		) );

		while ( $pages->have_posts() ) {
			$pages->the_post();
			$title = get_the_title();
		}
		wp_reset_postdata();
	} elseif ( is_search() ) {
		$title    = __( 'Search Results', 'ci_theme' );
		$subtitle = sprintf( __( 'Results for: <strong>%s</strong>', 'ci_theme' ), get_search_query() );
	} elseif ( is_404() ) {
		$title    = __( 'Oops! 404', 'ci_theme' );
		$subtitle = __( 'The page could not be found', 'ci_theme' );
	} elseif ( is_tax( 'portfolio-category' ) ) {
		$taxonomy     = 'portfolio-category';
		$terms_array  = array();
		$queried_term = get_query_var( $taxonomy );
		$terms        = get_terms( $taxonomy, 'slug=' . $queried_term );
		if ( $terms ) {
			foreach ( $terms as $term ) {
				$terms_array[] = $term->name;
			}
		}
		$tax      = get_taxonomy( $taxonomy );
		$title    = __( 'Portfolio', 'ci_theme' );
		$subtitle = implode( ', ', $terms_array );
	}
?>
<div class="page-hero" <?php echo $style; ?>>
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<?php
				if(is_singular( array( 'cpt_service', 'cpt_feature','cpt_portfolio','post' ) )) { ?>
					<p class="hero-title"><?php echo $title; ?></p>
				<?php		
				} else { ?>
					<h1 class="hero-title"><?php echo $title; ?></h1>
				<?php	
				}
				?>
				
				<?php if ( ! empty( $subtitle ) ) : ?>
					<p class="hero-subtitle"><?php echo $subtitle; ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>