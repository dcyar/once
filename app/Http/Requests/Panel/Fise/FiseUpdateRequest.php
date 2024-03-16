<?php

namespace App\Http\Requests\Panel\Fise;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FiseUpdateRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'client_id'       => ['required', 'integer', 'exists:clients,id'],
            'code'            => ['required', 'string', 'max:12', Rule::unique('fises')->ignore($this->fise)],
            'amount'          => ['required', 'integer', 'min:1'],
            'expiration_date' => ['required', 'date'],
            'is_active'       => ['required', 'boolean'],
            'used_at'         => ['nullable', 'date'],
        ];
    }

    public function prepareForValidation() {
        $this->merge([
            'used_at' => $this->boolean('is_active') ? null : now()->toDateTimeString(),
        ]);
    }
}
