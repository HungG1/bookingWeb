<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Pagination extends Component
{
    public $paginator;
    
    public function mount($paginator)
    {
        $this->paginator = $paginator;
    }
    
    public function render()
    {
        return view('livewire.components.pagination');
    }
}