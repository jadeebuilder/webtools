<?php

namespace App\Services\Tools;

abstract class AbstractToolProcessor implements ToolProcessor
{
    /**
     * Traite les données d'entrée et retourne le résultat.
     *
     * @param array $data Les données d'entrée du formulaire
     * @return array Les données de résultat
     */
    abstract public function process(array $data): array;
    
    /**
     * Valide les données d'entrée selon les règles spécifiques à l'outil.
     *
     * @param array $data Les données d'entrée du formulaire
     * @return array Les règles de validation
     */
    abstract public function getValidationRules(array $data = []): array;
    
    /**
     * Convertit les chaînes vides en null.
     *
     * @param array $data
     * @return array
     */
    protected function nullifyEmptyStrings(array $data): array
    {
        return array_map(function ($value) {
            if (is_array($value)) {
                return $this->nullifyEmptyStrings($value);
            }
            
            return $value === '' ? null : $value;
        }, $data);
    }
    
    /**
     * Nettoie les données d'entrée.
     *
     * @param array $data
     * @return array
     */
    protected function sanitizeInput(array $data): array
    {
        $data = $this->nullifyEmptyStrings($data);
        
        // Filtrer les valeurs nulles si nécessaire
        // $data = array_filter($data, function ($value) {
        //    return $value !== null;
        // });
        
        return $data;
    }
    
    /**
     * Prépare la réponse avec les données de résultat.
     *
     * @param array $data Les données de résultat
     * @param string|null $message Message optionnel
     * @return array
     */
    protected function prepareResponse(array $data, ?string $message = null): array
    {
        $response = [
            'success' => true,
            'data' => $data,
        ];
        
        if ($message) {
            $response['message'] = $message;
        }
        
        return $response;
    }
    
    /**
     * Prépare une réponse d'erreur.
     *
     * @param string $message Message d'erreur
     * @param array $data Données additionnelles
     * @return array
     */
    protected function prepareErrorResponse(string $message, array $data = []): array
    {
        return [
            'success' => false,
            'message' => $message,
            'data' => $data,
        ];
    }
} 