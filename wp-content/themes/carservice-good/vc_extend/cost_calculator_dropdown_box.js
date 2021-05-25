(function($){
	$('body').on('change', ".vc_ui-panel [name='options_count']", function(){
		var self = $(this);
		var multipler = $(".vc_ui-panel [name$='29']").length;
		$(".vc_ui-panel [name^='option_name'], .vc_ui-panel [name^='option_value']").parent().parent().addClass("wpb_el_type_hidden");
		self.parent().parent().nextUntil('', ':lt(' + (self.val()*multipler) + ')').removeClass("wpb_el_type_hidden");
	});
	vc.atts.dropdown = {
		render: function ( param, value ) {
			return value;
		},
		init: function ( param, $field ) {
			$( '.wpb_vc_param_value.dropdown', $field ).change( function () {
				var $this = $( this ),
					$options = $this.find( ':selected' ),
					prev_option_class = $this.data( 'option' ),
					option_class = $options.length ? $options.attr( 'class' ).replace( /\s/g, '_' ) : '';
				prev_option_class != undefined && $this.removeClass( prev_option_class );
				option_class != undefined && $this.data( 'option', option_class ) && $this.addClass( option_class );
			} ).trigger("change");
		},
		defaults: function ( param ) {
			var values;
			if ( ! _.isArray( param.value ) && ! _.isString( param.value ) ) {
				values = _.values( param.value )[ 0 ];
				return values.label ? values.value : values;
			} else if ( _.isArray( param.value ) ) {
				values = param.value[ 0 ];
				return _.isArray( values ) && values.length ? values[ 0 ] : values;
			}
			return '';
		}
	};
})(window.jQuery);