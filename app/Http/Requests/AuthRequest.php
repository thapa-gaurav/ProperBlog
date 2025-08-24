<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
//    public mixed $password;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules():array
    {
        if($this->is('api/register')){
            return  $this->registerRules();
        }elseif ($this->is('api/login')){
            return $this->loginRules();
        }
        return [
            'password'=>['required'],
        ];
    }

    public function registerRules()
    {
        return [
            'name'=>['required'],
            'email'=>['required','email'],
            'password'=>['required'],
            'role_id'=>['required']
        ];
    }

    public function loginRules()
    {
        return [
            'email'=>['required','email'],
            'password'=>['required'],
        ];
    }
}
