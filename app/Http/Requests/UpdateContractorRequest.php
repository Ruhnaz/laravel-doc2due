<?php

namespace App\Http\Requests;

use App\Models\Contractor;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateContractorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $contractor = $this->route('contractor');

        return $contractor instanceof Contractor && ($this->user()?->can('update', $contractor) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
