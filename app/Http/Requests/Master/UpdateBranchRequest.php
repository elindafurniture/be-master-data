<?php

namespace App\Http\Requests\Master;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends BaseRequest
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
        $branchId = $this->route('id');
        
        return [
            'code' => [
                'required',
                'string',
                'max:2',
                Rule::unique('branches')->where('deleted_status', 1)->ignore($branchId)
            ],
            'name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
