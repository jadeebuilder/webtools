<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\PaymentMethod;

class PaymentSystemSeeder extends Seeder
{
    /**
     * Exécuter le seeder.
     */
    public function run(): void
    {
        // Créer les devises courantes
        $currencies = [
            [
                'name' => 'Euro',
                'code' => 'EUR',
                'symbol' => '€',
                'is_default' => true,
                'is_active' => true,
                'decimal_mark' => ',',
                'thousands_separator' => ' ',
                'precision' => 2,
                'symbol_position' => 'after',
                'display_order' => 1,
            ],
            [
                'name' => 'US Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'is_default' => false,
                'is_active' => true,
                'decimal_mark' => '.',
                'thousands_separator' => ',',
                'precision' => 2,
                'symbol_position' => 'before',
                'display_order' => 2,
            ],
            [
                'name' => 'British Pound',
                'code' => 'GBP',
                'symbol' => '£',
                'is_default' => false,
                'is_active' => true,
                'decimal_mark' => '.',
                'thousands_separator' => ',',
                'precision' => 2,
                'symbol_position' => 'before',
                'display_order' => 3,
            ],
        ];

        foreach ($currencies as $currencyData) {
            Currency::updateOrCreate(
                ['code' => $currencyData['code']],
                $currencyData
            );
        }

        // Créer les méthodes de paiement
        $paymentMethods = [
            [
                'name' => 'Stripe',
                'code' => 'stripe',
                'description' => 'Paiement sécurisé par carte bancaire via Stripe',
                'is_active' => true,
                'processor_class' => 'App\\Services\\Payments\\StripePaymentProcessor',
                'icon' => 'fa-credit-card',
                'display_order' => 1,
                'settings' => [
                    'sandbox' => true,
                    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
                    'secret_key' => env('STRIPE_SECRET', ''),
                    'public_key' => env('STRIPE_KEY', ''),
                ]
            ],
            [
                'name' => 'PayPal',
                'code' => 'paypal',
                'description' => 'Paiement sécurisé via PayPal',
                'is_active' => true,
                'processor_class' => 'App\\Services\\Payments\\PaypalPaymentProcessor',
                'icon' => 'fa-paypal',
                'display_order' => 2,
                'settings' => [
                    'sandbox' => true,
                    'client_id' => env('PAYPAL_CLIENT_ID', ''),
                    'client_secret' => env('PAYPAL_SECRET', ''),
                    'subscription_enabled' => true,
                ]
            ],
            [
                'name' => 'Virement bancaire',
                'code' => 'bank_transfer',
                'description' => 'Paiement par virement bancaire',
                'is_active' => true,
                'processor_class' => null,
                'icon' => 'fa-university',
                'display_order' => 3,
                'settings' => [
                    'bank_name' => 'Banque Exemple',
                    'account_name' => 'WebTools SAS',
                    'account_number' => 'FR76 1234 5678 9012 3456 7890 123',
                    'bic' => 'EXAMPLEBIC',
                    'instructions' => 'Veuillez inclure votre numéro de commande dans la référence du virement.',
                ]
            ],
        ];

        foreach ($paymentMethods as $methodData) {
            $method = PaymentMethod::updateOrCreate(
                ['code' => $methodData['code']],
                $methodData
            );

            // Attacher toutes les devises à chaque méthode de paiement
            $currencies = Currency::where('is_active', true)->get();
            foreach ($currencies as $currency) {
                $method->currencies()->syncWithoutDetaching([
                    $currency->id => [
                        'is_active' => true,
                        'display_order' => 1,
                        'settings' => json_encode([]),
                    ]
                ]);
            }
        }
    }
} 