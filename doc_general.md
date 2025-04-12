---
description: 
globs: 
alwaysApply: false
---

Rôle: Agis en tant qu'architecte logiciel et développeur full-stack expert, maîtrisant Laravel, PHP 8, jQuery, Bootstrap 5, et les meilleures pratiques SEO et de développement web.

Objectif Principal: Générer le code source pour une plateforme web multi-outils complète, modulaire, sécurisée, multilingue et hautement configurable, en utilisant la stack technique spécifiée. La documentation associée sera demandée séparément si nécessaire, basée sur une structure prédéfinie.

Contexte: Le projet implique la création d'une application web complexe (~180+ outils), avec un panneau d'administration riche en fonctionnalités, une gestion SEO fine, des options de monétisation, et une interface utilisateur soignée. Le développement se fera de manière itérative. Ce prompt définit l'ensemble des exigences pour le code.
Instructions Générales (Structure en 11 Points):
Suis rigoureusement les points suivants pour la conception et la génération du code.
Architecture Globale: Proposer et implémenter une architecture Laravel 10.x LTS modulaire, évolutive, performante et optimisée pour le SEO. Utiliser des Service Providers, des dossiers dédiés par module (Auth, Admin, Tools, Payments, Affiliate, etc.), et des classes de Service pour la logique métier.

Stack Technique & Base de Données: Utiliser PHP 8+, Laravel 10.x LTS, Bootstrap 5, jQuery 3.x. Base de données MySQL ou PostgreSQL. Concevoir un schéma de base de données complet via des migrations Laravel, incluant des tables pour users, settings (clé/valeur), pages, page_translations (ou colonnes par langue), blog_categories, blog_category_translations, blog_posts, blog_post_translations, tools, tool_translations (ou colonnes par langue incluant custom_h1, custom_description), plans, plan_features, plan_tool, subscriptions, payments, invoices, affiliates, affiliate_commissions, withdrawal_requests, languages, et potentiellement une table seo_metadata pour les pages statiques ou intégrer les champs SEO dans les tables/translations existantes. Utiliser un package comme spatie/laravel-translatable ou implémenter une logique de traduction via des tables pivots *_translations.

Structure du Projet: Définir et utiliser une structure de dossiers claire et cohérente dans app/, routes/, resources/, config/, etc., reflétant l'architecture modulaire.
Authentification & Gestion Utilisateurs: Implémenter un système d'authentification complet : enregistrement standard (activable/désactivable), connexion, mot de passe oublié, "Se souvenir de moi" (configurable), authentification à deux facteurs (2FA), connexion/inscription via réseaux sociaux (Facebook, Google, X, Discord, LinkedIn, Microsoft - configurables individuellement) via Laravel Socialite. Inclure toutes les options de gestion des utilisateurs spécifiées dans l'admin (social login exclusif, mot de passe requis pour social, cases newsletter, confirmation email, email de bienvenue, auto-suppression inactifs/non confirmés, rappels).

Panneau d'Administration: Créer un panneau d'administration complet (protégé par middleware auth et potentiellement un rôle/permission admin) utilisant Blade/Bootstrap/jQuery. Il doit permettre la gestion détaillée de tous les points 1 à 19 listés dans les prompts précédents, notamment : Paramétrage Général, Mode Maintenance, Sitemap, Gestion Utilisateurs (avec toutes les bascules), Gestion Outils (avec personnalisation H1/Description par outil/langue), Paramètres Paiements, Détails Entreprise, Configuration Processeurs Paiement, Système d'Affiliation, Configuration Captcha, Configuration Social Login, Gestion Publicités (avec détecteur AdBlock), Gestion Langues (ajout/suppression et édition des traductions UI/Outils/SEO), Liens Sociaux, Paramètres SMTP, Code Personnalisé, Gestion Packages (avec sélection des outils), et Gestion SEO (métadonnées title/description multilingues pour pages Accueil, Auth, Termes, Contact, etc.).

