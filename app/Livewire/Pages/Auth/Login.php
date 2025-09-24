<?php

namespace App\Livewire\Pages\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\LoginForm;

class Login extends Component
{
    public LoginForm $form;

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->intended(route('dashboard'));
        }
    }

    public function login()
    {
        $this->validate();

        $this->form->authenticate();

        session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.pages.auth.login');
            
    }
}