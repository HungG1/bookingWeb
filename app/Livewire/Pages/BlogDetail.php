<?php

namespace App\Livewire\Pages;

use App\Models\Post;
use Livewire\Component;

class BlogDetail extends Component
{
    public Post $post;
    public $relatedPosts;

    public function mount($slug)
    {
        $this->post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with('category')
            ->firstOrFail();

        $this->relatedPosts = Post::where('category_id', $this->post->category_id)
            ->where('id', '!=', $this->post->id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.blog-detail');
    }
}