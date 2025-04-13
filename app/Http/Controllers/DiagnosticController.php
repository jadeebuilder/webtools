<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\ToolCategory;

class DiagnosticController extends Controller
{
    public function checkCategories()
    {
        // Récupérer toutes les catégories
        $categories = DB::table('tool_categories')->get();
        
        // Récupérer tous les slugs des liens
        $slugsInLinks = [
            'checker', 'text', 'converter', 'generator', 'developer', 
            'image', 'unit', 'time', 'data', 'color', 'misc'
        ];
        
        $results = [];
        
        // Vérifier chaque slug utilisé dans les liens
        foreach ($slugsInLinks as $slug) {
            $category = DB::table('tool_categories')->where('slug', $slug)->first();
            $results[$slug] = [
                'exists' => $category !== null,
                'id' => $category->id ?? 'N/A',
                'is_active' => $category->is_active ?? 'N/A',
                'route_works' => $this->testRoute($slug)
            ];
        }
        
        // Récupérer toutes les routes liées aux catégories
        $categoryRoutes = collect(Route::getRoutes())->filter(function ($route) {
            return strpos($route->uri, 'tools/category') !== false;
        })->map(function ($route) {
            return [
                'uri' => $route->uri,
                'methods' => $route->methods,
                'name' => $route->getName(),
                'action' => $route->getActionName()
            ];
        });
        
        return response()->json([
            'all_categories' => $categories,
            'category_checks' => $results,
            'category_routes' => $categoryRoutes
        ]);
    }
    
    private function testRoute($slug)
    {
        try {
            $url = route('tools.category', ['locale' => app()->getLocale(), 'slug' => $slug], false);
            return $url;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Tester directement l'accès à une catégorie spécifique.
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function testCategory($slug)
    {
        try {
            // Debug - Journaliser les valeurs
            \Log::info('Test direct de catégorie', ['slug_reçu' => $slug, 'url' => request()->url(), 'path' => request()->path()]);
            
            // S'assurer que le slug n'est pas un code de langue
            if ($slug === 'en' || $slug === 'fr' || $slug === 'es') {
                // Essayer d'extraire le vrai slug de l'URL
                $path = request()->path();
                $segments = explode('/', $path);
                if (count($segments) >= 4) {
                    $slug = $segments[3]; // Essayer d'extraire le slug de l'URL
                    \Log::info('Correction du slug', ['slug_corrigé' => $slug]);
                } else {
                    \Log::warning('Impossible de corriger le slug', ['segments' => $segments]);
                }
            }
            
            // Rechercher la catégorie
            $category = \App\Models\ToolCategory::where('slug', $slug)->first();
            
            $data = [
                'slug_recherché' => $slug, 
                'catégorie_trouvée' => ($category !== null),
                'toutes_les_catégories' => \App\Models\ToolCategory::pluck('slug')->toArray(),
                'url_analysée' => request()->url(),
                'segments_url' => explode('/', request()->path())
            ];
            
            if ($category) {
                $data['catégorie'] = [
                    'id' => $category->id,
                    'slug' => $category->slug,
                    'icon' => $category->icon,
                    'is_active' => $category->is_active
                ];
                
                // Vérifier les traductions
                $translations = \App\Models\ToolCategoryTranslation::where('tool_category_id', $category->id)->get();
                $data['traductions'] = $translations->toArray();
                
                // Vérifier les outils
                $tools = \App\Models\Tool::where('tool_category_id', $category->id)
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get();
                $data['nombre_outils'] = $tools->count();
                $data['outils'] = $tools->pluck('slug')->toArray();
            }
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            return response()->json([
                'erreur' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
} 