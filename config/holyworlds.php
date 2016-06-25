<?php

return [
	'settings' => [
		'default_group' => [
			'title' => "Default Group",
			'desc' => "The system-wide default group for new users",
			'global_only' => true,
			'def' => 'member'
		],
		'forum_trash_threshold' => [
			'title' => "Forum Trash Threshold",
			'desc' => "How much time until trashed threads and posts are permanently removed",
			'global_only' => true,
			'def' => '7 days'
		],
		'forum_post_curve' => [
			'title' => "User Post Rank Curve",
			'desc' => "Helps determine the users rank by deviding this number by number of posts",
			'global_only' => true,
			'def' => 15000
		],
		'forum_posts_per_page' => [
			'title' => "Posts Per Page",
			'desc' => "How many posts to display per page",
			'global_only' => false,
			'def' => 15
		],
	]
];
