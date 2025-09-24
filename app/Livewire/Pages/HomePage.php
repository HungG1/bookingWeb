<?php

namespace App\Livewire\Pages;

use App\Models\Hotel;
use App\Models\Category;
use App\Models\Post;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        $featuredHotels = Hotel::active()->featured()->with('reviews', 'amenities')->take(6)->get();
        $latestPosts = Post::where('status', 'published')
                         ->orderBy('published_at', 'desc')
                         ->take(4)
                         ->get();
        
        return view('livewire.pages.home-page', [
            'latestPosts' => $latestPosts,
        ]);
    }
}