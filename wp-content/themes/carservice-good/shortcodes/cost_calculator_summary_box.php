<?php
function cs_theme_cost_calculator_summary_box_shortcode($atts)
{
	extract(shortcode_atts(array(
		"id" => "cost",
		"formula" => "",
		"icon" => "none",
		"currency" => "$",
		"currency_position" => "before",
		"thousandth_separator" => ",",
		"decimal_separator" => ".",
		"description" => __("Approximate Cost", 'carservice')
	), $atts));
	
	$output = '<div class="cost-calculator-box cost-calculator-sum clearfix' . (!empty($icon) && $icon!="none" ? ' sl-small-' . esc_attr($icon) : '') . '">
		<span class="cost-calculator-price" id="' . esc_attr($id) . '">' . ($currency_position=="before" ? $currency : '') . '0.00' . ($currency_position=="after" ? $currency : '') . '</span>';
	if(!empty($description))
		$output .= '<p>' . $description . '</p>
	<input type="hidden" id="' . esc_attr($id) . '-total" name="total_cost" value="' . ($currency_position=="before" ? $currency : '') . '0.00' . ($currency_position=="after" ? $currency : '') . '">
	</div>';
	$output .= '<script type="text/javascript">
	jQuery(document).ready(function($){
		$("#' . esc_attr($id) . '").costCalculator({
			formula: "' . $formula . '",
			currency: "' . $currency . '",
			currencyPosition: "' . $currency_position . '",
			thousandthSeparator: "' . $thousandth_separator . '",
			decimalSeparator: "' . $decimal_separator . '",
			updateHidden: $("#' . esc_attr($id) . '-total")
		});
	});
	</script>';
	return $output;
}
add_shortcode("cost_calculator_summary_box", "cs_theme_cost_calculator_summary_box_shortcode");
//visual composer
vc_map( array(
	"name" => __("Cost calculator summary box", 'carservice'),
	"base" => "cost_calculator_summary_box",
	"class" => "",
	"controls" => "full",
	"show_settings_on_create" => true,
	"icon" => "icon-wpb-layer-cost-calculator-summary-box",
	"category" => __('Carservice', 'carservice'),
	"params" => array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Id", 'carservice'),
			"param_name" => "id",
			"value" => "cost",
			"description" => __("Please provide unique id for each 'Cost calculator summary box' on your page.", 'carservice')
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Summary formula", 'carservice'),
			"param_name" => "formula",
			"value" => "",
			"description" => __("Please put here the calculate formula for your form using the form elements ids. Available operators: + and *. Example: square-feet*walls+square-feet*floors", 'carservice')
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Icon", 'carservice'),
			"param_name" => "icon",
			"value" => array(
				__("none", 'carservice') => "none",
				__("bricks", 'carservice') => "bricks",
				__("briefcase", 'carservice') => "briefcase",
				__("brush-1", 'carservice') => "brush-1",
				__("brush-2", 'carservice') => "brush-2",
				__("bubble", 'carservice') => "bubble",
				__("bubble check", 'carservice') => "bubble-check",
				__("bucket", 'carservice') => "bucket",
				__("building", 'carservice') => "building",
				__("calculator", 'carservice') => "calculator",
				__("camera", 'carservice') => "camera",
				__("cart-1", 'carservice') => "cart-1",
				__("cart-2", 'carservice') => "cart-2",
				__("chat", 'carservice') => "chat",
				__("clock", 'carservice') => "clock",
				__("cone", 'carservice') => "cone",
				__("construction", 'carservice') => "construction",
				__("conversation", 'carservice') => "conversation",
				__("lab", 'carservice') => "documents",
				__("door", 'carservice') => "door",
				__("driller", 'carservice') => "driller",
				__("eco", 'carservice') => "eco",
				__("faq", 'carservice') => "faq",
				__("fax", 'carservice') => "fax",
				__("fence", 'carservice') => "fence",
				__("forklift", 'carservice') => "forklift",
				__("garage", 'carservice') => "garage",
				__("gears", 'carservice') => "gears",
				__("globe", 'carservice') => "globe",
				__("hammer", 'carservice') => "hammer",
				__("helmet", 'carservice') => "helmet",
				__("house-1", 'carservice') => "house-1",
				__("house-2", 'carservice') => "house-2",
				__("key", 'carservice') => "key",
				__("documents", 'carservice') => "lab",
				__("lightbulb", 'carservice') => "lightbulb",
				__("list", 'carservice') => "list",
				__("location", 'carservice') => "location",
				__("lock", 'carservice') => "lock",
				__("mail", 'carservice') => "mail",
				__("measure", 'carservice') => "measure",
				__("megaphone", 'carservice') => "megaphone",
				__("payment", 'carservice') => "payment",
				__("pencil", 'carservice') => "pencil",
				__("percent", 'carservice') => "percent",
				__("person", 'carservice') => "person",
				__("phone", 'carservice') => "phone",
				__("photo", 'carservice') => "photo",
				__("picture", 'carservice') => "picture",
				__("plan", 'carservice') => "plan",
				__("poster", 'carservice') => "poster",
				__("quote", 'carservice') => "quote",
				__("roller", 'carservice') => "roller",
				__("ruler", 'carservice') => "ruler",
				__("scissors", 'carservice') => "scissors",
				__("screwdriver", 'carservice') => "screwdriver",
				__("shield", 'carservice') => "shield",
				__("shovel", 'carservice') => "shovel",
				__("speaker", 'carservice') => "speaker",
				__("stationery", 'carservice') => "stationery",
				__("team", 'carservice') => "team",
				__("tick", 'carservice') => "tick",
				__("trolley", 'carservice') => "trolley",
				__("trophy", 'carservice') => "trophy",
				__("trowel", 'carservice') => "trowel",
				__("truck", 'carservice') => "truck",
				__("video", 'carservice') => "video",
				__("wallet", 'carservice') => "wallet",
				__("watering-can", 'carservice') => "watering-can",
				__("wrench", 'carservice') => "wrench",
				__("wrenches", 'carservice') => "wrenches")
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Currency", 'carservice'),
			"param_name" => "currency",
			"value" => "$"
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Currency sign position", 'carservice'),
			"param_name" => "currency_position",
			"value" => array(__("before value", 'carservice') => 'before', __("after value", 'carservice') => 'after')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Thousandth separator", 'carservice'),
			"param_name" => "thousandth_separator",
			"value" => ","
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Decimal separator", 'carservice'),
			"param_name" => "decimal_separator",
			"value" => "."
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Description", 'carservice'),
			"param_name" => "description",
			"value" => __("Approximate Cost", 'carservice')
		)
	)
));
?>
