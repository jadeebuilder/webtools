<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;
use App\Models\FaqTranslation;
use App\Models\Language;

class FaqTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les langues
        $frenchLanguage = Language::where('code', 'fr')->first();
        $englishLanguage = Language::where('code', 'en')->first();
        $spanishLanguage = Language::where('code', 'es')->first();

        if (!$frenchLanguage || !$englishLanguage || !$spanishLanguage) {
            $this->command->error('Les langues nécessaires ne sont pas disponibles dans la base de données.');
            return;
        }

        // Récupérer toutes les FAQs existantes
        $faqs = Faq::all();

        // Pour chaque FAQ, créer les traductions
        foreach ($faqs as $faq) {
            // Créer la traduction française (à partir des données de base)
            FaqTranslation::updateOrCreate(
                ['faq_id' => $faq->id, 'language_id' => $frenchLanguage->id],
                ['question' => $faq->question, 'answer' => $faq->answer]
            );

            // Créer les traductions pour chaque FAQ en anglais et en espagnol
            switch ($faq->id) {
                case 1:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'What is WebTools?',
                            'answer' => 'WebTools is an online platform that offers a collection of web tools to simplify daily tasks for developers, content creators, and marketing professionals. Our mission is to provide useful, fast, and easy-to-use tools.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Qué es WebTools?',
                            'answer' => 'WebTools es una plataforma en línea que ofrece una colección de herramientas web para simplificar las tareas diarias de desarrolladores, creadores de contenido y profesionales de marketing. Nuestra misión es proporcionar herramientas útiles, rápidas y fáciles de usar.'
                        ]
                    );
                    break;

                case 2:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'Are all tools free?',
                            'answer' => 'Yes, most of our tools are available for free. However, some advanced features or intensive use of certain tools may require a Pro subscription. Check our pricing page for more details.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Todas las herramientas son gratuitas?',
                            'answer' => 'Sí, la mayoría de nuestras herramientas están disponibles de forma gratuita. Sin embargo, algunas funciones avanzadas o el uso intensivo de ciertas herramientas pueden requerir una suscripción Pro. Consulte nuestra página de precios para obtener más detalles.'
                        ]
                    );
                    break;

                case 3:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'Do you offer API access?',
                            'answer' => 'Yes, API access is available with our Pro subscription. This allows you to integrate our tools directly into your applications or automate certain tasks. Complete API documentation is provided to subscribers.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Ofrecen acceso API?',
                            'answer' => 'Sí, el acceso a la API está disponible con nuestra suscripción Pro. Esto le permite integrar nuestras herramientas directamente en sus aplicaciones o automatizar ciertas tareas. La documentación completa de la API se proporciona a los suscriptores.'
                        ]
                    );
                    break;

                case 4:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'How often do you add new tools?',
                            'answer' => 'We regularly expand our catalog of tools and update existing features. On average, we add 2-3 new tools each month. You can subscribe to our newsletter to stay informed about the latest additions.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Con qué frecuencia agregan nuevas herramientas?',
                            'answer' => 'Expandimos regularmente nuestro catálogo de herramientas y actualizamos las funciones existentes. En promedio, agregamos de 2 a 3 herramientas nuevas cada mes. Puede suscribirse a nuestro boletín para mantenerse informado sobre las últimas novedades.'
                        ]
                    );
                    break;

                case 5:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'How do I create an account?',
                            'answer' => 'To create an account, click on the "Sign Up" button at the top right of the homepage. Fill out the form with your information and validate your registration. You will receive a confirmation email to activate your account.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Cómo creo una cuenta?',
                            'answer' => 'Para crear una cuenta, haga clic en el botón "Registrarse" en la parte superior derecha de la página de inicio. Complete el formulario con su información y valide su registro. Recibirá un correo electrónico de confirmación para activar su cuenta.'
                        ]
                    );
                    break;

                case 6:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'How do I reset my password?',
                            'answer' => 'If you forgot your password, click on "Forgot Password?" on the login page. Enter your email address and follow the instructions sent by email to reset your password.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Cómo restablezco mi contraseña?',
                            'answer' => 'Si olvidó su contraseña, haga clic en "¿Olvidó su contraseña?" en la página de inicio de sesión. Ingrese su dirección de correo electrónico y siga las instrucciones enviadas por correo electrónico para restablecer su contraseña.'
                        ]
                    );
                    break;

                case 7:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'Is my data secure?',
                            'answer' => 'Yes, we take data security very seriously. We use SSL encryption for all communications and do not store your processed data. Most operations are performed client-side when possible to ensure the confidentiality of your information.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Están seguros mis datos?',
                            'answer' => 'Sí, nos tomamos muy en serio la seguridad de los datos. Utilizamos encriptación SSL para todas las comunicaciones y no almacenamos sus datos procesados. La mayoría de las operaciones se realizan del lado del cliente cuando es posible para garantizar la confidencialidad de su información.'
                        ]
                    );
                    break;

                case 8:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'How do I use the image conversion tools?',
                            'answer' => 'To use our image conversion tools, select the appropriate tool in the "Image Tools" category. Follow the on-screen instructions to upload your source image, set the desired parameters, then click the conversion button. You can then download the converted image.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Cómo utilizo las herramientas de conversión de imágenes?',
                            'answer' => 'Para utilizar nuestras herramientas de conversión de imágenes, seleccione la herramienta adecuada en la categoría "Herramientas de imagen". Siga las instrucciones en pantalla para cargar su imagen de origen, establezca los parámetros deseados y luego haga clic en el botón de conversión. A continuación, podrá descargar la imagen convertida.'
                        ]
                    );
                    break;

                case 9:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'Is there a file size limit?',
                            'answer' => 'Yes, with free access, files are limited to 10 MB. Pro users can process files up to 100 MB. This limitation is in place to ensure optimal performance of the platform for all users.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Hay un límite de tamaño de archivo?',
                            'answer' => 'Sí, con acceso gratuito, los archivos están limitados a 10 MB. Los usuarios Pro pueden procesar archivos de hasta 100 MB. Esta limitación está establecida para garantizar un rendimiento óptimo de la plataforma para todos los usuarios.'
                        ]
                    );
                    break;

                case 10:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'Can I use the tools on mobile?',
                            'answer' => 'Absolutely! Our platform is fully responsive and optimized for mobile devices and tablets. You can use most of our tools on any device with an internet connection.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Puedo usar las herramientas en el móvil?',
                            'answer' => '¡Absolutamente! Nuestra plataforma es completamente responsiva y está optimizada para dispositivos móviles y tabletas. Puede utilizar la mayoría de nuestras herramientas en cualquier dispositivo con conexión a internet.'
                        ]
                    );
                    break;

                case 11:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'What payment methods do you accept?',
                            'answer' => 'We accept major credit cards (Visa, Mastercard, American Express), PayPal, and in some countries, bank transfers. All transactions are secure and encrypted.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Qué métodos de pago aceptan?',
                            'answer' => 'Aceptamos las principales tarjetas de crédito (Visa, Mastercard, American Express), PayPal y, en algunos países, transferencias bancarias. Todas las transacciones son seguras y están encriptadas.'
                        ]
                    );
                    break;

                case 12:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'How do I cancel my subscription?',
                            'answer' => 'You can cancel your subscription at any time from your member area, in the "Subscriptions" section. The cancellation will take effect at the end of the current billing period. You will retain access to premium features until that date.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Cómo cancelo mi suscripción?',
                            'answer' => 'Puede cancelar su suscripción en cualquier momento desde su área de miembro, en la sección "Suscripciones". La cancelación entrará en vigor al final del período de facturación actual. Conservará el acceso a las funciones premium hasta esa fecha.'
                        ]
                    );
                    break;

                case 13:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'Do you offer a money-back guarantee?',
                            'answer' => 'Yes, we offer a 14-day money-back guarantee. If you are not satisfied with your premium subscription, you can request a full refund within 14 days of your subscription. Contact our customer service for more details.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Ofrecen garantía de devolución de dinero?',
                            'answer' => 'Sí, ofrecemos una garantía de devolución de dinero de 14 días. Si no está satisfecho con su suscripción premium, puede solicitar un reembolso completo dentro de los 14 días posteriores a su suscripción. Contacte con nuestro servicio de atención al cliente para más detalles.'
                        ]
                    );
                    break;

                case 14:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'How do I contact technical support?',
                            'answer' => 'You can contact our technical support team via the contact form available on our site, by email at support@webtools.com, or via live chat available for premium users. Our team is available Monday to Friday, 9am to 6pm (GMT+1).'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Cómo contacto con el soporte técnico?',
                            'answer' => 'Puede contactar con nuestro equipo de soporte técnico a través del formulario de contacto disponible en nuestro sitio, por correo electrónico a support@webtools.com o a través del chat en vivo disponible para usuarios premium. Nuestro equipo está disponible de lunes a viernes, de 9 a 18 horas (GMT+1).'
                        ]
                    );
                    break;

                case 15:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'The site is inaccessible or a tool is not working, what should I do?',
                            'answer' => 'If you are experiencing problems accessing the site or if a specific tool is not working, first try refreshing the page and clearing your browser cache. If the problem persists, check our service status page or contact our technical support.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => 'El sitio es inaccesible o una herramienta no funciona, ¿qué debo hacer?',
                            'answer' => 'Si experimenta problemas para acceder al sitio o si una herramienta específica no funciona, primero intente actualizar la página y limpiar la caché de su navegador. Si el problema persiste, consulte nuestra página de estado del servicio o contacte con nuestro soporte técnico.'
                        ]
                    );
                    break;

                case 16:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'Can I suggest a new tool?',
                            'answer' => 'Absolutely! We appreciate user feedback. You can send us your tool suggestions via our contact form or by email at suggestions@webtools.com. We review all proposals and regularly incorporate new ideas into our roadmap.'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '¿Puedo sugerir una nueva herramienta?',
                            'answer' => '¡Por supuesto! Agradecemos los comentarios de los usuarios. Puede enviarnos sus sugerencias de herramientas a través de nuestro formulario de contacto o por correo electrónico a suggestions@webtools.com. Revisamos todas las propuestas e incorporamos regularmente nuevas ideas a nuestra hoja de ruta.'
                        ]
                    );
                    break;

                case 17:
                    // Traduction anglaise
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => 'test en',
                            'answer' => 'test en'
                        ]
                    );
                    
                    // Traduction espagnole
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => 'test es',
                            'answer' => 'test es'
                        ]
                    );
                    break;

                default:
                    // Pour les FAQs non spécifiées, créer des traductions génériques
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $englishLanguage->id],
                        [
                            'question' => '[EN] ' . $faq->question,
                            'answer' => '[EN] ' . $faq->answer
                        ]
                    );
                    
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $faq->id, 'language_id' => $spanishLanguage->id],
                        [
                            'question' => '[ES] ' . $faq->question,
                            'answer' => '[ES] ' . $faq->answer
                        ]
                    );
                    break;
            }
        }

        $this->command->info('Les traductions des FAQs ont été créées avec succès.');
    }
} 