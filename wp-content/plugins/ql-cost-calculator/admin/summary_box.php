<div id="summary-box-modal" class="cost-calculator-modal cost-calculator-element-modal" title="<?php esc_attr_e("Summary Box Settings", 'cost-calculator'); ?>">
	<form action="#" class="cost-calculator-modal-form" id="summary-box-modal-form">
		<div class="cost-calculator-form-container">
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Id", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="id" value="cost">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Name", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="name" value="total_cost">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Formula", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="formula" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Currency", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="currency" value="$">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Currency size", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="currency_size" class="cost-calculator-modal-dropdown">
						<option value="default" selected="selected"><?php _e("Default", 'cost-calculator'); ?></option>
						<option value="small"><?php _e("Small", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix currency-size-depends">
				<div class="cost-calculator-column-left">
					<label><?php _e("Currency vertical align", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="currency_align" class="cost-calculator-modal-dropdown">
						<option value="top" selected="selected"><?php _e("Top", 'cost-calculator'); ?></option>
						<option value="bottom"><?php _e("Bottom", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Currency sign position", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="currency_position" class="cost-calculator-modal-dropdown">
						<option value="before" selected="selected"><?php _e("before value", 'cost-calculator'); ?></option>
						<option value="after"><?php _e("after value", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Thousandth separator", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="thousandth_separator" value=",">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Decimal separator", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="decimal_separator" value=".">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Decimal places", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="decimal_places" value="2">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Display not number result as 0", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="not_number" class="cost-calculator-modal-dropdown">
						<option value="1" selected="selected"><?php _e("Yes", 'cost-calculator'); ?></option>
						<option value="0"><?php _e("No", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Description", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="description" value="">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Icon", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<select name="icon" class="cost-calculator-modal-dropdown">
						<option value="" selected="selected"><?php _e("none", 'cost-calculator'); ?></option>
						<option value="cc-template-calculation"><?php _e("calculation", 'cost-calculator'); ?></option>
						<option value="cc-template-card"><?php _e("credit card", 'cost-calculator'); ?></option>
						<option value="cc-template-wallet"><?php _e("wallet", 'cost-calculator'); ?></option>
					</select>
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Label", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="label" value="<?php esc_attr_e("Total cost: ", 'cost-calculator'); ?>">
				</div>
			</div>
			<div class="cost-calculator-form-container-row cost-calculator-clearfix">
				<div class="cost-calculator-column-left">
					<label><?php _e("Extra class name", 'cost-calculator'); ?></label>
				</div>
				<div class="cost-calculator-column-right">
					<input type="text" name="el_class" value="">
				</div>
			</div>
		</div>
		<input type="hidden" name="shortcode" value="cost_calculator_summary_box">
	</form>
</div>