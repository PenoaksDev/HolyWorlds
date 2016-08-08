<?php namespace HolyWorlds\Policies;

use HolyWorlds\Models\Article;
use HolyWorlds\Models\User;
use Milky\Account\Permissions\Policy;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class ArticlePolicy extends Policy
{
	/**
	 * Determine if the given user can add a comment on the article.
	 *
	 * @param  User $user
	 * @param  Article $article
	 * @return bool
	 */
	public function addComment( User $user, Article $article )
	{
		return !$user->isNew;
	}
}
