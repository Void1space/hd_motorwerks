(function() {
    tinymce.PluginManager.add('BUPShortcodes', function( editor, url ) {
        editor.addButton( 'bup_shortcodes_button', {
            title: 'Booking Ultra Pro Shortcodes',
            type: 'menubutton',
            icon: 'icon mce_bup_shortcodes_button',
            menu: [
                
                {
                    text: 'Booking Forms',
                    value: 'Text from menu item II',
                    onclick: function() {
                        editor.insertContent(this.value());
                    },
                    menu: [
                        {
                            text: 'Booking Form',
                            value: '[bupro_appointment]',
                            onclick: function(e) {
                                e.stopPropagation();
                                editor.insertContent(this.value());
                            }       
                        },
                        
						
						
                    ]
                }
			
				
				
           ]
        });
    });
})();