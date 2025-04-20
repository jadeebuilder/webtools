<?php

namespace App\Services\Payments;

interface PaymentProcessorInterface
{
    /**
     * Initialise un nouveau paiement
     * 
     * @param array $data Les données du paiement (package, utilisateur, devise, cycle, etc.)
     * @return array Les données de réponse (URL de redirection, formulaire HTML, etc.)
     */
    public function initiatePayment(array $data): array;
    
    /**
     * Vérifie et traite une notification de paiement (webhook)
     * 
     * @param array $data Les données de la notification
     * @return bool Succès ou échec du traitement
     */
    public function handleNotification(array $data): bool;
    
    /**
     * Traite le retour après paiement (redirection depuis la page de paiement)
     * 
     * @param array $data Les données de retour
     * @return bool Succès ou échec du traitement
     */
    public function handleReturn(array $data): bool;
    
    /**
     * Annule un paiement
     * 
     * @param string $paymentId L'identifiant du paiement à annuler
     * @return bool Succès ou échec de l'annulation
     */
    public function cancelPayment(string $paymentId): bool;
    
    /**
     * Vérifie si ce processeur peut gérer les abonnements récurrents
     * 
     * @return bool
     */
    public function supportsRecurring(): bool;
} 