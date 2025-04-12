<?php

namespace App\Services\Tools;

interface ToolProcessor
{
    /**
     * Traite les données d'entrée et retourne le résultat.
     *
     * @param array $data Les données d'entrée du formulaire
     * @return array Les données de résultat
     */
    public function process(array $data): array;
    
    /**
     * Valide les données d'entrée selon les règles spécifiques à l'outil.
     *
     * @param array $data Les données d'entrée du formulaire
     * @return array Les règles de validation
     */
    public function getValidationRules(array $data = []): array;
} 