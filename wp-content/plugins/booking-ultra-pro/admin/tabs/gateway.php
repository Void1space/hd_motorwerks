<?php 
global $bookingultrapro,   $bupcomplement;
?>
<h3><?php _e('Payment Gateways Settings','booking-ultra-pro'); ?></h3>
<form method="post" action="">
<input type="hidden" name="update_settings" />


<?php if(isset($bupcomplement))
{?>
<div class="bup-sect ">
  <h3><?php _e('Stripe Settings','booking-ultra-pro'); ?></h3>
  
  <p><?php _e("Stripe is a payment gateway for mechants. If you don't have a Stripe account, you can <a href='https://stripe.com/'> sign up for one account here</a> ",'booking-ultra-pro'); ?></p>
  
  <p><?php _e('Here you can configure Stripe if you wish to accept credit card payments directly in your website. Find your Stripe API keys here <a href="https://dashboard.stripe.com/account/apikeys">https://dashboard.stripe.com/account/apikeys</a>','booking-ultra-pro'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_stripe_active',
                __('Activate Stripe','booking-ultra-pro'),
                '1',
                __('If checked, Stripe will be activated as payment method','booking-ultra-pro'),
                __('If checked, Stripe will be activated as payment method','booking-ultra-pro')
        ); 


$this->create_plugin_setting(
        'input',
        'test_secret_key',
        __('Test Secret Key','booking-ultra-pro'),array(),
        __('You can get this on stripe.com','booking-ultra-pro'),
        __('You can get this on stripe.com','booking-ultra-pro')
);

$this->create_plugin_setting(
        'input',
        'test_publish_key',
        __('Test Publishable Key','booking-ultra-pro'),array(),
        __('You can get this on stripe.com','booking-ultra-pro'),
        __('You can get this on stripe.com','booking-ultra-pro')
);

$this->create_plugin_setting(
        'input',
        'live_secret_key',
        __('Live Secret Key','booking-ultra-pro'),array(),
        __('You can get this on stripe.com','booking-ultra-pro'),
        __('You can get this on stripe.com','booking-ultra-pro')
);

$this->create_plugin_setting(
        'input',
        'live_publish_key',
        __('Live Publishable Key','booking-ultra-pro'),array(),
        __('You can get this on stripe.com','booking-ultra-pro'),
        __('You can get this on stripe.com','booking-ultra-pro')
);


$this->create_plugin_setting(
        'input',
        'gateway_stripe_currency',
        __('Currency','booking-ultra-pro'),array(),
        __('Please enter the currency, example USD.','booking-ultra-pro'),
        __('Please enter the currency, example USD.','booking-ultra-pro')
);

$this->create_plugin_setting(
        'textarea',
        'gateway_stripe_success_message',
        __('Custom Message','booking-ultra-pro'),array(),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','booking-ultra-pro'),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','booking-ultra-pro')
);

$this->create_plugin_setting(
                'checkbox',
                'gateway_stripe_success_active',
                __('Custom Success Page Redirect ','booking-ultra-pro'),
                '1',
                __('If checked, the users will be taken to this page once the payment has been confirmed','booking-ultra-pro'),
                __('If checked, the users will be taken to this page once the payment has been confirmed','booking-ultra-pro')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_stripe_success',
            __('Success Page','booking-ultra-pro'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page if the payment was approved by stripe.",'booking-ultra-pro'),
            __('Select the sucess page. The user will be taken to this page if the payment was approved by stripe.','booking-ultra-pro')
    );


$this->create_plugin_setting(
	'select',
	'enable_live_key',
	__('Mode','booking-ultra-pro'),
	array(
		1 => __('Production Mode','booking-ultra-pro'), 
		2 => __('Test Mode (Sandbox)','booking-ultra-pro')
		),
		
	__('.','booking-ultra-pro'),
  __('.','booking-ultra-pro')
       );
	   



		
?>
</table>

  
</div>

<?php }?>


