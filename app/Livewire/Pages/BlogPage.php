<?php

namespace App\Livewire\Pages;

use App\Models\Post;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class BlogPage extends Component
{
    use WithPagination;

    public function render()
    {

        $posts = Post::where('status', 'published')
                    ->with('category') 
                    ->orderBy('published_at', 'desc')
                    ->paginate(9); 

        $categories = Category::withCount(['posts' => function ($query) {
            $query->where('status', 'published');
        }])->get();

        return view('livewire.pages.blog-page', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }
}