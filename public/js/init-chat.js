$(function() {

	$('#chat_header').click(function() {

		var chatBox = $('#chat_box');

		if ( chatBox.hasClass('active') ) {
			// Animate the chat box
			chatBox
				.animate({
					height: 0,
					width: '62px',
					display: 'none'
				}, 1000)
				.removeClass('active')
				.children().fadeOut();

			// Change the chat button
			$('#chat_header_icon').fadeOut(500, function() {
				$(this).removeClass('fa-times');
				$(this).addClass('fa-comments');
				$(this).fadeIn(500);
			});

			// Change the header width
			$(this).animate({
				width: '62px'
			}, 1000);

			// Display the title
			$('#chat_header_title').fadeOut(1000);

		} else {

			// Animate the closing of the chat box
			chatBox
				.show()
				.animate({
					height: '35vh',
					width: '300px'
				}, 1000)
				.addClass('active')
				.children().fadeIn();

			// Change the chat button
			$('#chat_header_icon').fadeOut(500, function() {
				$(this).removeClass('fa-comments');
				$(this).addClass('fa-times');
				$(this).fadeIn(500);
			});

			// Reset the width
			$(this).css('width', '100%');

			// Hide the title
			$('#chat_header_title').fadeIn(1000);
		}

	});

	$('#chat_input').keydown(function(e) {

		var key = e.which || e.keyCode;

		if ( key == 13 ) {
			$('#chat_box_content').append($(this).val());
			$(this).val('');
		}

	});

});
