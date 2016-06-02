/**
 * This file maintains and handles all communication using the Holy Worlds Push Service.
 */

(function( $ ) {
	var conn = new ab.Session('wss://api.holyworlds.org/pusher',
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
		function()
		{
			console.warn('WebSocket connection closed');
			$.event.trigger( "push:closed" );
		},
		{
			'skipSubprotocolCheck': true
		});

	window.pushMessage = function( channel, msg )
	{
		conn.publish( channel, {msg: msg} )
	}
}( jQuery ));