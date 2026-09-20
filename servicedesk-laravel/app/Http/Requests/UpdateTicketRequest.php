<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:Low,Medium,High',
            'comment' => 'nullable|string',
        ];
    }
}