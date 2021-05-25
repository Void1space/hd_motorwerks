<?php
/**
 * Provide a admin area view for the create legal pages.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    Wplegalpages
 * @subpackage Wplegalpages/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$lp_obj           = new WP_Legal_Pages();
$baseurl          = esc_url( get_bloginfo( 'url' ) );
$privacy          = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/privacy.html' );
$lp_pro_installed = get_option( '_lp_pro_installed' );
?>
<div class="wrap">
	<?php
	if ( '1' !== $lp_pro_installed ) :
		?>
	<div style="">
		<div style="line-height: 2.4em;" class='wplegalpages-pro-promotion'>
			<a href="https://club.wpeka.com/product/wplegalpages/?utm_source=plugin-banner&utm_campaign=wplegalpages&utm_content=upgrade-to-pro" target="_blank">
				<img alt="Upgrade to Pro" src="<?php echo esc_attr( WPL_LITE_PLUGIN_URL ) . 'admin/images/upgrade-to-pro.jpg'; ?>">
			</a>
		</div>
	</div>
	<div style="clear:both;"></div>
		<?php
	endif;
	if ( ! empty( $_POST ) && isset( $_POST['lp-submit'] ) && 'Publish' === $_POST['lp-submit'] ) :
		check_admin_referer( 'lp-submit-create-page' );
		$page_title    = isset( $_POST['lp-title'] ) ? sanitize_text_field( wp_unslash( $_POST['lp-title'] ) ) : '';
		$content       = isset( $_POST['lp-content'] ) ? wp_kses_post( wp_unslash( $_POST['lp-content'] ) ) : '';
		$template_lang = isset( $_POST['template_language'] ) ? sanitize_text_field( wp_unslash( $_POST['template_language'] ) ) : '';
		$post_args     = array(
			'post_title'   => apply_filters( 'the_title', $page_title ),
			'post_content' => $content,
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_author'  => 1,
		);
		$pid           = wp_insert_post( $post_args );
		update_post_meta( $pid, 'is_legal', 'yes' );
		$url = get_permalink( $pid );
		?>
	<div id="message">
		<p><span class="label label-success myAlert"><?php esc_attr_e( 'Page Successfully Created. You can view your page as a normal Page in Pages Menu.', 'wplegalpages' ); ?> </span></p>
		<p><a href="<?php echo esc_url_raw( get_admin_url() ); ?>/post.php?post=<?php echo esc_attr( $pid ); ?>&action=edit"><?php esc_attr_e( 'Edit', 'wplegalpages' ); ?></a> | <a href="<?php echo esc_url( $url ); ?>"><?php esc_attr_e( 'View', 'wplegalpages' ); ?></a></p>
	</div>
		<?php
	endif;
	$current_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
	$lptype       = isset( $_REQUEST['lp-type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['lp-type'] ) ) : '';
	$template     = isset( $_REQUEST['lp-template'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['lp-template'] ) ) : '';
	if ( ! isset( $template_lang ) || empty( $template_lang ) ) {
		$template_lang = isset( $_REQUEST['lp-template-lang'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['lp-template-lang'] ) ) : 'eng';
	}
	$general  = get_option( 'wpgattack_general' );
	$checked  = 'checked="checked"';
	$selected = 'selected="selected"';
	?>

<?php
	global $wpdb;
	$post_tbl      = $wpdb->prefix . 'posts';
	$postmeta_tbl  = $wpdb->prefix . 'postmeta';
	$countof_pages = $wpdb->get_results( $wpdb->prepare( 'SELECT count(meta_id) as cntPages FROM ' . $post_tbl . ' as ptbl, ' . $postmeta_tbl . ' as pmtbl WHERE ptbl.ID = pmtbl.post_id and ptbl.post_status=%s AND pmtbl.meta_key =  %s', array( 'publish', 'is_legal' ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

	$max_limit = 15;
	$max_limit = apply_filters( 'wplegalpages_pages_limit', $max_limit );
if ( $countof_pages[0]->cntPages < $max_limit ) {
	?>

<div class="postbox ">
	<h3 class="hndle myLabel-head"  style="cursor:pointer; padding:7px 10px; font-size:20px;"> <?php esc_attr_e( 'Create Page :', 'wplegalpages' ); ?></h3>
	<div id="lp_generalid">

	<p>&nbsp;&nbsp</p>
		<form name="terms" method="post" enctype="multipart/form-data">
	<?php
	if ( ! empty( $template ) ) {

		$row = $wpdb->get_row( $wpdb->prepare( 'SELECT * from ' . $lp_obj->tablename . ' where id=%d', array( $template ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
	}
	?>
			<p><input type="text" class="form-control myText" name="lp-title" id="lp-title"
		<?php if ( isset( $row->title ) ) { ?>
				value="<?php echo esc_attr( $row->title ); ?>"
			<?php } else { ?>
				value="<?php esc_attr_e( 'Privacy Policy', 'wplegalpages' ); ?>"
			<?php } ?>
				/></p>
			<p>
			<div id="poststuff">
				<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" >
				<?php
				$content    = isset( $row->content ) ? $row->content : $privacy;
				$lp_find    = array( '[Domain]', '[Business Name]', '[Phone]', '[Street]', '[City, State, Zip code]', '[Country]', '[Email]', '[Address]', '[Niche]' );
				$lp_general = get_option( 'lp_general' );
				$content    = str_replace( $lp_find, $lp_general, stripslashes( $content ) );
				if ( ! shortcode_exists( 'wpl_cookie_details' ) ) {
					$content = str_replace( '[wpl_cookie_details]', '', stripslashes( $content ) );
				}
				$content   = apply_filters( 'wplegalpages_shortcode_content', $content );
				$editor_id = 'lp-content';
				$args      = array();
				wp_editor( stripslashes( html_entity_decode( $content ) ), 'content', $args );
				?>
				</div>
					<script type="text/javascript">

					function sp_content_save(){
						var obj = document.getElementById('lp-content');

						var content = document.getElementById('content');
						console.log(content);
						tinyMCE.triggerSave(0,1);
						obj.value = content.value;
					}


					</script>
					<textarea id="lp-content" name="lp-content" value="5" style="display:none" rows="10"></textarea>
			</div></p>
			<p>
				<?php
				if ( function_exists( 'wp_nonce_field' ) ) {
					wp_nonce_field( 'lp-submit-create-page' );
				}
				?>
			<input type="submit"  class="btn btn-primary mybtn" onclick="sp_content_save();" name="lp-submit" value="Publish" />
			</p>

		</form>
	</div>
<div class="lp_generalid_right_wraper" style="min-height:900px;">
	<div id="lp_generalid_right" class="postbox ">
		<h3 class="hndle"  style="cursor:pointer; padding:0px 10px 12px 10px; font-size:20px;"> <?php esc_attr_e( 'Choose Template', 'wplegalpages' ); ?> </h3>
		<div class="wplegal-choose-template">
		<select name="template_language" class="wplegal-template-language">
			<option value="eng" 
			<?php
			if ( 'eng' === $template_lang ) {
				echo 'selected';}
			?>
			>English</option>
			<option value="fr" 
			<?php
			if ( 'fr' === $template_lang ) {
				echo 'selected';}
			?>
			>French</option>
			<option value="de" 
			<?php
			if ( 'de' === $template_lang ) {
				echo 'selected';}
			?>
			>German</option>
		</select>
		</div>
		<ul class="wplegal-templates">
		<?php

		$result = $wpdb->get_results( $wpdb->prepare( 'select * from ' . $lp_obj->tablename . ' where `is_active`=%d ORDER BY `id`', array( 1 ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		foreach ( $result as $ras ) {
			if ( strpos( $ras->title, 'FR' ) !== false ) {
				if ( 'fr' === $template_lang ) :
					?>
					<li class="wplegal-template-fr"><span id="legalpages<?php echo esc_attr( $ras->id ); ?>"><a class="myLink" href="<?php echo esc_url( admin_url() ); ?>admin.php?page=<?php echo esc_attr( $current_page ); ?>&lp-template-lang=fr&lp-type=<?php echo esc_attr( $lptype ); ?>&lp-template=<?php echo esc_attr( $ras->id ); ?>"><?php echo esc_attr( $ras->title ); ?> &raquo;</a></span></li>
				<?php else : ?>
					<li style="display:none;" class="wplegal-template-fr"><span id="legalpages<?php echo esc_attr( $ras->id ); ?>"><a class="myLink" href="<?php echo esc_url( admin_url() ); ?>admin.php?page=<?php echo esc_attr( $current_page ); ?>&lp-template-lang=fr&lp-type=<?php echo esc_attr( $lptype ); ?>&lp-template=<?php echo esc_attr( $ras->id ); ?>"><?php echo esc_attr( $ras->title ); ?> &raquo;</a></span></li>
				<?php endif; ?>
			<?php } elseif ( strpos( $ras->title, 'DE' ) !== false ) { ?>
				<?php if ( 'de' === $template_lang ) : ?>
					<li class="wplegal-template-de"><span id="legalpages<?php echo esc_attr( $ras->id ); ?>"><a class="myLink" href="<?php echo esc_url( admin_url() ); ?>admin.php?page=<?php echo esc_attr( $current_page ); ?>&lp-template-lang=de&lp-type=<?php echo esc_attr( $lptype ); ?>&lp-template=<?php echo esc_attr( $ras->id ); ?>"><?php echo esc_attr( $ras->title ); ?> &raquo;</a></span></li>
				<?php else : ?>
					<li style="display:none;" class="wplegal-template-de"><span id="legalpages<?php echo esc_attr( $ras->id ); ?>"><a class="myLink" href="<?php echo esc_url( admin_url() ); ?>admin.php?page=<?php echo esc_attr( $current_page ); ?>&lp-template-lang=de&lp-type=<?php echo esc_attr( $lptype ); ?>&lp-template=<?php echo esc_attr( $ras->id ); ?>"><?php echo esc_attr( $ras->title ); ?> &raquo;</a></span></li>
				<?php endif; ?>
			<?php } else { ?>
				<?php if ( 'eng' === $template_lang ) : ?>
					<li class="wplegal-template-eng"><span id="legalpages<?php echo esc_attr( $ras->id ); ?>"><a class="myLink" href="<?php echo esc_url( admin_url() ); ?>admin.php?page=<?php echo esc_attr( $current_page ); ?>&lp-template-lang=eng&lp-type=<?php echo esc_attr( $lptype ); ?>&lp-template=<?php echo esc_attr( $ras->id ); ?>"><?php echo esc_attr( $ras->title ); ?> &raquo;</a></span></li>
				<?php else : ?>
					<li style="display:none;" class="wplegal-template-eng"><span id="legalpages<?php echo esc_attr( $ras->id ); ?>"><a class="myLink" href="<?php echo esc_url( admin_url() ); ?>admin.php?page=<?php echo esc_attr( $current_page ); ?>&lp-template-lang=eng&lp-type=<?php echo esc_attr( $lptype ); ?>&lp-template=<?php echo esc_attr( $ras->id ); ?>"><?php echo esc_attr( $ras->title ); ?> &raquo;</a></span></li>
				<?php endif; ?>
				<?php
			}
		}
		?>

		</ul>
	</div>
		<?php if ( '1' !== $lp_pro_installed ) : ?>
		<div id="lp_generalid_right">
			<a href="https://club.wpeka.com/product/wplegalpages/?utm_source=plugin&utm_campaign=wplegalpages&utm_content=upgrade-to-pro-for-all-templates" style="text-decoration:none;padding-left:20px;" target="_blank">
			<?php esc_attr_e( 'Upgrade to Pro for All templates', 'wplegalpages' ); ?>
			</a>
		</div>

		<div id="lp_generalid_right" class="postbox ">
			<h3 class="hndle"  style="padding:0px 10px 12px 10px; font-size:20px;"> <?php esc_attr_e( 'WP LegalPages Pro Templates', 'wplegalpages' ); ?> </h3><br/>
			<ul>
				<li>Terms of use <strong>(forced agreement - don't allow your users to proceed without agreeing to your terms)</strong></li>
				<li>Linking policy template</li>
                <li>About Us</li>
				<li>External links policy template</li>
				<li>Refund policy template</li>
				<li>Affiliate disclosure template</li>
                <li>Cookies Policy</li>
				<li>Privacy Policy template</li>
				<li>Affiliate agreement template</li>
				<li>FB privacy policy template</li>
				<li>Earnings Disclaimer template</li>
				<li>Antispam template</li>
				<li>Double dart cookie template</li>
				<li>Disclaimer template (English, French, German)</li>
				<li>FTC statement template</li>
				<li>Medical disclaimer template</li>
				<li>Testimonials disclosure template</li>
				<li>Amazon affiliate template</li>
				<li>DMCA policy</li>
				<li>California Privacy Rights</li>
				<li>Blog Comment Policy</li>
				<li>Children's Online Privacy Protection Act</li>
				<li>Digital Products Refund Policy</li>
				<li>Newsletter Subscription and Disclaimer template</li>
                <li>Confidentiality Discloser template</li>
				<li>Return Refund Policy template</li>
				<li>GDPR Cookie Policy template</li>
				<li>GDPR Privacy Policy template (English, French, German)</li>
			</ul>
		</div>
	<?php endif; ?>
</div>
<?php } else { ?>
		<div id="message" class="updated">
			<p><?php esc_attr_e( 'You are exceeding the limit of creating 15 legal pages.', 'wplegalpages' ); ?></p>
		</div>
<?php } ?>
</div>
