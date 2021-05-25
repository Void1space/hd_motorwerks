<?php
class badgetools
{
	private $badgeid;	 
	
	function __construct($bid) {
		$this->badgeid = $bid;
	 }
		 
	public function gettotalsaverages($template_misc_array='',$currentform='') {
		$bid = $this->badgeid;
		global $wpdb;
		
		//if template_misc_array and currentform are not set then we could pull from db based on bid
		if($template_misc_array=='' && $currentform=='' && $bid>0){
			$table_name = $wpdb->prefix . 'wpfb_badges';
			$currentform = $wpdb->get_results("SELECT * FROM $table_name WHERE id = ".$bid);
			$template_misc_array = json_decode($currentform[0]->badge_misc, true);
		}
		
		//===switching to table not options
		//find the total reviews and the average rating based on what kind of badge this is
		/*
		$wppro_total_avg_reviews_array = get_option('wppro_total_avg_reviews');
		if(isset($wppro_total_avg_reviews_array)){
			$wppro_total_avg_reviews_array = json_decode($wppro_total_avg_reviews_array, true);
		} else {
			$wppro_total_avg_reviews_array = array();
		}
		print_r($wppro_total_avg_reviews_array);
		*/
		$table_name = $wpdb->prefix . 'wpfb_total_averages';
		$wppro_total_avg_reviews_array_new = $wpdb->get_results("SELECT * FROM $table_name WHERE btp_type = 'page'",ARRAY_A);
		//echo "<br><br>";
		//print_r($wppro_total_avg_reviews_array_new);
		//loop array and get in to correct format
		$wppro_total_avg_reviews_array = array();
		for ($x = 0; $x < count($wppro_total_avg_reviews_array_new); $x++) {
			$tempbtipid = $wppro_total_avg_reviews_array_new[$x]['btp_id'];
			$temptotalindb = $wppro_total_avg_reviews_array_new[$x]['total_indb'];
			$tempavgindb = $wppro_total_avg_reviews_array_new[$x]['avg_indb'];
			$temptotal = $wppro_total_avg_reviews_array_new[$x]['total'];
			$tempavg = $wppro_total_avg_reviews_array_new[$x]['avg'];
			$tempnumr1 = $wppro_total_avg_reviews_array_new[$x]['numr1'];
			$tempnumr2 = $wppro_total_avg_reviews_array_new[$x]['numr2'];
			$tempnumr3 = $wppro_total_avg_reviews_array_new[$x]['numr3'];
			$tempnumr4 = $wppro_total_avg_reviews_array_new[$x]['numr4'];
			$tempnumr5 = $wppro_total_avg_reviews_array_new[$x]['numr5'];
			$wppro_total_avg_reviews_array[$tempbtipid]=array("total_indb"=>"$temptotalindb", "avg_indb"=>"$tempavgindb","total"=>"$temptotal", "avg"=>"$tempavg","numr1"=>"$tempnumr1","numr2"=>"$tempnumr2","numr3"=>"$tempnumr3","numr4"=>"$tempnumr4","numr5"=>"$tempnumr5");
		}
		//echo "<br>newarray<br>";
		//print_r($tempnewarrow);
		//echo "<br>newarray_end<br>";
		
		if(!isset($wppro_total_avg_reviews_array)){
			$wppro_total_avg_reviews_array = array();
		}
		
		$badgeorgin = $currentform[0]->badge_orgin;
		$finaltotal=0;
		$finalavg=0;
		$temprating[1][0]=0;
		$temprating[2][0]=0;
		$temprating[3][0]=0;
		$temprating[4][0]=0;
		$temprating[5][0]=0;
				

		if(isset($template_misc_array['ratingsfrom'])){
			$ratingsfrom = $template_misc_array['ratingsfrom'];
		} else {
			$ratingsfrom = 'table';
		}

		//print_r($wppro_total_avg_reviews_array );

		if($ratingsfrom=='input'){
			$finaltotal =esc_html($template_misc_array['ratingstot'])." ";
			$finalavg = esc_html($template_misc_array['ratingsavg']);

		} else {
			//if this is all or manual treat differently, need to find totals, only doing this is ratingsfrom not set or set to table not input
			if($badgeorgin=='manual'){
				$x=0;
				foreach ($wppro_total_avg_reviews_array as $key => $valuearray) {
					//echo $key."<br>";
					//search pageid array to see if these values should be included
					if ($key=="manually_added"){
						//found it, add to total and avg arrays
						$totalreviewsarray[$x]=$valuearray['total'];
						$avgreviewsarray[$x]=$valuearray['avg'];
						$temprating[1][$x]=$valuearray['numr1'];
						$temprating[2][$x]=$valuearray['numr2'];
						$temprating[3][$x]=$valuearray['numr3'];
						$temprating[4][$x]=$valuearray['numr4'];
						$temprating[5][$x]=$valuearray['numr5'];
						$x++;
					}		
				}
				if(count($totalreviewsarray)>0){
				$finaltotal = array_sum($totalreviewsarray)." ";
				}
				if(count($avgreviewsarray)>0){
				$finalavg = round(array_sum($avgreviewsarray)/count($avgreviewsarray),1);
				}
			} else if($badgeorgin=='postid'){
				
				
				//first grab current post id from this page, doesn't work on load more, need to pass through ajax.
				$rpostidarray[] = get_the_ID();
				
				//now add additional post id
				$rpostids = $currentform[0]->rpage;
				$rppostidjsondecode = str_replace("-", "", $rpostids);
				$rpostidarraymore = json_decode($rppostidjsondecode,true);
				
				if(is_array($rpostidarraymore)){
					$rpostidarray = array_merge($rpostidarray, $rpostidarraymore);
				}
				if(is_array($rpostidarray)){
					$rpostidarray = array_filter($rpostidarray);
					$rpostidarray = array_values($rpostidarray);
					if(count($rpostidarray)>0){
						for ($x = 0; $x < count($rpostidarray); $x++) {
							if($x==0){
								$rpostidfilter = "AND (posts LIKE '%-".$rpostidarray[$x]."-%'";
							} else {
								$rpostidfilter = $rpostidfilter." OR posts LIKE '%-".$rpostidarray[$x]."-%'";
							}
						}
						$rpostidfilter = $rpostidfilter.")";
					}
				}
				
				$table_name = $wpdb->prefix . 'wpfb_reviews';
				$totalreviews = $wpdb->get_results(
					$wpdb->prepare("SELECT * FROM ".$table_name." WHERE id>%d AND hide != %s ".$rpostidfilter." ", "0","yes"),ARRAY_A
				);
				//we need both the reviews and the total in db if we are using load more or rich snippet
				$totalreviewsarray['reviews']=$totalreviews;
				$totalreviewsarray['totalcount']='';
				$totalreviewsarray['totalavg']='';
					$tempnum ='';
				//print_r($nolimitreviews);
				if(is_array($totalreviews)){
					$reviewratingsarray = Array();
					//loop allrevs to find total number of reviews and average of all of them.
					foreach ($totalreviews as $review) {
						if($review['rating']>0){
							$reviewratingsarray[] = intval($review['rating']);
							$tempnum = intval($review['rating']);
						}
						//also count positive and negative recommendations
						if($review['rating']<1 && $review['recommendation_type']=='positive'){
							$reviewratingsarray[] = 5;
							$tempnum = 5;
						}
						if($review['rating']<1 && $review['recommendation_type']=='negative'){
							$reviewratingsarray[] = 2;
							$tempnum = 2;
						}
						//find number of each star
						if($tempnum==1){
							$temprating[1][]=1;
						} else if($tempnum==2){
							$temprating[2][]=1;
						} else if($tempnum==3){
							$temprating[3][]=1;
						} else if($tempnum==4){
							$temprating[4][]=1;
						} else if($tempnum==5){
							$temprating[5][]=1;
						}
					}

					//remove empties
					$reviewratingsarray = array_filter($reviewratingsarray);
					$totalreviewsarray['totalcount']=count($reviewratingsarray);
					if(count($reviewratingsarray)>0){
						$reviewratingsarrayavg = array_sum($reviewratingsarray)/count($reviewratingsarray);
					} else {
						$reviewratingsarrayavg = 0;
					}
					$totalreviewsarray['totalavg'] = round($reviewratingsarrayavg,1);
				}
				$finaltotal=$totalreviewsarray['totalcount'];
				$finalavg=$totalreviewsarray['totalavg'];
				
				
			} else {
				//if this is not all or manual find pageids
				//find the pageid array
				if(isset($currentform[0]->rpage)){
					$rpagearray = json_decode($currentform[0]->rpage);
				} else {
					$rpagearray=[''];
				}
				if(!$rpagearray){$rpagearray=[''];}
				$rpagearray = array_filter($rpagearray);
				
				//if type is submitted and rpage isn't set or is blank then try to use pageid of the currentpage
				if($badgeorgin=='submitted' && count($rpagearray)==0){
					//find current pageid
					$id = get_the_ID();
					$rpagearray[]=$id;
				}
				
				$useall = '';
				if($badgeorgin=='custom' && count($rpagearray)==0){
					//set on custom and no pages selected use all
					$useall = true;
				}
				
				//loop pageidarray and get new total and avg if needed
				
				//print_r($rpagearray);

				//stripslashes from pageids
				$rpagearray2=[''];
				$rpagearray3=[''];
				foreach ($rpagearray as $key=>$value) {
					$rpagearray[$key] = stripslashes(htmlentities($value));
					$rpagearray[$key] = preg_replace("#\r|\n#", "", $rpagearray[$key]);
					$rpagearray[$key] = trim($rpagearray[$key]);
					//also check non htmlentities
					$rpagearray2[$key] = stripslashes(($value));
					$rpagearray2[$key] = preg_replace("#\r|\n#", "", $rpagearray2[$key]);
					$rpagearray2[$key] = trim($rpagearray2[$key]);

					$tempcheck = str_replace("'","",$rpagearray[$key]);
					$tempcheck = str_replace("&","",$tempcheck);
					$tempcheck = str_replace("amp;","",$tempcheck);
					$rpagearray3[$key] =$tempcheck;
				}


				$x=0;
				foreach ($wppro_total_avg_reviews_array as $key => $valuearray) {
					//search pageid array to see if these values should be included
					$keycheck1 = str_replace("'","",$key);
					$keycheck1 = str_replace("&","",$keycheck1);
					$keycheck1 = str_replace("amp;","",$keycheck1);
					$keycheck1 = str_replace("#x27;","",$keycheck1);
					$keycheck1 = trim($keycheck1);

					if (in_array($key, $rpagearray) || in_array($key, $rpagearray2) || in_array($keycheck1, $rpagearray3) || $useall){
						//force to use db values if set
						if($ratingsfrom=='db' || $currentform[0]->style=='2'){

							//found it, add to total and avg arrays
							if(isset($valuearray['total_indb'])){
								$totalreviewsarray[$x]=$valuearray['total_indb'];
							} else {
								$totalreviewsarray[$x]='';
							}
							if(isset($valuearray['avg_indb'])){
								$avgreviewsarray[$x]=$valuearray['avg_indb'];
							} else {
								$avgreviewsarray[$x]='';
							}
							
						} else {
						
							//found it, add to total and avg arrays
							$usedbavg =false;
							if(isset($valuearray['total']) && $valuearray['total']>$valuearray['total_indb']){
								$totalreviewsarray[$x]=$valuearray['total'];
							} else if(isset($valuearray['total_indb'])){
								$totalreviewsarray[$x]=$valuearray['total_indb'];
							} else {
								$totalreviewsarray[$x]='';
							}
							if(isset($valuearray['avg']) && $valuearray['avg']>0){
								$avgreviewsarray[$x]=$valuearray['avg'];
							} else {
								if(isset($valuearray['avg_indb'])){
								$avgreviewsarray[$x]=$valuearray['avg_indb'];
								}
							}
							
						}
						
						if(isset($valuearray['numr1'])){
							$temprating[1][$x]=$valuearray['numr1'];
							$temprating[2][$x]=$valuearray['numr2'];
							$temprating[3][$x]=$valuearray['numr3'];
							$temprating[4][$x]=$valuearray['numr4'];
							$temprating[5][$x]=$valuearray['numr5'];
						} else {
							$temprating[1][$x]=0;
							$temprating[2][$x]=0;
							$temprating[3][$x]=0;
							$temprating[4][$x]=0;
							$temprating[5][$x]=0;
							
						}
				
						//we need to normalize in case we have a lot of reviews from one source then only a couple from another.
						$avgtimestotal[$x]=$avgreviewsarray[$x]*$totalreviewsarray[$x];
						$x++;
					}		
					//print_r($valuearray);
				}

				//print_r($totalreviewsarray);
				//print_r($avgreviewsarray);
				if(!isset($totalreviewsarray)){
					$totalreviewsarray=[''];
				}
				if(!isset($avgreviewsarray)){
					$avgreviewsarray=[''];
				}
				$avgreviewsarray = array_filter($avgreviewsarray);
				if(isset($avgtimestotal) && count($totalreviewsarray)>0){
					$finaltotal = array_sum($totalreviewsarray);
					//$finalavg = round(array_sum($avgreviewsarray)/count($avgreviewsarray),1);
					$finalavg = round(array_sum($avgtimestotal)/array_sum($totalreviewsarray),1);
				}
			}
		}
		
		$resultarray['finaltotal']=$finaltotal;
		$resultarray['finalavg']=$finalavg;
		$resultarray['temprating']=$temprating;

		return $resultarray;
	}
	
}

?>