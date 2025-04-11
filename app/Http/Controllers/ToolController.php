<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index()
    {
        return view('tools.index');
    }

    public function checker()
    {
        return view('tools.checker');
    }

    public function text()
    {
        return view('tools.text');
    }

    public function converter()
    {
        return view('tools.converter');
    }

    public function generator()
    {
        return view('tools.generator');
    }

    public function developer()
    {
        return view('tools.developer');
    }

    public function image()
    {
        return view('tools.image');
    }

    public function unit()
    {
        return view('tools.unit');
    }

    public function time()
    {
        return view('tools.time');
    }

    public function data()
    {
        return view('tools.data');
    }

    public function color()
    {
        return view('tools.color');
    }

    public function misc()
    {
        return view('tools.misc');
    }

    public function show($slug)
    {
        // Ici, nous récupérerons les détails de l'outil spécifique
        // basé sur le slug
        return view('tools.show', compact('slug'));
    }
}
