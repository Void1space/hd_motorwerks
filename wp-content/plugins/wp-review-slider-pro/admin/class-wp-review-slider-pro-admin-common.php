<?php
class Common_Admin_Functions {
	
	//============================================================
	//functions for use in both admin files, can be included where needed
	//--------------------------
	
	//========================================
	//simple function to create json from string of comma list 
	//used in partials/review_list and partials/templates_posts, and admin_hooks
	public function wprev_commastrtojson($str,$dashes=false,$isnumber=true){
			if($str!=""){
			$str = preg_replace('/\s/', '', $str);
			$strarray = explode(',',$str);
			$strarray = array_filter($strarray);
			foreach ($strarray as $each_number) {
				if($isnumber==true){
					if($dashes==false){
						$strarraynew[] = (int) $each_number;
					} else {
						$strarraynew[] = "-".(int) $each_number."-";
					}
				} else {
					if($dashes==false){
						$strarraynew[] = $each_number;
					} else {
						$strarraynew[] = "-".$each_number."-";
					}
				}
			}
			$strarrayjson = json_encode($strarraynew);
			} else {
				$strarrayjson = '[]';
			}
			return $strarrayjson;
	}
	

}
	//========================================
	
	?>