<?php namespace App\Http\Controllers;

use App\Entities\Agency\Agency;
use App\Entities\Cms\Category;
use App\Entities\Sys\SysMenus;
use App\Entities\Sys\SysPermissionMenu;
use App\Http\Controllers\Controller;
use App\Repositories\Common;
use Auth;
use DB;
use Illuminate\Http\Request;
use Session;
use App\Entities\StockSir\Admin;
use App\Entities\Sys\SysUserRole;
use App\Entities\Sys\SysFunctions;

class IndexController extends Controller
{
    
    
    public function index(SysUserRole $SysUserRole, SysFunctions $SysFunctions)
    {
        
        try {
            $SysMenus = new SysMenus();
            $user     = Auth::user();
            
            if (!$user) {
                throw new \Exception('没有找到用户，请登录');
            }
            
            if (is_null($user->roles)) {
                throw new \Exception('非法用户，请按照要求注册');
            }
            
            if ($user->admin == 1) {
                $MenusList = $SysMenus->getMenusList();
            } else {
                $RoleMenusList = $SysMenus->getRoleMenus($user->roles->pluck('id'))->toArray();
                $UserMenusList = $SysMenus->getUserMenus($user->id)->toArray();
                $MenusList     = array_merge($RoleMenusList, $UserMenusList);
                //二维数组去重
                if (!empty($MenusList) && count($MenusList) > 0) {
                    $MenusList = Common::array_unique_fb($MenusList);
                }
            }
            $MenusList = $this->node_merge($MenusList);
//            dd($MenusList);
        } catch (\Exception $ex) {
            \Log::alert($ex->getMessage());
            if ($ex->getCode() == 0) {
                return redirect()->guest('/login');
            }
            
            return redirect()->guest('/login')->withErrors($ex->getMessage());
        }
        
        $user = Auth::user();
        
        $results           = Agency::where(['agency_id' => $user->affiliation])->select([
                                                                                            'is_edit',
                                                                                            'agency_code',
                                                                                        ])->first();
        $user->agency_code = $results['agency_code'];
        
        $is_edit = 0;
        if (!isset($results['is_edit']) || $results['is_edit'] == 0) {
            $is_edit = 1;
            
            return view('layouts.admin', [
                'list'     => [],
                'userInfo' => $user,
                'is_edit'  => $is_edit,
            ]);
        } else {
            
            return view('layouts.admin', [
                'list'     => $MenusList,
                'userInfo' => $user,
                'is_edit'  => $is_edit,
            ]);
        }
        
    }
    //递归通过pid 将其压入到一个多维数组!
    /*
     * $node 存放所有节点的节点数组
     * $access 判断有误权限
     * $pid 父id
     * return 多维数组;
     * */
    protected function node_merge($node, $pid = 0)
    {
        $arr = [];
        foreach ($node as $v) {
            if (empty($v)) {
                continue;
            }
            if ($v['pid'] == $pid) {
                $v["child"] = $this->node_merge($node, $v["id"]);
                $arr[]      = $v;
            }
        }
        
        return $arr;
    }
    
    public function viewLog(Request $request)
    {
        $index = empty($request->input('index')) ? "storage/logs" : "storage/logs";
        $name  = empty($request->input('name')) ? "" : $request->input('name');
        $load  = empty($request->input('load')) ? "" : $request->input('load');
        $path  = base_path() . "/{$index}/";
        $dir   = scandir($path);
        $arr   = array_map(function ($d) use ($path) {
            return $path . $d;
        }, $dir);
        if (!empty($name) && empty($load)) {
            $file = file_get_contents(base_path() . $name);
            echo "<pre>";
            print_r($file);
        } elseif (!empty($name) && !empty($load)) {
            $filename = base_path() . $name; //文件名
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=".basename($filename));
            readfile($filename);
        }else{
            dump($arr);
        }
        
    }
}