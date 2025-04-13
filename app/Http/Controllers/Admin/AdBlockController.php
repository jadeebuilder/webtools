<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdBlockService;
use Illuminate\Http\Request;

class AdBlockController extends Controller
{
    /**
     * Le service AdBlock.
     *
     * @var AdBlockService
     */
    protected $adBlockService;

    /**
     * Créer une nouvelle instance du contrôleur.
     *
     * @param AdBlockService $adBlockService
     * @return void
     */
    public function __construct(AdBlockService $adBlockService)
    {
        $this->adBlockService = $adBlockService;
    }

    /**
     * Affiche la page de configuration de détection d'AdBlock.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = $this->adBlockService->getSettings();
        
        return view('admin.adblock.index', compact('settings'));
    }

    /**
     * Met à jour la configuration de détection d'AdBlock.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'boolean',
            'block_content' => 'boolean',
            'show_message' => 'boolean',
            'message_title' => 'required_if:show_message,1|string|max:255',
            'message_text' => 'required_if:show_message,1|string',
            'message_button' => 'required_if:show_message,1|string|max:255',
            'countdown' => 'integer|min:0|max:60',
        ]);
        
        // S'assurer que les valeurs booléennes sont correctement définies
        $data = [
            'enabled' => (bool) $request->input('enabled'),
            'block_content' => (bool) $request->input('block_content'),
            'show_message' => (bool) $request->input('show_message'),
            'message_title' => $request->input('message_title'),
            'message_text' => $request->input('message_text'),
            'message_button' => $request->input('message_button'),
            'countdown' => (int) $request->input('countdown', 0),
        ];
        
        $success = $this->adBlockService->updateSettings($data);
        
        if ($success) {
            return redirect()->route('admin.adblock.index', ['locale' => app()->getLocale()])
                ->with('success', __('Les paramètres de détection d\'AdBlock ont été mis à jour avec succès.'));
        }
        
        return redirect()->route('admin.adblock.index', ['locale' => app()->getLocale()])
            ->with('error', __('Une erreur est survenue lors de la mise à jour des paramètres de détection d\'AdBlock.'));
    }

    /**
     * Afficher la page de test de détection d'AdBlock.
     *
     * @return \Illuminate\View\View
     */
    public function test()
    {
        $settings = $this->adBlockService->getSettings();
        
        return view('admin.adblock.test', compact('settings'));
    }
}
