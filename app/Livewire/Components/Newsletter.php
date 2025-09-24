<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Newsletter extends Component
{
    public $email = '';

    public function subscribe()
    {
        $this->validate([
            'email' => 'required|email|unique:newsletters,email'
        ]);

        session()->flash('success', 'Cảm ơn bạn đã đăng ký nhận bản tin!');
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.components.newsletter');
    }
}