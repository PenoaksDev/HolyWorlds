<?php

namespace HolyWorlds\Http\Controllers;

use Penoaks\Http\JsonResponse;
use Penoaks\Http\Response;
use Penoaks\Bus\DispatchesJobs;
use Penoaks\Routing\Controller as BaseController;
use Penoaks\Validation\ValidatesRequests;
use Illuminate\Auth\Access\AuthorizesRequests;
use Illuminate\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
	use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

	public function error( $code = 404, $msg = "Resource not found" )
	{
		$content = ['error' => $msg];

		return (request()->ajax() || request()->wantsJson())
			? new JsonResponse($content, $code)
			: new Response($content, $code);
	}
}
