<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Extensions\OAuth\PasswordGrantVerifier;
use \Illuminate\Http\Request;
class MscAuthenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $roleName = \Illuminate\Support\Facades\Session::get('roleName','');
        if ($this->auth->guest() || !in_array($roleName,['超级管理员','实验室管理员'])) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return '<script> if(window.parent.location == window.location) window.location = "'.url('/admin/login').'"; else window.parent.location.reload(); </script>';
            }
        }
        //判断用户是否被禁用
        $user = Auth::user();
        if($user->status != '正常' ){
            return '<script> if(window.parent.location == window.location) window.location = "'.url('/admin/login').'"; else window.parent.location.reload(); </script>';
        }
        //判断是否登录
        $MenusPermissions = Session::get('MenusPermissions','');
        if(empty($MenusPermissions)){
            return redirect()->guest('/admin/index');
        }

        //判断是否拥有菜单权限
        $PasswordGrantVerifier = new PasswordGrantVerifier;
        $rew = $PasswordGrantVerifier->validationMenusPermissions($_SERVER['REQUEST_URI']);
        if(!$rew){
            dd('你没有访问该页面的权限');
        }
        return $next($request);
    }
}
