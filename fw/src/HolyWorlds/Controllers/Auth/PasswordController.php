<?php

namespace HolyWorlds\Http\Controllers\Auth;

use Http\Controllers\Controller;
use Illuminate\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @var string
     */
    protected $redirectPath = '/';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
