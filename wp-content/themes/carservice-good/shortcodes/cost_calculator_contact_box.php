<?php
function cs_theme_cost_calculator_contact_box_shortcode($atts)
{
	extract(shortcode_atts(array(
		"label" => __("CONTACT DETAILS", 'carservice'),
		"submit_label" => __("SUBMIT NOW", 'carservice'),
		"type" => "",
		"el_class" => ""
	), $atts));
	
	$output = '<div class="cost-calculator-box cost-calculator-contact clearfix' . (!empty($el_class) ? ' ' . esc_attr($el_class) : '') . '">
		<div class="vc_row wpb_row vc_inner">
			<label>' . $label . '</label>
		</div>
		<div class="vc_row wpb_row vc_inner margin-top-20">
			<div class="block">
				<input class="text_input" name="name" type="text" value="' . esc_html__("Your Name *", 'carservice') . '" placeholder="' . esc_html__("Your Name *", 'carservice') . '">
			</div>
			<div class="block">
				<input class="text_input" name="email" type="text" value="' . esc_html__("Your Email *", 'carservice') . '" placeholder="' . esc_html__("Your Email *", 'carservice') . '">
			</div>
			<div class="block">
				<input class="text_input" name="phone" type="text" value="' . esc_html__("Your Phone", 'carservice') . '" placeholder="' . esc_html__("Your Phone", 'carservice') . '">
			</div>
			<div class="block">
				<textarea class="margin-top-20" name="message" placeholder="' . esc_html__("Additional Questions or Comments", 'carservice') . '">' . __("Additional Questions or Comments", 'carservice') . '</textarea>
			</div>
		</div>
		<div class="vc_row wpb_row vc_inner margin-top-20">
			<input type="hidden" name="action" value="theme_cost_calculator_form">
			<input type="hidden" name="type" value="' . (!empty($type) ? esc_attr($type) : 'appointment') . '">
			<a class="more display-block submit-contact-form" href="#" title="' . esc_attr($submit_label) . '"><span>' . $submit_label . '</span></a>
		</div>
	</div>';
	
	return $output;
}
add_shortcode("cost_calculator_contact_box", "cs_theme_cost_calculator_contact_box_shortcode");
//visual composer
vc_map( array(
	"name" => __("Cost calculator contact box", 'carservice'),
	"base" => "cost_calculator_contact_box",
	"class" => "",
	"controls" => "full",
	"show_settings_on_create" => true,
	"icon" => "icon-wpb-layer-contact-form",
	"category" => __('Carservice', 'carservice'),
	"params" => array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Label", 'carservice'),
			"param_name" => "label",
			"value" => __("CONTACT DETAILS", 'carservice')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Submit label", 'carservice'),
			"param_name" => "submit_label",
			"value" => __("SUBMIT NOW", 'carservice')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Type", 'carservice'),
			"param_name" => "type",
			"value" => ""
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'carservice' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'carservice' )
		)
	)
));

//cost calculator form submit
function cs_theme_cost_calculator_form()
{
	ob_start();
	global $theme_options;

    $result = array();
	$result["isOk"] = true;
	
	if($_POST["name"]!="" && $_POST["name"]!=__("Your Name *", 'carservice') && $_POST["email"]!="" && $_POST["email"]!=__("Your Email *", 'carservice') && preg_match("#^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,12})$#", $_POST["email"]))
	{
		$values = array(
			"name" => $_POST["name"],
			"phone" => ($_POST["phone"]!=__("Your Phone", 'carservice') ? $_POST["phone"] : ""),
			"email" => $_POST["email"],
			"message" => $_POST["message"]
		);
		if((bool)ini_get("magic_quotes_gpc")) 
			$values = array_map("stripslashes", $values);
		$values = array_map("htmlspecialchars", $values);
		
		$form_data = "";
		foreach($_POST as $key=>$value)
		{
			if(array_key_exists($key . "-label", $_POST))
			{
				if(array_key_exists($key . "-name", $_POST))
				{
					if(!empty($value))
						$form_data .= "<br>" . $_POST[$key . "-label"] . " " . $_POST[$key . "-name"] . " (" . $value . ")";
				}
				else
				{
					if(!empty($value))
						$form_data .= "<br>" . $_POST[$key . "-label"] . " " . $value;
				}
			}
		}
		if(!empty($_POST["total_cost"]))
			$form_data .= "<br>" . __("Total cost: ", 'carservice') . $_POST["total_cost"];
			
		$headers[] = 'Reply-To: ' . $values["name"] . ' <' . $values["email"] . '>' . "\r\n";
		$headers[] = 'From: ' . $theme_options["cf_admin_name"] . ' <' . $theme_options["cf_admin_email"] . '>' . "\r\n";
		$headers[] = 'Content-type: text/html';
		$subject = $theme_options["cf_email_subject"];
		$subject = str_replace("[name]", $values["name"], $subject);
		$subject = str_replace("[email]", $values["email"], $subject);
		$subject = str_replace("[phone]", $values["phone"], $subject);
		$subject = str_replace("[message]", $values["message"], $subject);
		$body = $theme_options["cf_template"];
		$body = str_replace("[name]", $values["name"], $body);
		$body = str_replace("[email]", $values["email"], $body);
		$body = str_replace("[phone]", $values["phone"], $body);
		$body = str_replace("[message]", $values["message"], $body);
		$body = str_replace("[form_data]", $form_data, $body);

		if(wp_mail($theme_options["cf_admin_name"] . ' <' . $theme_options["cf_admin_email"] . '>', $subject, $body, $headers))
			$result["submit_message"] = __("Thank you for contacting us", 'carservice');
		else
		{
			$result["isOk"] = false;
			$result["error_message"] = $GLOBALS['phpmailer']->ErrorInfo;
			$result["submit_message"] = __("Sorry, we can't send this message", 'carservice');
		}
	}
	else
	{
		$result["isOk"] = false;
		if($_POST["name"]=="" || $_POST["name"]==__("Your Name *", 'carservice'))
			$result["error_name"] = __("Please enter your name.", 'carservice');
		if($_POST["email"]=="" || $_POST["email"]==__("Your Email *", 'carservice') || !preg_match("#^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,12})$#", $_POST["email"]))
			$result["error_email"] = __("Please enter valid e-mail.", 'carservice');
	}
	$system_message = ob_get_clean();
	$result["system_message"] = $system_message;
	echo @json_encode($result);
	exit();
}
add_action("wp_ajax_theme_cost_calculator_form", "cs_theme_cost_calculator_form");
add_action("wp_ajax_nopriv_theme_cost_calculator_form", "cs_theme_cost_calculator_form");
?>
