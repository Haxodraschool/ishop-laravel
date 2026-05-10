<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $featuredNames = ['iPhone 17 Pro Max', 'MacBook Pro M5', 'iPad Pro 11-inch', 'AirPods Pro 2'];
        $featuredProducts = \App\Models\Product::whereIn('name', $featuredNames)->get()->keyBy('name');
        return view('home', compact('featuredProducts'));
    }
}
