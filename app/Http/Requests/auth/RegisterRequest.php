<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
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
    public function rules(): array
    {
        return [
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6',
            'profile_image'=>'required|file|mimes:jpg,png'
        ];
    }

     /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'my message:Name is required',
            'email.required' => 'my message:Email is required',
            'email.unique'=>'This email already exist try another',
            'password.min'=>'password length is less than 6 characters',
            'password.required' =>'my message:password is required to proceed',
            'profile_image.required'=>'my message:profile image is not required',
            'profile_image.mimes'=>'must be jpg or png'
        ];
    }
}