<?php if(isset($bupcomplement))
{?>
<div class="bup-sect " style="display:none">
  <h3><?php _e('Authorize.NET AIM Settings','booking-ultra-pro'); ?></h3>
  
  <p><?php _e(" ",'booking-ultra-pro'); ?></p>
  
  <p><?php _e(' ','booking-ultra-pro'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_authorize_active',
                __('Activate Authorize','booking-ultra-pro'),
                '1',
                __('If checked, Authorize will be activated as payment method','booking-ultra-pro'),
                __('If checked, Authorize will be activated as payment method','booking-ultra-pro')
        ); 



$this->create_plugin_setting(
        'input',
        'authorize_login',
        __('API Login ID','booking-ultra-pro'),array(),
        __('You can get this on authorize.net','booking-ultra-pro'),
        __('You can get this on authorize.net','booking-ultra-pro')
);

$this->create_plugin_setting(
        'input',
        'authorize_key',
        __('API Transaction Key','booking-ultra-pro'),array(),
        __('You can get this on authorize.net','booking-ultra-pro'),
        __('You can get this on authorize.net','booking-ultra-pro')
);


$this->create_plugin_setting(
        'input',
        'authorize_currency',
        __('Currency','booking-ultra-pro'),array(),
        __('Please enter the currency, example USD.','booking-ultra-pro'),
        __('Please enter the currency, example USD.','booking-ultra-pro')
);

$this->create_plugin_setting(
        'textarea',
        'gateway_authorize_success_message',
        __('Custom Message','booking-ultra-pro'),array(),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','booking-ultra-pro'),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','booking-ultra-pro')
);

$this->create_plugin_setting(
                'checkbox',
                'gateway_authorize_success_active',
                __('Custom Success Page Redirect ','booking-ultra-pro'),
                '1',
                __('If checked, the users will be taken to this page once the payment has been confirmed','booking-ultra-pro'),
                __('If checked, the users will be taken to this page once the payment has been confirmed','booking-ultra-pro')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_authorize_success',
            __('Success Page','booking-ultra-pro'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page if the payment was approved by Authorize.net ",'booking-ultra-pro'),
            __('Select the sucess page. The user will be taken to this page if the payment was approved by Authorize.net','booking-ultra-pro')
    );


$this->create_plugin_setting(
	'select',
	'authorize_mode',
	__('Mode','booking-ultra-pro'),
	array(
		1 => __('Production Mode','booking-ultra-pro'), 
		2 => __('Test Mode (Sandbox)','booking-ultra-pro')
		),
		
	__('.','booking-ultra-pro'),
  __('.','booking-ultra-pro')
       );
	   



		
?>
</table>

  
</div>

<?php }?>

<div class="bup-sect ">
  <h3><?php _e('PayPal','booking-ultra-pro'); ?></h3>
  
  <p><?php _e('Here you can configure PayPal if you wish to accept paid registrations','booking-ultra-pro'); ?></p>
    <p><?php _e("Please note: You have to set a right currency <a href='https://developer.paypal.com/docs/classic/api/currency_codes/' target='_blank'> check supported currencies here </a> ",'booking-ultra-pro'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_paypal_active',
                __('Activate PayPal','booking-ultra-pro'),
                '1',
                __('If checked, PayPal will be activated as payment method','booking-ultra-pro'),
                __('If checked, PayPal will be activated as payment method','booking-ultra-pro')
        ); 

$this->create_plugin_setting(
	'select',
	'uultra_send_ipn_to_admin',
	__('The Paypal IPN response will be sent to the admin','booking-ultra-pro'),
	array(
		'no' => __('No','booking-ultra-pro'), 
		'yes' => __('Yes','booking-ultra-pro'),
		),
		
	__("If 'yes' the admin will receive the whole Paypal IPN response. This helps to troubleshoot issues.",'booking-ultra-pro'),
  __("If 'yes' the admin will receive the whole Paypal IPN response. This helps to troubleshoot issues.",'booking-ultra-pro')
       );

$this->create_plugin_setting(
        'input',
        'gateway_paypal_email',
        __('PayPal Email Address','booking-ultra-pro'),array(),
        __('Enter email address associated to your PayPal account.','booking-ultra-pro'),
        __('Enter email address associated to your PayPal account.','booking-ultra-pro')
);

$this->create_plugin_setting(
        'input',
        'gateway_paypal_sandbox_email',
        __('Paypal Sandbox Email Address','booking-ultra-pro'),array(),
        __('This is not used for production, you can use this email for testing.','booking-ultra-pro'),
        __('This is not used for production, you can use this email for testing.','booking-ultra-pro')
);

$this->create_plugin_setting(
        'input',
        'gateway_paypal_currency',
        __('Currency','booking-ultra-pro'),array(),
        __('Please enter the currency, example USD.','booking-ultra-pro'),
        __('Please enter the currency, example USD.','booking-ultra-pro')
);


