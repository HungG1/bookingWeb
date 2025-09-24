<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingForm extends Component
{
    public $hotel;
    public $room;
    public $checkInDate;
    public $checkOutDate;
    public $adults = 2;
    public $children = 0;
    public $rooms = 1;
    
    public $customerName;
    public $customerEmail;
    public $customerPhone;
    public $customerNotes;
    
    protected $rules = [
        'customerName' => 'required|min:3',
        'customerEmail' => 'required|email',
        'customerPhone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'customerNotes' => 'nullable|max:500',
        'checkInDate' => 'required|date|after:today',
        'checkOutDate' => 'required|date|after:checkInDate',
        'adults' => 'required|integer|min:1',
        'children' => 'required|integer|min:0',
        'rooms' => 'required|integer|min:1',
    ];

    public function mount($hotel, $room, $checkInDate, $checkOutDate, $adults, $children, $rooms)
    {
        $this->hotel = $hotel;
        $this->room = $room;
        $this->checkInDate = $checkInDate;
        $this->checkOutDate = $checkOutDate;
        $this->adults = $adults;
        $this->children = $children;
        $this->rooms = $rooms;

        if (Auth::check()) {
            $user = Auth::user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
            $this->customerPhone = $user->phone;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm()
    {
        $this->validate();
        
        $nights = Carbon::parse($this->checkInDate)->diffInDays(Carbon::parse($this->checkOutDate));
        $basePrice = $this->room->base_price * $this->rooms * $nights;
        
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'hotel_id' => $this->hotel->id,
            'room_id' => $this->room->id,
            'check_in_date' => $this->checkInDate,
            'check_out_date' => $this->checkOutDate,
            'num_adults' => $this->adults,
            'num_children' => $this->children,
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'customer_phone' => $this->customerPhone,
            'customer_notes' => $this->customerNotes,
            'base_price' => $basePrice,
            'final_price' => $basePrice,
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ]);

        $this->dispatch('booking-created', bookingId: $booking->id);
    }

    public function render()
    {
        return view('livewire.components.booking-form');
    }
}