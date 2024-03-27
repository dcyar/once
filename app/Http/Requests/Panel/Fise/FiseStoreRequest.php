<?php

namespace App\Http\Requests\Panel\Fise;

use Illuminate\Foundation\Http\FormRequest;

class FiseStoreRequest extends FormRequest {
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
            'code'            => ['required', 'string', 'min:12', 'max:12', 'unique:fises'],
            'amount'          => ['required', 'integer', 'min:1'],
            'expiration_date' => ['required', 'date'],
            'is_active'       => ['required', 'boolean'],
            'used_at'         => ['nullable', 'date'],
            'notes'           => ['nullable', 'string'],
        ];
    }

    public function prepareForValidation() {
        $this->merge([
            'used_at' => $this->boolean('is_active') ? now()->toDateTimeString() : null,
        ]);
    }
}
