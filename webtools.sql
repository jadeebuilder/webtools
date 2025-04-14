-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2025 at 08:12 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webtools`
--

-- --------------------------------------------------------

--
-- Table structure for table `ad_block_settings`
--

CREATE TABLE `ad_block_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `block_content` tinyint(1) NOT NULL DEFAULT '0',
  `show_message` tinyint(1) NOT NULL DEFAULT '1',
  `message_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_button` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `countdown` int NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ad_block_settings`
--

INSERT INTO `ad_block_settings` (`id`, `enabled`, `block_content`, `show_message`, `message_title`, `message_text`, `message_button`, `countdown`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Nous avons détecté que vous utilisez un bloqueur de publicités', 'Notre site est gratuit et ne survit que grâce à la publicité. Merci de désactiver votre bloqueur de publicités pour continuer.', 'J\'ai désactivé mon AdBlock', 10, '2025-04-12 20:57:05', '2025-04-12 22:37:35');

-- --------------------------------------------------------

--
-- Table structure for table `ad_block_trackings`
--

CREATE TABLE `ad_block_trackings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `device` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_adblock_detected` tinyint(1) NOT NULL DEFAULT '0',
  `visit_date` datetime NOT NULL,
  `page_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ad_settings`
--

CREATE TABLE `ad_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` text COLLATE utf8mb4_unicode_ci,
  `display_on` json DEFAULT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ad_settings`
--

