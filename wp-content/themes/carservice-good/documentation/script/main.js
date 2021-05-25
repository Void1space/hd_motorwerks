
    $(document).ready(function() 
    {
        $('.tabs').tabs();
        
        jQuery.getScript('http://quanticalabs.com/.tools/EnvatoItems/js/getItems.js',function() { });
		$(".go-getting-started").click(function(event){
			var tab = $(this).attr('href');
			$('.tabs').tabs('select', tab);
		});
    });

