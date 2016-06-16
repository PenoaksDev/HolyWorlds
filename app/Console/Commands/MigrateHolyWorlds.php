<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Forum\Thread;
use App\Models\Forum\Post;
use App\Models\User;
use App\Models\UserAuth;
use App\Models\UserProfile;
use App\Models\Setting;
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
		 * Migrate phpBB users
		 */
		$this->info("Transcading users...");
		User::getQuery()->delete();
		UserAuth::getQuery()->delete();
		UserProfile::getQuery()->delete();

		$this->info("Migrating users...");
		$query = $bh->table('users')->where('user_type', '!=', '2')->orderBy('user_id');
		$defGroup = Setting::findOrNew('default_group')->value('member');
		$userMapping = [];
		$offset = 0;
		$max = $query->count();
		$this->info( "Found " . $max . " users to migrate, grabbing in chunks of " . $chunks . " rows." );
		while( $offset < $max )
		{
			$this->info( "Querying for chunk " . ( round( $offset / $chunks ) + 1 ) . " of " . ( round( $max / $chunks ) + 1 ) );
			$chunk = $query->offset( $offset )->limit( $chunks )->get();

			foreach ( $chunk as $row )
			{
				$offset++;

				$num = strlen( $row->user_id ) < 4 ? $row->user_id . \App\Util::rand( 4 - strlen( $row->user_id ), true, false ) : substr( $row->user_id, 0, 4 );
				do
				{
					$id = strtolower( substr( $row->user_email, 0, 2 ) ) . $num . strtoupper( substr( $row->username, 0, 1 ) );
					$num++;
				}
				while( User::where( 'id', $id )->exists() );

				$activated = false;
				// NOTE TEMP USER OVERRIDES
				if ( $row->user_id == '65616' )
				{
					$activated = true;
					$id = 'cg0092m';
				}
				if ( $row->user_id == '210' )
					$activated = true;

				$this->info( "Row " . $offset . " of " . $max . ":: Migrating user # " . $row->user_id . " (as " . $id . ")" );

				$email = $row->user_email;
				$cnt = -1;
				while ( User::where( 'email', $email )->exists() )
				{
					$cnt++;
					$email = "DUPLICATE" . $cnt . '_' . $row->user_email;
				}
				if ( $email != $row->user_email )
					$this->error("User # " . $row->user_id . " has a duplicate e-mail address: " . $row->user_email);

				$user = User::create([
					'id' => $id,
					'oldid' => $row->user_id,
					'name' => $row->username,
					'email' => $email,
					'usebbhash' => true,
					'password' => $row->user_password,
					'activation_token' => $activated ? null : 'MIGRATED',
					'activation_updated' => Carbon::now(),
					'created_at' => Carbon::createFromTimestamp( $row->user_regdate ),
					'updated_at' => Carbon::createFromTimestamp( $row->user_passchg > 0 ? $row->user_passchg : $row->user_regdate ),
					'visited_at' => Carbon::createFromTimestamp( $row->user_lastvisit )
				]);

				$profile = UserProfile::create([
					'id' => $id,
					'avatar_url' => $row->user_avatar,
					'signature' => str_replace( ':' . $row->user_sig_bbcode_uid, '', $row->user_sig ),
					'signature_bbcode' => true,
					'location' => $row->user_from,
					'website' => $row->user_website,
					'occupation' => $row->user_occ,
					'interests' => $row->user_interests,
					'post_count' => $row->user_posts,
					'timezone' => $row->user_timezone,
					'dst' => $row->user_dst
				]);

				$user->addGroup( $defGroup );
				// TODO Group translations

				$user->save();
				$profile->save();

				$userMapping[$row->user_id] = $user->id;
			}

			unset( $chunk );
		}

		/*
		 * Migrate phpBB topics
		 */
		$this->info("Transcading topics table...");
		Thread::getQuery()->delete();

		$this->info("Migrating topics table...");
		$query = $bh->table('holyworldsforum_topics');
		$offset = 0;
		$max = $query->count();
		$this->info( "Found " . $max . " topics to migrate, grabbing in chunks of " . $chunks . " rows." );
		while( $offset < $max )
		{
			$this->info( "Querying for chunk " . ( round( $offset / $chunks ) + 1 ) . " of " . ( round( $max / $chunks ) + 1 ) );
			$chunk = $query->offset( $offset )->limit( $chunks )->get();

			foreach ( $chunk as $row )
			{
				$offset++;
				$this->info( "Row " . $offset . " of " . $max . ":: Migrating topic # " . $row->topic_id );

				$author = $row->topic_poster;
				if ( array_key_exists( $row->topic_poster, $userMapping ) && ( $user = User::find( $userMapping[ $row->topic_poster ] ) ) != null )
				{
					$author = $user->id;
				}
				else {
					$this->error( "User " . $row->topic_poster . " not found!" );
				}

				Thread::create([
					'id' => $row->topic_id,
					'category_id' => $row->forum_id,
					'author_id' => $author,
					'title' => $row->topic_title,
					'type' => $row->topic_type,
					'locked' => $row->topic_status,
					'last_thread' => $row->topic_moved_id,
					'last_post' => $row->topic_last_post_id,
					'count_views' => $row->topic_views,
					'count_posts' => $row->topic_replies,
					'created_at' => Carbon::createFromTimestamp( $row->topic_time ),
					'updated_at' => Carbon::createFromTimestamp( $row->topic_last_post_time ),
					'deleted_at' => null,
					'poll_title' => $row->poll_title,
					'poll_start' => $row->poll_start,
					'poll_length' => $row->poll_length,
					'poll_max_options' => $row->poll_max_options,
					'poll_last_vote' => $row->poll_last_vote,
					'poll_vote_change' => $row->poll_vote_change
				])->save();
			}

			unset( $chunk );
		}

		/*
		 * Transfer phpBB posts
		 */
		$this->info("Transcading posts table...");
		Post::getQuery()->delete();

		$this->info("Migrating posts table...");
		$query = $bh->table( 'holyworldsforum_posts' );
		$offset = 0;
		$max = $query->count();
		$this->info( "Found " . $max . " posts to migrate, grabbing in chunks of " . $chunks . " rows." );
		while( $offset < $max )
		{
			$this->info( "Querying for chunk " . ( round( $offset / $chunks ) + 1 ) . " of " . ( round( $max / $chunks ) + 1 ) );
			$chunk = $query->offset( $offset )->limit( $chunks )->get();

			foreach ( $chunk as $row )
			{
				$offset++;
				$this->info( "Row " . $offset . " of " . $max . ":: Migrating post # " . $row->post_id );

				$author = $row->poster_id;
				if ( array_key_exists( $row->poster_id, $userMapping ) && ( $user = User::find( $userMapping[ $row->poster_id ] ) ) != null )
				{
					$author = $user->id;
				}
				else {
					$this->error( "User " . $row->poster_id . " not found!" );
				}

				Post::create([
					'id' => $row->post_id,
					'thread_id' => $row->topic_id,
					'category_id' => $row->forum_id,
					'author_id' => $author,
					'content' => str_replace( ':' . $row->bbcode_uid, '', $row->post_text ),
					'created_at' => Carbon::createFromTimestamp( $row->post_time ),
					'updated_at' => Carbon::createFromTimestamp( $row->post_edit_time == 0 ? $row->post_time : $row->post_edit_time ),
					'deleted_at' => null,
					'post_id' => 0
					])->save();
			}

			unset( $chunk );
		}

		$this->info("DONE!");
	}
}
