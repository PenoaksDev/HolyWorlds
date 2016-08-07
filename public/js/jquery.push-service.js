/**
 * This file maintains and handles all communication using the Holy Worlds Push Service.
 */

(function( $ ) {
	var conn = new ab.Session('wss://dev.holyworlds.org/pusher',

		// On open
		function()
		{
			$.event.trigger( "push:connected" );

			conn.subscribe('chatPublic', function( channel, data )
			{
				$.event.trigger({
					type: "push:received",
					channel: channel,
					msg: data
				});
			});
		},

		// On close
		function()
		{
			if ( console )
				console.error('WebSocket connection closed');
			$.event.trigger( "push:closed" );
		},

		// Additional Params
		{
			'skipSubprotocolCheck': true
		});

	window.pushMessage = function( channel, msg )
	{
		conn.publish( channel, {msg: msg} );
	}
}( jQuery ));
