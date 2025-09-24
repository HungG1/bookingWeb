<?php

namespace App\Livewire\Components;

use App\Models\Amenity;
use Livewire\Component;

class HotelFilters extends Component
{
    public $starRating = [];
    public $priceRange = [0, 5000000];
    public $selectedAmenities = [];
    
    protected $queryString = [
        'starRating' => ['except' => []],
        'priceRange' => ['except' => [0, 5000000]],
        'selectedAmenities' => ['except' => []],
    ];

    public function updatedStarRating()
    {
        $this->dispatch('filters-updated');
    }

    public function updatedPriceRange()
    {
        $this->dispatch('filters-updated');
    }

    public function updatedSelectedAmenities()
    {
        $this->dispatch('filters-updated');
    }

    public function resetFilters()
    {
        $this->starRating = [];
        $this->priceRange = [0, 5000000];
        $this->selectedAmenities = [];
        $this->dispatch('filters-updated');
    }

    public function render()
    {
        $amenities = Amenity::all();
        
        return view('livewire.components.hotel-filters', [
            'amenities' => $amenities,
        ]);
    }
}