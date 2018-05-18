jQuery(function($){
	$('.wordpress_loadmore').click(function()
	{ 
		var button = $(this), data = {
			'action'	: 	'load_more',
			'query'		: 	load_more_params.posts, // that's how we get params from wp_localize_script() function
			'max' 		: 	load_more_params.max_page,
			'page' 		: 	load_more_params.current_page,
			'results'	: 	load_more_params.results
			
		};

		console.log(data);

		$.ajax(
		{
			url		: 	load_more_params.ajaxurl, // AJAX handler
			data 	: 	data,
			type 	: 	'POST',
			beforeSend : function ( xhr ) 
			{
				console.log("Loading");
		
				button.text('Indlæser...'); // change the button text, you can also add a preloader image
			},
			success : function( data )
			{
				console.log("Success");

				if(data) 
				{ 
					console.log("We got data!");
					console.log(load_more_params.current_page);
					console.log(load_more_params.max_page);

					button.text( 'Flere indlæg' ).prev().before(data); // insert new posts
					load_more_params.current_page++;
 
					if ( load_more_params.current_page == load_more_params.max_page ) 
					{
						console.log("Last page! Remove button");

						button.remove(); // if last page, remove the button
					}
 
				} else {
					
					console.log("No data! Remove button");

					button.remove(); // if no data, remove the button as well
				}
			},
			error: function() 
			{
	            console.log("jQuery error");            
	        }
		});
	});
});