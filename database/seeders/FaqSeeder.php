<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assurez-vous que les catégories existent
        if (FaqCategory::count() === 0) {
            $this->call(FaqCategorySeeder::class);
        }

        $faqs = [
            // Informations générales (ID: 1)
            [
                'question' => 'Qu\'est-ce que WebTools?',
                'answer' => 'WebTools est une plateforme en ligne qui propose une collection d\'outils web pour simplifier les tâches quotidiennes des développeurs, des créateurs de contenu et des professionnels du marketing. Notre mission est de fournir des outils utiles, rapides et faciles à utiliser.',
                'category_id' => 1,
                'order' => 1,
            ],
            [
                'question' => 'Tous les outils sont-ils gratuits ?',
                'answer' => 'Oui, la plupart de nos outils sont disponibles gratuitement. Cependant, certaines fonctionnalités avancées ou l\'utilisation intensive de certains outils peuvent nécessiter un abonnement Pro. Consultez notre page de tarification pour plus de détails.',
                'category_id' => 1,
                'order' => 2,
            ],
            [
                'question' => 'Proposez-vous un accès API ?',
                'answer' => 'Oui, un accès API est disponible avec notre abonnement Pro. Vous pouvez ainsi intégrer nos outils directement dans vos applications ou automatiser certaines tâches. La documentation complète de l\'API est fournie aux abonnés.',
                'category_id' => 1,
                'order' => 3,
            ],
            [
                'question' => 'À quelle fréquence ajoutez-vous de nouveaux outils ?',
                'answer' => 'Nous enrichissons régulièrement notre catalogue d\'outils et mettons à jour les fonctionnalités existantes. En moyenne, nous ajoutons 2 à 3 nouveaux outils chaque mois. Vous pouvez vous abonner à notre newsletter pour être informé des dernières nouveautés.',
                'category_id' => 1,
                'order' => 4,
            ],

            // Compte et utilisateur (ID: 2)
            [
                'question' => 'Comment créer un compte ?',
                'answer' => 'Pour créer un compte, cliquez sur le bouton "S\'inscrire" en haut à droite de la page d\'accueil. Remplissez le formulaire avec vos informations et validez votre inscription. Vous recevrez un email de confirmation pour activer votre compte.',
                'category_id' => 2,
                'order' => 1,
            ],
            [
                'question' => 'Comment réinitialiser mon mot de passe ?',
                'answer' => 'Si vous avez oublié votre mot de passe, cliquez sur "Mot de passe oublié ?" sur la page de connexion. Entrez votre adresse email et suivez les instructions envoyées par email pour réinitialiser votre mot de passe.',
                'category_id' => 2,
                'order' => 2,
            ],
            [
                'question' => 'Mes données sont-elles sécurisées ?',
                'answer' => 'Oui, nous prenons la sécurité des données très au sérieux. Nous utilisons un chiffrement SSL pour toutes les communications et ne stockons pas vos données traitées. La plupart des opérations sont effectuées côté client lorsque c\'est possible pour garantir la confidentialité de vos informations.',
                'category_id' => 2,
                'order' => 3,
            ],

            // Utilisation des outils (ID: 3)
            [
                'question' => 'Comment utiliser les outils de conversion d\'images ?',
                'answer' => 'Pour utiliser nos outils de conversion d\'images, sélectionnez l\'outil approprié dans la catégorie "Outils d\'image". Suivez les instructions à l\'écran pour télécharger votre image source, définir les paramètres souhaités, puis cliquez sur le bouton de conversion. Vous pourrez ensuite télécharger l\'image convertie.',
                'category_id' => 3,
                'order' => 1,
            ],
            [
                'question' => 'Y a-t-il une limite de taille pour les fichiers ?',
                'answer' => 'Oui, en accès gratuit, les fichiers sont limités à 10 Mo. Les utilisateurs Pro peuvent traiter des fichiers jusqu\'à 100 Mo. Cette limitation est en place pour garantir des performances optimales de la plateforme pour tous les utilisateurs.',
                'category_id' => 3,
                'order' => 2,
            ],
            [
                'question' => 'Puis-je utiliser les outils sur mobile ?',
                'answer' => 'Absolument ! Notre plateforme est entièrement responsive et optimisée pour les appareils mobiles et tablettes. Vous pouvez utiliser la plupart de nos outils sur n\'importe quel appareil avec une connexion internet.',
                'category_id' => 3,
                'order' => 3,
            ],

            // Facturation et abonnements (ID: 4)
            [
                'question' => 'Quels modes de paiement acceptez-vous ?',
                'answer' => 'Nous acceptons les principales cartes de crédit (Visa, Mastercard, American Express), PayPal, et dans certains pays, les virements bancaires. Toutes les transactions sont sécurisées et chiffrées.',
                'category_id' => 4,
                'order' => 1,
            ],
            [
                'question' => 'Comment annuler mon abonnement ?',
                'answer' => 'Vous pouvez annuler votre abonnement à tout moment depuis votre espace membre, dans la section "Abonnements". L\'annulation prendra effet à la fin de la période de facturation en cours. Vous conserverez l\'accès aux fonctionnalités premium jusqu\'à cette date.',
                'category_id' => 4,
                'order' => 2,
            ],
            [
                'question' => 'Proposez-vous une garantie de remboursement ?',
                'answer' => 'Oui, nous offrons une garantie de remboursement de 14 jours. Si vous n\'êtes pas satisfait de votre abonnement premium, vous pouvez demander un remboursement complet dans les 14 jours suivant votre souscription. Contactez notre service client pour plus de détails.',
                'category_id' => 4,
                'order' => 3,
            ],

            // Support technique (ID: 5)
            [
                'question' => 'Comment contacter le support technique ?',
                'answer' => 'Vous pouvez contacter notre équipe de support technique via le formulaire de contact disponible sur notre site, par email à support@webtools.com ou via le chat en direct disponible pour les utilisateurs premium. Notre équipe est disponible du lundi au vendredi, de 9h à 18h (GMT+1).',
                'category_id' => 5,
                'order' => 1,
            ],
            [
                'question' => 'Le site est inaccessible ou un outil ne fonctionne pas, que faire ?',
                'answer' => 'Si vous rencontrez des problèmes d\'accès au site ou si un outil spécifique ne fonctionne pas, essayez d\'abord de rafraîchir la page et de vider le cache de votre navigateur. Si le problème persiste, vérifiez notre page de statut des services ou contactez notre support technique.',
                'category_id' => 5,
                'order' => 2,
            ],
            [
                'question' => 'Puis-je suggérer un nouvel outil ?',
                'answer' => 'Bien sûr ! Nous apprécions les retours de nos utilisateurs. Vous pouvez nous envoyer vos suggestions d\'outils via notre formulaire de contact ou par email à suggestions@webtools.com. Nous étudions toutes les propositions et intégrons régulièrement de nouvelles idées à notre feuille de route.',
                'category_id' => 5,
                'order' => 3,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create([
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'faq_category_id' => $faq['category_id'],
                'is_active' => true,
                'order' => $faq['order'],
            ]);
        }
    }
} 