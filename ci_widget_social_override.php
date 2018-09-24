<?php 
//
// Font widget
//

if ( ! class_exists( 'SW_Socials_Ignited_Widget' ) ):
class SW_Socials_Ignited_Widget extends WP_Widget {

	function __construct() {
		$widget_ops  = array(
			'description' => esc_html__( 'Social Icons widget, FontAwesome edition', 'socials-ignited' ),
			'classname'   => 'widget_socials_ignited'
		);
		$control_ops = array(/*'width' => 300, 'height' => 400*/ );
		parent::__construct( 'socials-ignited', $name = esc_html__( '-= SW CI Socials Ignited =-', 'socials-ignited' ), $widget_ops, $control_ops );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_css' ) );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$nofollow = '';
		if ( isset( $instance['nofollow'] ) && $instance['nofollow'] == 'on' ) {
			$nofollow = 'rel="nofollow"';
		}

		$new_win = $instance['new_win'] == 'on' ? 'target="_blank"' : '';
		$icons   = ! empty( $instance['icons'] ) ? $instance['icons'] : array();
		$icons   = $this->convert_repeating_icons_from_unnamed( $icons );

		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		echo '<div class="ci-socials-ignited ci-socials-ignited-fa">';

		if ( ! empty( $icons ) ) {
			foreach ( $icons as $field ) {
				echo sprintf( '<a href="%s" %s %s %s><i class="fab %s"></i></a>',
					esc_url( $field['url'] ),
					$new_win,
					$nofollow,
					! empty( $field['title'] ) ? sprintf( 'title="%s"', esc_attr( $field['title'] ) ) : '',
					esc_attr( $field['icon'] )
				);
			}
		}

		echo '</div>';

		echo $after_widget;

	} // widget

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']            = sanitize_text_field( $new_instance['title'] );
		$instance['color']            = cisiw_sanitize_hex_color( $new_instance['color'] );
		$instance['background_color'] = cisiw_sanitize_hex_color( $new_instance['background_color'] );
		$instance['size']             = cisiw_absint_or_empty( $new_instance['size'] );
		$instance['background_size']  = cisiw_absint_or_empty( $new_instance['background_size'] );
		$instance['border_radius']    = cisiw_absint_or_empty( $new_instance['border_radius'] );
		$instance['border_color']     = cisiw_sanitize_hex_color( $new_instance['border_color'] );
		$instance['border_width']     = absint( $new_instance['border_width'] );
		$instance['opacity']          = round( floatval( $new_instance['opacity'] ), 1 );
		$instance['new_win']          = cisiw_sanitize_checkbox( $new_instance['new_win'] );
		$instance['nofollow']         = cisiw_sanitize_checkbox( $new_instance['nofollow'] );
		$instance['icons']            = $this->sanitize_repeating_icons( $new_instance );

