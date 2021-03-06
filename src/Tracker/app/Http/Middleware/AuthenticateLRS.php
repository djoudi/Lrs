<?php

namespace App\Http\Middleware;

use App\Models\Lrs;
use Closure;
use Illuminate\Support\Facades\Redirect;

class AuthenticateLRS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //check to see if lrs id exists?
        $lrs  = Lrs::find( $request->route()->parameter('id') );
        //if not, let's try the lrs parameter
        if( !$lrs ){
            $lrs  = Lrs::find( $request->route()->parameter('lrs') );
        }
        $user = \Auth::user();

        if( $lrs ){
            //get all users will access to the lrs
            foreach( $lrs->users as $u ){
                $get_users[] = $u['_id'];
            }
            //check current user is in the list of allowed users, or is super admin
            if( !in_array($user->_id, $get_users) && $user->role != 'super' ){
                return Redirect::to('/');
            }

        }else{
            return Redirect::to('/');
        }

        return $next($request);
    }
}
