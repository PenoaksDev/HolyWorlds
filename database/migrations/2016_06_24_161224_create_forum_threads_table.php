<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumThreadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forum_threads', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id')->unsigned()->default(0)->index('forum_appr_last');
			$table->string('author_id')->default('0');
			$table->string('title')->default('');
			$table->boolean('type')->default(0);
			$table->boolean('locked')->default(0);
			$table->integer('last_thread')->unsigned()->default(0);
			$table->integer('last_post')->unsigned();
			$table->integer('count_views')->unsigned()->default(0);
			$table->integer('count_posts')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			$table->string('poll_title')->default('');
			$table->integer('poll_start')->unsigned()->default(0);
			$table->integer('poll_length')->unsigned()->default(0);
			$table->boolean('poll_max_options')->default(1);
			$table->integer('poll_last_vote')->unsigned()->default(0);
			$table->boolean('poll_vote_change')->default(0);
			$table->index(['category_id','type'], 'forum_id_type');
			$table->index(['category_id','last_thread'], 'fid_time_moved');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('forum_threads');
	}

}
