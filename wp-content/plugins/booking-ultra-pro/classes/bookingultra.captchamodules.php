<?php
class BookingUltraCaptchaModule
{
    public $load_captcha = false;
    private $captcha_plugin = '';
    public $default_captcha_plugin = 'recaptcha';
    
    public function __construct()
    {
        // Nothing to do here.    
    }
    
    private function load_captcha_plugin_setting($captcha= '')
    {
        // Getting values from database
        $settings = get_option('bup_options');
        
        // Shortcode optionis not given or given blank
        if($captcha == '')
        {
            if(isset($settings['captcha_plugin']) && $settings['captcha_plugin'] != '' && $settings['captcha_plugin'] != 'none')
            {
                $this->load_captcha = true;
                $this->captcha_plugin = $settings['captcha_plugin']; 
            }
            else
            {
                $this->load_captcha = false;
            }
            
        }
        else if($captcha == 'no' || $captcha == 'false')
        {
            $this->load_captcha = false;
        }
        else 
        {
            if($captcha == 'yes' || $captcha == 'true')
            {
                if(isset($settings['captcha_plugin']) && $settings['captcha_plugin'] != '' && $settings['captcha_plugin'] != 'none')
                {
                    $this->load_captcha = true;
                    $this->captcha_plugin = $settings['captcha_plugin']; 
                }
                else
                {
                    $this->load_captcha = false;
                }
            }
            else
            {
                $this->load_captcha = true;
                $this->captcha_plugin = $captcha;
            }
                
        }
        
    }
    
    public function load_captcha($captcha= '')
    {
        global $bookingultrapro;
        
        // Load captcha plugin settings based on shortcode and database values.
        $this->load_captcha_plugin_setting($captcha);
        
        if($this->load_captcha == true)
        {
            $method_name = 'load_'.$this->captcha_plugin;
            
            if(method_exists($this, $method_name))
            {
                $captcha_html = '';
                $captcha_html = $this->$method_name();
                
                if($captcha_html == '')
                {
                    return $this->load_no_captcha_html();
                }
                else
                {
                    $form_text = '';
                    $form_text = stripslashes( $bookingultrapro->get_option('captcha_label'));
                    
					if($form_text == ''){
                        $form_text = __('Human Check','booking-ultra-pro');}
						
						
					//heading text					
					$heading_text = '';
                    $heading_text = stripslashes($bookingultrapro->get_option('captcha_heading'));
                    
					if($heading_text == ''){
                        $heading_text = __("Prove you're not a robot",'booking-ultra-pro');}
					
                    
                    $display = '';    
										
					$display .= '<div class="bup-profile-separator ">'.$heading_text.'</div>';
					
					
					$display .= '<div class="bup-field ">';
					              					
					
					$display .= '<label class="bup-field-type" for="'.$meta.'">';			
					$display .= '<span>'.$form_text.' </span></label>';
					
					$display .= '<div class="bup-field-value">';
					$display .= $captcha_html;						
					$display .='<input type="hidden" name="captcha_plugin" value="'.$this->captcha_plugin.'" />';					
					$display .= '</div>';	
					
					
					$display .= '</div>';					
					
                    
                    return $display;
                }
            }
            else
            {
                return $this->load_no_captcha_html();
            }
        }
        else
        {
            return $this->load_no_captcha_html();
        }
    }
    
    public function load_no_captcha_html()
    {
        return '<input type="hidden" name="no_captcha" value="yes" />';
    }
    
    public function validate_captcha($captcha_plugin = '')
    {
		//echo "captcha module set: " .$captcha_plugin ;
        if($captcha_plugin == '')
        {
            // No plugin set, returning true
            return true; 
        }
        else
        {
            $method_name = 'validate_'.$captcha_plugin;
            
            if(method_exists($this, $method_name))
            {
			//	echo "captcha module set: " .$captcha_plugin ." ". $this->$method_name() ;
                return $this->$method_name();
            }
            else
            {
                return true;
            }
            
        }
    }
    
        
    
   /*
    *  Function to Load ReCaptcha
    */
    
