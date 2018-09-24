<?php
/**
	* Function name : sw_velocityconveyor_enqueue_styles
	* Enqueue script and style
	**/

add_action( 'wp_enqueue_scripts', 'sw_velocityconveyor_enqueue_styles' );
function sw_velocityconveyor_enqueue_styles() {
	$parent_style = 'parent-style';
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style('fontawsm-style','//use.fontawesome.com/releases/v5.3.1/css/all.css', '', '5.3.1');
    
    wp_enqueue_style('customstylesheet', get_stylesheet_directory_uri() . '/css/custom.css',array( $parent_style ), time());
    
    // wp_enqueue_style('style-custom', get_stylesheet_directory_uri() . '/css/style.min.css',array( $parent_style ), '');

    wp_enqueue_style('style-custom', get_stylesheet_directory_uri() . '/css/style.min.css',array( $parent_style ), time());

   

}


require_once(get_stylesheet_directory() . '/ci_widget_testimonials_override.php');
require_once(get_stylesheet_directory() . '/ci_widget_social_override.php');

/**
	* Function name : acf_add_options_page
	* To create options page
	**/
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
}

/**
	* Function name : sw_velocityconveyor_register_post_type_args
	* Change post type name and slug
	**/

add_filter( 'register_post_type_args', 'sw_velocityconveyor_register_post_type_args', 10, 2 );
function sw_velocityconveyor_register_post_type_args( $args, $post_type ) {

    if ( 'cpt_feature' === $post_type ) {    	
        $args['rewrite']['slug'] = 'conveyor';
        $args['labels']['name'] = __( 'Conveyor', 'ci_theme');
    }

    if ( 'cpt_portfolio' === $post_type ) {    	
        $args['rewrite']['slug'] = 'project';
        $args['labels']['name'] = __( 'Project', 'ci_theme');
    }

    return $args;
}

/**
	* Function name : sw_velocityconveyor_widgets_init
	* Create Contact page sidebar
	**/

add_action( 'widgets_init', 'sw_velocityconveyor_widgets_init' );
function sw_velocityconveyor_widgets_init() {
	register_sidebar( array(
			'name'          => __( 'Contact Page Sidebar', 'ci_theme' ),
			'id'            => 'contactpage-sidebar',
			'description'   => __( 'Widgets placed in this sidebar will appear in the contact page.', 'ci_theme' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s group">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		) );
}


/**
	* Function name: sw_velocityconveyor_wp_is_mobile
	* Modify wp_is_mobile wp function
	*/
add_filter('wp_is_mobile','sw_velocityconveyor_wp_is_mobile');
function sw_velocityconveyor_wp_is_mobile($is_mobile) {
		
  if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
      $is_mobile = false;
  } elseif (
      strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
      || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
      || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
      || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
      || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false
      || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false ) {
          $is_mobile = true;
  } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false) {
      $is_mobile = false;
  } else {
      $is_mobile = false;
  }

  return $is_mobile;
}



/**
	* Function name: sw_velocityconveyor_safe_email
	* Antispam Email Shortcode
	*/
function sw_velocityconveyor_safe_email( $atts, $content ) {

return '<a href="mailto:' . antispambot( $content ) . '">' . antispambot( $content ) . '</a>';
}
add_shortcode( 'email', 'sw_velocityconveyor_safe_email' );

?>