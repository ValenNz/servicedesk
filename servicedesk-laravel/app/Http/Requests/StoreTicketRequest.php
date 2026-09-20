<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|min:5',
            'description' => 'required|string|min:10',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:Low,Medium,High',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:5120', 
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul tiket harus diisi',
            'title.min' => 'Judul minimal 5 karakter',
            'description.required' => 'Deskripsi harus diisi',
            'description.min' => 'Deskripsi minimal 10 karakter',
            'category_id.required' => 'Kategori harus dipilih',
            'priority.required' => 'Prioritas harus dipilih',
            'attachment.max' => 'Ukuran file maksimal 5MB',
        ];
    }
}