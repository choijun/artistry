<?php
/**
 * Fields.
 *
 * @package ConvertPro
 */

// Add new input type "textfield".
if ( function_exists( 'cp_add_input_type' ) ) {
	cp_add_input_type( 'cp_textarea', 'cp_v2_textarea_field_settings_action' );
}

/**
 * Function Name: cp_v2_label_settings_field.
 * Function Description: Function to handle new input type.
 *
 * @param string $name string parameter.
 * @param string $settings string parameter.
 * @param string $sections string parameter.
 * @param string $value string parameter.
 * @param string $default_value string parameter.
 */
function cp_v2_textarea_field_settings_action( $name, $settings, $sections, $value, $default_value ) {
	$data_json = array(
		'id'         => $name,
		'title'      => $settings['title'],
		'sections'   => $sections,
		'resize'     => $settings['resize'],
		'has_editor' => isset( $settings['editor'] ) ? true : false,
	);

	$data = json_encode( $data_json );

	$input_name = $name;

	$output  = "<div class='fields-panel'>";
	$output .= "<div class='cp-droppable-item list-group-item draggable' data-title='" . $settings['title'] . "' data-type='cp_textarea' data-value='" . $settings['value'] . "' data-json='" . $data . "' data-resize='" . $settings['resize'] . "'>
        <div class='cp-panel-content-icon'><i class='dashicons dashicons-text'></i></div>
        <div class='cp-element-title-wrapper'>
           <span class='cp-element-title'>" . __( 'Text Area', 'convertpro' ) . "</span>
        </div>
        <textarea style='display:none;' class='cp-customizer-target cp-textarea-field' placeholder='" . __( 'Enter Message Here', 'convertpro' ) . "' value=''></textarea>
    </div>";
	$output .= '</div>';

	return $output;
}
