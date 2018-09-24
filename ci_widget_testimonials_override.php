<?php 
if( !class_exists('CI_Testimonials') ):
class CI_Testimonials extends WP_Widget {

function __construct(){
	$widget_ops  = array( 'description' => __( 'Testimonials widget', 'ci_theme' ) );
	$control_ops = array( /*'width' => 300, 'height' => 400*/ );
	parent::__construct( 'ci-testimonials', __( '-= CI Testimonials =-', 'ci_theme' ), $widget_ops, $control_ops );

	add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_custom_css' ) );
}

function widget($args, $instance) {
	extract($args);

	$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
	$count  = $instance['count'];
	$random = $instance['random'];

	if ( $random == 'on' ) {
		$random = 'rand';
	} else {
		$random = 'post_date';
	}

	echo $before_widget;

	echo '<div class="widget-wrap">';

	if ( in_array( $id, array( 'frontpage-widgets', 'bottom-widgets' ) ) ) {
		echo '<div class="container"><div class="row">';
	}

	if ( $title ) {
		echo $before_title . $title . $after_title;
	}

	echo '<div class="col-xs-12"><div class="testimonials flexslider"><ul class="slides">';

	$q = new WP_Query( array(
		'posts_per_page' => $count,
		'orderby'        => $random,
		'post_type'      => 'cpt_testimonial'
	) );

	while( $q->have_posts() ) : $q->the_post();
		global $post;
		?>
		<li class="testimonial">
			<div class="row">
				<div class="col-md-12">
					<blockquote>
						<?php the_content(); ?>
						<cite>
							<?php the_post_thumbnail(array(300,300)); ?>
							<span><?php the_title(); ?> </span>
						</cite>
					</blockquote>
				</div>
			</div>
		</li>
		<?php
	endwhile;
	wp_reset_postdata();

	echo '</ul></div></div>';

	if ( in_array( $id, array( 'frontpage-widgets', 'bottom-widgets' ) ) ) {
		echo '</div></div>';
	}

	echo '</div>';

	echo $after_widget;
}

function update($new_instance, $old_instance){
	$instance           = $old_instance;
	$instance['title']  = sanitize_text_field( $new_instance['title'] );
	$instance['count']  = intval( $new_instance['count'] );
	$instance['random'] = ci_sanitize_checkbox( $new_instance['random'] );

	$instance['color']             = ci_sanitize_hex_color( $new_instance['color'] );
	$instance['background_color']  = ci_sanitize_hex_color( $new_instance['background_color'] );
	$instance['background_image']  = esc_url_raw( $new_instance['background_image'] );
	$instance['background_repeat'] = in_array( $new_instance['background_repeat'], array( 'repeat', 'no-repeat', 'repeat-x', 'repeat-y' ) ) ? $new_instance['background_repeat'] : 'repeat';

	return $instance;
}

function form($instance){
	$instance = wp_parse_args( (array) $instance, array(
		'title'             => __( 'Testimonials', 'ci_theme' ),
		'count'             => 5,
		'random'            => 'on',
		'color'             => '',
		'background_color'  => '',
		'background_image'  => '',
		'background_repeat' => 'repeat',
	) );

	$title  = $instance['title'];
	$count  = $instance['count'];
	$random = $instance['random'];

	$color             = $instance['color'];
	$background_color  = $instance['background_color'];
	$background_image  = $instance['background_image'];
	$background_repeat = $instance['background_repeat'];

	echo '<p><label for="'.$this->get_field_id('title').'">' . __('Title:', 'ci_theme') . '</label><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" class="widefat" /></p>';
	echo '<p><label for="'.$this->get_field_id('count').'">' . __('Number of testimonials:', 'ci_theme') . '</label><input id="' . $this->get_field_id('count') . '" name="' . $this->get_field_name('count') . '" type="text" value="' . esc_attr($count) . '" class="widefat" /></p>';
	echo '<p><label for="'.$this->get_field_id('random').'"><input type="checkbox" name="' . $this->get_field_name('random') . '" id="' . $this->get_field_id('random') . '" value="on" ' . checked($random, 'on', false) . ' />' . __('Show random testimonials', 'ci_theme') . '</label></p>';

	?>
	<fieldset class="ci-collapsible">
		<legend><?php _e('Custom Colors', 'ci_theme'); ?> <i class="dashicons dashicons-arrow-down"></i></legend>
		<div class="elements">
			<p><label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Foreground Color:', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" type="text" value="<?php echo esc_attr($color); ?>" class="colorpckr widefat" /></p>
			<p><label for="<?php echo $this->get_field_id('background_color'); ?>"><?php _e('Background Color:', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('background_color'); ?>" name="<?php echo $this->get_field_name('background_color'); ?>" type="text" value="<?php echo esc_attr($background_color); ?>" class="colorpckr widefat" /></p>
			<p><label for="<?php echo $this->get_field_id('background_image'); ?>"><?php _e('Background Image:', 'ci_theme'); ?></label><input id="<?php echo $this->get_field_id('background_image'); ?>" name="<?php echo $this->get_field_name('background_image'); ?>" type="text" value="<?php echo esc_attr($background_image); ?>" class="uploaded widefat" /><a href="#" class="button ci-upload"><?php _e('Upload', 'ci_theme'); ?></a></p>
			<p>
				<label for="<?php echo $this->get_field_id('background_repeat'); ?>"><?php _e('Background Repeat:', 'ci_theme'); ?></label>
				<select id="<?php echo $this->get_field_id('background_repeat'); ?>" name="<?php echo $this->get_field_name('background_repeat'); ?>">
					<option value="repeat" <?php selected('repeat', $background_repeat); ?>><?php _e('Repeat', 'ci_theme'); ?></option>
					<option value="repeat-x" <?php selected('repeat-x', $background_repeat); ?>><?php _e('Repeat Horizontally', 'ci_theme'); ?></option>
					<option value="repeat-y" <?php selected('repeat-y', $background_repeat); ?>><?php _e('Repeat Vertically', 'ci_theme'); ?></option>
					<option value="no-repeat" <?php selected('no-repeat', $background_repeat); ?>><?php _e('No Repeat', 'ci_theme'); ?></option>
				</select>
			</p>
		</div>
	</fieldset>
	<?php
}

function enqueue_custom_css() {
	$settings = $this->get_settings();

	if ( empty( $settings ) )
		return;

	foreach( $settings as $instance_id => $instance ) {
		$id = $this->id_base.'-'.$instance_id;

		if ( !is_active_widget( false, $id, $this->id_base ) ) {
			continue;
		}

		$sidebar_id      = false; // Holds the sidebar id that the widget is assigned to.
		$sidebar_widgets = wp_get_sidebars_widgets();
		if ( !empty( $sidebar_widgets ) ) {
			foreach ( $sidebar_widgets as $sidebar => $widgets ) {
				// We need to check $widgets for emptiness due to https://core.trac.wordpress.org/ticket/14876
				if ( !empty( $widgets ) && array_search( $id, $widgets ) !== false ) {
					$sidebar_id = $sidebar;
				}
			}
		}

		$color             = $instance['color'];
		$background_color  = $instance['background_color'];
		$background_image  = $instance['background_image'];
		$background_repeat = $instance['background_repeat'];

		$css = '';
		$padding_css = '';

		if ( !empty( $color ) ) {
			$css .= 'color: ' . $color . '; ';
		}
		if ( !empty( $background_color ) ) {
			$css .= 'background-color: ' . $background_color . '; ';
		}
		if ( !empty( $background_image ) ) {
			$css .= 'background: url(' . esc_url($background_image) . ') '. $background_repeat .' top center; ';
		}

		if( !empty( $background_color ) or !empty( $background_image ) ) {
			if( !in_array( $sidebar_id, array( 'frontpage-widgets', 'bottom-widgets' ) ) ) {
				$padding_css .= 'padding: 25px 15px; ';
			}
		}

		if ( !empty( $css ) ) {
			$css = '#' . $id . ' .widget-wrap { ' . $css . $padding_css . ' } ' . PHP_EOL;
			wp_add_inline_style('ci-style', $css);
		}

	}

}

} // class

register_widget('CI_Testimonials');

endif; // class_exists
?>