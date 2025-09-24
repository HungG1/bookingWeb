<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Room;
use Carbon\Carbon;

class RoomSelection extends Component
{
    public $hotel;
    public $checkInDate;
    public $checkOutDate;
    public $adults;
    public $children;
    public $rooms;
    public $selectedRoomId = null;
    public $availableRooms = [];

    protected $listeners = ['dateChanged' => 'loadAvailableRooms'];

    public function mount($hotel, $checkInDate, $checkOutDate, $adults, $children, $rooms)
    {
        $this->hotel = $hotel;
        $this->checkInDate = $checkInDate;
        $this->checkOutDate = $checkOutDate;
        $this->adults = $adults;
        $this->children = $children;
        $this->rooms = $rooms;

        $this->loadAvailableRooms();
    }

    public function loadAvailableRooms()
    {
        $checkIn = Carbon::parse($this->checkInDate);
        $checkOut = Carbon::parse($this->checkOutDate);
        $nights = $checkIn->diffInDays($checkOut);

        $this->availableRooms = [];

        foreach ($this->hotel->rooms as $room) {
            if ($room->max_occupancy >= ($this->adults + $this->children)) {
                $isAvailable = true;
                $totalPrice = 0;

                for ($date = clone $checkIn; $date->lt($checkOut); $date->addDay()) {
                    $availability = $room->roomAvailabilities()
                        ->where('date', $date->format('Y-m-d'))
                        ->first();

                    if ($availability) {
                        if ($availability->available_count < $this->rooms) {
                            $isAvailable = false;
                            break;
                        }
                        $totalPrice += $availability->price * $this->rooms;
                    } else {
                        $totalPrice += $room->base_price * $this->rooms;
                    }
                }

                if ($isAvailable) {
                    $this->availableRooms[] = [
                        'id' => $room->id,
                        'room' => $room,
                        'total_price' => $totalPrice,
                        'price_per_night' => $totalPrice / $nights,
                    ];
                }
            }
        }
    }

    public function selectRoom($roomId)
    {
        $this->selectedRoomId = $roomId;
        $this->dispatch('roomSelected', roomId: $roomId);
    }

    public function render()
    {
        return view('livewire.components.room-selection');
    }
}