<?php

namespace App\Livewire\Components;

use Livewire\Component;

class HotelInfo extends Component
{
    public $hotel;
    public $showFullDescription = false;

    public function mount($hotel)
    {
        $this->hotel = $hotel;
    }

    public function toggleDescription()
    {
        $this->showFullDescription = !$this->showFullDescription;
    }

    public function render()
    {
        return view('livewire.components.hotel-info');
    }
}