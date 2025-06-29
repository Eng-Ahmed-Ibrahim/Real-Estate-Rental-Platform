<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ResponseTrait;

class CheckIfUserBlocked
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $lang=$user->lang;
        App::setLocale($lang);

        if($user && $user->email_verified_at ==null){
            return $this->Response(null,__('messages.Email_verification_required'),403);
        }
        if ($user && $user->blocked) {
            return $this->Response(null,__('messages.This_Profile_Blocked_by_admin'),423);
        }

        return $next($request);
    }
}

