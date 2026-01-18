<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account; // <-- ici, pas User

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array{
        return [
            'COM_PSEUDO' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Account::class, 'COM_PSEUDO')
                    ->ignore($this->user()->COM_ID, 'COM_ID'),
            ],
            'COM_MAIL' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Account::class, 'COM_MAIL')
                    ->ignore($this->user()->COM_ID, 'COM_ID'),
            ],
            'COM_PRENOM' => ['required', 'string', 'max:255'],
            'COM_NOM' => ['required', 'string', 'max:255'],
            'COM_DATE_NAISSANCE' => ['nullable', 'date'],
            'COM_ADRESSE' => ['nullable', 'string', 'max:255'],
            'COM_TELEPHONE' => ['nullable', 'string', 'max:20'],

            'ADH_NUM_LICENCIE' => ['nullable', 'string', 'max:50'],
            'ADH_NUM_PUCE'     => ['nullable', 'string', 'max:50'],
        ];
    }

}
