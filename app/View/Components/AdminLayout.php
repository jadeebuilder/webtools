<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    /**
     * Titre de la page.
     *
     * @var string
     */
    public $title;
    
    /**
     * Description meta pour la page.
     *
     * @var string
     */
    public $metaDescription;
    
    /**
     * Create a new component instance.
     *
     * @param string|null $title
     * @param string|null $metaDescription
     * @return void
     */
    public function __construct(?string $title = null, ?string $metaDescription = null)
    {
        $this->title = $title;
        $this->metaDescription = $metaDescription;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('layouts.admin');
    }
} 