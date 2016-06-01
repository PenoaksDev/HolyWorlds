/**
 * This file maintains and handles all communication using the Holy Worlds Push Service.
 */
 var conn = new ab.Session('wss://api.holyworlds.org/pusher',
function() {
	conn.subscribe('kittensCategory', function(topic, data)
	{
		console.log('New article published to category "' + topic + '" : ' + data.title);
	});
},
function() {
	console.warn('WebSocket connection closed');
},
{
	'skipSubprotocolCheck': true
});