INSERT INTO `ad_settings` (`id`, `position`, `active`, `type`, `image`, `link`, `alt`, `code`, `display_on`, `priority`, `created_at`, `updated_at`) VALUES
(4, 'after_nav', 1, 'image', 'storage/images/ads/ad_1744616979_ad720x90.png', 'http://webtools.test/', '#', NULL, '\"[\\\"home\\\",\\\"tool\\\",\\\"category\\\",\\\"admin\\\"]\"', 10, '2025-04-12 14:52:49', '2025-04-14 06:49:39'),
(6, 'before_tool', 1, 'image', 'storage/images/ads/ad_1744475785_ad720x90.png', 'http://webtools.test/', '#', NULL, '\"[\\\"home\\\",\\\"tool\\\",\\\"category\\\"]\"', 10, '2025-04-12 15:36:25', '2025-04-12 17:50:18'),
(7, 'before_footer', 1, 'image', 'storage/images/ads/ad_1744476408_ad300x250.png', 'http://webtools.test/', '#', NULL, '\"[\\\"home\\\",\\\"tool\\\",\\\"category\\\"]\"', 10, '2025-04-12 15:46:48', '2025-04-12 15:46:48'),
(31, 'after_tool', 1, 'image', 'storage/images/ads/ad_1744491388_ad300x250.png', 'http://webtools.test/', 'TEST DE PUBLICITE', NULL, '\"[\\\"home\\\",\\\"tool\\\",\\\"category\\\"]\"', 10, '2025-04-12 19:56:28', '2025-04-12 19:57:03'),
(34, 'left_sidebar', 1, 'image', 'storage/images/ads/ad_1744501401_ad300x250.png', 'http://webtools.test/', 'TEST DE PUBLICITE', NULL, '\"[\\\"home\\\",\\\"tool\\\",\\\"category\\\"]\"', 10, '2025-04-12 22:43:21', '2025-04-12 22:43:21'),
(35, 'right_sidebar', 1, 'image', 'storage/images/ads/ad_1744501467_ad300x250.png', 'http://webtools.test/', 'TEST DE PUBLICITE', NULL, '\"[\\\"home\\\",\\\"tool\\\",\\\"category\\\"]\"', 10, '2025-04-12 22:44:27', '2025-04-12 22:44:27');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `faq_category_id` bigint UNSIGNED NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `faq_category_id`, `question`, `answer`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Qu\'est-ce que WebTools?', 'WebTools est une plateforme en ligne qui propose une collection d\'outils web pour simplifier les tâches quotidiennes des développeurs, des créateurs de contenu et des professionnels du marketing. Notre mission est de fournir des outils utiles, rapides et faciles à utiliser.', 1, 1, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(2, 1, 'Tous les outils sont-ils gratuits ?', 'Oui, la plupart de nos outils sont disponibles gratuitement. Cependant, certaines fonctionnalités avancées ou l\'utilisation intensive de certains outils peuvent nécessiter un abonnement Pro. Consultez notre page de tarification pour plus de détails.', 1, 2, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(3, 1, 'Proposez-vous un accès API ?', 'Oui, un accès API est disponible avec notre abonnement Pro. Vous pouvez ainsi intégrer nos outils directement dans vos applications ou automatiser certaines tâches. La documentation complète de l\'API est fournie aux abonnés.', 1, 3, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(4, 1, 'À quelle fréquence ajoutez-vous de nouveaux outils ?', 'Nous enrichissons régulièrement notre catalogue d\'outils et mettons à jour les fonctionnalités existantes. En moyenne, nous ajoutons 2 à 3 nouveaux outils chaque mois. Vous pouvez vous abonner à notre newsletter pour être informé des dernières nouveautés.', 1, 4, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(5, 2, 'Comment créer un compte ?', 'Pour créer un compte, cliquez sur le bouton \"S\'inscrire\" en haut à droite de la page d\'accueil. Remplissez le formulaire avec vos informations et validez votre inscription. Vous recevrez un email de confirmation pour activer votre compte.', 1, 1, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(6, 2, 'Comment réinitialiser mon mot de passe ?', 'Si vous avez oublié votre mot de passe, cliquez sur \"Mot de passe oublié ?\" sur la page de connexion. Entrez votre adresse email et suivez les instructions envoyées par email pour réinitialiser votre mot de passe.', 1, 2, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(7, 2, 'Mes données sont-elles sécurisées ?', 'Oui, nous prenons la sécurité des données très au sérieux. Nous utilisons un chiffrement SSL pour toutes les communications et ne stockons pas vos données traitées. La plupart des opérations sont effectuées côté client lorsque c\'est possible pour garantir la confidentialité de vos informations.', 1, 3, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(8, 3, 'Comment utiliser les outils de conversion d\'images ?', 'Pour utiliser nos outils de conversion d\'images, sélectionnez l\'outil approprié dans la catégorie \"Outils d\'image\". Suivez les instructions à l\'écran pour télécharger votre image source, définir les paramètres souhaités, puis cliquez sur le bouton de conversion. Vous pourrez ensuite télécharger l\'image convertie.', 1, 1, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(9, 3, 'Y a-t-il une limite de taille pour les fichiers ?', 'Oui, en accès gratuit, les fichiers sont limités à 10 Mo. Les utilisateurs Pro peuvent traiter des fichiers jusqu\'à 100 Mo. Cette limitation est en place pour garantir des performances optimales de la plateforme pour tous les utilisateurs.', 1, 2, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(10, 3, 'Puis-je utiliser les outils sur mobile ?', 'Absolument ! Notre plateforme est entièrement responsive et optimisée pour les appareils mobiles et tablettes. Vous pouvez utiliser la plupart de nos outils sur n\'importe quel appareil avec une connexion internet.', 1, 3, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(11, 4, 'Quels modes de paiement acceptez-vous ?', 'Nous acceptons les principales cartes de crédit (Visa, Mastercard, American Express), PayPal, et dans certains pays, les virements bancaires. Toutes les transactions sont sécurisées et chiffrées.', 1, 1, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(12, 4, 'Comment annuler mon abonnement ?', 'Vous pouvez annuler votre abonnement à tout moment depuis votre espace membre, dans la section \"Abonnements\". L\'annulation prendra effet à la fin de la période de facturation en cours. Vous conserverez l\'accès aux fonctionnalités premium jusqu\'à cette date.', 1, 2, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(13, 4, 'Proposez-vous une garantie de remboursement ?', 'Oui, nous offrons une garantie de remboursement de 14 jours. Si vous n\'êtes pas satisfait de votre abonnement premium, vous pouvez demander un remboursement complet dans les 14 jours suivant votre souscription. Contactez notre service client pour plus de détails.', 1, 3, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(14, 5, 'Comment contacter le support technique ?', 'Vous pouvez contacter notre équipe de support technique via le formulaire de contact disponible sur notre site, par email à support@webtools.com ou via le chat en direct disponible pour les utilisateurs premium. Notre équipe est disponible du lundi au vendredi, de 9h à 18h (GMT+1).', 1, 1, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(15, 5, 'Le site est inaccessible ou un outil ne fonctionne pas, que faire ?', 'Si vous rencontrez des problèmes d\'accès au site ou si un outil spécifique ne fonctionne pas, essayez d\'abord de rafraîchir la page et de vider le cache de votre navigateur. Si le problème persiste, vérifiez notre page de statut des services ou contactez notre support technique.', 1, 2, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(16, 5, 'Puis-je suggérer un nouvel outil ?', 'Bien sûr ! Nous apprécions les retours de nos utilisateurs. Vous pouvez nous envoyer vos suggestions d\'outils via notre formulaire de contact ou par email à suggestions@webtools.com. Nous étudions toutes les propositions et intégrons régulièrement de nouvelles idées à notre feuille de route.', 1, 3, '2025-04-13 13:24:16', '2025-04-13 13:24:16'),
(17, 2, 'test fr', 'test fr', 1, 2, '2025-04-13 15:37:25', '2025-04-13 15:37:25');

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE `faq_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faq_categories`
--

INSERT INTO `faq_categories` (`id`, `name`, `slug`, `description`, `icon`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Informations générales', 'informations-generales', 'Informations de base sur notre plateforme et nos services', 'fas fa-info-circle', 1, 1, '2025-04-13 13:24:15', '2025-04-13 13:24:15'),
(2, 'Compte et utilisateur', 'compte-et-utilisateur', 'Questions concernant la gestion de votre compte et vos données utilisateur', 'fas fa-user', 1, 2, '2025-04-13 13:24:15', '2025-04-13 13:24:15'),
(3, 'Utilisation des outils', 'utilisation-des-outils', 'Comment utiliser efficacement les outils disponibles sur notre plateforme', 'fas fa-tools', 1, 3, '2025-04-13 13:24:15', '2025-04-13 13:24:15'),
(4, 'Facturation et abonnements', 'facturation-et-abonnements', 'Questions relatives aux paiements, abonnements et facturation', 'fas fa-credit-card', 1, 4, '2025-04-13 13:24:15', '2025-04-13 13:24:15'),
(5, 'Support technique', 'support-technique', 'Assistance technique et résolution des problèmes courants', 'fas fa-headset', 1, 5, '2025-04-13 13:24:15', '2025-04-13 13:24:15');

-- --------------------------------------------------------

--
-- Table structure for table `faq_category_translations`
--

CREATE TABLE `faq_category_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `faq_category_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faq_category_translations`
--

INSERT INTO `faq_category_translations` (`id`, `faq_category_id`, `language_id`, `name`, `description`, `slug`) VALUES
(1, 1, 1, 'Informations générales', 'Informations de base sur notre plateforme et nos services', 'informations-generales'),
(2, 2, 1, 'Compte et utilisateur', 'Questions concernant la gestion de votre compte et vos données utilisateur', 'compte-et-utilisateur'),
(3, 3, 1, 'Utilisation des outils', 'Comment utiliser efficacement les outils disponibles sur notre plateforme', 'utilisation-des-outils'),
(4, 4, 1, 'Facturation et abonnements', 'Questions relatives aux paiements, abonnements et facturation', 'facturation-et-abonnements'),
(5, 5, 1, 'Support technique', 'Assistance technique et résolution des problèmes courants', 'support-technique'),
(6, 1, 2, 'General Information', 'Basic information about our platform and services', 'general-information'),
(7, 1, 3, 'Información General', 'Información básica sobre nuestra plataforma y servicios', 'informacion-general'),
(8, 2, 2, 'Account and User', 'Questions regarding account management and user data', 'account-and-user'),
(9, 2, 3, 'Cuenta y Usuario', 'Preguntas sobre la gestión de cuentas y datos de usuario', 'cuenta-y-usuario'),
(10, 3, 2, 'Using the Tools', 'How to effectively use the tools available on our platform', 'using-the-tools'),
(11, 3, 3, 'Uso de las Herramientas', 'Cómo utilizar eficazmente las herramientas disponibles en nuestra plataforma', 'uso-de-las-herramientas'),
(12, 4, 2, 'Billing and Subscriptions', 'Questions related to payments, subscriptions, and billing', 'billing-and-subscriptions'),
(13, 4, 3, 'Facturación y Suscripciones', 'Preguntas relacionadas con pagos, suscripciones y facturación', 'facturacion-y-suscripciones'),
(14, 5, 2, 'Technical Support', 'Technical assistance and resolution of common problems', 'technical-support'),
(15, 5, 3, 'Soporte Técnico', 'Asistencia técnica y resolución de problemas comunes', 'soporte-tecnico');

-- --------------------------------------------------------

--
-- Table structure for table `faq_translations`
--

CREATE TABLE `faq_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `faq_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faq_translations`
--

INSERT INTO `faq_translations` (`id`, `faq_id`, `language_id`, `question`, `answer`) VALUES
(1, 17, 1, 'test en', 'test en'),
(2, 17, 2, 'test en', 'test en'),
(3, 17, 3, 'test es', 'test es'),
(4, 1, 1, 'Qu\'est-ce que WebTools?', 'WebTools est une plateforme en ligne qui propose une collection d\'outils web pour simplifier les tâches quotidiennes des développeurs, des créateurs de contenu et des professionnels du marketing. Notre mission est de fournir des outils utiles, rapides et faciles à utiliser.'),
(5, 1, 2, 'What is WebTools?', 'WebTools is an online platform that offers a collection of web tools to simplify daily tasks for developers, content creators, and marketing professionals. Our mission is to provide useful, fast, and easy-to-use tools.'),
(6, 1, 3, '¿Qué es WebTools?', 'WebTools es una plataforma en línea que ofrece una colección de herramientas web para simplificar las tareas diarias de desarrolladores, creadores de contenido y profesionales de marketing. Nuestra misión es proporcionar herramientas útiles, rápidas y fáciles de usar.'),
(7, 2, 1, 'Tous les outils sont-ils gratuits ?', 'Oui, la plupart de nos outils sont disponibles gratuitement. Cependant, certaines fonctionnalités avancées ou l\'utilisation intensive de certains outils peuvent nécessiter un abonnement Pro. Consultez notre page de tarification pour plus de détails.'),
(8, 2, 2, 'Are all tools free?', 'Yes, most of our tools are available for free. However, some advanced features or intensive use of certain tools may require a Pro subscription. Check our pricing page for more details.'),
(9, 2, 3, '¿Todas las herramientas son gratuitas?', 'Sí, la mayoría de nuestras herramientas están disponibles de forma gratuita. Sin embargo, algunas funciones avanzadas o el uso intensivo de ciertas herramientas pueden requerir una suscripción Pro. Consulte nuestra página de precios para obtener más detalles.'),
(10, 3, 1, 'Proposez-vous un accès API ?', 'Oui, un accès API est disponible avec notre abonnement Pro. Vous pouvez ainsi intégrer nos outils directement dans vos applications ou automatiser certaines tâches. La documentation complète de l\'API est fournie aux abonnés.'),
(11, 3, 2, 'Do you offer API access?', 'Yes, API access is available with our Pro subscription. This allows you to integrate our tools directly into your applications or automate certain tasks. Complete API documentation is provided to subscribers.'),
(12, 3, 3, '¿Ofrecen acceso API?', 'Sí, el acceso a la API está disponible con nuestra suscripción Pro. Esto le permite integrar nuestras herramientas directamente en sus aplicaciones o automatizar ciertas tareas. La documentación completa de la API se proporciona a los suscriptores.'),
(13, 4, 1, 'À quelle fréquence ajoutez-vous de nouveaux outils ?', 'Nous enrichissons régulièrement notre catalogue d\'outils et mettons à jour les fonctionnalités existantes. En moyenne, nous ajoutons 2 à 3 nouveaux outils chaque mois. Vous pouvez vous abonner à notre newsletter pour être informé des dernières nouveautés.'),
(14, 4, 2, 'How often do you add new tools?', 'We regularly expand our catalog of tools and update existing features. On average, we add 2-3 new tools each month. You can subscribe to our newsletter to stay informed about the latest additions.'),
(15, 4, 3, '¿Con qué frecuencia agregan nuevas herramientas?', 'Expandimos regularmente nuestro catálogo de herramientas y actualizamos las funciones existentes. En promedio, agregamos de 2 a 3 herramientas nuevas cada mes. Puede suscribirse a nuestro boletín para mantenerse informado sobre las últimas novedades.'),
(16, 5, 1, 'Comment créer un compte ?', 'Pour créer un compte, cliquez sur le bouton \"S\'inscrire\" en haut à droite de la page d\'accueil. Remplissez le formulaire avec vos informations et validez votre inscription. Vous recevrez un email de confirmation pour activer votre compte.'),
(17, 5, 2, 'How do I create an account?', 'To create an account, click on the \"Sign Up\" button at the top right of the homepage. Fill out the form with your information and validate your registration. You will receive a confirmation email to activate your account.'),
(18, 5, 3, '¿Cómo creo una cuenta?', 'Para crear una cuenta, haga clic en el botón \"Registrarse\" en la parte superior derecha de la página de inicio. Complete el formulario con su información y valide su registro. Recibirá un correo electrónico de confirmación para activar su cuenta.'),
(19, 6, 1, 'Comment réinitialiser mon mot de passe ?', 'Si vous avez oublié votre mot de passe, cliquez sur \"Mot de passe oublié ?\" sur la page de connexion. Entrez votre adresse email et suivez les instructions envoyées par email pour réinitialiser votre mot de passe.'),
(20, 6, 2, 'How do I reset my password?', 'If you forgot your password, click on \"Forgot Password?\" on the login page. Enter your email address and follow the instructions sent by email to reset your password.'),
(21, 6, 3, '¿Cómo restablezco mi contraseña?', 'Si olvidó su contraseña, haga clic en \"¿Olvidó su contraseña?\" en la página de inicio de sesión. Ingrese su dirección de correo electrónico y siga las instrucciones enviadas por correo electrónico para restablecer su contraseña.'),
(22, 7, 1, 'Mes données sont-elles sécurisées ?', 'Oui, nous prenons la sécurité des données très au sérieux. Nous utilisons un chiffrement SSL pour toutes les communications et ne stockons pas vos données traitées. La plupart des opérations sont effectuées côté client lorsque c\'est possible pour garantir la confidentialité de vos informations.'),
(23, 7, 2, 'Is my data secure?', 'Yes, we take data security very seriously. We use SSL encryption for all communications and do not store your processed data. Most operations are performed client-side when possible to ensure the confidentiality of your information.'),
(24, 7, 3, '¿Están seguros mis datos?', 'Sí, nos tomamos muy en serio la seguridad de los datos. Utilizamos encriptación SSL para todas las comunicaciones y no almacenamos sus datos procesados. La mayoría de las operaciones se realizan del lado del cliente cuando es posible para garantizar la confidencialidad de su información.'),
(25, 8, 1, 'Comment utiliser les outils de conversion d\'images ?', 'Pour utiliser nos outils de conversion d\'images, sélectionnez l\'outil approprié dans la catégorie \"Outils d\'image\". Suivez les instructions à l\'écran pour télécharger votre image source, définir les paramètres souhaités, puis cliquez sur le bouton de conversion. Vous pourrez ensuite télécharger l\'image convertie.'),
(26, 8, 2, 'How do I use the image conversion tools?', 'To use our image conversion tools, select the appropriate tool in the \"Image Tools\" category. Follow the on-screen instructions to upload your source image, set the desired parameters, then click the conversion button. You can then download the converted image.'),
(27, 8, 3, '¿Cómo utilizo las herramientas de conversión de imágenes?', 'Para utilizar nuestras herramientas de conversión de imágenes, seleccione la herramienta adecuada en la categoría \"Herramientas de imagen\". Siga las instrucciones en pantalla para cargar su imagen de origen, establezca los parámetros deseados y luego haga clic en el botón de conversión. A continuación, podrá descargar la imagen convertida.'),
(28, 9, 1, 'Y a-t-il une limite de taille pour les fichiers ?', 'Oui, en accès gratuit, les fichiers sont limités à 10 Mo. Les utilisateurs Pro peuvent traiter des fichiers jusqu\'à 100 Mo. Cette limitation est en place pour garantir des performances optimales de la plateforme pour tous les utilisateurs.'),
(29, 9, 2, 'Is there a file size limit?', 'Yes, with free access, files are limited to 10 MB. Pro users can process files up to 100 MB. This limitation is in place to ensure optimal performance of the platform for all users.'),
(30, 9, 3, '¿Hay un límite de tamaño de archivo?', 'Sí, con acceso gratuito, los archivos están limitados a 10 MB. Los usuarios Pro pueden procesar archivos de hasta 100 MB. Esta limitación está establecida para garantizar un rendimiento óptimo de la plataforma para todos los usuarios.'),
(31, 10, 1, 'Puis-je utiliser les outils sur mobile ?', 'Absolument ! Notre plateforme est entièrement responsive et optimisée pour les appareils mobiles et tablettes. Vous pouvez utiliser la plupart de nos outils sur n\'importe quel appareil avec une connexion internet.'),
(32, 10, 2, 'Can I use the tools on mobile?', 'Absolutely! Our platform is fully responsive and optimized for mobile devices and tablets. You can use most of our tools on any device with an internet connection.'),
(33, 10, 3, '¿Puedo usar las herramientas en el móvil?', '¡Absolutamente! Nuestra plataforma es completamente responsiva y está optimizada para dispositivos móviles y tabletas. Puede utilizar la mayoría de nuestras herramientas en cualquier dispositivo con conexión a internet.'),
(34, 11, 1, 'Quels modes de paiement acceptez-vous ?', 'Nous acceptons les principales cartes de crédit (Visa, Mastercard, American Express), PayPal, et dans certains pays, les virements bancaires. Toutes les transactions sont sécurisées et chiffrées.'),
(35, 11, 2, 'What payment methods do you accept?', 'We accept major credit cards (Visa, Mastercard, American Express), PayPal, and in some countries, bank transfers. All transactions are secure and encrypted.'),
(36, 11, 3, '¿Qué métodos de pago aceptan?', 'Aceptamos las principales tarjetas de crédito (Visa, Mastercard, American Express), PayPal y, en algunos países, transferencias bancarias. Todas las transacciones son seguras y están encriptadas.'),
(37, 12, 1, 'Comment annuler mon abonnement ?', 'Vous pouvez annuler votre abonnement à tout moment depuis votre espace membre, dans la section \"Abonnements\". L\'annulation prendra effet à la fin de la période de facturation en cours. Vous conserverez l\'accès aux fonctionnalités premium jusqu\'à cette date.'),
(38, 12, 2, 'How do I cancel my subscription?', 'You can cancel your subscription at any time from your member area, in the \"Subscriptions\" section. The cancellation will take effect at the end of the current billing period. You will retain access to premium features until that date.'),
(39, 12, 3, '¿Cómo cancelo mi suscripción?', 'Puede cancelar su suscripción en cualquier momento desde su área de miembro, en la sección \"Suscripciones\". La cancelación entrará en vigor al final del período de facturación actual. Conservará el acceso a las funciones premium hasta esa fecha.'),
(40, 13, 1, 'Proposez-vous une garantie de remboursement ?', 'Oui, nous offrons une garantie de remboursement de 14 jours. Si vous n\'êtes pas satisfait de votre abonnement premium, vous pouvez demander un remboursement complet dans les 14 jours suivant votre souscription. Contactez notre service client pour plus de détails.'),
(41, 13, 2, 'Do you offer a money-back guarantee?', 'Yes, we offer a 14-day money-back guarantee. If you are not satisfied with your premium subscription, you can request a full refund within 14 days of your subscription. Contact our customer service for more details.'),
(42, 13, 3, '¿Ofrecen garantía de devolución de dinero?', 'Sí, ofrecemos una garantía de devolución de dinero de 14 días. Si no está satisfecho con su suscripción premium, puede solicitar un reembolso completo dentro de los 14 días posteriores a su suscripción. Contacte con nuestro servicio de atención al cliente para más detalles.'),
(43, 14, 1, 'Comment contacter le support technique ?', 'Vous pouvez contacter notre équipe de support technique via le formulaire de contact disponible sur notre site, par email à support@webtools.com ou via le chat en direct disponible pour les utilisateurs premium. Notre équipe est disponible du lundi au vendredi, de 9h à 18h (GMT+1).'),
(44, 14, 2, 'How do I contact technical support?', 'You can contact our technical support team via the contact form available on our site, by email at support@webtools.com, or via live chat available for premium users. Our team is available Monday to Friday, 9am to 6pm (GMT+1).'),
(45, 14, 3, '¿Cómo contacto con el soporte técnico?', 'Puede contactar con nuestro equipo de soporte técnico a través del formulario de contacto disponible en nuestro sitio, por correo electrónico a support@webtools.com o a través del chat en vivo disponible para usuarios premium. Nuestro equipo está disponible de lunes a viernes, de 9 a 18 horas (GMT+1).'),
(46, 15, 1, 'Le site est inaccessible ou un outil ne fonctionne pas, que faire ?', 'Si vous rencontrez des problèmes d\'accès au site ou si un outil spécifique ne fonctionne pas, essayez d\'abord de rafraîchir la page et de vider le cache de votre navigateur. Si le problème persiste, vérifiez notre page de statut des services ou contactez notre support technique.'),
(47, 15, 2, 'The site is inaccessible or a tool is not working, what should I do?', 'If you are experiencing problems accessing the site or if a specific tool is not working, first try refreshing the page and clearing your browser cache. If the problem persists, check our service status page or contact our technical support.'),
(48, 15, 3, 'El sitio es inaccesible o una herramienta no funciona, ¿qué debo hacer?', 'Si experimenta problemas para acceder al sitio o si una herramienta específica no funciona, primero intente actualizar la página y limpiar la caché de su navegador. Si el problema persiste, consulte nuestra página de estado del servicio o contacte con nuestro soporte técnico.'),
(49, 16, 1, 'Puis-je suggérer un nouvel outil ?', 'Bien sûr ! Nous apprécions les retours de nos utilisateurs. Vous pouvez nous envoyer vos suggestions d\'outils via notre formulaire de contact ou par email à suggestions@webtools.com. Nous étudions toutes les propositions et intégrons régulièrement de nouvelles idées à notre feuille de route.'),
(50, 16, 2, 'Can I suggest a new tool?', 'Absolutely! We appreciate user feedback. You can send us your tool suggestions via our contact form or by email at suggestions@webtools.com. We review all proposals and regularly incorporate new ideas into our roadmap.'),
(51, 16, 3, '¿Puedo sugerir una nueva herramienta?', '¡Por supuesto! Agradecemos los comentarios de los usuarios. Puede enviarnos sus sugerencias de herramientas a través de nuestro formulario de contacto o por correo electrónico a suggestions@webtools.com. Revisamos todas las propuestas e incorporamos regularmente nuevas ideas a nuestra hoja de ruta.');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_rtl` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `flag`, `is_active`, `is_default`, `is_rtl`, `created_at`, `updated_at`) VALUES
(1, 'Français', 'fr', 'fr', 1, 1, 0, '2025-04-11 14:10:30', '2025-04-11 14:10:30'),
(2, 'English', 'en', 'gb', 1, 0, 0, '2025-04-11 14:10:30', '2025-04-11 14:10:30'),
(3, 'Español', 'es', 'es', 1, 0, 0, '2025-04-11 14:10:30', '2025-04-11 14:10:30'),
(4, 'العربية', 'ar', 'sa', 0, 0, 1, '2025-04-11 14:10:30', '2025-04-11 14:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(16, '2014_10_12_000000_create_users_table', 1),
(17, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(18, '2019_08_19_000000_create_failed_jobs_table', 1),
(19, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(20, '2025_04_11_100212_add_firstname_lastname_phone_newsletter_to_users_table', 1),
(21, '2025_04_11_132542_add_is_admin_to_users_table', 1),
(22, '2025_04_11_143405_create_tool_categories_table', 1),
(23, '2025_04_11_143411_create_tool_category_translations_table', 1),
(24, '2025_04_11_144102_create_tools_table', 1),
(25, '2025_04_11_144111_create_tool_translations_table', 1),
(26, '2025_04_11_144117_create_languages_table', 1),
(27, '2025_04_11_144124_create_plans_table', 1),
(28, '2025_04_11_144130_create_plan_tools_table', 1),
(29, '2025_04_11_144136_create_settings_table', 1),
(30, '2025_04_11_144257_create_subscriptions_table', 1),
(31, '2025_04_11_160059_create_ad_settings_table', 2),
(32, '2023_05_20_000001_create_tool_template_sections_table', 3),
(33, '2023_05_20_000002_create_tool_templates_table', 3),
(34, '2025_04_12_134957_create_tool_ad_settings_table', 4),
(35, '2025_04_12_215244_create_ad_block_settings_table', 5),
(36, '2025_04_12_220119_create_ad_block_trackings_table', 6),
(37, '2025_04_13_093539_add_meta_data_to_tool_category_translations', 7),
(38, '2023_05_25_000001_create_faq_categories_table', 8),
(39, '2023_05_25_000002_create_faqs_table', 8),
(40, '2023_05_26_000001_create_faq_category_translations_table', 9),
(41, '2023_05_26_000002_create_faq_translations_table', 9),
(42, '2025_04_14_000001_create_testimonials_table', 10),
(43, '2025_04_14_000002_create_testimonial_translations_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `monthly_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `annual_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `lifetime_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `features` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plan_tools`
--

CREATE TABLE `plan_tools` (
  `id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED NOT NULL,
  `tool_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `is_translatable` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `group`, `key`, `value`, `is_public`, `is_translatable`, `created_at`, `updated_at`) VALUES
(1, 'general', 'site_name', 'Webtools', 1, 0, '2025-04-12 09:35:33', '2025-04-12 09:35:33'),
(2, 'general', 'site_description', 'Your one-stop platform for all web development tools. Simple, fast, and reliable solutions for developers worldwide.', 1, 0, '2025-04-12 09:35:33', '2025-04-13 10:37:57'),
(3, 'general', 'meta_title', 'Webtools', 1, 0, '2025-04-12 09:35:33', '2025-04-12 09:35:33'),
(4, 'general', 'meta_description', 'Your one-stop platform for all web development tools. Simple, fast, and reliable solutions for developers worldwide.', 1, 0, '2025-04-12 09:35:33', '2025-04-13 10:37:57'),
(5, 'general', 'meta_keywords', 'Webtools', 1, 0, '2025-04-12 09:35:33', '2025-04-12 09:35:33'),
(6, 'general', 'meta_author', 'Webtools', 1, 0, '2025-04-12 09:35:33', '2025-04-12 09:35:33'),
(7, 'general', 'contact_email', 'contact@webtools.com', 1, 0, '2025-04-12 09:35:33', '2025-04-13 10:37:57'),
(8, 'general', 'google_analytics_id', NULL, 1, 0, '2025-04-12 09:35:33', '2025-04-12 09:35:33'),
(9, 'general', 'facebook_pixel_id', NULL, 1, 0, '2025-04-12 09:35:33', '2025-04-12 09:35:33'),
(10, 'general', 'enable_cookie_banner', '0', 1, 0, '2025-04-12 09:35:33', '2025-04-12 09:35:33'),
(11, 'general', 'enable_dark_mode', '0', 1, 0, '2025-04-12 09:35:33', '2025-04-12 09:35:33'),
(12, 'general', 'default_timezone', 'Europe/Paris', 1, 0, '2025-04-12 09:35:33', '2025-04-12 09:35:33'),
(13, 'general', 'default_locale', 'en', 1, 0, '2025-04-12 09:35:33', '2025-04-12 09:35:33'),
(14, 'general', 'tools_per_page', '12', 1, 0, '2025-04-12 09:35:34', '2025-04-12 09:35:34'),
(15, 'general', 'tools_order', 'desc', 1, 0, '2025-04-12 09:35:34', '2025-04-12 09:35:34'),
(16, 'maintenance', 'maintenance_mode', '0', 1, 0, '2025-04-12 09:39:39', '2025-04-13 10:42:56'),
(17, 'maintenance', 'maintenance_message', 'Notre site est actuellement en maintenance. Nous serons bientôt de retour!', 1, 0, '2025-04-12 09:39:39', '2025-04-12 09:39:39'),
(18, 'maintenance', 'maintenance_end_date', '2025-04-27T11:39', 1, 0, '2025-04-12 09:39:39', '2025-04-13 10:40:25'),
(19, 'maintenance', 'maintenance_allow_ips', NULL, 1, 0, '2025-04-12 09:39:39', '2025-04-12 09:39:39'),
(20, 'company', 'company_name', 'LUXE CONSULTING', 1, 1, '2025-04-12 09:47:31', '2025-04-12 09:47:31'),
(21, 'company', 'company_registration', '12556565850525', 1, 1, '2025-04-12 09:47:31', '2025-04-12 09:48:38'),
(22, 'company', 'company_vat', '10020245555475', 1, 1, '2025-04-12 09:47:31', '2025-04-12 09:48:38'),
(23, 'company', 'company_address', '02bp457 Cotonou\r\nAgla rue en face petit à petit 2 immeuble carrelé Bureau 136', 1, 1, '2025-04-12 09:47:32', '2025-04-12 09:47:32'),
(24, 'company', 'company_phone', '+22996451040', 1, 1, '2025-04-12 09:47:32', '2025-04-12 09:48:38'),
(25, 'company', 'company_email', 'contact@luxeserverpro.com', 1, 1, '2025-04-12 09:47:32', '2025-04-12 09:48:38'),
(26, 'company', 'company_opening_hours', 'Lundi-Vendredi: 9h-18h, Samedi: 10h-16h\r\nSamedi 10h-14h', 1, 1, '2025-04-12 09:47:32', '2025-04-12 09:48:38'),
(27, 'company', 'company_social_facebook', NULL, 1, 1, '2025-04-12 09:47:32', '2025-04-12 09:47:32'),
(28, 'company', 'company_social_twitter', NULL, 1, 1, '2025-04-12 09:47:32', '2025-04-12 09:47:32'),
(29, 'company', 'company_social_instagram', NULL, 1, 1, '2025-04-12 09:47:32', '2025-04-12 09:47:32'),
(30, 'company', 'company_social_linkedin', NULL, 1, 1, '2025-04-12 09:47:32', '2025-04-12 09:47:32'),
(31, 'company', 'company_social_youtube', NULL, 1, 1, '2025-04-12 09:47:32', '2025-04-12 09:47:32'),
(32, 'general', 'site_logo_light', '/storage/images/logo-light/75k331UXjQ2isxz5Pf1aw53OAv2Rsqohzms4K6jc.png', 1, 0, '2025-04-13 10:37:57', '2025-04-13 10:37:57'),
(33, 'general', 'site_logo_dark', '/storage/images/logo-dark/uIaU6hH8iu0OTBAZRSyztZz3Mu0f4U2u2nbLB0gf.png', 1, 0, '2025-04-13 10:37:57', '2025-04-13 10:37:57'),
(34, 'general', 'site_logo_email', '/storage/images/logo-email/OHMB9jlYXapxpGH8vfL1QIl4BXhc6HoJiYlQwOyx.png', 1, 0, '2025-04-13 10:37:57', '2025-04-13 10:37:57');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED NOT NULL,
  `processor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `processor_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `starts_at` timestamp NOT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `auto_renew` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int NOT NULL DEFAULT '5',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `position`, `content`, `avatar`, `rating`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Sarah Johnson', 'Frontend Developer', 'These tools have become an essential part of my development workflow. They\'re intuitive and save me hours of work.', NULL, 5, 1, 1, '2025-04-13 16:06:10', '2025-04-13 16:06:10'),
(2, 'Michael Chen', 'Full Stack Developer', 'The variety and quality of tools available is impressive. Everything I need is just a click away.', NULL, 5, 1, 2, '2025-04-13 16:06:10', '2025-04-13 16:06:10'),
(3, 'Emily Rodriguez', 'UX Designer', 'Clean interface, powerful features, and regular updates. It\'s exactly what I needed for my projects.', NULL, 5, 1, 3, '2025-04-13 16:06:10', '2025-04-13 16:06:10'),
(4, 'Bobo', 'Ceo', 'The is active field must be true or false.', 'testimonials/1dp4HjMcLeyDrhi9yF3E74CChSyNg8S2BCDwoqQR.png', 5, 1, 4, '2025-04-13 22:14:25', '2025-04-13 22:14:25');

-- --------------------------------------------------------

--
-- Table structure for table `testimonial_translations`
--

CREATE TABLE `testimonial_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `testimonial_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonial_translations`
--

INSERT INTO `testimonial_translations` (`id`, `testimonial_id`, `language_id`, `name`, `position`, `content`) VALUES
(1, 1, 1, 'Sarah Johnson', 'Développeuse Frontend', 'Ces outils sont devenus une partie essentielle de mon flux de travail de développement. Ils sont intuitifs et me font gagner des heures de travail.'),
(2, 1, 3, 'Sarah Johnson', 'Desarrolladora Frontend', 'Estas herramientas se han convertido en una parte esencial de mi flujo de trabajo de desarrollo. Son intuitivas y me ahorran horas de trabajo.'),
(3, 2, 1, 'Michael Chen', 'Développeur Full Stack', 'La variété et la qualité des outils disponibles est impressionnante. Tout ce dont j\'ai besoin est à portée de clic.'),
(4, 2, 3, 'Michael Chen', 'Desarrollador Full Stack', 'La variedad y calidad de las herramientas disponibles es impresionante. Todo lo que necesito está a un solo clic de distancia.'),
(5, 3, 1, 'Emily Rodriguez', 'Designer UX', 'Interface épurée, fonctionnalités puissantes et mises à jour régulières. C\'est exactement ce dont j\'avais besoin pour mes projets.'),
(6, 3, 3, 'Emily Rodriguez', 'Diseñadora UX', 'Interfaz limpia, características potentes y actualizaciones regulares. Es exactamente lo que necesitaba para mis proyectos.'),
(7, 4, 1, 'Test fr', 'Test fr', 'Test fr'),
(8, 4, 2, 'test en', 'test en', 'test en'),
(9, 4, 3, 'test es', 'test es', 'test es');

-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE `tools` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tool_category_id` bigint UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tools`
--

INSERT INTO `tools` (`id`, `slug`, `icon`, `tool_category_id`, `is_active`, `is_premium`, `order`, `created_at`, `updated_at`) VALUES
(1, 'case-converter', 'fa-solid fa-font', 1, 1, 0, 1, '2025-04-11 14:10:31', '2025-04-11 14:10:31');

-- --------------------------------------------------------

--
-- Table structure for table `tool_ad_settings`
--

CREATE TABLE `tool_ad_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `tool_id` bigint UNSIGNED NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tool_categories`
--

CREATE TABLE `tool_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tool_categories`
--

INSERT INTO `tool_categories` (`id`, `slug`, `icon`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'text', 'fa-solid fa-font', 1, 1, '2025-04-11 14:10:30', '2025-04-11 14:10:30'),
(3, 'generator', 'fa-solid fa-magic', 1, 3, '2025-04-11 14:10:31', '2025-04-13 07:48:03'),
(4, 'checker', 'fa-solid fa-check-circle', 1, 0, '2025-04-13 07:35:51', '2025-04-13 07:35:51'),
(5, 'developer', 'fa-solid fa-code', 1, 4, '2025-04-13 07:35:51', '2025-04-13 07:35:51'),
(6, 'image', 'fa-solid fa-image', 1, 5, '2025-04-13 07:35:51', '2025-04-13 07:35:51'),
(7, 'unit', 'fa-solid fa-ruler', 1, 6, '2025-04-13 07:35:51', '2025-04-13 07:35:51'),
(8, 'time', 'fa-solid fa-clock', 1, 7, '2025-04-13 07:35:51', '2025-04-13 07:35:51'),
(9, 'data', 'fa-solid fa-database', 1, 8, '2025-04-13 07:35:51', '2025-04-13 07:35:51'),
(10, 'color', 'fa-solid fa-palette', 1, 9, '2025-04-13 07:35:51', '2025-04-13 07:35:51'),
(11, 'misc', 'fa-solid fa-toolbox', 1, 10, '2025-04-13 07:35:51', '2025-04-13 07:35:51'),
(12, 'converter', 'fa-solid fa-exchange-alt', 1, 2, '2025-04-13 07:35:51', '2025-04-13 07:35:51');

-- --------------------------------------------------------

--
-- Table structure for table `tool_category_translations`
--

CREATE TABLE `tool_category_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `tool_category_id` bigint UNSIGNED NOT NULL,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tool_category_translations`
--

INSERT INTO `tool_category_translations` (`id`, `tool_category_id`, `locale`, `name`, `description`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'fr', 'Outils de texte', 'Outils pour manipuler, formater et analyser du texte.', 'Outils de texte - WebTools | Outils web en ligne', 'Découvrez nos Outils de texte. Outils pour manipuler, formater et analyser du texte. Utilisez-les gratuitement en ligne sans inscription.', '2025-04-11 14:10:30', '2025-04-13 07:40:35'),
(2, 1, 'en', 'Text Tools', 'Tools to manipulate, format and analyze text.', 'Text Tools - WebTools | Outils web en ligne', 'Discover our Text Tools. Tools to manipulate, format and analyze text. Use them online for free without registration.', '2025-04-11 14:10:30', '2025-04-13 07:40:35'),
(3, 1, 'es', 'Herramientas de texto', 'Herramientas para manipular, formatear y analizar texto.', 'Herramientas de texto - WebTools | Outils web en ligne', 'Descubre nuestras Herramientas de texto. Herramientas para manipular, formatear y analizar texto. Utilízalas en línea gratis sin registro.', '2025-04-11 14:10:30', '2025-04-13 07:40:35'),
(7, 3, 'fr', 'Générateurs', 'Outils pour générer des données, du texte ou des codes.', 'Générateurs - WebTools | Outils web en ligne', 'Découvrez nos Générateurs. Outils pour générer des données, du texte ou des codes. Utilisez-les gratuitement en ligne sans inscription.', '2025-04-11 14:10:31', '2025-04-13 07:40:35'),
(8, 3, 'en', 'Generators', 'Tools to generate data, text or codes.', 'Generators - WebTools | Outils web en ligne', 'Discover our Generators. Tools to generate data, text or codes. Use them online for free without registration.', '2025-04-11 14:10:31', '2025-04-13 07:40:35'),
(9, 3, 'es', 'Generadores', 'Herramientas para generar datos, texto o códigos.', 'Generadores - WebTools | Outils web en ligne', 'Descubre nuestras Generadores. Herramientas para generar datos, texto o códigos. Utilízalas en línea gratis sin registro.', '2025-04-11 14:10:31', '2025-04-13 07:40:35'),
(10, 4, 'fr', 'Outils de vérification', 'Une collection d\'excellents outils de vérification pour vous aider à contrôler et vérifier différents types de données.', 'Outils de vérification - WebTools | Outils web en ligne', 'Découvrez nos Outils de vérification. Une collection d\'excellents outils de vérification pour vous aider à contrôler et vérifier différents types de données. Utilisez-les gratuitement en ligne sans inscription.', '2025-04-13 07:35:51', '2025-04-13 07:40:35'),
(11, 4, 'en', 'Checker tools', 'A collection of great checker-type tools to help you check & verify different types of things.', 'Checker tools - WebTools | Outils web en ligne', 'Discover our Checker tools. A collection of great checker-type tools to help you check & verify different types of things. Use them online for free without registration.', '2025-04-13 07:35:51', '2025-04-13 07:40:35'),
(12, 4, 'es', 'Herramientas de verificación', 'Una colección de excelentes herramientas de verificación para ayudarte a verificar diferentes tipos de datos.', 'Herramientas de verificación - WebTools | Outils web en ligne', 'Descubre nuestras Herramientas de verificación. Una colección de excelentes herramientas de verificación para ayudarte a verificar diferentes tipos de datos. Utilízalas en línea gratis sin registro.', '2025-04-13 07:35:51', '2025-04-13 07:40:35'),
(13, 5, 'fr', 'Outils de développement', 'Une collection d\'outils très utiles principalement pour les développeurs et pas seulement.', 'Outils de développement - WebTools | Outils web en ligne', 'Découvrez nos Outils de développement. Une collection d\'outils très utiles principalement pour les développeurs et pas seulement. Utilisez-les gratuitement en ligne sans inscription.', '2025-04-13 07:35:51', '2025-04-13 07:40:35'),
(14, 5, 'en', 'Developer tools', 'A collection of highly useful tools mainly for developers and not only.', 'Developer tools - WebTools | Outils web en ligne', 'Discover our Developer tools. A collection of highly useful tools mainly for developers and not only. Use them online for free without registration.', '2025-04-13 07:35:51', '2025-04-13 07:40:35'),
(15, 5, 'es', 'Herramientas para desarrolladores', 'Una colección de herramientas muy útiles principalmente para desarrolladores y no solo.', 'Herramientas para desarrolladores - WebTools | Outils web en ligne', 'Descubre nuestras Herramientas para desarrolladores. Una colección de herramientas muy útiles principalmente para desarrolladores y no solo. Utilízalas en línea gratis sin registro.', '2025-04-13 07:35:51', '2025-04-13 07:40:35'),
(16, 6, 'fr', 'Outils d\'image', 'Une collection d\'outils qui aident à modifier et convertir des fichiers image.', 'Outils d\'image - WebTools | Outils web en ligne', 'Découvrez nos Outils d\'image. Une collection d\'outils qui aident à modifier et convertir des fichiers image. Utilisez-les gratuitement en ligne sans inscription.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(17, 6, 'en', 'Image tools', 'A collection of tools that help modify & convert image files.', 'Image tools - WebTools | Outils web en ligne', 'Discover our Image tools. A collection of tools that help modify & convert image files. Use them online for free without registration.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(18, 6, 'es', 'Herramientas de imagen', 'Una colección de herramientas que ayudan a modificar y convertir archivos de imagen.', 'Herramientas de imagen - WebTools | Outils web en ligne', 'Descubre nuestras Herramientas de imagen. Una colección de herramientas que ayudan a modificar y convertir archivos de imagen. Utilízalas en línea gratis sin registro.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(19, 7, 'fr', 'Convertisseurs d\'unités', 'Une collection des outils les plus populaires et utiles qui vous aident à convertir facilement des données quotidiennes.', 'Convertisseurs d\'unités - WebTools | Outils web en ligne', 'Découvrez nos Convertisseurs d\'unités. Une collection des outils les plus populaires et utiles qui vous aident à convertir facilement des données quotidiennes. Utilisez-les gratuitement en ligne sans inscription.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(20, 7, 'en', 'Unit converter tools', 'A collection of the most popular and useful tools that help you easily convert day-to-day data.', 'Unit converter tools - WebTools | Outils web en ligne', 'Discover our Unit converter tools. A collection of the most popular and useful tools that help you easily convert day-to-day data. Use them online for free without registration.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(21, 7, 'es', 'Convertidores de unidades', 'Una colección de las herramientas más populares y útiles que te ayudan a convertir fácilmente datos cotidianos.', 'Convertidores de unidades - WebTools | Outils web en ligne', 'Descubre nuestras Convertidores de unidades. Una colección de las herramientas más populares y útiles que te ayudan a convertir fácilmente datos cotidianos. Utilízalas en línea gratis sin registro.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(22, 8, 'fr', 'Convertisseurs de temps', 'Une collection d\'outils liés à la conversion de date et d\'heure.', 'Convertisseurs de temps - WebTools | Outils web en ligne', 'Découvrez nos Convertisseurs de temps. Une collection d\'outils liés à la conversion de date et d\'heure. Utilisez-les gratuitement en ligne sans inscription.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(23, 8, 'en', 'Time converter tools', 'A collection of date & time conversion related tools.', 'Time converter tools - WebTools | Outils web en ligne', 'Discover our Time converter tools. A collection of date & time conversion related tools. Use them online for free without registration.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(24, 8, 'es', 'Convertidores de tiempo', 'Una colección de herramientas relacionadas con la conversión de fecha y hora.', 'Convertidores de tiempo - WebTools | Outils web en ligne', 'Descubre nuestras Convertidores de tiempo. Una colección de herramientas relacionadas con la conversión de fecha y hora. Utilízalas en línea gratis sin registro.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(25, 9, 'fr', 'Convertisseurs de données', 'Une collection d\'outils de conversion de données informatiques et de dimensionnement.', 'Convertisseurs de données - WebTools | Outils web en ligne', 'Découvrez nos Convertisseurs de données. Une collection d\'outils de conversion de données informatiques et de dimensionnement. Utilisez-les gratuitement en ligne sans inscription.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(26, 9, 'en', 'Data converter tools', 'A collection of computer data & sizing converter tools.', 'Data converter tools - WebTools | Outils web en ligne', 'Discover our Data converter tools. A collection of computer data & sizing converter tools. Use them online for free without registration.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(27, 9, 'es', 'Convertidores de datos', 'Una colección de herramientas de conversión de datos informáticos y dimensionamiento.', 'Convertidores de datos - WebTools | Outils web en ligne', 'Descubre nuestras Convertidores de datos. Una colección de herramientas de conversión de datos informáticos y dimensionamiento. Utilízalas en línea gratis sin registro.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(28, 10, 'fr', 'Convertisseurs de couleurs', 'Une collection d\'outils qui aident à convertir les couleurs entre les formats HEX, RGBA, RGB, HSLA, HSL, etc.', 'Convertisseurs de couleurs - WebTools | Outils web en ligne', 'Découvrez nos Convertisseurs de couleurs. Une collection d\'outils qui aident à convertir les couleurs entre les formats HEX, RGBA, RGB, HSLA, HSL, etc. Utilisez-les gratuitement en ligne sans inscription.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(29, 10, 'en', 'Color converter tools', 'A collection of tools that help convert colors between HEX, RGBA, RGB, HSLA, HSL, etc. formats.', 'Color converter tools - WebTools | Outils web en ligne', 'Discover our Color converter tools. A collection of tools that help convert colors between HEX, RGBA, RGB, HSLA, HSL, etc. formats. Use them online for free without registration.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(30, 10, 'es', 'Convertidores de color', 'Una colección de herramientas que ayudan a convertir colores entre formatos HEX, RGBA, RGB, HSLA, HSL, etc.', 'Convertidores de color - WebTools | Outils web en ligne', 'Descubre nuestras Convertidores de color. Una colección de herramientas que ayudan a convertir colores entre formatos HEX, RGBA, RGB, HSLA, HSL, etc. Utilízalas en línea gratis sin registro.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(31, 11, 'fr', 'Outils divers', 'Une collection d\'autres outils aléatoires, mais excellents et utiles.', 'Outils divers - WebTools | Outils web en ligne', 'Découvrez nos Outils divers. Une collection d\'autres outils aléatoires, mais excellents et utiles. Utilisez-les gratuitement en ligne sans inscription.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(32, 11, 'en', 'Misc tools', 'A collection of other random, but great & useful tools.', 'Misc tools - WebTools | Outils web en ligne', 'Discover our Misc tools. A collection of other random, but great & useful tools. Use them online for free without registration.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(33, 11, 'es', 'Herramientas diversas', 'Una colección de otras herramientas aleatorias, pero excelentes y útiles.', 'Herramientas diversas - WebTools | Outils web en ligne', 'Descubre nuestras Herramientas diversas. Una colección de otras herramientas aleatorias, pero excelentes y útiles. Utilízalas en línea gratis sin registro.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(34, 12, 'fr', 'Outils de conversion', 'Une collection d\'outils qui vous aident à convertir facilement des données.', 'Outils de conversion - WebTools | Outils web en ligne', 'Découvrez nos Outils de conversion. Une collection d\'outils qui vous aident à convertir facilement des données. Utilisez-les gratuitement en ligne sans inscription.', '2025-04-13 07:35:51', '2025-04-13 07:40:36'),
(35, 12, 'en', 'Converter tools', 'A collection of tools that help you easily convert data.', 'Converter tools - WebTools | Outils web en ligne', 'Discover our Converter tools. A collection of tools that help you easily convert data. Use them online for free without registration.', '2025-04-13 07:35:51', '2025-04-13 07:40:37'),
(36, 12, 'es', 'Herramientas de conversión', 'Una colección de herramientas que te ayudan a convertir datos fácilmente.', 'Herramientas de conversión - WebTools | Outils web en ligne', 'Descubre nuestras Herramientas de conversión. Una colección de herramientas que te ayudan a convertir datos fácilmente. Utilízalas en línea gratis sin registro.', '2025-04-13 07:35:51', '2025-04-13 07:40:37');

-- --------------------------------------------------------

--
-- Table structure for table `tool_templates`
--

CREATE TABLE `tool_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `tool_id` bigint UNSIGNED NOT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tool_template_sections`
--

CREATE TABLE `tool_template_sections` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `partial_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tool_template_sections`
--

INSERT INTO `tool_template_sections` (`id`, `name`, `partial_path`, `icon`, `description`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Témoignages', 'partials.home.testimonials', 'chat-bubble-left-right', 'Section des témoignages clients', 1, 1, '2025-04-11 20:00:55', '2025-04-11 20:00:55'),
(2, 'FAQ', 'partials.home.faq', 'question-mark-circle', 'Questions fréquemment posées', 1, 2, '2025-04-11 20:00:55', '2025-04-11 20:00:55'),
(3, 'Outils populaires', 'partials.home.popular-tools', 'star', 'Affiche les outils les plus populaires', 1, 3, '2025-04-11 20:00:55', '2025-04-11 20:00:55'),
(4, 'Catégories d\'outils', 'partials.home.tool-categories', 'folder', 'Liste des catégories d\'outils disponibles', 1, 4, '2025-04-11 20:00:55', '2025-04-11 20:00:55'),
(5, 'Forfaits', 'partials.home.packages', 'currency-dollar', 'Présentation des forfaits disponibles', 1, 5, '2025-04-11 20:00:55', '2025-04-11 20:00:55'),
(6, 'Témoignages', 'partials.home.testimonials', 'chat-bubble-left-right', 'Section des témoignages clients', 1, 1, '2025-04-12 06:06:56', '2025-04-12 06:06:56'),
(7, 'FAQ', 'partials.home.faq', 'question-mark-circle', 'Questions fréquemment posées', 1, 2, '2025-04-12 06:06:57', '2025-04-12 06:06:57'),
(8, 'Outils populaires', 'partials.home.popular-tools', 'star', 'Affiche les outils les plus populaires', 1, 3, '2025-04-12 06:06:57', '2025-04-12 06:06:57'),
(9, 'Catégories d\'outils', 'partials.home.tool-categories', 'folder', 'Liste des catégories d\'outils disponibles', 1, 4, '2025-04-12 06:06:57', '2025-04-12 06:06:57'),
(10, 'Forfaits', 'partials.home.packages', 'currency-dollar', 'Présentation des forfaits disponibles', 1, 5, '2025-04-12 06:06:57', '2025-04-12 06:06:57');

-- --------------------------------------------------------

--
-- Table structure for table `tool_translations`
--

CREATE TABLE `tool_translations` (
  `id` bigint UNSIGNED NOT NULL,
  `tool_id` bigint UNSIGNED NOT NULL,
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `custom_h1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_description` text COLLATE utf8mb4_unicode_ci,
  `meta_title` text COLLATE utf8mb4_unicode_ci,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tool_translations`
--

INSERT INTO `tool_translations` (`id`, `tool_id`, `locale`, `name`, `description`, `custom_h1`, `custom_description`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'fr', 'Convertisseur de Casse', 'Transformez facilement votre texte en majuscules, minuscules, casse de titre et plus encore avec notre convertisseur de casse en ligne gratuit.', 'Convertisseur de Casse en Ligne', 'Transformez le format de votre texte en majuscules, minuscules, camelCase, PascalCase, snake_case et bien plus encore, gratuitement et sans limite.', 'Convertisseur de Casse | Transformez le Texte en Majuscules, Minuscules et Plus', 'Utilisez notre outil gratuit de conversion de casse pour transformer instantanément votre texte en majuscules, minuscules, casse de titre, camelCase, PascalCase, snake_case et autres formats.', '2025-04-11 14:10:31', '2025-04-11 14:10:31'),
(2, 1, 'en', 'Case Converter', 'Easily transform your text to uppercase, lowercase, title case, and more with our free online case converter tool.', 'Online Case Converter', 'Transform your text format to uppercase, lowercase, camelCase, PascalCase, snake_case and more, for free with no limits.', 'Case Converter | Transform Text to Uppercase, Lowercase and More', 'Use our free case conversion tool to instantly transform your text to uppercase, lowercase, title case, camelCase, PascalCase, snake_case and other formats.', '2025-04-11 14:10:31', '2025-04-11 14:10:31'),
(3, 1, 'es', 'Convertidor de Mayúsculas y Minúsculas', 'Transforma fácilmente tu texto a mayúsculas, minúsculas, formato título y más con nuestra herramienta gratuita de conversión de casos.', 'Convertidor de Mayúsculas y Minúsculas Online', 'Transforma el formato de tu texto a mayúsculas, minúsculas, camelCase, PascalCase, snake_case y más, gratis y sin límites.', 'Convertidor de Mayúsculas y Minúsculas | Transforma Texto a Mayúsculas, Minúsculas y Más', 'Utiliza nuestra herramienta gratuita de conversión de casos para transformar instantáneamente tu texto a mayúsculas, minúsculas, formato título, camelCase, PascalCase, snake_case y otros formatos.', '2025-04-11 14:10:32', '2025-04-11 14:10:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `newsletter_subscribed` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `phone`, `email`, `is_admin`, `newsletter_subscribed`, `email_verified_at`, `password`, `profile_photo_path`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'DOHETO', 'FASSINOU', NULL, 'luxeconsultingpro@gmail.com', 1, 1, NULL, '$2y$10$V7uD9S0nqY0V3UAZ7ROgae0K7jeXSlW2.EyWBUScaa22Po8/04lb2', NULL, '9qBzqHFMznuyFtT8qPePcDBUWKMJxI0IMm2Ejkvsg04gmjbQFccDi0MQOT2X', '2025-04-11 19:55:17', '2025-04-11 19:55:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ad_block_settings`
--
ALTER TABLE `ad_block_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ad_block_trackings`
--
ALTER TABLE `ad_block_trackings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ad_block_trackings_user_id_foreign` (`user_id`);

--
-- Indexes for table `ad_settings`
--
ALTER TABLE `ad_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faqs_faq_category_id_foreign` (`faq_category_id`);

--
-- Indexes for table `faq_categories`
--
ALTER TABLE `faq_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faq_categories_slug_unique` (`slug`);

--
-- Indexes for table `faq_category_translations`
--
ALTER TABLE `faq_category_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faq_cat_trans_unique` (`faq_category_id`,`language_id`),
  ADD KEY `faq_category_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `faq_translations`
--
ALTER TABLE `faq_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faq_trans_unique` (`faq_id`,`language_id`),
  ADD KEY `faq_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `languages_code_unique` (`code`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plans_slug_unique` (`slug`);

--
-- Indexes for table `plan_tools`
--
ALTER TABLE `plan_tools`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plan_tools_plan_id_tool_id_unique` (`plan_id`,`tool_id`),
  ADD KEY `plan_tools_tool_id_foreign` (`tool_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`),
  ADD KEY `settings_group_index` (`group`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `subscriptions_plan_id_foreign` (`plan_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonial_translations`
--
ALTER TABLE `testimonial_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `testimonial_trans_unique` (`testimonial_id`,`language_id`),
  ADD KEY `testimonial_translations_language_id_foreign` (`language_id`);

--
-- Indexes for table `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tools_slug_unique` (`slug`),
  ADD KEY `tools_tool_category_id_foreign` (`tool_category_id`);

--
-- Indexes for table `tool_ad_settings`
--
ALTER TABLE `tool_ad_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tool_ad_settings_tool_id_position_index` (`tool_id`,`position`);

--
-- Indexes for table `tool_categories`
--
ALTER TABLE `tool_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tool_categories_slug_unique` (`slug`);

--
-- Indexes for table `tool_category_translations`
--
ALTER TABLE `tool_category_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tool_category_translations_tool_category_id_locale_unique` (`tool_category_id`,`locale`);

--
-- Indexes for table `tool_templates`
--
ALTER TABLE `tool_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tool_templates_tool_id_section_id_unique` (`tool_id`,`section_id`),
  ADD KEY `tool_templates_section_id_foreign` (`section_id`);

--
-- Indexes for table `tool_template_sections`
--
ALTER TABLE `tool_template_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tool_translations`
--
ALTER TABLE `tool_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tool_translations_tool_id_locale_unique` (`tool_id`,`locale`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ad_block_settings`
--
ALTER TABLE `ad_block_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ad_block_trackings`
--
ALTER TABLE `ad_block_trackings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ad_settings`
--
ALTER TABLE `ad_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `faq_categories`
--
ALTER TABLE `faq_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `faq_category_translations`
--
ALTER TABLE `faq_category_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `faq_translations`
--
ALTER TABLE `faq_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plan_tools`
--
ALTER TABLE `plan_tools`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `testimonial_translations`
--
ALTER TABLE `testimonial_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tools`
--
ALTER TABLE `tools`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tool_ad_settings`
--
ALTER TABLE `tool_ad_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tool_categories`
--
ALTER TABLE `tool_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tool_category_translations`
--
ALTER TABLE `tool_category_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tool_templates`
--
ALTER TABLE `tool_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tool_template_sections`
--
ALTER TABLE `tool_template_sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tool_translations`
--
ALTER TABLE `tool_translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ad_block_trackings`
--
ALTER TABLE `ad_block_trackings`
  ADD CONSTRAINT `ad_block_trackings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_faq_category_id_foreign` FOREIGN KEY (`faq_category_id`) REFERENCES `faq_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faq_category_translations`
--
ALTER TABLE `faq_category_translations`
  ADD CONSTRAINT `faq_category_translations_faq_category_id_foreign` FOREIGN KEY (`faq_category_id`) REFERENCES `faq_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `faq_category_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faq_translations`
--
ALTER TABLE `faq_translations`
  ADD CONSTRAINT `faq_translations_faq_id_foreign` FOREIGN KEY (`faq_id`) REFERENCES `faqs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `faq_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plan_tools`
--
ALTER TABLE `plan_tools`
  ADD CONSTRAINT `plan_tools_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plan_tools_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `testimonial_translations`
--
ALTER TABLE `testimonial_translations`
  ADD CONSTRAINT `testimonial_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `testimonial_translations_testimonial_id_foreign` FOREIGN KEY (`testimonial_id`) REFERENCES `testimonials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tools`
--
ALTER TABLE `tools`
  ADD CONSTRAINT `tools_tool_category_id_foreign` FOREIGN KEY (`tool_category_id`) REFERENCES `tool_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tool_ad_settings`
--
ALTER TABLE `tool_ad_settings`
  ADD CONSTRAINT `tool_ad_settings_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tool_category_translations`
--
ALTER TABLE `tool_category_translations`
  ADD CONSTRAINT `tool_category_translations_tool_category_id_foreign` FOREIGN KEY (`tool_category_id`) REFERENCES `tool_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tool_templates`
--
ALTER TABLE `tool_templates`
  ADD CONSTRAINT `tool_templates_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `tool_template_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tool_templates_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tool_translations`
--
ALTER TABLE `tool_translations`
  ADD CONSTRAINT `tool_translations_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
