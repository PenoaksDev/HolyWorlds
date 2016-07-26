<?php namespace HolyWorlds\Http\Controllers\User;

use Http\Controllers\Controller;
use Models\Comment;
use Models\User;
use Penoaks\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show a user profile by user ID.
     *
     * @param  int  $id
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show($id, $name)
    {
        $user = User::findOrFail($id);
        return view('user.profile.show', compact('user') + [
            'commentPaginator' => $user->profile->comments()->orderBy('created_at', 'desc')->paginate()
        ]);
    }
}
