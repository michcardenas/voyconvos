<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
     public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($this->user()->id)
            ],
            
            // ➕ AGREGADO: Campos adicionales
            'role' => ['required', 'string', 'in:pasajero,conductor'],
            'pais' => ['required', 'string', 'max:255'],
            'ciudad' => ['required', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:20'],
            'celular' => ['required', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // max 2MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está en uso.',
            'role.required' => 'Debes seleccionar un rol.',
            'role.in' => 'El rol seleccionado no es válido.',
            'pais.required' => 'La nacionalidad es obligatoria.',
            'ciudad.required' => 'La ciudad es obligatoria.',
            'celular.required' => 'El número de celular es obligatorio.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg o gif.',
            'foto.max' => 'La imagen no debe ser mayor a 2MB.',
        ];
    }
}
