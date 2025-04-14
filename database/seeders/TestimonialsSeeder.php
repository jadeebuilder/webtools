<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\TestimonialTranslation;
use App\Models\Language;

class TestimonialsSeeder extends Seeder
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

        // Témoignage 1
        $testimonial1 = Testimonial::create([
            'name' => 'Sarah Johnson',
            'position' => 'Frontend Developer',
            'content' => 'These tools have become an essential part of my development workflow. They\'re intuitive and save me hours of work.',
            'avatar' => null,
            'rating' => 5,
            'is_active' => true,
            'order' => 1
        ]);

        // Traduction Française
        TestimonialTranslation::create([
            'testimonial_id' => $testimonial1->id,
            'language_id' => $frenchLanguage->id,
            'name' => 'Sarah Johnson',
            'position' => 'Développeuse Frontend',
            'content' => 'Ces outils sont devenus une partie essentielle de mon flux de travail de développement. Ils sont intuitifs et me font gagner des heures de travail.'
        ]);

        // Traduction Espagnole
        TestimonialTranslation::create([
            'testimonial_id' => $testimonial1->id,
            'language_id' => $spanishLanguage->id,
            'name' => 'Sarah Johnson',
            'position' => 'Desarrolladora Frontend',
            'content' => 'Estas herramientas se han convertido en una parte esencial de mi flujo de trabajo de desarrollo. Son intuitivas y me ahorran horas de trabajo.'
        ]);

        // Témoignage 2
        $testimonial2 = Testimonial::create([
            'name' => 'Michael Chen',
            'position' => 'Full Stack Developer',
            'content' => 'The variety and quality of tools available is impressive. Everything I need is just a click away.',
            'avatar' => null,
            'rating' => 5,
            'is_active' => true,
            'order' => 2
        ]);

        // Traduction Française
        TestimonialTranslation::create([
            'testimonial_id' => $testimonial2->id,
            'language_id' => $frenchLanguage->id,
            'name' => 'Michael Chen',
            'position' => 'Développeur Full Stack',
            'content' => 'La variété et la qualité des outils disponibles est impressionnante. Tout ce dont j\'ai besoin est à portée de clic.'
        ]);

        // Traduction Espagnole
        TestimonialTranslation::create([
            'testimonial_id' => $testimonial2->id,
            'language_id' => $spanishLanguage->id,
            'name' => 'Michael Chen',
            'position' => 'Desarrollador Full Stack',
            'content' => 'La variedad y calidad de las herramientas disponibles es impresionante. Todo lo que necesito está a un solo clic de distancia.'
        ]);

        // Témoignage 3
        $testimonial3 = Testimonial::create([
            'name' => 'Emily Rodriguez',
            'position' => 'UX Designer',
            'content' => 'Clean interface, powerful features, and regular updates. It\'s exactly what I needed for my projects.',
            'avatar' => null,
            'rating' => 5,
            'is_active' => true,
            'order' => 3
        ]);

        // Traduction Française
        TestimonialTranslation::create([
            'testimonial_id' => $testimonial3->id,
            'language_id' => $frenchLanguage->id,
            'name' => 'Emily Rodriguez',
            'position' => 'Designer UX',
            'content' => 'Interface épurée, fonctionnalités puissantes et mises à jour régulières. C\'est exactement ce dont j\'avais besoin pour mes projets.'
        ]);

        // Traduction Espagnole
        TestimonialTranslation::create([
            'testimonial_id' => $testimonial3->id,
            'language_id' => $spanishLanguage->id,
            'name' => 'Emily Rodriguez',
            'position' => 'Diseñadora UX',
            'content' => 'Interfaz limpia, características potentes y actualizaciones regulares. Es exactamente lo que necesitaba para mis proyectos.'
        ]);
    }
} 