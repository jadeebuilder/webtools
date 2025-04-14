<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Package;
use App\Models\PackageTranslation;
use App\Models\Tool;
use App\Models\ToolCategory;
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
        $languages = Language::where('is_active', true)->get();
        $toolCategories = ToolCategory::with('tools')->where('is_active', true)->orderBy('order')->get();
        $cycleTypes = [
            Package::CYCLE_DAY => __('Jour(s)'),
            Package::CYCLE_MONTH => __('Mois'),
            Package::CYCLE_YEAR => __('Année(s)'),
            Package::CYCLE_LIFETIME => __('À vie'),
        ];
        
        return view('admin.packages.create', compact('languages', 'toolCategories', 'cycleTypes'));
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
            'tools' => 'nullable|array',
            'tools.*' => 'exists:tools,id',
            'vip_tools' => 'nullable|array',
            'vip_tools.*' => 'exists:tools,id',
            'ai_tools' => 'nullable|array',
            'ai_tools.*' => 'exists:tools,id',
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

            // Associer les outils
            $toolsData = [];
            
            // Outils standard
            if (isset($validatedData['tools'])) {
                foreach ($validatedData['tools'] as $toolId) {
                    $toolsData[$toolId] = ['is_vip' => false, 'is_ai' => false];
                }
            }
            
            // Outils VIP
            if (isset($validatedData['vip_tools'])) {
                foreach ($validatedData['vip_tools'] as $toolId) {
                    if (isset($toolsData[$toolId])) {
                        $toolsData[$toolId]['is_vip'] = true;
                    } else {
                        $toolsData[$toolId] = ['is_vip' => true, 'is_ai' => false];
                    }
                }
            }
            
            // Outils AI
            if (isset($validatedData['ai_tools'])) {
                foreach ($validatedData['ai_tools'] as $toolId) {
                    if (isset($toolsData[$toolId])) {
                        $toolsData[$toolId]['is_ai'] = true;
                    } else {
                        $toolsData[$toolId] = ['is_vip' => false, 'is_ai' => true];
                    }
                }
            }
            
            foreach ($toolsData as $toolId => $attrs) {
                $package->tools()->attach($toolId, $attrs);
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
    public function edit(Package $package)
    {
        $package->load('translations', 'tools');
        
        $languages = Language::where('is_active', true)->get();
        $toolCategories = ToolCategory::with('tools')->where('is_active', true)->orderBy('order')->get();
        $cycleTypes = [
            Package::CYCLE_DAY => __('Jour(s)'),
            Package::CYCLE_MONTH => __('Mois'),
            Package::CYCLE_YEAR => __('Année(s)'),
            Package::CYCLE_LIFETIME => __('À vie'),
        ];
        
        // Organiser les traductions par locale pour un accès facile dans la vue
        $translations = $package->translations->keyBy('locale')->toArray();
        
        // Récupérer les IDs des outils standards, VIP et AI
        $standardToolIds = $package->tools()->wherePivot('is_vip', false)->wherePivot('is_ai', false)->pluck('tools.id')->toArray();
        $vipToolIds = $package->tools()->wherePivot('is_vip', true)->pluck('tools.id')->toArray();
        $aiToolIds = $package->tools()->wherePivot('is_ai', true)->pluck('tools.id')->toArray();
        
        return view('admin.packages.edit', compact(
            'package', 
            'languages', 
            'toolCategories', 
            'cycleTypes', 
            'translations', 
            'standardToolIds', 
            'vipToolIds', 
            'aiToolIds'
        ));
    }

    /**
     * Met à jour le package spécifié.
     */
    public function update(Request $request, Package $package)
    {
        $validatedData = $request->validate([
            'slug' => ['required', 'max:255', 'regex:/^[a-z0-9\-]+$/', Rule::unique('packages')->ignore($package->id)],
            'color' => 'required|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'is_active' => 'sometimes|boolean',
            'is_default' => 'sometimes|boolean',
            'show_ads' => 'sometimes|boolean',
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
            'tools' => 'nullable|array',
            'tools.*' => 'exists:tools,id',
            'vip_tools' => 'nullable|array',
            'vip_tools.*' => 'exists:tools,id',
            'ai_tools' => 'nullable|array',
            'ai_tools.*' => 'exists:tools,id',
        ]);

        // Gérer le cas où cycle_type est lifetime
        if ($validatedData['cycle_type'] === Package::CYCLE_LIFETIME) {
            $validatedData['cycle_count'] = 1;
        }

        // Si ce package est défini par défaut, désactiver tous les autres packages par défaut
        if (isset($validatedData['is_default']) && $validatedData['is_default']) {
            Package::where('id', '!=', $package->id)->where('is_default', true)->update(['is_default' => false]);
        }

        DB::beginTransaction();

        try {
            // Mettre à jour le package
            $package->update([
                'slug' => $validatedData['slug'],
                'color' => $validatedData['color'],
                'is_active' => $validatedData['is_active'] ?? false,
                'is_default' => $validatedData['is_default'] ?? false,
                'show_ads' => $validatedData['show_ads'] ?? true,
                'cycle_type' => $validatedData['cycle_type'],
                'cycle_count' => $validatedData['cycle_count'],
                'monthly_price' => $validatedData['monthly_price'],
                'annual_price' => $validatedData['annual_price'],
                'lifetime_price' => $validatedData['lifetime_price'],
                'order' => $validatedData['order'] ?? 0,
            ]);

            // Mettre à jour les traductions
            foreach ($validatedData['translations'] as $locale => $translationData) {
                $package->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'name' => $translationData['name'],
                        'description' => $translationData['description'] ?? null,
                        'features' => $translationData['features'] ?? null,
                    ]
                );
            }

            // Détacher tous les outils existants
            $package->tools()->detach();

            // Associer les outils
            $toolsData = [];
            
            // Outils standard
            if (isset($validatedData['tools'])) {
                foreach ($validatedData['tools'] as $toolId) {
                    $toolsData[$toolId] = ['is_vip' => false, 'is_ai' => false];
                }
            }
            
            // Outils VIP
            if (isset($validatedData['vip_tools'])) {
                foreach ($validatedData['vip_tools'] as $toolId) {
                    if (isset($toolsData[$toolId])) {
                        $toolsData[$toolId]['is_vip'] = true;
                    } else {
                        $toolsData[$toolId] = ['is_vip' => true, 'is_ai' => false];
                    }
                }
            }
            
            // Outils AI
            if (isset($validatedData['ai_tools'])) {
                foreach ($validatedData['ai_tools'] as $toolId) {
                    if (isset($toolsData[$toolId])) {
                        $toolsData[$toolId]['is_ai'] = true;
                    } else {
                        $toolsData[$toolId] = ['is_vip' => false, 'is_ai' => true];
                    }
                }
            }
            
            foreach ($toolsData as $toolId => $attrs) {
                $package->tools()->attach($toolId, $attrs);
            }

            DB::commit();

            return redirect()->route('admin.packages.index', ['locale' => app()->getLocale()])
                ->with('success', __('Package mis à jour avec succès!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('Une erreur est survenue lors de la mise à jour du package: ') . $e->getMessage())->withInput();
        }
    }

    /**
     * Supprime le package spécifié.
     */
    public function destroy(Package $package)
    {
        try {
            $package->delete();
            return redirect()->route('admin.packages.index', ['locale' => app()->getLocale()])
                ->with('success', __('Package supprimé avec succès!'));
        } catch (\Exception $e) {
            return back()->with('error', __('Une erreur est survenue lors de la suppression du package: ') . $e->getMessage());
        }
    }

    /**
     * Active ou désactive un package.
     */
    public function toggleStatus(Package $package)
    {
        $package->is_active = !$package->is_active;
        $package->save();

        return response()->json([
            'success' => true,
            'is_active' => $package->is_active,
            'message' => $package->is_active 
                ? __('Package activé avec succès!') 
                : __('Package désactivé avec succès!')
        ]);
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
        return view('admin.packages.configuration');
    }
} 