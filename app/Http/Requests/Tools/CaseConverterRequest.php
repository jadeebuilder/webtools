<?php

namespace App\Http\Requests\Tools;

use App\Services\Tools\CaseConverterProcessor;
use Illuminate\Foundation\Http\FormRequest;

class CaseConverterRequest extends FormRequest
{
    /**
     * Instance du processeur de convertisseur de casse.
     *
     * @var CaseConverterProcessor
     */
    protected $processor;

    /**
     * Créer une nouvelle instance.
     *
     * @param CaseConverterProcessor $processor
     * @return void
     */
    public function __construct(CaseConverterProcessor $processor)
    {
        $this->processor = $processor;
        parent::__construct();
    }

    /**
     * Déterminer si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtenir les règles de validation qui s'appliquent à la requête.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->processor->getValidationRules($this->all());
    }

    /**
     * Obtenir les attributs personnalisés pour les erreurs de validation.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'text' => __('tools.case_converter.text'),
            'conversion_type' => __('tools.case_converter.conversion_type'),
        ];
    }

    /**
     * Obtenir les messages d'erreur personnalisés pour les règles de validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'text.required' => __('tools.case_converter.text_required'),
            'conversion_type.required' => __('tools.case_converter.conversion_type_required'),
            'conversion_type.in' => __('tools.case_converter.conversion_type_invalid'),
        ];
    }
}
