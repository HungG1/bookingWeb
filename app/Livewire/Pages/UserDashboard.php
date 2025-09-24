<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class UserDashboard extends Component
{
    public $activeTab = 'dashboard';

    protected $queryString = ['tab' => ['except' => 'dashboard']];

    public function mount($tab = null)
    {
        if ($tab && in_array($tab, ['dashboard', 'bookings', 'reviews', 'profile'])) {
            $this->activeTab = $tab;
        }
    }

    public function profile()
    {
        return view('livewire.pages.user-dashboard', [
            'activeTab' => 'profile'
        ]);
    }

    public function bookings()
    {
        return view('livewire.pages.user-dashboard', [
            'activeTab' => 'bookings'
        ]);
    }

    public function reviews()
    {
        return view('livewire.pages.user-dashboard', [
            'activeTab' => 'reviews'
        ]);
    }

    public function settings()
    {
        return view('livewire.pages.user-dashboard', [
            'activeTab' => 'settings'
        ]);
    }

    public function logout()
    {
        Auth::logout(); 
        
        request()->session()->invalidate(); 
        
        request()->session()->regenerateToken(); 
        
        return $this->redirect('/', navigate: true); 
    }

    public function render()
    {
        return view('livewire.pages.user-dashboard');
    }
}