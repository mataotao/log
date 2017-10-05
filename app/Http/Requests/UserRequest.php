<?php namespace App\Http\Requests;

use Illuminate\Http\Request as Req;
class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Req $Req)
    {
        $route = $Req->route()->getAction();

        //如果是更新,不验证密码
        if($route['as'] == 'user.update'){
            return [
                'name' => 'required',
                'mobile' => 'required|regex:/^1[34578]\d{9}$/',
                'username' => 'required|between:5,18'.$this->segment(3),
                'roles' => 'required',
            ];
        }else{
            return [
                'name' => 'required',
                'mobile' => 'required|regex:/^1[34578]\d{9}$/',
                'username' => 'required|between:5,18'.$this->segment(3),
                'password' => 'required|between:5,15',
                'roles' => 'required',
            ];
        }

    }

    public function messages(){
        return [
            'name.required'=>'用户名称必填',
            'mobile.required'=>'手机号码必填',
            'mobile.regex'=>'手机号格式不正确',
            'username.required'=>'登录名必填',
            'username.between'=>'登录名长度必须在5-18位之间',
            'password.required'=>'登录密码必填',
            'password.between'=>'登录密码必须在5-15位之间',
            'roles.required'=>'用户权限必选',
        ];
    }
}