$this->create_plugin_setting(
                'checkbox',
                'gateway_paypal_success_active',
                __('Custom Success Page Redirect ','booking-ultra-pro'),
                '1',
                __('If checked, the users will be taken to this page once the payment has been confirmed','booking-ultra-pro'),
                __('If checked, the users will be taken to this page once the payment has been confirmed','booking-ultra-pro')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_paypal_success',
            __('Success Page','booking-ultra-pro'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page if the payment was approved by stripe.",'booking-ultra-pro'),
            __('Select the sucess page. The user will be taken to this page if the payment was approved by stripe.','booking-ultra-pro')
    );
	
	
	$this->create_plugin_setting(
                'checkbox',
                'gateway_paypal_cancel_active',
                __('Custom Cancellation Page Redirect ','booking-ultra-pro'),
                '1',
                __('If checked, the users will be taken to this page if the payment is cancelled at PayPal website','booking-ultra-pro'),
                __('If checked, the users will be taken to this page if the payment is cancelled at PayPal website','booking-ultra-pro')
        ); 
		
		
		$this->create_plugin_setting(
            'select',
            'gateway_paypal_cancel',
            __('Cancellation Page','booking-ultra-pro'),
            $this->get_all_sytem_pages(),
            __("Select the cancellation page. The user will be taken to this page if the payment is cancelled at PayPal Website",'booking-ultra-pro'),
            __('Select the cancellation page. The user will be taken to this page if the payment is cancelled at PayPal Website','booking-ultra-pro')
    );


$this->create_plugin_setting(
	'select',
	'gateway_paypal_mode',
	__('Mode','booking-ultra-pro'),
	array(
		1 => __('Production Mode','booking-ultra-pro'), 
		2 => __('Test Mode (Sandbox)','booking-ultra-pro')
		),
		
	__('.','booking-ultra-pro'),
  __('.','booking-ultra-pro')
       );
	   





		
?>
</table>

  
</div>


<div class="bup-sect ">
  <h3><?php _e('Bank Deposit/Cash Other','booking-ultra-pro'); ?></h3>
  
  <p><?php _e('Here you can configure the information that will be sent to the client. This could be your bank account details.','booking-ultra-pro'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_bank_active',
                __('Activate Bank Deposit','booking-ultra-pro'),
                '1',
                __('If checked, Bank Payment Deposit will be activated as payment method','booking-ultra-pro'),
                __('If checked, Bank Payment Deposit will be activated as payment method','booking-ultra-pro')
        ); 


$this->create_plugin_setting(
        'input',
        'gateway_bank_label',
        __('Custom Label','booking-ultra-pro'),array(),
        __('Example: Bank Deposit , Cash, Wire etc.','booking-ultra-pro'),
        __('Example: Bank Deposit , Cash, Wire etc.','booking-ultra-pro')
);


$this->create_plugin_setting(
        'textarea',
        'gateway_bank_success_message',
        __('Custom Message','booking-ultra-pro'),array(),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','booking-ultra-pro'),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','booking-ultra-pro')
);



$this->create_plugin_setting(
                'checkbox',
                'gateway_bank_success_active',
                __('Custom Success Page Redirect ','booking-ultra-pro'),
                '1',
                __('If checked, the users will be taken to this page ','booking-ultra-pro'),
                __('If checked, the users will be taken to this page ','booking-ultra-pro')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_bank_success',
            __('Success Page','booking-ultra-pro'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page on purchase confirmation",'booking-ultra-pro'),
            __('Select the sucess page. The user will be taken to this page on purchase confirmation','booking-ultra-pro')
    );
	
	$data_status = array(
		 				'0' => 'Pending',
                        '1' =>'Approved'
                       
                    );
$this->create_plugin_setting(
            'select',
            'gateway_bank_default_status',
            __('Default Status for Local Payments','booking-ultra-pro'),
            $data_status,
            __("Set the default status an appointment will have when using local payment method. You won't have to approve the appointments manually, they will get approved automatically.",'booking-ultra-pro'),
            __('et the default status an appointment will have when using local payment method.','booking-ultra-pro')
    );	

		
?>
</table>

  
</div>



<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','booking-ultra-pro'); ?>"  />
	
</p>

</form>