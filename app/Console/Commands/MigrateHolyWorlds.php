<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Forum\Thread;
use App\Models\Forum\Post;
use App\Models\User;
use Carbon\Carbon;

class MigrateHolyWorlds extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'migrate:holyworlds';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Migrates old forum data for Holy Worlds';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		ini_set('memory_limit','4G');
		set_time_limit(0);

		$bh = \DB::connection('bluehost');
		$chunks = 5000;

		/*
		 * Migrate phpBB topics
		 */
		$this->info("Transcading topics table...");
		Thread::getQuery()->delete();

		$this->info("Migrating topics table...");
		$offset = 0;
		$max = $bh->table('holyworldsforum_topics')->count();
		$this->info( "Found " . $max . " topics to migrate, grabbing in chunks of " . $chunks . " rows." );
		while( $offset < $max )
		{
			$this->info( "Querying for chunk " . ( round( $offset / $chunks ) + 1 ) . " of " . round( $max / $chunks ) );
			$topics = $bh->table( 'holyworldsforum_topics' )->offset( $offset )->limit( $chunks )->get();

			foreach ( $topics as $topic )
			{
				$offset++;
				$this->info( "Row " . $offset . " of " . $max . ":: Migrated topic # " . $topic->topic_id );

				$user = User::where('oldid', $topic->topic_poster)->limit(1)->first();
				$author = ( $user == null ) ? $topic->topic_poster : $user->id;

				Thread::create([
					'id' => $topic->topic_id,
					'category_id' => $topic->forum_id,
					'author_id' => $author,
					'title' => $topic->topic_title,
					'type' => $topic->topic_type,
					'locked' => $topic->topic_status,
					'last_thread' => $topic->topic_moved_id,
					'last_post' => $topic->topic_last_post_id,
					'count_views' => $topic->topic_views,
					'count_posts' => $topic->topic_replies,
					'created_at' => Carbon::createFromTimestamp( $topic->topic_time ),
					'updated_at' => Carbon::createFromTimestamp( $topic->topic_last_post_time ),
					'deleted_at' => null,
					'poll_title' => $topic->poll_title,
					'poll_start' => $topic->poll_start,
					'poll_length' => $topic->poll_length,
					'poll_max_options' => $topic->poll_max_options,
					'poll_last_vote' => $topic->poll_last_vote,
					'poll_vote_change' => $topic->poll_vote_change
				])->save();
			}

			unset( $topics );
		}

		/*
		 * Transfer phpBB posts
		 */
		$this->info("Transcading posts table...");
		Post::getQuery()->delete();

		$this->info("Migrating posts table...");
		$offset = 0;
		$max = $bh->table('holyworldsforum_posts')->count();
		$this->info( "Found " . $max . " posts to migrate, grabbing in chunks of " . $chunks . " rows." );
		while( $offset < $max )
		{
			$this->info( "Querying for chunk " . ( round( $offset / $chunks ) + 1 ) . " of " . round( $max / $chunks ) );
			$posts = $bh->table( 'holyworldsforum_posts' )->offset( $offset )->limit( $chunks )->get();

			foreach ( $posts as $post )
			{
				$offset++;
				$this->info( "Row " . $offset . " of " . $max . ":: Migrated post # " . $post->post_id );

				$user = User::where('oldid', $post->poster_id)->limit(1)->first();
				$author = ( $user == null ) ? $post->poster_id : $user->id;

				Post::create([
					'id' => $post->post_id,
					'thread_id' => $post->topic_id,
					'category_id' => $post->forum_id,
					'author_id' => $author,
					'content' => $post->post_text,
					'created_at' => Carbon::createFromTimestamp( $post->post_time ),
					'updated_at' => Carbon::createFromTimestamp( $post->post_edit_time == 0 ? $post->post_time : $post->post_edit_time ),
					'deleted_at' => null,
					'post_id' => 0
					])->save();
			}

			unset( $posts );
		}

		$this->info("DONE!");
	}
}