		return $instance;
	} // save

	function form( $instance ) {
		$cisiw = get_option('cisiw_settings');
		$cisiw = $cisiw !== false ? $cisiw : array();

		$instance = wp_parse_args( (array) $instance, array(
			'title'            => '',
			'color'            => isset( $cisiw['f_color'] ) ? $cisiw['f_color'] : '',
			'background_color' => isset( $cisiw['f_background_color'] ) ? $cisiw['f_background_color'] : '',
			'border_radius'    => isset( $cisiw['f_border_radius'] ) ? $cisiw['f_border_radius'] : 50,
			'border_color'     => isset( $cisiw['f_border_color'] ) ? $cisiw['f_border_color'] : '',
			'border_width'     => isset( $cisiw['f_border_width'] ) ? $cisiw['f_border_width'] : 0,
			'size'             => isset( $cisiw['f_size'] ) ? $cisiw['f_size'] : 17,
			'background_size'  => isset( $cisiw['f_background_size'] ) ? $cisiw['f_background_size'] : 30,
			'opacity'          => isset( $cisiw['f_opacity'] ) ? $cisiw['f_opacity'] : 1,
			'new_win'          => '',
			'nofollow'         => '',
			'icons'            => array(),
		) );
		extract( $instance );

		?>
		<p class="cisiw-icon-instructions">
			<?php $allowed_tags = array(
				'em'     => array(),
				'strong' => array(),
				'a'      => array(
					'href'   => array(),
					'target' => array(),
				),
			); ?>
			<small><?php echo wp_kses( sprintf( __( 'To add icons click on "Add Icon" at the bottom of the widget and then insert the <em>Icon code</em> and its <em>Link URL</em>. Icon codes can be found <a target="_blank" href="%s">here</a>, type them exactly as they are shown (with fa- in front), e.g. <strong>fa-facebook</strong>. You can also drag and drop the boxes to rearrange the icons.', 'socials-ignited' ), 'http://fontawesome.io/icons/#brand' ), $allowed_tags ); ?></small>
		</p>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'socials-ignited' ); ?></label><input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat"/></p>
		<p><label for="<?php echo $this->get_field_id( 'color' ); ?>"><?php esc_html_e( 'Icon Color:', 'socials-ignited' ); ?></label><input id="<?php echo $this->get_field_id( 'color' ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>" type="text" value="<?php echo esc_attr( $color ); ?>" class="cisiw-colorpckr widefat"/></p>
		<p><label for="<?php echo $this->get_field_id( 'background_color' ); ?>"><?php _e( 'Icon Background Color:', 'socials-ignited' ); ?></label><input id="<?php echo $this->get_field_id( 'background_color' ); ?>" name="<?php echo $this->get_field_name( 'background_color' ); ?>" type="text" value="<?php echo esc_attr( $background_color ); ?>" class="cisiw-colorpckr widefat"/></p>
		<p><label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php esc_html_e( 'Icon Size (single integer in pixels):', 'socials-ignited' ); ?></label><input id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" type="number" value="<?php echo esc_attr( $size ); ?>" class="widefat"/></p>
		<p><label for="<?php echo $this->get_field_id( 'background_size' ); ?>"><?php esc_html_e( 'Background Size (single integer in pixels):', 'socials-ignited' ); ?></label><input id="<?php echo $this->get_field_id( 'background_size' ); ?>" name="<?php echo $this->get_field_name( 'background_size' ); ?>" type="number" value="<?php echo esc_attr( $background_size ); ?>" class="widefat"/></p>
		<p><label for="<?php echo $this->get_field_id( 'border_radius' ); ?>"><?php esc_html_e( 'Border Radius (single integer in pixels):', 'socials-ignited' ); ?></label><input id="<?php echo $this->get_field_id( 'border_radius' ); ?>" name="<?php echo $this->get_field_name( 'border_radius' ); ?>" type="number" value="<?php echo esc_attr( $border_radius ); ?>" class="widefat"/></p>
		<p><label for="<?php echo $this->get_field_id( 'border_color' ); ?>"><?php esc_html_e( 'Border Color:', 'socials-ignited' ); ?></label><input id="<?php echo $this->get_field_id( 'border_color' ); ?>" name="<?php echo $this->get_field_name( 'border_color' ); ?>" type="text" value="<?php echo esc_attr( $border_color ); ?>" class="cisiw-colorpckr widefat"/></p>
		<p><label for="<?php echo $this->get_field_id( 'border_width' ); ?>"><?php esc_html_e( 'Border Width (single integer in pixels):', 'socials-ignited' ); ?></label><input id="<?php echo $this->get_field_id( 'border_width' ); ?>" name="<?php echo $this->get_field_name( 'border_width' ); ?>" type="number" min="0" value="<?php echo esc_attr( $border_width ); ?>" class="widefat"/></p>
		<p><label for="<?php echo $this->get_field_id( 'opacity' ); ?>"><?php esc_html_e( 'Opacity (0.1 up to 1):', 'socials-ignited' ); ?></label><input id="<?php echo $this->get_field_id( 'opacity' ); ?>" name="<?php echo $this->get_field_name( 'opacity' ); ?>" type="number" min="0.1" max="1" step="0.1" value="<?php echo esc_attr( $opacity ); ?>" class="widefat"/></p>
		<p><label><input id="<?php echo $this->get_field_id( 'new_win' ); ?>" name="<?php echo $this->get_field_name( 'new_win' ); ?>" type="checkbox" value="on" <?php checked( 'on', $new_win ); ?> /> <?php esc_html_e( 'Open in new window.', 'socials-ignited' ); ?></label></p>
		<p><label><input id="<?php echo $this->get_field_id( 'nofollow' ); ?>" name="<?php echo $this->get_field_name( 'nofollow' ); ?>" type="checkbox" value="on" <?php checked( 'on', $nofollow ); ?> /> <?php echo wp_kses( __( 'Add <code>rel="nofollow"</code> to links.', 'socials-ignited' ), array( 'code' => array() ) ); ?></label></p>

		<span class="hid_id" data-hidden-name="<?php echo $this->get_field_name( 'icons' ); ?>"></span>

		<fieldset class="cisiw-repeating-fields">
			<div class="inner">
				<?php
					$icons = $this->convert_repeating_icons_from_unnamed( $icons );
					if ( ! empty( $icons ) ) {
						foreach ( $icons as $field ) {
							?>
							<div class="post-field">
								<label><?php esc_html_e( 'Icon Code:', 'socials-ignited' ); ?> <input type="text" name="<?php echo $this->get_field_name( 'icon_code' ); ?>[]" value="<?php echo esc_attr( $field['icon'] ); ?>" class="widefat"/></label>
								<label><?php esc_html_e( 'Link URL:', 'socials-ignited' ); ?> <input type="text" name="<?php echo $this->get_field_name( 'icon_url' ); ?>[]" value="<?php echo esc_url( $field['url'] ); ?>" class="widefat"/></label>
								<label><?php esc_html_e( 'Title text (optional):', 'socials-ignited' ); ?> <input type="text" name="<?php echo $this->get_field_name( 'icon_title' ); ?>[]" value="<?php echo esc_attr( $field['title'] ); ?>" class="widefat"/></label>
								<p class="cisiw-repeating-remove-action"><a href="#" class="button cisiw-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php esc_html_e( 'Remove me', 'ci_theme' ); ?></a></p>
							</div>
							<?php
						}
					}
				?>
				<div class="post-field field-prototype" style="display: none;">
					<label><?php esc_html_e( 'Icon Code:', 'socials-ignited' ); ?> <input type="text" name="<?php echo $this->get_field_name( 'icon_code' ); ?>[]" value="" class="widefat"/></label>
					<label><?php esc_html_e( 'Link URL:', 'socials-ignited' ); ?> <input type="text" name="<?php echo $this->get_field_name( 'icon_url' ); ?>[]" value="" class="widefat"/></label>
					<label><?php esc_html_e( 'Title text (optional):', 'socials-ignited' ); ?> <input type="text" name="<?php echo $this->get_field_name( 'icon_title' ); ?>[]" value="" class="widefat"/></label>
					<p class="cisiw-repeating-remove-action"><a href="#" class="button cisiw-repeating-remove-field"><i class="dashicons dashicons-dismiss"></i><?php esc_html_e( 'Remove me', 'ci_theme' ); ?></a></p>
				</div>
			</div>
			<a href="#" class="cisiw-repeating-add-field button"><i class="dashicons dashicons-plus-alt"></i><?php esc_html_e( 'Add Field', 'ci_theme' ); ?></a>
		</fieldset>
		<?php
	} // form

	function enqueue_css() {
		$settings = $this->get_settings();

		if ( empty( $settings ) ) {
			return;
		}

		foreach ( $settings as $instance_id => $instance ) {
			$id = $this->id_base . '-' . $instance_id;

			if ( ! is_active_widget( false, $id, $this->id_base ) ) {
				continue;
			}

			$color            = $instance['color'];
			$background_color = $instance['background_color'];
			$size             = $instance['size'];
			$background_size  = $instance['background_size'];
			$border_radius    = $instance['border_radius'];
			$border_color     = ! empty( $instance['border_color'] ) ? $instance['border_color'] : '';
			$border_width     = ! empty( $instance['border_width'] ) ? $instance['border_width'] : '';
			$opacity          = $instance['opacity'];

			$css          = '';
			$css_hover    = '';
			$widget_style = '';

			if ( ! empty( $color ) ) {
				$css .= 'color: ' . $color . '; ';
			}
			if ( ! empty( $background_color ) ) {
				$css .= 'background: ' . $background_color . '; ';
			}
			if ( ! empty( $size ) ) {
				$css .= 'font-size: ' . $size . 'px; ';
			}
			if ( ! empty( $background_size ) ) {
				$css .= 'width: ' . $background_size . 'px; ';
				$css .= 'height: ' . $background_size . 'px; ';
				$css .= 'line-height: ' . $background_size . 'px; ';
			}
			if ( ! empty( $border_radius ) ) {
				$css .= 'border-radius: ' . $border_radius . 'px; ';
			}
			if ( ! empty( $border_color ) ) {
				$css .= 'border-color: ' . $border_color . '; ';
			}
			if ( ! empty( $border_width ) ) {
				$css .= 'border-style: solid; ';
				$css .= 'border-width: ' . $border_width . 'px; ';
			}
			if ( ! empty( $opacity ) ) {
				$css .= 'opacity: ' . $opacity . '; ';
				if ( $opacity < 1 ) {
					$css_hover = '#' . $id . ' a:hover i { opacity: 1; }' . PHP_EOL;
				}
			}

			if ( ! empty( $css ) ) {
				$css          = '#' . $id . ' i { ' . $css . ' } ' . PHP_EOL;
				$widget_style = $css . $css_hover;
				wp_add_inline_style( 'socials-ignited', $widget_style );
			}

		}

	}

	function sanitize_repeating_icons( $POST_array ) {
		if ( empty( $POST_array ) || !is_array( $POST_array ) ) {
			return array();
		}

		$codes  = $POST_array['icon_code'];
		$urls   = $POST_array['icon_url'];
		$titles = $POST_array['icon_title'];

		$count = max(
			count( $codes ),
			count( $urls ),
			count( $titles )
		);

		$new_fields = array();

		$records_count = 0;

		for ( $i = 0; $i < $count; $i ++ ) {
			if ( empty( $codes[ $i ] ) && empty( $urls[ $i ] ) ) {
				continue;
			}

			$new_fields[ $records_count ]['icon']  = sanitize_key( $codes[ $i ] );
			$new_fields[ $records_count ]['url']   = esc_url_raw( $urls[ $i ] );
			$new_fields[ $records_count ]['title'] = sanitize_text_field( $titles[ $i ] );

			$records_count++;
		}
		return $new_fields;
	}

	function convert_repeating_icons_from_unnamed( $fields ) {

		// This array must match the order of the old numeric parameters, e.g. [0] will map to 'title', etc.
		$names = array( 'icon', 'url', 'title', 'reserved_field' );

		if ( ! is_array( $fields ) ) {
			return $fields;
		}

		$first_value = reset( $fields );

		if ( ! is_array( $first_value ) && ! empty( $fields ) ) {
			$new_fields = array();

			for ( $t = 0; $t < count( $fields ); $t += count( $names ) ) {
				$new_icon = array();

				for ( $tf = 0; $tf < count( $names ); $tf ++ ) {
					if ( isset( $fields[ $t + $tf ] ) ) {
						$new_icon[ $names[ $tf ] ] = $fields[ $t + $tf ];
					}
				}
				unset( $new_icon['reserved_field'] );
				$new_fields[] = $new_icon;
			}
			$fields = $new_fields;
		}

		return $fields;
	}

} // class

function SW_CI_SocialsIgnited_FontAwesome_Action() {
	register_widget( 'SW_Socials_Ignited_Widget' );
}

add_action( 'widgets_init', 'SW_CI_SocialsIgnited_FontAwesome_Action' );

endif; //class_exists






	
