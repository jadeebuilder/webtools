<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'position' => 'required|string',
            'active' => 'sometimes|boolean',
            'type' => 'required|in:image,adsense',
            'image' => 'required_if:type,image|nullable|string',
            'link' => 'required_if:type,image|nullable|url',
            'alt' => 'nullable|string|max:255',
            'code' => 'required_if:type,adsense|nullable|string',
            'display_on' => 'nullable|array',
            'display_on.*' => 'string|in:home,tool,category,admin',
            'priority' => 'integer|min:0',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'position.required' => 'La position est obligatoire.',
            'type.required' => 'Le type de publicité est obligatoire.',
            'type.in' => 'Le type de publicité doit être "image" ou "adsense".',
            'image.required_if' => 'L\'image est obligatoire pour les publicités de type "image".',
            'link.required_if' => 'Le lien est obligatoire pour les publicités de type "image".',
            'code.required_if' => 'Le code est obligatoire pour les publicités de type "adsense".',
        ];
    }
}
