<?php
function cs_theme_cost_calculator_input_box_shortcode($atts)
{
	extract(shortcode_atts(array(
		"id" => "input-box",
		"name" => "input-box",
		"label" => "",
		"default_value" => "",
		"type" => "text",
		"checked" => "1",
		"placeholder" => "",
		"top_margin" => "none",
		"el_class" => ""
	), $atts));
	
	$output = '<div class="cost-calculator-box clearfix' . ($type=="checkbox" ? ' float' : '') . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . ($el_class!="" ? ' ' . esc_attr($el_class) : '') . '">';
	if($type!="checkbox")
		$output .= '<label>' . $label . '</label>';
	$output .= '<input type="hidden" name="' . esc_attr($name) . '-label" value="' . esc_attr($label) . '">';
	if($type=="date")
		$output .= '<div class="datepicker-container"><span class="ui-icon template-arrow-dropdown"></span>';
	$output .= '<input id="' . esc_attr($id) . '" class="cost-slider-input big type-' . esc_attr($type) . '" name="' . esc_attr($name) . '" type="' . ($type=="date" ? "type-date" : esc_attr($type)) . '"' . ($type=="checkbox" && (int)$checked ? ' checked="checked"' : '') . ' value="' . ($type=="checkbox" && !(int)$checked ? 0 : esc_attr($default_value)) . '" data-value="' . esc_attr($default_value) . '" placeholder="' . esc_attr($placeholder) . '">
		' . ($type=="checkbox" ? '<label class="checkbox-label template-bullet" for="' . esc_attr($id) . '"><span class="checkbox-box"></span>' . $label . '</label>' : '');
	if($type=="date")
		$output .= '</div>';
	$output .= '</div>';
	return $output;
}
add_shortcode("cost_calculator_input_box", "cs_theme_cost_calculator_input_box_shortcode");
//visual composer
vc_map( array(
	"name" => __("Cost calculator input box", 'carservice'),
	"base" => "cost_calculator_input_box",
	"class" => "",
	"controls" => "full",
	"show_settings_on_create" => true,
	"icon" => "icon-wpb-layer-cost-calculator-input-box",
	"category" => __('Carservice', 'carservice'),
	"params" => array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Id", 'carservice'),
			"param_name" => "id",
			"value" => "input-box",
			"description" => __("Please provide unique id for each 'Cost calculator input box' on your page.", 'carservice')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Input name", 'carservice'),
			"param_name" => "name",
			"value" => "input-box"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Label", 'carservice'),
			"param_name" => "label",
			"value" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Default value", 'carservice'),
			"param_name" => "default_value",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", 'carservice'),
			"param_name" => "type",
			"value" => array(__("text", 'carservice') => "text", __("number", 'carservice') => "number", __("date", 'carservice') => "date", __("email", 'carservice') => "email", __("checkbox", 'carservice') => "checkbox")
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Is checked", 'carservice'),
			"param_name" => "checked",
			"value" => array(__("yes", 'carservice') => "1", __("no", 'carservice') => "0"),
			"dependency" => Array('element' => "type", 'value' => "checkbox")
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Placeholder", 'carservice'),
			"param_name" => "placeholder",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Top margin", 'carservice'),
			"param_name" => "top_margin",
			"value" => array(__("None", 'carservice') => "none",  __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section")
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'carservice' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'carservice' )
		)
	)
));
?>
