<?php

namespace App\Http\Requests;

use App\Models\Document;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Document::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $companyId = $this->user()->company_id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', Rule::exists('categories', 'id')->where('company_id', $companyId)],
            'contractor_id' => ['nullable', Rule::exists('contractors', 'id')->where('company_id', $companyId)],
            'expiry_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg', 'max:2048'],
        ];
    }
}
