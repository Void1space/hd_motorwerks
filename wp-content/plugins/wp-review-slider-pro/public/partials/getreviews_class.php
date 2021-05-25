<?php
class GetReviews_Functions {
	
	//============================================================
	//functions for querying database for correct reviews to display, called by wp-review-slider-pro-public-display
	//--------------------------
	//
	/**
	 * Called from public partials wp-review-slider-pro-public-display, to return totalreivews
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wppro_queryreviews($currentform,$startoffset=0,$totaltoget=0,$notinstring='',$shortcodepageid='',$shortcodelang='',$cpostid='', $textsearch='', $textsort='', $textrating='', $textlang='',$shortcodetag=''){
		global $wpdb;
		
		
		//table limit changes if we are calling this from load more button click
		$notinsearchstring ='';
		if($totaltoget>0){
			//this must be load more click
			$totaltoget = $totaltoget + 1;	//testing to see if we need to show the load more btn again.
			$tablelimit = $totaltoget;
			//add a not in statement after $sortdir if we are rand search ex: AND book_price NOT IN (100,200)
			if($notinstring!=''){
				//explode implode for safety
				$tempnotinarray = explode(",",$notinstring);
				if(is_array($tempnotinarray)){
					$notinstring = implode(",",$tempnotinarray);
					$notinsearchstring = " AND id NOT IN (".$notinstring.") ";
				}
			}
			$tablelimit = $startoffset.",".$totaltoget;


		} else {
			$reviewsperpage= $currentform[0]->display_num*$currentform[0]->display_num_rows;
			$tablelimit = $reviewsperpage;
			//change limit for slider
			if($currentform[0]->createslider == "yes" || $currentform[0]->createslider == "sli"){
				$tablelimit = $tablelimit*$currentform[0]->numslides;
			}
		}

		
		//template misc stuff
		$template_misc_array = json_decode($currentform[0]->template_misc, true);
		
		//add text search if we are using pagination and the search box
		$textsearchquery = '';
		$tagsearch='';
		if(!isset($template_misc_array['header_tag_search'])){
			$template_misc_array['header_tag_search']='';
		}
		if($textsearch!=''){
			if($template_misc_array['header_tag_search']=='tags'){
				$textsearchquery = "AND (tags LIKE '%%".$textsearch."%%')";
			} else if($template_misc_array['header_tag_search']=='both'){
				$textsearchquery = "AND (reviewer_name LIKE '%%".$textsearch."%%' or review_text LIKE '%%".$textsearch."%%' or review_title LIKE '%%".$textsearch."%%' or tags LIKE '%%".$textsearch."%%')";
			} else {
			$textsearchquery = "AND (reviewer_name LIKE '%%".$textsearch."%%' or review_text LIKE '%%".$textsearch."%%' or review_title LIKE '%%".$textsearch."%%')";
			}
		}
		
		//filter by tag, template setting
		$tagfilter='';
		if(isset($template_misc_array['tagfilterlist']) && $template_misc_array['tagfilterlist']!=''){
			$tagcodearray = json_decode($template_misc_array['tagfilterlist'],true);
			$tagfilterlist_opt = '';
			if(isset($template_misc_array['tagfilterlist_opt']) && $template_misc_array['tagfilterlist_opt']!=''){
				$tagfilterlist_opt = $template_misc_array['tagfilterlist_opt'];
			}
			if(is_array($tagcodearray)){
				$tagcodearray = array_filter($tagcodearray);
				$tagcodearray = array_values($tagcodearray);
				if(count($tagcodearray)>0){
					for ($x = 0; $x < count($tagcodearray); $x++) {
						if($tagfilterlist_opt=='notthese'){
							if($x==0){
								$tagfilter = "AND (tags NOT LIKE '%%".$tagcodearray[$x]."%%'";
							} else {
								$tagfilter = $tagfilter." OR tags NOT LIKE '%%".$tagcodearray[$x]."%%'";
							}
						} else {
							if($x==0){
								$tagfilter = "AND (tags LIKE '%%".$tagcodearray[$x]."%%'";
							} else {
								$tagfilter = $tagfilter." OR tags LIKE '%%".$tagcodearray[$x]."%%'";
							}
						}
					}
					$tagfilter = $tagfilter.")";
				}
			}
		}
		
		//use values from currentform to get reviews from db
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		
		if($currentform[0]->hide_no_text=="yes"){
			$min_words = 1;
			$max_words = 5000;
		} else {
			$min_words = 0;
			$max_words = 5000;
		}
		
		if($textsort!=''){
			if($textsort=='newest'){
				$sorttable = "created_time_stamp ";
				$sortdir = "DESC";
			} else if($textsort=='oldest'){
				$sorttable = "created_time_stamp ";
				$sortdir = "ASC";
			} else if($textsort=='highest'){
				$sorttable = "rating";
				$sortdir = "DESC, recommendation_type DESC";
			} else if($textsort=='lowest'){
				$sorttable = "rating ";
				$sortdir = "ASC, recommendation_type ASC";
			} else if($textsort=='longest'){
				$sorttable = "review_length_char ";
				$sortdir = "DESC";
			} else if($textsort=='shortest'){
				$sorttable = "review_length_char ";
				$sortdir = "ASC";
			} else if($textsort=='random'){
				$sorttable = "RAND() ";
				$sortdir = "";
			}
		} else {
			if($currentform[0]->display_order=="random"){
				$sorttable = "RAND() ";
				$sortdir = "";
			} else if($currentform[0]->display_order=="oldest"){
				$sorttable = "created_time_stamp ";
				$sortdir = "ASC";
			} else if($currentform[0]->display_order=="newest"){
				$sorttable = "created_time_stamp ";
				$sortdir = "DESC";
			} else if($currentform[0]->display_order=='highest'){
				$sorttable = "rating";
				$sortdir = "DESC, recommendation_type DESC";
			} else if($currentform[0]->display_order=='lowest'){
				$sorttable = "rating ";
				$sortdir = "ASC, recommendation_type ASC";
			} else if($currentform[0]->display_order=='longest'){
				$sorttable = "review_length_char ";
				$sortdir = "DESC";
			} else if($currentform[0]->display_order=='shortest'){
				$sorttable = "review_length_char ";
				$sortdir = "ASC";
			} else if($currentform[0]->display_order=='sortweight'){
				$sorttable = "sort_weight ";
				$sortdir = "DESC";
			}
			//add second sort_weight
			if(isset($currentform[0]->display_order_second) && $currentform[0]->display_order_second!=''){
				if($currentform[0]->display_order_second=="random"){
					$sortdir = $sortdir . ",RAND()";
				} else if($currentform[0]->display_order_second=="oldest"){
					$sortdir = $sortdir . ", created_time_stamp ASC";
				} else if($currentform[0]->display_order_second=="newest"){
					$sortdir = $sortdir . ", created_time_stamp DESC";
				} else if($currentform[0]->display_order_second=='highest'){
					$sortdir = $sortdir . ", rating DESC";
				} else if($currentform[0]->display_order_second=='lowest'){
					$sortdir = $sortdir . ", rating ASC";
				} else if($currentform[0]->display_order_second=='longest'){
					$sortdir = $sortdir . ", review_length_char DESC";
				} else if($currentform[0]->display_order_second=='shortest'){
					$sortdir = $sortdir . ", review_length_char ASC";
				} else if($currentform[0]->display_order_second=='sortweight'){
					$sortdir = $sortdir . ", sort_weight DESC";
				}
			}
		}
		
		//-----------------------------
		//======pro filter settings 	min_words, max_words, min_rating, rtype, rpage, filterbytext showreviewsbyid========
		if(isset($currentform[0]->min_words) && $currentform[0]->min_words>0){
			$min_words = intval($currentform[0]->min_words);
		}
		if(isset($currentform[0]->max_words) &&  $currentform[0]->max_words>0){
			$max_words = intval($currentform[0]->max_words);
		}
		//filter length based on word count or char count
		$lengthquery = "review_length >= %d AND review_length <= %d";
		if(isset($currentform[0]->word_or_char) && $currentform[0]->word_or_char=='char'){
			$lengthquery = "review_length_char >= %d AND review_length_char <= %d";
		}
		
		//min_rating filter----
		if($currentform[0]->min_rating>0){
			$min_rating = intval($currentform[0]->min_rating);
			//grab positive recommendations as well
			if($min_rating==1){
				$min_rating=0;
			}
			if($min_rating<3){
				//show positive and negative
				$ratingquery = " AND rating >= '".$min_rating."' ";
			} else {
				//only show positive
				$ratingquery = " AND (rating >= '".$min_rating."' OR recommendation_type = 'positive' ) ";
			}
			
		} else {
			$min_rating ="";
			$ratingquery ="";
		}
		
		//customer input rating filter
		$ratingquerypublic ="";
		if($textrating>0 && $textrating!='unset'){
			$textrating = intval($textrating);
			$ratingquerypublic = " AND rating = '".$textrating."' ";
		}
		
		//display random limit by month
		$randlimitfilter = "";
		if($currentform[0]->display_order=="random" &&  $currentform[0]->display_order_limit!="all" &&  $currentform[0]->display_order_limit!=""){
			$current_time=time();
			$howmanyago = "-".$currentform[0]->display_order_limit." month";
			$pasttime = strtotime($howmanyago, $current_time);
			$randlimitfilter = " AND created_time_stamp > '".$pasttime."' ";
		}
		
		//rtype filter-----
		$rtypefilter = "";
		if($currentform[0]->rtype!=""){
			$rtypearray = json_decode($currentform[0]->rtype);
			if(is_array($rtypearray)){
			$rtypearray = array_filter($rtypearray);
			$rtypearray = array_values($rtypearray);
			if(count($rtypearray)>0){
				for ($x = 0; $x < count($rtypearray); $x++) {
					//check if any manual_custom is set
					if (strpos($rtypearray[$x], '_') !== false) {
						//add from_name search
						$tempsearcharray = explode("_",$rtypearray[$x]);
						if($x==0){
							$rtypefilter = "AND ((type = '".$tempsearcharray[0]."' AND from_name = '".$tempsearcharray[1]."')";
						} else {
							$rtypefilter = $rtypefilter." OR (type = '".$tempsearcharray[0]."' AND from_name = '".$tempsearcharray[1]."')";
						}
					} else {
						if($x==0){
							$rtypefilter = "AND (type = '".$rtypearray[$x]."'";
						} else {
							$rtypefilter = $rtypefilter." OR type = '".$rtypearray[$x]."'";
						}
					}
				}
				$rtypefilter = $rtypefilter.")";
			}
			}
		}
		//rpage filter-----
		$rpagefilter = "";
		$shortcodepageidarray = explode(",",$shortcodepageid);
		//print_r($shortcodepageidarray);
		if($currentform[0]->rpage!=""){
			$rpagearray = json_decode($currentform[0]->rpage);
			//need to also check for &amp; in pageid, this is because of it getting saved wrong.
			if(isset($rpagearray) && is_array($rpagearray)){
				foreach ($rpagearray as &$value) {
					if(strpos($value, '&')){
						$newpageid = str_replace("&", "&amp;", $value);
						$rpagearraynew[] = $newpageid;
					}
				}
			}
			if(isset($rpagearraynew) && is_array($rpagearraynew)){
				$rpagearray = array_merge($rpagearray, $rpagearraynew);
			}
			
			if(is_array($rpagearray)){
			$rpagearray = array_filter($rpagearray);
			$rpagearray = array_values($rpagearray);
			if(count($rpagearray)>0){
				for ($x = 0; $x < count($rpagearray); $x++) {
					if($x==0){
						$rpagefilter = "AND (pageid = '".$rpagearray[$x]."'";
					} else {
						$rpagefilter = $rpagefilter." OR pageid = '".$rpagearray[$x]."'";
					}
				}
				//add shortcode pageid
				for ($k = 0; $k < count($shortcodepageidarray); $k++) {
					if($shortcodepageidarray[$k]!=''){
						$rpagefilter = $rpagefilter." OR pageid = '".trim($shortcodepageidarray[$k])."'";
					}
				}
				$rpagefilter = $rpagefilter.")";
			}
			}
		}
		//rpage filter in shortcodepageid
		if($shortcodepageid!='' && $rpagefilter==""){
				for ($x = 0; $x < count($shortcodepageidarray); $x++) {
					if($x==0){
						$rpagefilter = "AND (pageid = '".trim($shortcodepageidarray[$x])."'";
					} else {
						$rpagefilter = $rpagefilter." OR pageid = '".trim($shortcodepageidarray[$x])."'";
					}
				}
				$rpagefilter = $rpagefilter.")";
		}
		
		//echo $rpagefilter;
		
		//rpostid filter-----
		$rpostidfilter = "";
		//echo "pfilter:".$template_misc_array['postfilter'];
		//echo "<br>";
		if(!isset($template_misc_array['postfilter'])){
			$template_misc_array['postfilter']='no';
		}
		if($template_misc_array['postfilter']=='yes'){
			//first grab current post id from this page, doesn't work on load more, need to pass through ajax.
			//check if passed first
			if($cpostid!=''){
				$rpostidarray[] = $cpostid;
			} else { 
				$rpostidarray[] = get_the_ID();
			}
			//now add additional post id
			$rpostidarraymore = json_decode($template_misc_array['postfilterlist'],true);
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
		}
		//echo "rpostidfilter:".$rpostidfilter;
		//echo "<br>";
		
		//rcatid filter-----
		$rcatidfilter = "";
		if(!isset($template_misc_array['categoryfilter'])){
			$template_misc_array['categoryfilter']='no';
		}
		if($template_misc_array['categoryfilter']=='yes'){
			//first grab current category id from this page
			//$categories = get_the_category();
			$taxonomies=get_taxonomies('','names');
			if($cpostid!=''){
				$postid = $cpostid;
			} else { 
				$postid = get_the_ID();
			}
			$categories =wp_get_post_terms($postid, $taxonomies,  array("fields" => "ids"));
			$arrlength = count($categories);
			if($arrlength>0){
				$rcategoryidarray = $categories;
			//for($x = 0; $x < $arrlength; $x++) {
			//	$rcategoryidarray[] = $categories[$x]->cat_ID;	//array containing just the cat_IDs that this post belongs to
			//}
			} else {
				$rcategoryidarray[]='';
			}
			//now add additional category id
			$rcategoryidarraymore = json_decode($template_misc_array['categoryfilterlist'],true);
			if(is_array($rcategoryidarraymore) && is_array($rcategoryidarray)){
				$rcategoryidarray = array_merge($rcategoryidarray, $rcategoryidarraymore);
			}
			if(is_array($rcategoryidarray)){
				$rcategoryidarray = array_filter($rcategoryidarray);
				$rcategoryidarray = array_values($rcategoryidarray);
				if(count($rcategoryidarray)>0){
					for ($x = 0; $x < count($rcategoryidarray); $x++) {
						if($x==0){
							$rcatidfilter = "AND (categories LIKE '%-".$rcategoryidarray[$x]."-%'";
						} else {
							$rcatidfilter = $rcatidfilter." OR categories LIKE '%-".$rcategoryidarray[$x]."-%'";
						}
					}
					$rcatidfilter = $rcatidfilter.")";
				}
			}
		}

		//filter by language code
		$langfilter='';
		if(isset($template_misc_array['langfilterlist']) && $template_misc_array['langfilterlist']!=''){
			$langcodearray = json_decode($template_misc_array['langfilterlist'],true);
			
			if(is_array($langcodearray)){
				$langcodearray = array_filter($langcodearray);
				$langcodearray = array_values($langcodearray);
				if(count($langcodearray)>0){
					for ($x = 0; $x < count($langcodearray); $x++) {
						if($x==0){
							$langfilter = "AND (language_code = '".$langcodearray[$x]."'";
						} else {
							$langfilter = $langfilter." OR language_code = '".$langcodearray[$x]."'";
						}
					}
					$langfilter = $langfilter.")";
				}
			}
		}
		
		//tag filter in shortcode
		$shortcodetagfilter='';
		if($shortcodetag!=''){
			$shortcodetagarray = explode(",",$shortcodetag);
				for ($x = 0; $x < count($shortcodetagarray); $x++) {
					if($x==0){
							$shortcodetagfilter = "AND (tags LIKE '%%".$shortcodetagarray[$x]."%%'";
						} else {
							$shortcodetagfilter = $shortcodetagfilter." OR tags LIKE '%%".$shortcodetagarray[$x]."%%'";
						}
					}
					$shortcodetagfilter = $shortcodetagfilter.")";
		}
		
		//language code filter in shortcode
		$shortlangfilter='';
		if($shortcodelang!=''){
			$shortcodelangarray = explode(",",$shortcodelang);
				for ($x = 0; $x < count($shortcodelangarray); $x++) {
					if($x==0){
							$shortlangfilter = "AND (language_code = '".$shortcodelangarray[$x]."'";
						} else {
							$shortlangfilter = $shortlangfilter." OR language_code = '".$shortcodelangarray[$x]."'";
						}
					}
					$shortlangfilter = $shortlangfilter.")";
		}
		//language code filter on front end
		$publiclangfilter='';
		if($textlang!='' && $textlang!='unset'){
			$publiclangfilter = "AND language_code = '".$textlang."'";
		}
		
		//filter by keyword
		$rstringfilter = "";
		if(isset($currentform[0]->string_text) && $currentform[0]->string_text!=""){
			$strarray = explode(',',$currentform[0]->string_text);
			$strarray = array_filter($strarray);
			if(count($strarray)>0){
					for ($x = 0; $x < count($strarray); $x++) {
						$tempstring = trim($strarray[$x]);
						if($currentform[0]->string_sel=='all'){
							if($x==0){
								$rstringfilter = "AND (review_text LIKE '%%".$tempstring."%%'";
							} else {
								$rstringfilter = $rstringfilter." AND review_text LIKE '%%".$tempstring."%%'";
							}
					
						} else if ($currentform[0]->string_sel=='any'){
							if($x==0){
								$rstringfilter = "AND (review_text LIKE '%%".$tempstring."%%'";
							} else {
								$rstringfilter = $rstringfilter." OR review_text LIKE '%%".$tempstring."%%'";
							}
						} else if ($currentform[0]->string_sel=='not'){
							if($x==0){
								$rstringfilter = "AND (review_text NOT LIKE '%%".$tempstring."%%'";
							} else {
								$rstringfilter = $rstringfilter." AND review_text NOT LIKE '%%".$tempstring."%%'";
							}
						}
					}
					$rstringfilter = $rstringfilter.")";
			}
		}
		
		//showreviewsbyid filter---------replaces all other filters
		$onlyselected = false;
		$selectedfilter = "";
		$selectedreviews= array();
		if(!isset($currentform[0]->showreviewsbyid_sel)){
			$currentform[0]->showreviewsbyid_sel='';
		}
		if($currentform[0]->showreviewsbyid!=""){
			$showreviewsbyidarray = json_decode($currentform[0]->showreviewsbyid);
			$showreviewsbyidarray = array_filter($showreviewsbyidarray);
			$showreviewsbyidarray = array_values($showreviewsbyidarray);
			if(count($showreviewsbyidarray)>0){
				if( $currentform[0]->showreviewsbyid_sel!='theseplus'){
					$onlyselected = true;
				} else {
					//showing these plus other reviews from filter, build filter here
					$selectedfilter = "AND id IN (". implode(",",$showreviewsbyidarray).")";
					$selectedreviews = $wpdb->get_results(
						$wpdb->prepare("SELECT * FROM ".$table_name." WHERE id>%d ".$selectedfilter." ORDER BY ".$sorttable." ".$sortdir."","0")
					);
					//echo $wpdb->last_query ;
					//print_r($selectedreviews);
				}
			}
		}
		$nolimitreviews='';
		if(!isset($template_misc_array['header_text'])){
			$template_misc_array['header_text']='';
		}
		if(!isset($currentform[0]->load_more)){
			$currentform[0]->load_more='';
		}
		

		if($onlyselected){
			$query = "SELECT * FROM ".$table_name." WHERE id IN (";
			//loop array and add to query
			$n=1;
			foreach ($showreviewsbyidarray as $value) {
				if($value!=""){
					if(count($showreviewsbyidarray)==$n){
						$query = $query." $value";
					} else {
						$query = $query." $value,";
					}
				}
				$n++;
			}
			$querylimit = $query.") ".$notinsearchstring."".$textsearchquery."".$ratingquerypublic."".$publiclangfilter." ORDER BY ".$sorttable." ".$sortdir." LIMIT ".$tablelimit." ";
			$querynolimit = $query.") ".$textsearchquery."".$ratingquerypublic."".$publiclangfilter." ORDER BY ".$sorttable." ".$sortdir."";
			
			$totalreviews = $wpdb->get_results($querylimit);
			$totalreviewsarray['dbcall'] = $wpdb->last_query;
			//run another query if we need total and average.
			if($currentform[0]->google_snippet_add=='yes' || $currentform[0]->load_more=='yes' ||  $template_misc_array['header_text']!=''){
				//find total and average in db
				$nolimitreviews = $wpdb->get_results($querynolimit,ARRAY_A);
			}
		} else {
			$totalreviews = $wpdb->get_results(
				$wpdb->prepare("SELECT * FROM ".$table_name."
				WHERE id>%d AND ".$lengthquery." AND hide != %s ".$rtypefilter." ".$rpagefilter." ".$rpostidfilter." ".$rstringfilter." ".$rcatidfilter." ".$tagfilter." ".$langfilter." ".$shortlangfilter." ".$shortcodetagfilter." ".$randlimitfilter."".$ratingquery."".$notinsearchstring."".$textsearchquery."".$ratingquerypublic."".$publiclangfilter."
				ORDER BY ".$sorttable." ".$sortdir." LIMIT ".$tablelimit." ", "0","$min_words","$max_words","yes")
			);
			//echo $wpdb->last_query ;
			$totalreviewsarray['dbcall'] = $wpdb->last_query;
			//run another query if we need total and average.
			if($currentform[0]->google_snippet_add=='yes' || $currentform[0]->load_more=='yes' ||  $template_misc_array['header_text']!=''){
				$notinsearchstring ='';
				$nolimitreviews = $wpdb->get_results(
				$wpdb->prepare("SELECT * FROM ".$table_name."
				WHERE id>%d AND ".$lengthquery." AND hide != %s ".$rtypefilter." ".$rpagefilter." ".$rpostidfilter." ".$rstringfilter." ".$rcatidfilter." ".$tagfilter." ".$langfilter." ".$shortlangfilter." ".$shortcodetagfilter." ".$randlimitfilter."".$ratingquery."".$notinsearchstring."".$textsearchquery."".$ratingquerypublic."".$publiclangfilter."
				ORDER BY ".$sorttable." ".$sortdir." ", "0","$min_words","$max_words","yes"),ARRAY_A);
			}
		}

		//if we also must show selected reviews combine here, only if not on Load More
		if( $currentform[0]->showreviewsbyid_sel=='theseplus' && $totaltoget<1){
			//print_r($selectedreviews);
			//add to $totalreviews if not in there.
			$flipselectedreviews = array_reverse($selectedreviews);		//so we keep order
			foreach ($flipselectedreviews as $current) {
				if ( ! in_array($current, $totalreviews)) {
					array_unshift($totalreviews,$current);	//adds item to beginning
					array_pop($totalreviews);	//removes an item at the end
				}
			}
		}
		
		//we need both the reviews and the total in db if we are using load more or rich snippet
		$totalreviewsarray['reviews']=$totalreviews;
		$totalreviewsarray['totalcount']='';
		$totalreviewsarray['totalavg']='';
	$tempnum ='';
		//print_r($nolimitreviews);
		if(is_array($nolimitreviews)){
			$reviewratingsarray = Array();
			//loop allrevs to find total number of reviews and average of all of them.
			foreach ($nolimitreviews as $review) {
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
			if(isset($temprating[1])){
				$totalreviewsarray['numr1'] = array_sum($temprating[1]);
			} else {
				$totalreviewsarray['numr1'] =0;
			}
			if(isset($temprating[2])){
				$totalreviewsarray['numr2'] = array_sum($temprating[2]);
			} else {
				$totalreviewsarray['numr2'] =0;
			}
			if(isset($temprating[3])){
				$totalreviewsarray['numr3'] = array_sum($temprating[3]);
			} else {
				$totalreviewsarray['numr3'] =0;
			}
			if(isset($temprating[4])){
				$totalreviewsarray['numr4'] = array_sum($temprating[4]);
			} else {
				$totalreviewsarray['numr4'] =0;
			}
			if(isset($temprating[5])){
				$totalreviewsarray['numr5'] = array_sum($temprating[5]);
			} else {
				$totalreviewsarray['numr5'] =0;
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

		//print_r($totalreviewsarray);
		
			//echo "<br><br>";
		return $totalreviewsarray;

	}	
	
	public function wppro_getloadmorebtnhtml($currentform,$iswidget,$makingslideshow, $notinstring='',$shortcodepageid='',$shortcodelang='',$cpostid='',$totalcount='',$shortcodetag='' ){
		$resultsecho='';
		$jslastslide ='';
		$ismakingslideshow = "";
		$iswidgethtml ='';
		$imageclassslideshow ='';
		$template_misc_array = json_decode($currentform[0]->template_misc, true);
		$loading_img_url = esc_url( plugins_url( 'imgs/', __FILE__ ) ).'loading_ripple.gif';
		//number of reviews per a page
		$reviewsperpage= $currentform[0]->display_num*$currentform[0]->display_num_rows;
		$lastslidenumb = ceil($totalcount/$reviewsperpage);
		//echo "here:".$lastslidenumb;
		$hidepagination ='';
		if($lastslidenumb<2){	//need to hide pagination because only one page	
			$hidepagination = "style=display:none;";
		}
		//check for load more pagination setting, default to button
		if(isset($template_misc_array['load_more_porb']) && $template_misc_array['load_more_porb']=="pagenums"){
			$btnorpagenums = 'pagenums';
		} else {
			$btnorpagenums = 'btn';
		}
		
		if($iswidget==true){
			$iswidgethtml ="_widget";
		}
		$hidebtnhtml = '';
		if($currentform[0]->createslider == "sli"){
			$hidebtnhtml = "style=display:none;";
			$ismakingslideshow = "sli";
			$btnorpagenums='btn';
		}
		if($currentform[0]->load_more=="yes" ){
			if($makingslideshow){
				$ismakingslideshow = "yes";
				$imageclassslideshow = "isinslideshowloadingimg";
				//hide button
				$hidebtnhtml = "style=display:none;";
				//different flor slideshow maybe
				$resultsecho = $resultsecho. '<li>';
				$jslastslide = 'slider.on("wprs_unslider.change", function(event, index, slide) {
					var loopnow = $("#wprev_load_more_btn_'.$currentform[0]->id.'").attr("loopnow");
					if(loopnow!="yes"){
					var numslides = $("#wprev-slider-'.$currentform[0]->id.$iswidgethtml.'").find( "li" ).length;
					if(index==-1){index = numslides-1;}
					if((numslides-1)==index){addslide(index+1,numslides+1);}
					}
					});
				function addslide(index,numslides){
					var hideldbtn = $("#wprev_load_more_btn_'.$currentform[0]->id.'").attr("hideldbtn");
					if(hideldbtn!="yes"){
					$("#wprev_load_more_btn_'.$currentform[0]->id.'").trigger("wprevlastslide");
					$("#wprev-slider-'.$currentform[0]->id.$iswidgethtml.'").find( "ul" ).append("<li></li>");
					slider.data("wprs_unslider").calculateSlides();
					$("#wprev-slider-'.$currentform[0]->id.$iswidgethtml.'").siblings("nav").remove();
					slider.data("wprs_unslider").initNav();
					$("#wprev-slider-'.$currentform[0]->id.$iswidgethtml.'").siblings("nav").find( "li" ).last().prev().addClass("wprs_unslider-active");
					} else {
					$("#wprev_load_more_btn_'.$currentform[0]->id.'").attr("loopnow","yes");
					$("#wprev-slider-'.$currentform[0]->id.$iswidgethtml.'").find( "ul li:last").remove();
					slider.data("wprs_unslider").calculateSlides();
					$("#wprev-slider-'.$currentform[0]->id.$iswidgethtml.'").siblings("nav").remove();
					slider.data("wprs_unslider").initNav();
					$("#wprev-slider-'.$currentform[0]->id.$iswidgethtml.'").siblings("nav").find( "li" ).last().prev().addClass("wprs_unslider-active");
					setTimeout(function(){slider.data("wprs_unslider").animate(0);}, 100);
					}}';
			}
			
			$reviewssameheight = "";
			if($currentform[0]->review_same_height=='yes' || $currentform[0]->review_same_height=='cur'){
				$reviewssameheight = 'data-revsameheight="yes"';
			} else {
				$reviewssameheight = 'data-revsameheight="no"';
			}
					
			if($makingslideshow || $btnorpagenums=='btn'){
				$mobileoneperslide = "";
					if($currentform[0]->slidermobileview == "one"){
						$mobileoneperslide = 'data-onemobil="yes"';
					} else {
						$mobileoneperslide = 'data-onemobil="no"';
					}
					
				$resultsecho = $resultsecho. '<div '.$hidepagination.' class="wprevpro_load_more_div"><button data-notinstring="'.$notinstring.'" '.$mobileoneperslide.' '.$reviewssameheight.' data-callnum="1" data-ismasonry="'.$currentform[0]->display_masonry.'" data-slideshow="'.$ismakingslideshow.'" data-tid="'.$currentform[0]->id.'" data-nrows="'.$currentform[0]->display_num_rows.'" data-perrow="'.$currentform[0]->display_num.'" data-cpostid="'.$cpostid.'" data-shortcodepageid="'.$shortcodepageid.'" data-shortcodelang="'.$shortcodelang.'" data-shortcodetag="'.$shortcodetag.'" class="wprevpro_load_more_btn brnprevclass" id="wprev_load_more_btn_'.$currentform[0]->id.'" '.$hidebtnhtml.'>'.esc_html( $currentform[0]->load_more_text ).'</button><img src="'.$loading_img_url.'" class="wprploadmore_loading_image '.$imageclassslideshow.'" style="display:none;"></div>';
			}
			if($makingslideshow){
				//different for slideshow maybe
				$resultsecho = $resultsecho. '</li>';
			}
			//add pagination div if not makingslideshow and set to pagenmus
			if(!$makingslideshow && $btnorpagenums=='pagenums'){
				
				//find the number of last slide and create correct html

					//add first number
					$resultsecho = $resultsecho. '
						<div id="wppro_review_pagination'.$currentform[0]->id.'" '.$hidepagination.' class="wppro_pagination" '.$reviewssameheight.' data-notinstring="'.$notinstring.'" data-nrows="'.$currentform[0]->display_num_rows.'" data-ismasonry="'.$currentform[0]->display_masonry.'" data-perrow="'.$currentform[0]->display_num.'" data-cpostid="'.$cpostid.'" data-shortcodepageid="'.$shortcodepageid.'" data-shortcodelang="'.$shortcodelang.'" data-shortcodetag="'.$shortcodetag.'" data-tid="'.$currentform[0]->id.'" data-lastslidenum="'.$lastslidenumb.'" data-totalreviewsindb="'.$totalcount.'">
						<ul class="wppro_page_numbers_ul">
							<li><span class="brnprevclass wppro_page_numbers current">1</span></li>';
					if($lastslidenumb<4){
						for ($x = 2; $x <= $lastslidenumb; $x++) {
							$resultsecho = $resultsecho. '<li><span class="brnprevclass wppro_page_numbers">'.$x.'</span></li>';
						} 
					} else if ($lastslidenumb>3){
						$resultsecho = $resultsecho. '
							<li><span class="brnprevclass wppro_page_numbers">2</span></li>
							<li><span class="brnprevclass wppro_page_dots">…</span></li>
							<li><span class="brnprevclass wppro_page_numbers">'.$lastslidenumb.'</span></li>
							<li><span class="brnprevclass wppro_page_numbers">></span></li>
						';
					}
					$resultsecho = $resultsecho. '</ul><img src="'.$loading_img_url.'" class="wprppagination_loading_image '.$imageclassslideshow.'" style="display:none;"></div>';
				
			}
		}
		$results['jslastslide']=$jslastslide;
		$results['echothis']=$resultsecho;
		
		return $results;
	}
	
	public function wppro_getgooglesnippet($currentform,$totalcount,$totalavg,$totalreviews=''){
		$google_snippet_add ='';
		$google_snippet_type ='';
		$google_snippet_name ='';
		$google_snippet_desc ='';
		$google_snippet_business_image ='';
		$google_snippet_more_array_encode ='';
		$tempsnippethtml ='';
		
		//snippet
		$google_snippet_add =$currentform[0]->google_snippet_add;
		$google_snippet_type =$currentform[0]->google_snippet_type;
		$google_snippet_name =stripslashes($currentform[0]->google_snippet_name);
		$google_snippet_desc =stripslashes($currentform[0]->google_snippet_desc);
		$google_snippet_business_image =$currentform[0]->google_snippet_business_image;
		$google_snippet_more_array_encode =$currentform[0]->google_snippet_more;
		
		//turn on google snippet if set to yes
		
		if($google_snippet_add=="yes" && $totalavg>0){
			
			$google_misc_array = json_decode($google_snippet_more_array_encode, true);
			if(!is_array($google_misc_array) && $google_snippet_type!='Product'){
				$google_misc_array=array();
				$google_misc_array['telephone']="";
				$google_misc_array['priceRange']="";
				$google_misc_array['streetAddress']="";
				$google_misc_array['addressLocality']="";
				$google_misc_array['addressRegion']="";
				$google_misc_array['postalCode']="";
			}
			if($google_misc_array['streetAddress']!='' || $google_misc_array['addressLocality']!='' || $google_misc_array['addressRegion']!='' || $google_misc_array['postalCode']!=''){
				$gsaddress = ', "address": {"@type": "PostalAddress","addressLocality": "'.$google_misc_array['addressLocality'].'","addressRegion": "'.stripslashes($google_misc_array['addressRegion']).'","postalCode": "'.$google_misc_array['postalCode'].'","streetAddress": "'.stripslashes($google_misc_array['streetAddress']).'"}';
			} else {
				$gsaddress = '';
			}
			if($google_misc_array['telephone']!=''){
				$gsphone = ', "telephone": "'.$google_misc_array['telephone'].'"';
			} else {
				$gsphone = '';
			}
			if($google_misc_array['priceRange']!=''){
				$gsprice = ', "priceRange": "'.$google_misc_array['priceRange'].'"';
			} else {
				$gsprice = '';
			}
			if(isset($google_misc_array['schemaid']) && $google_misc_array['schemaid']!=''){
				$schemaid = $google_misc_array['schemaid'];
				$schemaidtext = '"@id": "'.$schemaid .'",';
			} else {
				$schemaidtext = '';
			}
			
			//add product stuff here if set
			$prodmoretxt='';
			if($google_snippet_type=='Product'){
				if(!isset($google_misc_array['brand'])){
					$google_misc_array['brand']="";
					$google_misc_array['price']="";
					$google_misc_array['priceCurrency']="";
					$google_misc_array['sku']="";
					$google_misc_array['giname']="";
					$google_misc_array['gival']="";
					$google_misc_array['url']="";
					$google_misc_array['availability']="";
					$google_misc_array['priceValidUntil']="";
				}
				if($google_misc_array['brand']!=''){
					$prodmoretxt=', "brand": {"@type": "Thing","name": "'.$google_misc_array['brand'].'"}';
				}
				if($google_misc_array['price']!='' || $google_misc_array['priceCurrency']!=''){
					$prodmoretxt=$prodmoretxt.', 
					  "offers": {
						"@type": "Offer",
						"url": "'.$google_misc_array['url'].'",
						"priceCurrency": "'.$google_misc_array['priceCurrency'].'",
						"price": "'.$google_misc_array['price'].'",
						"availability": "'.$google_misc_array['availability'].'",
						"priceValidUntil": "'.$google_misc_array['priceValidUntil'].'"
						}';
				}
				if($google_misc_array['sku']!=''){
					$prodmoretxt=$prodmoretxt.', "sku": "'.$google_misc_array['sku'].'"';
				}
				if($google_misc_array['giname']!='' && $google_misc_array['gival']!=''){
					$prodmoretxt=$prodmoretxt.', "'.$google_misc_array['giname'].'": "'.$google_misc_array['gival'].'"';
				}

			}
			
			//add the individual review markup if set
			$irmtext = '';
			$reviewmarkuparray = Array();
			if(isset($google_misc_array['irm']) && $google_misc_array['irm']=='yes' && $totalreviews!=''){
				$filtertype = $google_misc_array['irm_type'];
				for ($x = 0; $x < count($totalreviews); $x++) {
					if(isset($totalreviews[$x]->type)){
						if($filtertype=='Manual' && $totalreviews[$x]->type=='Manual'){
							$reviewmarkuparray[$x] = $totalreviews[$x];
						}
						if($filtertype=='Submitted' && $totalreviews[$x]->type=='Submitted'){
							$reviewmarkuparray[$x] = $totalreviews[$x];
						}
						if($filtertype=='ManualSubmitted' && ($totalreviews[$x]->type=='Submitted' || $totalreviews[$x]->type=='Manual')){
							$reviewmarkuparray[$x] = $totalreviews[$x];
						}
						if($filtertype=='all'){
							$reviewmarkuparray[$x] = $totalreviews[$x];
						}
					}
				}
			}
			if(count($reviewmarkuparray)>0){
				$reviewmarkuparray = array_values($reviewmarkuparray);
				$irmtext = ', "review": [';
				for ($x = 0; $x < count($reviewmarkuparray); $x++) {
					$ratingvalue = 5;
					if($reviewmarkuparray[$x]->rating < 1){
						if($reviewmarkuparray[$x]->recommendation_type=='negative'){
							$ratingvalue = 2;
						}
					} else {
						$ratingvalue = $reviewmarkuparray[$x]->rating;
					}
					
					if($x >0){
						$irmtext = $irmtext .',';
					}
					$reviewtext = stripslashes($reviewmarkuparray[$x]->review_text);
					$reviewtext = str_replace( '"', '\"', $reviewtext);
					$reviewtext = str_replace(array("\r", "\n", "<br>", "</br>"), '', $reviewtext);
					$irmtext = $irmtext . '{
							"@type": "Review",
							"reviewRating": {
							  "@type": "Rating",
							  "ratingValue": "'.$ratingvalue.'"
							},
							"author": {
							  "@type": "Person",
							  "name": "'.$reviewmarkuparray[$x]->reviewer_name.'"
							},
							"reviewBody": "'.$reviewtext.'"
						  }';
				}
				$irmtext = $irmtext .']';
			}
			
			$ratingcounthtml = "ratingCount";
			if(isset($google_misc_array['tvr']) && $google_misc_array['tvr']!=''){
				if($google_misc_array['tvr']=='reviews'){
					$ratingcounthtml = "reviewCount";
				}
			}
			
			$tempsnippethtml = '<script type="application/ld+json">{'.$schemaidtext.'"@context": "http://schema.org/","@type": "'.$google_snippet_type.'","name": "'.$google_snippet_name.'","description": "'.$google_snippet_desc.'","aggregateRating": {"@type": "AggregateRating","ratingValue": "'.$totalavg.'","'.$ratingcounthtml.'": "'.$totalcount.'","bestRating": "5","worstRating": "1"},"image": "'.$google_snippet_business_image.'"'.$gsaddress.$gsphone.$gsprice.$prodmoretxt.$irmtext.'}</script>';

		}
		
		return $tempsnippethtml;
	}
	
	//get style code html
	public function wppro_gettemplatestylecode($currentform,$iswidget,$template_misc_array){
		
		//add styles from template misc here
		$templatestylecode = '';
		if(is_array($template_misc_array)){
			$misc_style ="";
			
			//template margins desktop
			if(isset($template_misc_array['template_margin_tb'])){
				if($template_misc_array['template_margin_tb']>0){
					$misc_style = $misc_style .'@media screen and (min-width: 600px) {';
					$misc_style = $misc_style .'div#wprev-slider-'.$currentform[0]->id.'{margin-top: '.intval($template_misc_array['template_margin_tb']).'px;}';
					$misc_style = $misc_style .'div#wprev-slider-'.$currentform[0]->id.'{margin-bottom: '.intval($template_misc_array['template_margin_tb']).'px;}';
					$misc_style = $misc_style .'}';
				}
			}
			if(isset($template_misc_array['template_margin_lr'])){
				if($template_misc_array['template_margin_lr']>0){
					$misc_style = $misc_style .'@media screen and (min-width: 800px) {';
					$misc_style = $misc_style .'div.wprs_unslider {margin-left: '.intval($template_misc_array['template_margin_lr']).'px;}';
					$misc_style = $misc_style .'div.wprs_unslider {margin-right: '.intval($template_misc_array['template_margin_lr']).'px;}';
					
					$misc_style = $misc_style .'div#wprev-slider-'.$currentform[0]->id.'.wprev-slick-slider {margin-left: '.intval($template_misc_array['template_margin_lr']).'px;}';
					$misc_style = $misc_style .'div#wprev-slider-'.$currentform[0]->id.'.wprev-slick-slider {margin-right: '.intval($template_misc_array['template_margin_lr']).'px;}';
					
					$misc_style = $misc_style .'}';
				}
			}
			//template margins mobile
			if(isset($template_misc_array['template_margin_tb_m'])){
				if($template_misc_array['template_margin_tb_m']>0){
					$misc_style = $misc_style .'@media only screen and (max-width: 600px) {';
					$misc_style = $misc_style .'div#wprev-slider-'.$currentform[0]->id.'{margin-top: '.intval($template_misc_array['template_margin_tb_m']).'px;}';
					$misc_style = $misc_style .'div#wprev-slider-'.$currentform[0]->id.'{margin-bottom: '.intval($template_misc_array['template_margin_tb_m']).'px;}';
					$misc_style = $misc_style .'}';
				}
			}
			if(isset($template_misc_array['template_margin_lr_m'])){
				if($template_misc_array['template_margin_lr_m']>0){
					$misc_style = $misc_style .'@media only screen and (max-width: 600px) {';
					$misc_style = $misc_style .'div.wprs_unslider {margin-left: '.intval($template_misc_array['template_margin_lr_m']).'px;}';
					$misc_style = $misc_style .'div.wprs_unslider {margin-right: '.intval($template_misc_array['template_margin_lr_m']).'px;}';
					$misc_style = $misc_style .'div#wprev-slider-'.$currentform[0]->id.'.wprev-slick-slider {margin-left: '.intval($template_misc_array['template_margin_lr_m']).'px;}';
					$misc_style = $misc_style .'div#wprev-slider-'.$currentform[0]->id.'.wprev-slick-slider {margin-right: '.intval($template_misc_array['template_margin_lr_m']).'px;}';

					$misc_style = $misc_style .'}';
				}
			}
			
			//hide stars and/or date
			if(isset($template_misc_array['showstars']) && $template_misc_array['showstars']=="no"){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprevpro_star_imgs_T'.$currentform[0]->style.$iswidget.' {display: none;}';
			}
			//if(isset($template_misc_array['showdate']) && $template_misc_array['showdate']=="no"){
			//	$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_showdate_T'.$currentform[0]->style.$iswidget.' {display: none;}';
			//}
			//in case not set
			if(!isset($template_misc_array['starcolor'])){
				$template_misc_array['starcolor']='#FDD314';
			}
			
			$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprevpro_star_imgs{color: '.$template_misc_array['starcolor'].';}';
			$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprsp-star{color: '.$template_misc_array['starcolor'].';}';
			
			if(isset($template_misc_array['bradius'])){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_bradius_T'.$currentform[0]->style.$iswidget.' {border-radius: '.$template_misc_array['bradius'].'px;}';
			}
			if(isset($template_misc_array['bgcolor1'])){
			$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.' {background:'.$template_misc_array['bgcolor1'].';}';
			}
			if(isset($template_misc_array['bgcolor2'])){
			$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_bg2_T'.$currentform[0]->style.$iswidget.' {background:'.$template_misc_array['bgcolor2'].';}';
			}
			if(isset($template_misc_array['tcolor1'])){
			$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_tcolor1_T'.$currentform[0]->style.$iswidget.' {color:'.$template_misc_array['tcolor1'].';}';
			}
			if(isset($template_misc_array['tcolor2'])){
			$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_tcolor2_T'.$currentform[0]->style.$iswidget.' {color:'.$template_misc_array['tcolor2'].';}';
			}
			
			if(isset($template_misc_array['tfont1']) && $template_misc_array['tfont1']>0){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_tcolor1_T'.$currentform[0]->style.$iswidget.' {font-size:'.$template_misc_array['tfont1'].'px;line-height: normal;}';
			}
			if(isset($template_misc_array['tfont2']) && $template_misc_array['tfont2']>0){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_tcolor2_T'.$currentform[0]->style.$iswidget.' {font-size:'.$template_misc_array['tfont2'].'px;line-height: normal;}';
			}
			
			//style specific mods 	div > p
			if($currentform[0]->style=="1"){
				if(isset($template_misc_array['bgcolor1'])){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.'::after{ border-top: 30px solid '.$template_misc_array['bgcolor1'].'; }';
				}
			}
			if($currentform[0]->style=="2"){
				if(isset($template_misc_array['bgcolor2'])){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.' {border-bottom:3px solid '.$template_misc_array['bgcolor2'].'}';
				}
			}
			if($currentform[0]->style=="5"){
				if(isset($template_misc_array['bgcolor2'])){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.' {border-bottom:3px solid '.$template_misc_array['bgcolor2'].'}';
				}
			}
			if($currentform[0]->style=="3"){
				if(isset($template_misc_array['tcolor3'])){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_tcolor3_T'.$currentform[0]->style.$iswidget.' {text-shadow:'.$template_misc_array['tcolor3'].' 1px 1px 0px;}';
				}
				if(isset($template_misc_array['tfont2']) && $template_misc_array['tfont2']>0){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_tcolor3_T'.$currentform[0]->style.$iswidget.' {font-size:'.$template_misc_array['tfont2'].'px;line-height: normal;}';
				}
			}
			if($currentform[0]->style=="4"){
				if(isset($template_misc_array['tcolor3'])){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_tcolor3_T'.$currentform[0]->style.$iswidget.' {color:'.$template_misc_array['tcolor3'].';}';
				}
			}
			if($currentform[0]->style=="6"){
				if(isset($template_misc_array['bgcolor2'])){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprev_preview_bg1_T'.$currentform[0]->style.$iswidget.' {border:1px solid '.$template_misc_array['bgcolor2'].'}';
				}
			}
			if($currentform[0]->style=="7"){
				if(isset($template_misc_array['bgcolor2'])){
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wpproslider_t'.$currentform[0]->style.$iswidget.'_DIV_2 {border:1px solid '.$template_misc_array['bgcolor2'].'}';
				}
			}
			if(isset($template_misc_array['readmcolor'])){
			$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.$iswidget.' .wprs_rd_more,.wprs_rd_less{color:'.$template_misc_array['readmcolor'].';}';
			}
			//------------------------
			//echo "<style>".$misc_style."</style>";
			$templatestylecode = "<style>".$misc_style."</style>";
		}
		
		//check if we should be hiding navigation dots, fix for load more readding them
		if($currentform[0]->sliderdots!="" && $currentform[0]->sliderdots=='no'){
			$sliderdotscss = '#wprev-slider-'.$currentform[0]->id.$iswidget.' + .wprs_unslider-nav ol {display:none;}';
			$templatestylecode = $templatestylecode . "<style>".$sliderdotscss."</style>";
		}
		//check if we are hiding arrows on mobile
		if($currentform[0]->sliderarrows=='des'){
			if($currentform[0]->createslider == "sli"){
				$arrowhidecss = '@media only screen and (max-width: 600px) { 
				#wprev-slider-'.$currentform[0]->id.' .slick-arrow, #wprev-slider-'.$currentform[0]->id.'_widget .slick-arrow {display:none !important;}}';
			} else {
				$arrowhidecss = '@media only screen and (max-width: 600px) { .wprs_nav_arrow_'.$currentform[0]->id.$iswidget.' {display:none;}}';
			}
			$templatestylecode = $templatestylecode . "<style>".$arrowhidecss."</style>";
		}
		
		//print out user style added
		//echo "<style>".$currentform[0]->template_css."</style>";
		if($currentform[0]->template_css!=''){
			$templatestylecode = $templatestylecode . "<style>".stripslashes(sanitize_text_field($currentform[0]->template_css))."</style>";
		}
		
		//add pagination style if set.
		$paginationstyle = '';
		if(isset($template_misc_array['ps_bw']) && $template_misc_array['ps_bw']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{border-width:".intval($template_misc_array['ps_bw'])."px !important}";
		}
		if(isset($template_misc_array['ps_br']) && $template_misc_array['ps_br']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{border-radius:".intval($template_misc_array['ps_br'])."px !important}";
		}
		if(isset($template_misc_array['ps_bcolor']) && $template_misc_array['ps_bcolor']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{border-color:".$template_misc_array['ps_bcolor']." !important}";
		}
		if(isset($template_misc_array['ps_bgcolor']) && $template_misc_array['ps_bgcolor']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{background-color:".$template_misc_array['ps_bgcolor']." !important}";
			$paginationstyle = $paginationstyle . ".brnprevclass:hover{background-color:#00000066 !important}";
			$paginationstyle = $paginationstyle . ".brnprevclass.current{background-color:#00000066 !important}";
			
		}
		if(isset($template_misc_array['ps_fontcolor']) && $template_misc_array['ps_fontcolor']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{color:".$template_misc_array['ps_fontcolor']." !important}";
		}
		if(isset($template_misc_array['ps_fsize']) && $template_misc_array['ps_fsize']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{font-size:".intval($template_misc_array['ps_fsize'])."px !important}";
		}
		if(isset($template_misc_array['ps_paddingt']) && $template_misc_array['ps_paddingt']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{padding-top:".intval($template_misc_array['ps_paddingt'])."px !important}";
		}
		if(isset($template_misc_array['ps_paddingb']) && $template_misc_array['ps_paddingb']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{padding-bottom:".intval($template_misc_array['ps_paddingb'])."px !important}";
		}
		if(isset($template_misc_array['ps_paddingl']) && $template_misc_array['ps_paddingl']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{padding-left:".intval($template_misc_array['ps_paddingl'])."px !important}";
		}
		if(isset($template_misc_array['ps_paddingr']) && $template_misc_array['ps_paddingr']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{padding-right:".intval($template_misc_array['ps_paddingr'])."px !important}";
		}
		if(isset($template_misc_array['ps_margint']) && $template_misc_array['ps_margint']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{margin-top:".intval($template_misc_array['ps_margint'])."px !important}";
		}
		if(isset($template_misc_array['ps_marginb']) && $template_misc_array['ps_marginb']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{margin-bottom:".intval($template_misc_array['ps_marginb'])."px !important}";
		}
		if(isset($template_misc_array['ps_marginl']) && $template_misc_array['ps_marginl']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{margin-left:".intval($template_misc_array['ps_marginl'])."px !important}";
		}
		if(isset($template_misc_array['ps_marginr']) && $template_misc_array['ps_marginr']!=''){
			$paginationstyle = $paginationstyle . ".brnprevclass{margin-right:".intval($template_misc_array['ps_marginr'])."px !important}";
		}
		if($paginationstyle!=''){
			$paginationstyle = "<style>".$paginationstyle."</style>";
			$templatestylecode = $templatestylecode . $paginationstyle;
		}
		
		//remove line breaks and tabs
		$templatestylecode = str_replace(array("\n", "\t", "\r"), '', $templatestylecode);
		//echo $templatestylecode;
		
		//add masonry style css if this is a slideshow only, doing this with js if we are in grid
		$masonrystyle = '';
		//if(	$currentform[0]->display_masonry=="yes" && $currentform[0]->createslider == "yes"){
		if(	$currentform[0]->display_masonry=="yes" && $currentform[0]->createslider == "yes"){
					$tempdisplaynum['xs']= $currentform[0]->display_num-3;
					if($tempdisplaynum['xs']<1){
						$tempdisplaynum['xs']=1;
					}
					$tempdisplaynum['s']= $currentform[0]->display_num-2;
					if($tempdisplaynum['s']<1){
						$tempdisplaynum['s']=1;
					}
					$tempdisplaynum['m']= $currentform[0]->display_num-1;
					if($tempdisplaynum['m']<1){
						$tempdisplaynum['m']=1;
					}
					$tempdisplaynum['l']= $currentform[0]->display_num;
					if($tempdisplaynum['l']<1){
						$tempdisplaynum['l']=1;
					}
			$misc_masonry_style = '.wprs_masonry {margin: 0 5px 0 5px;padding: 0;}.wprs_masonry_item {display: grid;width: 100%;padding-top: 5px;margin-bottom: 10px;margin-top: 0px;break-inside: avoid;-webkit-column-break-inside: avoid;page-break-inside: avoid;}@media only screen and (min-width: 400px) {.wprs_masonry {-moz-column-count: '.$tempdisplaynum['xs'].';-webkit-column-count: '.$tempdisplaynum['xs'].';	column-count: '.$tempdisplaynum['xs'].';}}@media only screen and (min-width: 700px) {.wprs_masonry {-moz-column-count: '.$tempdisplaynum['s'].';-webkit-column-count: '.$tempdisplaynum['s'].';column-count: '.$tempdisplaynum['s'].';}}@media only screen and (min-width: 900px) {.wprs_masonry {-moz-column-count: '.$tempdisplaynum['m'].';-webkit-column-count: '.$tempdisplaynum['m'].';column-count: '.$tempdisplaynum['m'].';}}@media only screen and (min-width: 1100px) {.wprs_masonry {-moz-column-count: '.$tempdisplaynum['l'].';-webkit-column-count: '.$tempdisplaynum['l'].';column-count: '.$tempdisplaynum['l'].';}}';
			$masonrystyle = "<style>".$misc_masonry_style."</style>";
			echo $masonrystyle;
		}
		$templatestylecode = $templatestylecode . $masonrystyle;
		
		return $templatestylecode;
	}
	

	
}
	//========================================
	
	?>