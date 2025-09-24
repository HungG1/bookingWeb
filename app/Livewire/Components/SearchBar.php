<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Carbon\Carbon;

class SearchBar extends Component
{
    public $location = '';
    public $checkInDate;
    public $checkOutDate;
    public $adults = 2;
    public $children = 0;
    public $rooms = 1;

    public function mount()
    {
        // Set default dates (today and tomorrow)
        $this->checkInDate = Carbon::today()->format('Y-m-d');
        $this->checkOutDate = Carbon::tomorrow()->format('Y-m-d');
    }

    public function search()
    {
        $params = [
            'location' => $this->location,
            'check_in' => $this->checkInDate,
            'check_out' => $this->checkOutDate,
            'adults' => $this->adults,
            'children' => $this->children,
            'rooms' => $this->rooms,
        ];

        return redirect()->route('hotels.list', $params);
    }
    
    // Add these methods for incrementing/decrementing values
    public function increaseAdults()
    {
        $this->adults++;
    }
    
    public function decreaseAdults()
    {
        if ($this->adults > 1) {
            $this->adults--;
        }
    }
    
    public function increaseChildren()
    {
        $this->children++;
    }
    
    public function decreaseChildren()
    {
        if ($this->children > 0) {
            $this->children--;
        }
    }
    
    public function increaseRooms()
    {
        $this->rooms++;
    }
    
    public function decreaseRooms()
    {
        if ($this->rooms > 1) {
            $this->rooms--;
        }
    }

    public function render()
    {
        return view('livewire.components.search-bar');
    }
}