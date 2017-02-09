<?php

namespace App\Http\Controllers;

use App\Entities\BaseUser;
use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $guard = 'web';

    /**
     * Json response
     * @param array $content
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($content = [], $status = 200, $headers = [])
    {
        return response()->json($content, $status, $headers);
    }

    /**
     * Get current auth
     * @return Authenticatable|BaseUser|null
     */
    protected function auth()
    {
        return Auth::guard($this->getGuard())->user();
    }

    /**
     * Get the guard to be used during authentication.
     * @return string
     */
    protected function getGuard()
    {
        return property_exists($this, 'guard') ? $this->guard : config('auth.defaults.guard');
    }
}