Fonctionnalités Utilisateur: Interface utilisateur claire (Blade/Bootstrap), accès aux outils organisés (par catégorie si activé), soumission des formulaires d'outils via jQuery/AJAX sans rechargement de page, mode sombre (optionnel via switch), support RTL, affichage des publicités configurées (avec gestion AdBlock), accès restreint aux outils selon l'abonnement. Implémentation SEO dynamique côté utilisateur : génération automatique des balises <title>, meta description, <h1>, et description associée selon les configurations admin (personnalisées ou par défaut) et la langue courante. Optimisation OpenGraph et Twitter Card.

Développement Modulaire des Outils: Pour chaque outil : Route(s) dédiée(s), Contrôleur (méthodes show et process), Form Request pour la validation, Service dédié pour la logique métier ("processeur"), Vue Blade spécifique. La vue doit utiliser les variables SEO ($pageTitle, $customH1, $customDescription) passées par le contrôleur et utiliser la fonction __() de Laravel pour tous les textes (labels, placeholders, options, messages d'aide, etc.) avec des clés de traduction structurées (ex: tools.case_converter.input_label). La soumission se fait via jQuery/AJAX.
Je te conseille de Tools_logic_example.php qui est la racine  du projet. Chaque methode de ce fichier donne un exemple d'outils developpé dans un autre projet tu pourras copié intelligemment et l'integrer ici

Multilinguisme: Implémenter un système multilingue robuste couvrant l'intégralité de l'application : interface générale, panneau d'administration, contenu généré par l'admin (pages, blog), tous les aspects des outils (noms, descriptions personnalisées, H1 personnalisés, labels de formulaire, placeholders, options, messages d'erreur/succès), et toutes les métadonnées SEO. L'admin doit pouvoir ajouter/gérer les langues et éditer toutes les traductions.
Monétisation (Licence Étendue): Implémenter le système de paiement complet : gestion des plans (CRUD, fréquences : mensuel/annuel/lifetime), association outils/plans, intégration des passerelles de paiement (PayPal, Stripe via Cashier, Flutterwave, etc. - configurables), gestion des paiements ponctuels et récurrents, support multi-devises (basé sur la configuration), gestion des codes promo/réduction, gestion des taxes (configurables et applicables aux plans), génération de factures, gestion des paiements dans l'admin, statistiques de revenus.

Sécurité: Appliquer les meilleures pratiques de sécurité Laravel : protection CSRF, validation rigoureuse de toutes les entrées (Form Requests), échappement des sorties (Blade par défaut), utilisation de requêtes préparées (Eloquent par défaut), prévention des injections SQL/XSS, politique de sécurité de contenu (CSP) si possible, limitation de taux (rate limiting) sur les routes sensibles (auth, soumission d'outils), gestion sécurisée des clés API et des secrets (.env).
Dépendances Externes / APIs: Pour les outils utilisant des API externes (Resmush.it, Google Safe Browsing, IP-API, etc.), encapsuler les appels dans des classes de Service dédiées. Gérer les clés API via la configuration admin et les fichiers .env. Implémenter une gestion robuste des erreurs (timeouts, réponses invalides, limites de taux API).
Première Étape Concrète Demandée (Action Initiale):

Génère le code initial pour :
La structure de dossiers détaillée (conformément au point 3).
Les migrations Laravel initiales pour les tables users, languages, settings, tools, tool_translations (ou structure alternative pour multilingue/SEO H1/Description), pages, page_translations (ou structure alternative pour multilingue/SEO Meta). Choisis et justifie brièvement une approche pour le stockage multilingue/SEO.
Le code complet (Route, Controller, FormRequest, Service, Vue Blade, JS/AJAX) pour l'outil "Case Converter", en démontrant 

explicitement :
Le passage et l'utilisation des variables SEO/Contenu ($pageTitle, $customH1, $customDescription) dans le contrôleur et la vue.
L'utilisation de __() pour tous les textes traduisibles de l'outil (titre, description par défaut si non personnalisée, labels, options, bouton).
La structure AJAX jQuery pour la soumission et l'affichage des résultats/erreurs.

Rappel Important: La qualité, la clarté, la maintenabilité, la sécurité, la traduisibilité complète et la personnalisation SEO sont primordiales. Le code généré servira de base et de référence pour le développement itératif des autres modules et outils. Il nécessitera une révision humaine.

STRUCTURE DE LA DOCUMENTATION MARKDOWN DEMANDÉE (À UTILISER COMME RÉFÉRENCE)
Utilisez ce plan pour structurer la documentation qui doit accompagner le code généré pour chaque point du prompt principal. Utilisez un formatage Markdown clair (titres, listes, blocs de code php ...) adapté aux IDE comme Cursor.
Documentation Générale du Projet
Brève description du projet.

Objectifs principaux.
Stack technique utilisée (versions précises).
Point 1: Architecture Globale
Description de l'Architecture:
Présentation de l'approche choisie (Modulaire, DDD-lite, etc.).
Justification des choix architecturaux.

Structure Modulaire:
Explication de l'organisation en modules (Auth, Admin, Tools, etc.).
Description de l'interaction entre les modules.
Flux de Données Typique:
Schéma simplifié ou description d'une requête typique traversant l'application.
Point 2: Stack Technique & Base de Données

Technologies:
Liste des versions précises (PHP, Laravel, Node, MySQL/PostgreSQL, etc.).
Liste des principaux packages Laravel utilisés (ex: Spatie, Socialite, Cashier).
Schéma de Base de Données:
Description textuelle ou diagramme des tables principales et de leurs relations.
Justification de la structure des tables clés (ex: settings, users).

Stratégie Multilingue/SEO:
Explication détaillée de l'approche choisie pour stocker les traductions et les données SEO (colonnes dédiées, tables *_translations, package spatie/laravel-translatable).
Avantages et inconvénients de l'approche retenue.

Migrations:
Exemples de code des migrations les plus importantes ou complexes.
Point 3: Structure du Projet
Arborescence des Dossiers:
Représentation textuelle de la structure des répertoires clés (app/, routes/, resources/, config/, database/, public/).
Description du rôle de chaque dossier principal et sous-dossier pertinent (ex: app/Services, app/Http/Controllers/Admin, resources/views/tools).

Conventions de Nommage:
Règles suivies pour nommer les classes, méthodes, vues, routes, etc.
Point 4: Authentification & Gestion Utilisateurs

Système d'Authentification:
Package utilisé (Breeze/Jetstream/Manuel) et adaptation éventuelle.
Flux d'enregistrement, de connexion, de réinitialisation de mot de passe.

Authentification à Deux Facteurs (2FA):
Méthode d'implémentation (package, logique custom).
Configuration requise.
Connexion Sociale (Socialite):
Configuration (.env, config/services.php).
Liste des fournisseurs implémentés.
Flux de connexion/enregistrement via un réseau social.

Gestion des Options Admin:
Comment les paramètres admin (ex: désactiver l'enregistrement) sont appliqués (middlewares, conditions dans les vues/contrôleurs).
Point 5: Panneau d'Administration

Accès et Sécurité:
Middleware de protection (auth, rôle/permission admin).
Structure des Routes/Contrôleurs:
Organisation des routes (routes/admin.php).
Liste et rôle des principaux contrôleurs admin (ex: DashboardController, UserController, ToolSettingsController).

Gestion des Paramètres:
Méthode de stockage (table settings, tables dédiées).
Exemple de formulaire admin pour un paramètre complexe.

Gestion des Traductions:
Interface ou méthode utilisée pour permettre à l'admin d'éditer les traductions (package, logique custom).
Interaction avec les fichiers de langue ou la base de données.

Gestion SEO Admin:
Description de l'interface permettant de gérer les métadonnées des pages statiques et les H1/Descriptions des outils.
Point 6: Fonctionnalités Utilisateur

Layout Principal:
Structure du layout Blade (layouts/app.blade.php).
Gestion du mode sombre et RTL.
Intégration dynamique du titre (<title>) et des meta tags.

Interaction AJAX (Outils):
Pattern jQuery/AJAX utilisé pour la soumission des formulaires d'outils.
Format JSON attendu en réponse du backend ({success: boolean, data: ..., message: ...}).
Gestion de l'affichage des résultats et des erreurs côté frontend.

Implémentation SEO Côté Utilisateur:
Comment les données SEO (titre, description, H1) sont récupérées dans le contrôleur et injectées dans la vue Blade.
Génération des balises OpenGraph et Twitter Card.

Contrôle d'Accès (Abonnements):
Middleware utilisé pour restreindre l'accès aux outils payants.
Logique de vérification de l'abonnement actif.
Point 7: Développement Modulaire des Outils

Structure d'un Module Outil:
Exemple complet pour un outil simple (ex: Case Converter) :
Définition de la Route (routes/tools.php).
Code du Contrôleur (ToolController ou dédié).
Code du Form Request.
Code de la Classe Service ("Processeur").
Code de la Vue Blade (incluant l'utilisation de __() et des variables $pageTitle, $customH1, $customDescription).
Code JavaScript/jQuery pour l'AJAX.

Conventions de Traduction:
Structure des clés de traduction pour les outils (ex: tools.{tool_slug}.{element}).
Récupération Données Personnalisées (SEO/H1/Desc):
Comment le contrôleur accède aux données custom_h1, custom_description via le modèle Tool et ses traductions.
Point 8: Multilinguisme
Mécanisme de Sélection de Langue:
Middleware, segment d'URL, session, ou autre méthode utilisée.

Stockage des Traductions:
Détails sur l'utilisation des fichiers lang/{locale}/ ou de la base de données (ex: avec spatie/laravel-translation-loader).

Utilisation dans le Code:
Exemples concrets d'utilisation de __() / @lang() dans Blade.
Exemples d'utilisation de __() dans les Contrôleurs/Services.

Modèles Traduisibles (Eloquent):
Configuration et utilisation du trait HasTranslations (Spatie) ou de la logique custom pour accéder aux attributs traduits.
Point 9: Monétisation (Licence Étendue)

Structure de la Base de Données:
Description des tables plans, subscriptions, payments, invoices, etc.
Intégration Passerelle de Paiement (Exemple: Stripe):
Installation/Configuration de Laravel Cashier (ou SDK direct).
Création des produits/prix sur Stripe et synchronisation.
Flux de souscription (Checkout).
Configuration et logique des Webhooks pour gérer les événements (paiement réussi, échec, annulation).

Gestion des Plans et Accès:
Interface admin pour gérer les plans et associer les outils.
Logique du middleware de contrôle d'accès.

Taxes et Facturation:
Mécanisme de configuration des taxes.
Utilisation de packages (ex: laravel-invoiceable) ou logique custom pour générer les factures.
Point 10: Sécurité

Mesures Implémentées:
CSRF (par défaut).
Validation (Form Requests).
Échappement des Sorties (Blade).
Prévention XSS/Injection SQL (bonnes pratiques Eloquent/Blade).
Rate Limiting (configuration et routes protégées).
Politique de Sécurité de Contenu (CSP) (si implémentée).

Gestion des Secrets:
Utilisation du fichier .env.
Configuration dans config/services.php.
Recommandations Production:
Permissions de fichiers/dossiers.
Configuration HTTPS.
Point 11: Dépendances Externes / APIs
Liste des APIs Externes Utilisées:
Pour quels outils ?
Encapsulation des Appels:
Exemple d'une classe Service dédiée à un appel API (utilisation de Http:: ou Guzzle).

Gestion des Clés API:
Configuration dans l'admin et stockage sécurisé (.env).
Gestion des Erreurs et Mise en Cache:
Stratégies pour gérer les timeouts, les réponses invalides, les limites de taux.
Utilisation éventuelle du cache Laravel pour les réponses API.

Aant de debuter le developpement je veux que tu mettre en place le landing page et le dashboard selon les capture que je t'enverrai. Je veux un landing page moderne avec des animations des effects visuels et un design.
Le dashboard sera aussi designer avec expertise et modernité avec des animation , un menu vertical avec une icone de rabat pour afficher des icone verticalement à la place du menu 

Des Icones seront utiliser pour la presentation de chaque outil

A chaque étape demande des précisions si tu as besoin de précision
