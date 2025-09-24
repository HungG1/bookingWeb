<?php

namespace App\Livewire\Components;

use Livewire\Component;

class HotelGallery extends Component
{
    public $hotel;
    public $selectedImage = 0;
    public $showModal = false;

    public function mount($hotel)
    {
        $this->hotel = $hotel;
    }

    public function selectImage($index)
    {
        $this->selectedImage = $index;
        $this->showModal = true;
    }

    public function previousImage()
    {
        if ($this->selectedImage > 0) {
            $this->selectedImage--;
        } else {
            $this->selectedImage = count($this->hotel->images) - 1;
        }
    }

    public function nextImage()
    {
        if ($this->selectedImage < count($this->hotel->images) - 1) {
            $this->selectedImage++;
        } else {
            $this->selectedImage = 0;
        }
    }

    public function render()
    {
        return view('livewire.components.hotel-gallery');
    }
}