<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\TestimonialTranslation;
use App\Models\SiteLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestimonialController extends Controller
{
    /**
     * Afficher la liste des témoignages
     */
    public function index()
    {
        try {
            // Utiliser une requête SQL brute pour éviter les accesseurs
            $testimonials = DB::table('testimonials')
                ->select('id', 'name', 'position', 'content', 'rating', 'is_active', 'order')
                ->orderBy('order')
                ->get();
            
            // Log simple
            \Log::info('Témoignages récupérés: ' . $testimonials->count());
            
            // Retourner la vue avec les témoignages
            return view('admin.testimonials.index', compact('testimonials'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des témoignages: ' . $e->getMessage());
            return view('admin.testimonials.index')
                ->with('error', 'Une erreur est survenue lors du chargement des témoignages.');
        }
    }

    /**
     * Afficher le formulaire de création d'un témoignage
     */
    public function create()
    {
        try {
            // Récupérer les langues actives directement depuis la base de données
            $languages = DB::table('site_languages')
                ->where('is_active', true)
                ->get();
            
            return view('admin.testimonials.create', [
                'languages' => $languages
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement du formulaire de création: ' . $e->getMessage());
            
            // En cas d'erreur, renvoyer une vue de base sans langues
            return view('admin.testimonials.create')
                ->with('error', 'Impossible de charger les langues. ' . $e->getMessage());
        }
    }

    /**
     * Enregistrer un nouveau témoignage
     */
    public function store(Request $request, string $locale)
    {
        try {
            // Validation des données de base
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'content' => 'required|string',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'rating' => 'required|integer|min:1|max:5',
                'order' => 'nullable|integer',
            ]);
            
            // Télécharger l'avatar si fourni
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('testimonials', 'public');
            }

            // Créer le témoignage avec DB::table en définissant is_active directement
            $data = [
                'name' => $request->name,
                'position' => $request->position,
                'content' => $request->content,
                'avatar' => $avatarPath,
                'rating' => $request->rating,
                'is_active' => $request->has('is_active') ? 1 : 0, // Utiliser 1/0 au lieu de true/false
                'order' => $request->input('order', 0),
            ];
            
            // Vérifie si les timestamps existent avant de les ajouter
            if (Schema::hasColumn('testimonials', 'created_at')) {
                $data['created_at'] = now();
            }
            if (Schema::hasColumn('testimonials', 'updated_at')) {
                $data['updated_at'] = now();
            }
            
            $testimonialId = DB::table('testimonials')->insertGetId($data);
            
            \Log::info('Témoignage créé avec succès: ' . $testimonialId);

            // Enregistrer les traductions
            if ($request->has('translations')) {
                foreach ($request->translations as $langId => $translation) {
                    if (!empty($translation['name']) || !empty($translation['position']) || !empty($translation['content'])) {
                        DB::table('testimonial_translations')->insert([
                            'testimonial_id' => $testimonialId,
                            'language_id' => $langId,
                            'name' => $translation['name'] ?? $request->name,
                            'position' => $translation['position'] ?? $request->position,
                            'content' => $translation['content'] ?? $request->content
                        ]);
                    }
                }
            }

            return redirect()->route('admin.testimonials.index', ['locale' => $locale])
                ->with('success', 'Témoignage créé avec succès');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du témoignage: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Une erreur est survenue lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire d'édition d'un témoignage
     */
    public function edit(string $locale, $testimonial)
    {
        try {
            // Récupérer le témoignage
            $testimonialId = is_object($testimonial) ? $testimonial->id : $testimonial;
            $testimonialData = DB::table('testimonials')->where('id', $testimonialId)->first();
            
            if (!$testimonialData) {
                return redirect()->route('admin.testimonials.index', ['locale' => $locale])
                    ->with('error', 'Témoignage introuvable');
            }
            
            // Récupérer les langues actives
            $languages = DB::table('site_languages')->where('is_active', true)->get();
            
            // Récupérer les traductions
            $translations = DB::table('testimonial_translations')
                ->where('testimonial_id', $testimonialId)
                ->get()
                ->keyBy('language_id');
            
            return view('admin.testimonials.edit', [
                'testimonial' => $testimonialData,
                'languages' => $languages,
                'translations' => $translations
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement du formulaire d\'édition: ' . $e->getMessage());
            
            return redirect()->route('admin.testimonials.index', ['locale' => $locale])
                ->with('error', 'Impossible de charger le témoignage. ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour un témoignage
     */
    public function update(Request $request, string $locale, $testimonial)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'content' => 'required|string',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'rating' => 'required|integer|min:1|max:5',
                'order' => 'nullable|integer',
            ]);

            // Récupérer le témoignage
            $testimonialId = is_object($testimonial) ? $testimonial->id : $testimonial;
            $testimonialData = DB::table('testimonials')->where('id', $testimonialId)->first();
            
            if (!$testimonialData) {
                return redirect()->route('admin.testimonials.index', ['locale' => $locale])
                    ->with('error', 'Témoignage introuvable');
            }
            
            // Télécharger l'avatar si fourni
            $avatarPath = $testimonialData->avatar;
            if ($request->hasFile('avatar')) {
                // Supprimer l'ancien avatar si existant
                if ($avatarPath) {
                    \Storage::disk('public')->delete($avatarPath);
                }
                
                $avatarPath = $request->file('avatar')->store('testimonials', 'public');
            }

            // Mettre à jour le témoignage
            $data = [
                'name' => $request->name,
                'position' => $request->position,
                'content' => $request->content,
                'avatar' => $avatarPath,
                'rating' => $request->rating,
                'is_active' => $request->has('is_active') ? 1 : 0, // Utiliser 1/0 au lieu de true/false
                'order' => $request->input('order', 0)
            ];
            
            // Vérifier si updated_at existe avant de l'ajouter
            if (Schema::hasColumn('testimonials', 'updated_at')) {
                $data['updated_at'] = now();
            }
            
            // Mettre à jour le témoignage au lieu d'en créer un nouveau
            DB::table('testimonials')
                ->where('id', $testimonialId)
                ->update($data);
            
            // Mettre à jour les traductions
            if ($request->has('translations')) {
                foreach ($request->translations as $langId => $translation) {
                    // Ne mettre à jour que si au moins un champ est rempli
                    if (!empty($translation['name']) || !empty($translation['position']) || !empty($translation['content'])) {
                        // Vérifier si la traduction existe déjà
                        $existingTranslation = DB::table('testimonial_translations')
                            ->where('testimonial_id', $testimonialId)
                            ->where('language_id', $langId)
                            ->first();
                            
                        if ($existingTranslation) {
                            // Mise à jour
                            DB::table('testimonial_translations')
                                ->where('id', $existingTranslation->id)
                                ->update([
                                    'name' => $translation['name'] ?? $testimonialData->name,
                                    'position' => $translation['position'] ?? $testimonialData->position,
                                    'content' => $translation['content'] ?? $testimonialData->content
                                ]);
                        } else {
                            // Création
                            DB::table('testimonial_translations')->insert([
                                'testimonial_id' => $testimonialId,
                                'language_id' => $langId,
                                'name' => $translation['name'] ?? $testimonialData->name,
                                'position' => $translation['position'] ?? $testimonialData->position,
                                'content' => $translation['content'] ?? $testimonialData->content
                            ]);
                        }
                    }
                }
            }

            return redirect()->route('admin.testimonials.index', ['locale' => $locale])
                ->with('success', 'Témoignage mis à jour avec succès');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Activer/désactiver un témoignage
     */
    public function toggleStatus(string $locale, $testimonial)
    {
        try {
            // Utiliser DB pour éviter les problèmes d'accesseurs
            $testimonialId = is_object($testimonial) ? $testimonial->id : $testimonial;
            $testimonialRecord = DB::table('testimonials')->where('id', $testimonialId)->first();
            
            if (!$testimonialRecord) {
                return redirect()->route('admin.testimonials.index', ['locale' => $locale])
                    ->with('error', 'Témoignage introuvable');
            }
            
            // Inverser le statut actif (utiliser 1/0 au lieu de true/false)
            $newStatus = $testimonialRecord->is_active ? 0 : 1;
            
            // Préparer les données à mettre à jour
            $data = ['is_active' => $newStatus];
            
            // Vérifier si updated_at existe avant de l'ajouter
            if (Schema::hasColumn('testimonials', 'updated_at')) {
                $data['updated_at'] = now();
            }
            
            // Mettre à jour le statut
            DB::table('testimonials')
                ->where('id', $testimonialId)
                ->update($data);
            
            return redirect()->route('admin.testimonials.index', ['locale' => $locale])
                ->with('success', 'Statut du témoignage mis à jour avec succès');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du statut: ' . $e->getMessage());
            return redirect()->route('admin.testimonials.index', ['locale' => $locale])
                ->with('error', 'Une erreur est survenue lors de la mise à jour du statut');
        }
    }
    
    /**
     * Supprimer un témoignage
     */
    public function destroy(string $locale, $testimonial)
    {
        try {
            // Vérifier si le témoignage existe
            $testimonialId = is_object($testimonial) ? $testimonial->id : $testimonial;
            $testimonialRecord = DB::table('testimonials')->where('id', $testimonialId)->first();
            
            if (!$testimonialRecord) {
                return redirect()->route('admin.testimonials.index', ['locale' => $locale])
                    ->with('error', 'Témoignage introuvable');
            }
            
            // Supprimer l'avatar si existant
            if ($testimonialRecord->avatar) {
                \Storage::disk('public')->delete($testimonialRecord->avatar);
            }
            
            // Supprimer le témoignage
            DB::table('testimonials')->where('id', $testimonialId)->delete();
            
            // Supprimer les traductions associées
            DB::table('testimonial_translations')->where('testimonial_id', $testimonialId)->delete();
            
            return redirect()->route('admin.testimonials.index', ['locale' => $locale])
                ->with('success', 'Témoignage supprimé avec succès');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression: ' . $e->getMessage());
            return redirect()->route('admin.testimonials.index', ['locale' => $locale])
                ->with('error', 'Une erreur est survenue lors de la suppression');
        }
    }
    
    /**
     * Gérer la modération des témoignages
     */
    public function moderation(string $locale)
    {
        try {
            // Utiliser DB pour récupérer tous les témoignages en attente de modération
            $testimonials = DB::table('testimonials')
                ->where('is_active', false)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Log simple
            \Log::info('Page de modération des témoignages chargée: ' . $testimonials->count() . ' témoignages en attente');
            
            // Retourner la vue avec les témoignages
            return view('admin.testimonials.moderation', compact('testimonials'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement de la page de modération: ' . $e->getMessage());
            return view('admin.testimonials.moderation')
                ->with('error', 'Une erreur est survenue lors du chargement des témoignages à modérer.');
        }
    }
} 