    private function load_recaptcha_class()
    {
        
        require_once bookingup_path . 'classes/bookingultra.recaptchalib.php';
    } 
    
    private function load_recaptcha()
    {
        global $bookingultrapro;
        $this->load_recaptcha_class();
        
        // Getting the Public Key to load reCaptcha
        $public_key = '';
        $public_key = $bookingultrapro->get_option('recaptcha_public_key');
        
        if($public_key != '')
        {
            $captcha_code = '';
            
            $recaptcha_theme = 'bookingup';
            
            if($recaptcha_theme == 'booking-ultra-pro')
            {
                $theme_code = "<script type=\"text/javascript\"> var RecaptchaOptions = {    theme : 'custom',lang: 'en',    custom_theme_widget: 'recaptcha_widget' };</script>";
                $captcha_code = $this->load_custom_recaptcha($public_key);
            }
            else
            {
                $theme_code = "<script type=\"text/javascript\">var RecaptchaOptions = {theme : '".$recaptcha_theme."', lang:'en'};</script>";
                $captcha_code = recaptcha_get_html($public_key, null);
            }
            
            return $theme_code.$captcha_code;
        }
        else
        {
            // No public key is not set in admin. So loading no captcha HTML. 
            return $this->load_no_captcha_html();
        }
        
    }
    
   /*
    *  Function to Validate ReCaptcha
    */
    
    private function validate_recaptcha()
    {
        global $bookingultrapro;
        $this->load_recaptcha_class();
        
        // Getting the Private Key to validate reCaptcha
        $private_key = '';
        $private_key = $bookingultrapro->get_option('recaptcha_private_key');
        
        
        if($private_key != '')
        {
            if (is_in_post('recaptcha_response_field'))
            {
                $resp = recaptcha_check_answer ($private_key,
                        $_SERVER["REMOTE_ADDR"],
                        post_value("recaptcha_challenge_field"),
                        post_value("recaptcha_response_field"));
            
                // Captcha is Valid
                if ($resp->is_valid)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true;
            }    
        }
        else
        {
            // Private key is not set in admin
            return true;
        }
    }
    
    private function load_custom_recaptcha($public_key='')
    {
		
		// if site is set to run on SSL, then force-enable SSL detection!
		if (stripos(get_option('siteurl'), 'https://') === 0)
		 {
			$_SERVER['HTTPS'] = 'on';
		}
  	
		
		
		if (is_ssl()) 
		{
			$url = "https://www.google.com/recaptcha/api";
			  			
  		}else{
			
			$url = "http://www.google.com/recaptcha/api";	
		
		}
  


        return '<div id="recaptcha_widget">
                        <div id="recaptcha_image_holder">
                            <div id="recaptcha_image" class="uultra-captcha-img"></div>
                            <div class="recaptcha_text_box">
                                <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" class="text" placeholder=" '.__("Enter Verification Words",'booking-ultra-pro').'" />
                            </div>
                        </div>
                        <div id="recaptcha_control_holder">
                            <a href="javascript:Recaptcha.switch_type(\'image\');" title="'.__("Load Image",'booking-ultra-pro').'"><i class="fa fa-camera"></i></a>
                            <a href="javascript:Recaptcha.switch_type(\'audio\');" title="'.__("Load Audio",'booking-ultra-pro').'"><i class="fa fa-volume-up"></i></a>
                            <a href="javascript:void(0);" id="recaptcha_reload_btn" onclick="Recaptcha.reload();" title="'.__("Refresh Image",'booking-ultra-pro').'"><i class="fa fa-refresh"></i></a>
                        </div> 
                </div>

                 <script type="text/javascript" src="'.$url.'/challenge?k='.$public_key.'"></script>
				 
                 <noscript>
                   <iframe src="'.$url.'/noscript?k='.$public_key.'" height="300" width="500" frameborder="0"></iframe>
                   <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                   <input type="hidden" name="recaptcha_response_field" value="manual_challenge">
                 </noscript>';
    }
    
    
}
$key = "captchamodule";
$this->{$key} = new BookingUltraCaptchaModule();