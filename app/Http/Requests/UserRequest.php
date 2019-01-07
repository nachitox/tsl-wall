<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
    public function rules()
    {
		$this->sanitize();
		
        return [
			'email'			=> 'max:255|required|string|unique:users',
        	'password'		=> 'min:8|required|string',
			'first_name'	=> 'max:255|required|string',
			'last_name'		=> 'max:255|required|string',
        ];
    }
	
	public function sanitize()
    {
        $input = $this->all();
		
		foreach ($input as $key => $val)
        	$input[$key] = filter_var($input[$key], FILTER_SANITIZE_STRING);

        $this->replace($input);     
    }
}
