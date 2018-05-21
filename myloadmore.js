jQuery(document).ready(function($)
{
	$(".wordpress_load_more").click(function(){

		var button = $(this), data = {
			action		: 	'load_more',
			// query		: 	wordpress_load_more_params.posts, // that's how we get params from wp_localize_script() function
			max 		: 	wordpress_load_more_params.max_page,
			// results		: 	wordpress_load_more_params.results,
			page 		: 	wordpress_load_more_params.current_page
			
		};

		console.log(data);

		$.ajax(
		{
			url		: 	wordpress_load_more_params.ajaxurl, // AJAX handler
			type 	: 	'POST',
			data 	: 	data,
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
					console.log(wordpress_load_more_params.current_page);
					console.log(wordpress_load_more_params.max_page);

					button.text( 'Flere indlæg' ).prev().before(data); // insert new posts
					wordpress_load_more_params.current_page++;
 
					if ( wordpress_load_more_params.current_page == wordpress_load_more_params.max_page ) 
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