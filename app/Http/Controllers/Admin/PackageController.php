<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteLanguage;
use App\Models\Package;
use App\Models\PackageTranslation;
use App\Models\Tool;
use App\Models\ToolCategory;
use App\Models\ToolType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    /**
     * Affiche la liste des packages.
     */
    public function index(Request $request)
    {
        $query = Package::query()->with(['translations']);
        
        // Filtrer par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('slug', 'like', "%{$search}%");
        }
        
        // Filtrer par statut
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $packages = $query->orderBy('order')->paginate(15);
        
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Affiche le formulaire de création d'un package.
     */
    public function create()
    {
        $languages = SiteLanguage::where('is_active', true)->get();
        $toolCategories = ToolCategory::with('tools')->where('is_active', true)->orderBy('order')->get();
        $toolTypes = ToolType::where('is_active', true)->orderBy('order')->get();
        $cycleTypes = [
            Package::CYCLE_DAY => __('Jour(s)'),
            Package::CYCLE_MONTH => __('Mois'),
            Package::CYCLE_YEAR => __('Année(s)'),
            Package::CYCLE_LIFETIME => __('À vie'),
        ];
        
        return view('admin.packages.create', compact('languages', 'toolCategories', 'toolTypes', 'cycleTypes'));
    }

    /**
     * Stocke un nouveau package dans la base de données.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'slug' => 'required|max:255|regex:/^[a-z0-9\-]+$/|unique:packages',
            'color' => 'required|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'is_active' => 'sometimes|boolean',
            'is_default' => 'sometimes|boolean',
            'show_ads' => 'sometimes|boolean',
            'has_trial' => 'sometimes|boolean',
            'trial_days' => 'integer|min:0|nullable',
            'trial_restrictions' => 'nullable|array',
            'trial_restrictions.*' => 'string|in:downloads,exports,ai',
            'cycle_type' => ['required', Rule::in([
                Package::CYCLE_DAY,
                Package::CYCLE_MONTH,
                Package::CYCLE_YEAR,
                Package::CYCLE_LIFETIME,
            ])],
            'cycle_count' => 'required_unless:cycle_type,lifetime|integer|min:1|nullable',
            'monthly_price' => 'required|numeric|min:0',
            'annual_price' => 'required|numeric|min:0',
            'lifetime_price' => 'required|numeric|min:0',
            'order' => 'nullable|integer|min:0',
            'translations' => 'required|array',
            'translations.*' => 'required|array',
            'translations.*.locale' => 'required|string|exists:languages,code',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'translations.*.features' => 'nullable|string',
            'tool_categories' => 'nullable|array',
            'tool_categories.*' => 'exists:tool_categories,id',
            'tool_types' => 'nullable|array',
            'tool_types.*' => 'exists:tool_types,id',
            'tool_type_limits' => 'nullable|array',
            'tool_type_limits.*' => 'integer|min:0',
        ]);

        // Gérer le cas où cycle_type est lifetime
        if ($validatedData['cycle_type'] === Package::CYCLE_LIFETIME) {
            $validatedData['cycle_count'] = 1;
        }

        // Si ce package est défini par défaut, désactiver tous les autres packages par défaut
        if (isset($validatedData['is_default']) && $validatedData['is_default']) {
            Package::where('is_default', true)->update(['is_default' => false]);
        }

        DB::beginTransaction();

        try {
            // Créer le package
            $package = Package::create([
                'slug' => $validatedData['slug'],
                'color' => $validatedData['color'],
                'is_active' => $validatedData['is_active'] ?? false,
                'is_default' => $validatedData['is_default'] ?? false,
                'show_ads' => $validatedData['show_ads'] ?? true,
                'has_trial' => $validatedData['has_trial'] ?? false,
                'trial_days' => $validatedData['trial_days'] ?? 0,
                'trial_restrictions' => isset($validatedData['trial_restrictions']) ? json_encode($validatedData['trial_restrictions']) : null,
                'cycle_type' => $validatedData['cycle_type'],
                'cycle_count' => $validatedData['cycle_count'],
                'monthly_price' => $validatedData['monthly_price'],
                'annual_price' => $validatedData['annual_price'],
                'lifetime_price' => $validatedData['lifetime_price'],
                'order' => $validatedData['order'] ?? 0,
            ]);

            // Créer les traductions
            foreach ($validatedData['translations'] as $locale => $translationData) {
                PackageTranslation::create([
                    'package_id' => $package->id,
                    'locale' => $locale,
                    'name' => $translationData['name'],
                    'description' => $translationData['description'] ?? null,
                    'features' => $translationData['features'] ?? null,
                ]);
            }
            
            // Associer les types d'outils
            if (isset($validatedData['tool_types'])) {
                foreach ($validatedData['tool_types'] as $typeId) {
                    $limit = $validatedData['tool_type_limits'][$typeId] ?? 0;
                    $package->toolTypes()->attach($typeId, ['tools_limit' => $limit]);
                }
            }
            
            // Associer les outils des catégories sélectionnées
            if (isset($validatedData['tool_categories'])) {
                $tools = Tool::whereIn('tool_category_id', $validatedData['tool_categories'])
                    ->where('is_active', true)
                    ->get();
                
                foreach ($tools as $tool) {
                    $package->tools()->attach($tool->id);
                }
            }

            DB::commit();

            return redirect()->route('admin.packages.index', ['locale' => app()->getLocale()])
                ->with('success', __('Package créé avec succès!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('Une erreur est survenue lors de la création du package: ') . $e->getMessage())->withInput();
        }
    }

    /**
     * Affiche le formulaire d'édition d'un package.
     */
    public function edit(string $locale, $package)
    {
        try {
            // Vérifier si package est un ID ou un objet
            $packageId = is_object($package) ? $package->id : $package;
            
            // Récupérer le package directement via DB pour éviter les problèmes d'accesseurs
            $packageData = DB::table('packages')->where('id', $packageId)->first();
            
            if (!$packageData) {
                return redirect()->route('admin.packages.index', ['locale' => $locale])
                    ->with('error', __('Package introuvable'));
            }
            
            // Maintenant, utiliser le modèle Package pour les relations
            $packageModel = Package::find($packageId);
            $packageModel->load('translations', 'tools', 'toolTypes');
            
            $languages = SiteLanguage::where('is_active', true)->get();
            $toolCategories = ToolCategory::with('tools')->where('is_active', true)->orderBy('order')->get();
            $toolTypes = ToolType::where('is_active', true)->orderBy('order')->get();
            $cycleTypes = [
                Package::CYCLE_DAY => __('Jour(s)'),
                Package::CYCLE_MONTH => __('Mois'),
                Package::CYCLE_YEAR => __('Année(s)'),
                Package::CYCLE_LIFETIME => __('À vie'),
            ];
            
            // Organiser les traductions par locale pour un accès facile dans la vue
            $translations = $packageModel->translations->keyBy('locale')->toArray();
            
            // Récupérer les IDs des outils standards, VIP et AI
            $toolsIds = $packageModel->tools()->pluck('tools.id')->toArray();
            
            // Récupérer les catégories sélectionnées
            $selectedCategories = ToolCategory::whereHas('tools', function($query) use ($toolsIds) {
                $query->whereIn('id', $toolsIds);
            })->pluck('id')->toArray();
            
            // Récupérer les types d'outils sélectionnés
            $selectedToolTypes = $packageModel->toolTypes()->pluck('tool_types.id')->toArray();
            
            // Récupérer les limites pour chaque type d'outil
            $toolTypeLimits = [];
            foreach ($packageModel->toolTypes as $type) {
                $toolTypeLimits[$type->id] = $type->pivot->tools_limit;
            }
            
            // On utilise $packageModel pour la vue car il contient les relations
            return view('admin.packages.edit', [
                'package' => $packageModel,
                'languages' => $languages,
                'toolCategories' => $toolCategories,
                'toolTypes' => $toolTypes,
                'cycleTypes' => $cycleTypes,
                'translations' => $translations,
                'selectedCategories' => $selectedCategories,
                'selectedToolTypes' => $selectedToolTypes,
                'toolTypeLimits' => $toolTypeLimits,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement du formulaire d\'édition: ' . $e->getMessage());
            
            return redirect()->route('admin.packages.index', ['locale' => $locale])
                ->with('error', __('Impossible de charger le package. ') . $e->getMessage());
        }
    }

    /**
     * Met à jour le package spécifié.
     */
    public function update(Request $request, string $locale, $package)
    {
        try {
            // Vérifier si package est un ID ou un objet
            $packageId = is_object($package) ? $package->id : $package;
            
            // Récupérer le package
            $packageModel = Package::findOrFail($packageId);
            
            $validatedData = $request->validate([
                'slug' => ['required', 'max:255', 'regex:/^[a-z0-9\-]+$/', Rule::unique('packages')->ignore($packageId)],
                'color' => 'required|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
                'is_active' => 'sometimes|boolean',
                'is_default' => 'sometimes|boolean',
                'show_ads' => 'sometimes|boolean',
                'has_trial' => 'sometimes|boolean',
                'trial_days' => 'integer|min:0|nullable',
                'trial_restrictions' => 'nullable|array',
                'trial_restrictions.*' => 'string|in:downloads,exports,ai',
                'cycle_type' => ['required', Rule::in([
                    Package::CYCLE_DAY,
                    Package::CYCLE_MONTH,
                    Package::CYCLE_YEAR,
                    Package::CYCLE_LIFETIME,
                ])],
                'cycle_count' => 'required_unless:cycle_type,lifetime|integer|min:1|nullable',
                'monthly_price' => 'required|numeric|min:0',
                'annual_price' => 'required|numeric|min:0',
                'lifetime_price' => 'required|numeric|min:0',
                'order' => 'nullable|integer|min:0',
                'translations' => 'required|array',
                'translations.*' => 'required|array',
                'translations.*.locale' => 'required|string|exists:languages,code',
                'translations.*.name' => 'required|string|max:255',
                'translations.*.description' => 'nullable|string',
                'translations.*.features' => 'nullable|string',
                'tool_categories' => 'nullable|array',
                'tool_categories.*' => 'exists:tool_categories,id',
                'tool_types' => 'nullable|array',
                'tool_types.*' => 'exists:tool_types,id',
                'tool_type_limits' => 'nullable|array',
                'tool_type_limits.*' => 'integer|min:0',
            ]);

            // Gérer le cas où cycle_type est lifetime
            if ($validatedData['cycle_type'] === Package::CYCLE_LIFETIME) {
                $validatedData['cycle_count'] = 1;
            }

            // Si ce package est défini par défaut, désactiver tous les autres packages par défaut
            if (isset($validatedData['is_default']) && $validatedData['is_default']) {
                Package::where('id', '!=', $packageId)->where('is_default', true)->update(['is_default' => false]);
            }

            DB::beginTransaction();

            try {
                // Mettre à jour le package
                $packageModel->update([
                    'slug' => $validatedData['slug'],
                    'color' => $validatedData['color'],
                    'is_active' => isset($validatedData['is_active']),
                    'is_default' => isset($validatedData['is_default']),
                    'show_ads' => isset($validatedData['show_ads']),
                    'has_trial' => isset($validatedData['has_trial']),
                    'trial_days' => $validatedData['trial_days'] ?? 0,
                    'trial_restrictions' => isset($validatedData['trial_restrictions']) ? json_encode($validatedData['trial_restrictions']) : null,
                    'cycle_type' => $validatedData['cycle_type'],
                    'cycle_count' => $validatedData['cycle_count'],
                    'monthly_price' => $validatedData['monthly_price'],
                    'annual_price' => $validatedData['annual_price'],
                    'lifetime_price' => $validatedData['lifetime_price'],
                    'order' => $validatedData['order'] ?? 0,
                ]);

                // Mettre à jour les traductions
                foreach ($validatedData['translations'] as $locale => $translationData) {
                    $packageModel->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'name' => $translationData['name'],
                            'description' => $translationData['description'] ?? null,
                            'features' => $translationData['features'] ?? null,
                        ]
                    );
                }
                
                // Détacher tous les types d'outils existants
                $packageModel->toolTypes()->detach();
                
                // Associer les types d'outils
                if (isset($validatedData['tool_types'])) {
                    foreach ($validatedData['tool_types'] as $typeId) {
                        $limit = $validatedData['tool_type_limits'][$typeId] ?? 0;
                        $packageModel->toolTypes()->attach($typeId, ['tools_limit' => $limit]);
                    }
                }

                // Détacher tous les outils existants
                $packageModel->tools()->detach();
                
                // Associer les outils des catégories sélectionnées
                if (isset($validatedData['tool_categories'])) {
                    $tools = Tool::whereIn('tool_category_id', $validatedData['tool_categories'])
                        ->where('is_active', true)
                        ->get();
                    
                    foreach ($tools as $tool) {
                        $packageModel->tools()->attach($tool->id);
                    }
                }

                DB::commit();

                return redirect()->route('admin.packages.index', ['locale' => $locale])
                    ->with('success', __('Package mis à jour avec succès!'));
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Erreur lors de la mise à jour du package: ' . $e->getMessage());
                return back()->with('error', __('Une erreur est survenue lors de la mise à jour du package: ') . $e->getMessage())->withInput();
            }
        } catch (\Exception $e) {
            \Log::error('Erreur de validation lors de la mise à jour: ' . $e->getMessage());
            return back()->with('error', __('Erreur de validation: ') . $e->getMessage())->withInput();
        }
    }

    /**
     * Supprime le package spécifié.
     */
    public function destroy(string $locale, $package)
    {
        try {
            // Vérifier si package est un ID ou un objet
            $packageId = is_object($package) ? $package->id : $package;
            
            // Récupérer le package
            $packageModel = Package::findOrFail($packageId);
            
            // Supprimer le package
            $packageModel->delete();
            
            return redirect()->route('admin.packages.index', ['locale' => $locale])
                ->with('success', __('Package supprimé avec succès!'));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du package: ' . $e->getMessage());
            return redirect()->route('admin.packages.index', ['locale' => $locale])
                ->with('error', __('Une erreur est survenue lors de la suppression du package: ') . $e->getMessage());
        }
    }

    /**
     * Active ou désactive un package.
     */
    public function toggleStatus(string $locale, $package)
    {
        try {
            // Vérifier si package est un ID ou un objet
            $packageId = is_object($package) ? $package->id : $package;
            
            // Récupérer le package
            $packageModel = Package::findOrFail($packageId);
            
            // Inverser le statut actif
            $packageModel->is_active = !$packageModel->is_active;
            $packageModel->save();

            return response()->json([
                'success' => true,
                'is_active' => $packageModel->is_active,
                'message' => $packageModel->is_active 
                    ? __('Package activé avec succès!') 
                    : __('Package désactivé avec succès!')
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du changement de statut du package: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Une erreur est survenue lors du changement de statut du package')
            ], 500);
        }
    }

    /**
     * Génère un slug à partir du nom fourni.
     */
    public function generateSlug(Request $request)
    {
        $name = $request->input('name');
        $slug = Str::slug($name);
        
        // Vérifier si le slug existe déjà
        $count = 0;
        $originalSlug = $slug;
        
        while (Package::where('slug', $slug)->exists()) {
            $count++;
            $slug = $originalSlug . '-' . $count;
        }
        
        return response()->json([
            'slug' => $slug
        ]);
    }

    /**
     * Affiche la page de configuration des packages.
     */
    public function configuration()
    {
        $packageSettings = [
            'packages_enabled' => true,
            'free_trial_days' => 7,
            'default_currency' => 'EUR',
            'trial_restrict_downloads' => true,
            'trial_restrict_exports' => true,
            'trial_restrict_ai' => true,
            'payment_gateway' => 'stripe',
            'stripe_key' => env('STRIPE_KEY', ''),
            'stripe_secret' => env('STRIPE_SECRET', ''),
            'stripe_webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
        ];
        
        return view('admin.packages.configuration', compact('packageSettings'));
    }
    
    /**
     * Met à jour la configuration des packages.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateConfiguration(Request $request)
    {
        $request->validate([
            'packages_enabled' => 'sometimes|boolean',
            'free_trial_days' => 'required|integer|min:0|max:90',
            'default_currency' => 'required|string|in:EUR,USD,GBP,CAD',
            'trial_restrict_downloads' => 'sometimes|boolean',
            'trial_restrict_exports' => 'sometimes|boolean',
            'trial_restrict_ai' => 'sometimes|boolean',
            'payment_gateway' => 'required|string|in:stripe,paypal,razorpay,custom',
            'stripe_key' => 'nullable|string',
            'stripe_secret' => 'nullable|string',
            'stripe_webhook_secret' => 'nullable|string',
        ]);
        
        // Ici, vous enregistreriez normalement ces paramètres dans la base de données
        // Par exemple avec le modèle Setting:
        // Setting::set('packages_enabled', $request->has('packages_enabled'), 'packages', true);
        
        // Pour les valeurs sensibles comme les clés API, mettez-les en .env
        if ($request->filled('stripe_key') && $request->filled('stripe_secret')) {
            // Mettre à jour le fichier .env ou utiliser une autre méthode sécurisée
        }
        
        return redirect()->route('admin.packages.configuration', ['locale' => app()->getLocale()])
            ->with('success', __('La configuration des packages a été mise à jour avec succès.'));
    }
} 