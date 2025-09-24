<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth; 

class Authentication extends Component
{
    public function logout()
    {

    }

    public function render()
    {
        $loggedInUser = Auth::user(); 

        return view('livewire.components.authentication', [
            'user' => $loggedInUser 
        ]);
    }
}