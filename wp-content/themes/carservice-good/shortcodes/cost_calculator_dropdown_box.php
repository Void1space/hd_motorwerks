<?php
function cs_theme_cost_calculator_dropdown_box_shortcode($atts)
{
	extract(shortcode_atts(array(
		"id" => "dropdown-box",
		"name" => "dropdown-box",
		"label" => "",
		"options_count" => 1,
		"default_value" => "",
		"show_choose_label" => 1,
		"choose_label" => __("Choose...", 'carservice'),
		"top_margin" => "none"
	), $atts));
	
	$output = '<div class="cost-calculator-box clearfix' . ($top_margin!="none" ? ' ' . esc_attr($top_margin) : '') . '">
		<label>' . $label . '</label>
		<input type="hidden" name="' . esc_attr($name) . '-label" value="' . esc_attr($label) . '">
		<select name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" class="cost-dropdown">';
	if((int)$show_choose_label)
		$output .= '<option value=""' . (empty($default_value) ? ' selected="selected"' : '') . '>' . $choose_label . '</option>';
	for($i=0; $i<$options_count; $i++)
		$output .= '<option value="' . (!empty($atts["option_value" . $i]) ? esc_attr($atts["option_value" . $i]) : "") . '"' . (!empty($default_value) && $atts["option_value" . $i]==$default_value ? ' selected="selected"' : '') . '>' . (!empty($atts["option_name" . $i]) ? $atts["option_name" . $i] : '') . '</option>';
	$output .= '</select>';
	$output .= '<input type="hidden" class="' . esc_attr($id) . '" name="' . esc_attr($name) . '-name" value="">
	</div>';
	return $output;
}
add_shortcode("cost_calculator_dropdown_box", "cs_theme_cost_calculator_dropdown_box_shortcode");
//visual composer
$count = array();
for($i=1; $i<=30; $i++)
	$count[$i] = $i;
	
$params = array(
	array(
		"type" => "textfield",
		"holder" => "div",
		"class" => "",
		"heading" => __("Id", 'carservice'),
		"param_name" => "id",
		"value" => "dropdown-box",
		"description" => __("Please provide unique id for each 'Cost calculator dropdown box' on your page.", 'carservice')
	),
	array(
		"type" => "textfield",
		"class" => "",
		"heading" => __("Input name", 'carservice'),
		"param_name" => "name",
		"value" => "dropdown-box"
	),
	array(
		"type" => "textfield",
		"class" => "",
		"heading" => __("Label", 'carservice'),
		"param_name" => "label",
		"value" => ""
	),
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Number of options", 'carservice'),
		"param_name" => "options_count",
		"value" => $count
	)
);
for($i=0; $i<30; $i++)
{
	$params[] = array(
		"type" => "textfield",
		"edit_field_class" => "vc_col-sm-12 vc_column" . ($i>0 ? " wpb_el_type_hidden" : ""),
		"heading" => __("Option name", 'carservice') . " " . ($i+1),
		"param_name" => "option_name" . $i,
		"value" => ""
	);
	$params[] = array(
		"type" => "textfield",
		"edit_field_class" => "vc_col-sm-12 vc_column" . ($i>0 ? " wpb_el_type_hidden" : ""),
		"heading" => __("Option value", 'carservice') . " " . ($i+1),
		"param_name" => "option_value" . $i,
		"value" => ""
	);
}
$params = array_merge($params, array(
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
		"heading" => __("Show 'choose' label", 'carservice'),
		"param_name" => "show_choose_label",
		"value" => array(__("Yes", 'carservice') => 1, __("No", 'carservice') => 0)
	),
	array(
		"type" => "textfield",
		"class" => "",
		"heading" => __("Choose label", 'carservice'),
		"param_name" => "choose_label",
		"value" => __("Choose...", 'carservice'),
		"dependency" => Array('element' => "show_choose_label", 'value' => "1")
	),
	array(
		"type" => "dropdown",
		"class" => "",
		"heading" => __("Top margin", 'carservice'),
		"param_name" => "top_margin",
		"value" => array(__("None", 'carservice') => "none",  __("Page (small)", 'carservice') => "page-margin-top", __("Section (large)", 'carservice') => "page-margin-top-section")
	)
));
vc_map( array(
	"name" => __("Cost calculator dropdown box", 'carservice'),
	"base" => "cost_calculator_dropdown_box",
	"class" => "",
	"controls" => "full",
	"show_settings_on_create" => true,
	'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/cost_calculator_dropdown_box.js'),
	"icon" => "icon-wpb-layer-cost-calculator-dropdown-box",
	"category" => __('Carservice', 'carservice'),
	"params" => $params
));
?>
