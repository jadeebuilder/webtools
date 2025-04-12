<?php

namespace App\Services\Tools;

class CaseConverterProcessor extends AbstractToolProcessor
{
    /**
     * Constantes pour les types de conversion
     */
    const LOWERCASE = 'lowercase';
    const UPPERCASE = 'uppercase';
    const TITLE_CASE = 'titlecase';
    const SENTENCE_CASE = 'sentencecase';
    const CAPITALIZE = 'capitalize';
    const ALTERNATING_CASE = 'alternatingcase';
    const INVERSE_CASE = 'inversecase';
    const PASCAL_CASE = 'pascalcase';
    const CAMEL_CASE = 'camelcase';
    const SNAKE_CASE = 'snakecase';
    const KEBAB_CASE = 'kebabcase';
    
    /**
     * Types de conversion disponibles
     *
     * @var array
     */
    protected $conversionTypes = [
        self::LOWERCASE,
        self::UPPERCASE,
        self::TITLE_CASE,
        self::SENTENCE_CASE,
        self::CAPITALIZE,
        self::ALTERNATING_CASE,
        self::INVERSE_CASE,
        self::PASCAL_CASE,
        self::CAMEL_CASE,
        self::SNAKE_CASE,
        self::KEBAB_CASE,
    ];
    
    /**
     * {@inheritdoc}
     */
    public function getValidationRules(array $data = []): array
    {
        return [
            'text' => ['required', 'string'],
            'conversion_type' => ['required', 'string', 'in:' . implode(',', $this->conversionTypes)],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function process(array $data): array
    {
        $data = $this->sanitizeInput($data);
        
        $text = $data['text'] ?? '';
        $conversionType = $data['conversion_type'] ?? self::LOWERCASE;
        
        $result = match ($conversionType) {
            self::LOWERCASE => $this->toLowerCase($text),
            self::UPPERCASE => $this->toUpperCase($text),
            self::TITLE_CASE => $this->toTitleCase($text),
            self::SENTENCE_CASE => $this->toSentenceCase($text),
            self::CAPITALIZE => $this->toCapitalize($text),
            self::ALTERNATING_CASE => $this->toAlternatingCase($text),
            self::INVERSE_CASE => $this->toInverseCase($text),
            self::PASCAL_CASE => $this->toPascalCase($text),
            self::CAMEL_CASE => $this->toCamelCase($text),
            self::SNAKE_CASE => $this->toSnakeCase($text),
            self::KEBAB_CASE => $this->toKebabCase($text),
            default => $text,
        };
        
        return $this->prepareResponse([
            'original' => $text,
            'converted' => $result,
            'conversion_type' => $conversionType,
        ]);
    }
    
    /**
     * Convertit le texte en minuscules.
     */
    protected function toLowerCase(string $text): string
    {
        return mb_strtolower($text);
    }
    
    /**
     * Convertit le texte en majuscules.
     */
    protected function toUpperCase(string $text): string
    {
        return mb_strtoupper($text);
    }
    
    /**
     * Convertit le texte en casse de titre (première lettre de chaque mot en majuscule).
     */
    protected function toTitleCase(string $text): string
    {
        return mb_convert_case($text, MB_CASE_TITLE, 'UTF-8');
    }
    
    /**
     * Convertit le texte en casse de phrase (première lettre de chaque phrase en majuscule).
     */
    protected function toSentenceCase(string $text): string
    {
        $text = mb_strtolower($text);
        $sentences = preg_split('/([.!?]+)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = '';
        
        for ($i = 0; $i < count($sentences); $i += 2) {
            $sentence = $sentences[$i];
            $delimiter = $sentences[$i + 1] ?? '';
            
            if (mb_strlen(trim($sentence)) > 0) {
                $sentence = mb_strtoupper(mb_substr($sentence, 0, 1)) . mb_substr($sentence, 1);
            }
            
            $result .= $sentence . $delimiter;
        }
        
        return $result;
    }
    
    /**
     * Convertit le texte pour capitaliser la première lettre de chaque mot.
     */
    protected function toCapitalize(string $text): string
    {
        return mb_convert_case($text, MB_CASE_TITLE, 'UTF-8');
    }
    
    /**
     * Convertit le texte en casse alternée (une lettre majuscule, une minuscule).
     */
    protected function toAlternatingCase(string $text): string
    {
        $result = '';
        $length = mb_strlen($text);
        
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1);
            $result .= ($i % 2 === 0) ? mb_strtoupper($char) : mb_strtolower($char);
        }
        
        return $result;
    }
    
    /**
     * Convertit le texte en casse inversée (minuscules en majuscules, majuscules en minuscules).
     */
    protected function toInverseCase(string $text): string
    {
        $result = '';
        $length = mb_strlen($text);
        
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1);
            $upper = mb_strtoupper($char);
            $lower = mb_strtolower($char);
            
            $result .= ($char === $upper && $char !== $lower) ? $lower : $upper;
        }
        
        return $result;
    }
    
    /**
     * Convertit le texte en PascalCase.
     */
    protected function toPascalCase(string $text): string
    {
        // Normaliser les espaces et les caractères spéciaux
        $text = preg_replace('/[^a-zA-Z0-9]+/', ' ', $text);
        $text = trim($text);
        
        // Convertir à la casse de titre puis supprimer les espaces
        $words = explode(' ', mb_convert_case($text, MB_CASE_TITLE, 'UTF-8'));
        return implode('', $words);
    }
    
    /**
     * Convertit le texte en camelCase.
     */
    protected function toCamelCase(string $text): string
    {
        $pascal = $this->toPascalCase($text);
        
        if (mb_strlen($pascal) > 0) {
            $pascal = mb_strtolower(mb_substr($pascal, 0, 1)) . mb_substr($pascal, 1);
        }
        
        return $pascal;
    }
    
    /**
     * Convertit le texte en snake_case.
     */
    protected function toSnakeCase(string $text): string
    {
        // Normaliser les espaces et les caractères spéciaux
        $text = preg_replace('/[^a-zA-Z0-9]+/', ' ', $text);
        $text = trim($text);
        
        // Convertir en minuscules et remplacer les espaces par des underscores
        return str_replace(' ', '_', mb_strtolower($text));
    }
    
    /**
     * Convertit le texte en kebab-case.
     */
    protected function toKebabCase(string $text): string
    {
        // Normaliser les espaces et les caractères spéciaux
        $text = preg_replace('/[^a-zA-Z0-9]+/', ' ', $text);
        $text = trim($text);
        
        // Convertir en minuscules et remplacer les espaces par des tirets
        return str_replace(' ', '-', mb_strtolower($text));
    }
} 