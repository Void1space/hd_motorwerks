<?php
global $bookingultrapro;

define('bup_maintenance_url',plugin_dir_url(__FILE__ ));
define('bup_maintenance_path',plugin_dir_path(__FILE__ ));



	/* functions */
	foreach (glob(bup_maintenance_path . 'functions/*.php') as $filename) { require_once $filename; }
	
	/* administration */
	if (is_admin()){
		foreach (glob(bup_maintenance_path . 'admin/*.php') as $filename) { include $filename; }
	}
	
