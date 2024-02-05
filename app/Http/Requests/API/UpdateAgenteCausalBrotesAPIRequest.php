<?php

namespace App\Http\Requests\API;

use App\Models\MTAgenteCausalBrote;
use InfyOm\Generator\Request\APIRequest;

class UpdateAgenteCausalBrotesAPIRequest extends APIRequest
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
        $rules = MTAgenteCausalBrote::$rules;

        return $rules;
    }
}
