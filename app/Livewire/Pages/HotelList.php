<?php

namespace App\Livewire\Pages;

use Illuminate\Support\Facades\DB;
use App\Models\Hotel;
use App\Models\Amenity;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;


class HotelList extends Component
{
    use WithPagination;
    
    public $location = '';
    public $checkIn;
    public $checkOut;
    public $adults = 2;
    public $children = 0;
    public $rooms = 1;
    
    // Filters
    public $starRating = [];
    public $priceRange = [0, 500000000]; // VND
    public $selectedAmenities = [];
    public $sortBy = 'recommended';
    
    protected $queryString = [
        'location' => ['except' => ''],
        'checkIn' => ['except' => ''],
        'checkOut' => ['except' => ''],
        'adults' => ['except' => 2],
        'children' => ['except' => 0],
        'rooms' => ['except' => 1],
        'starRating' => ['except' => []],
        'priceRange' => ['except' => [0, 500000000]],
        'selectedAmenities' => ['except' => []],
        'sortBy' => ['except' => 'recommended'],
    ];
    
    public function mount()
    {
        $this->checkIn = request()->query('check_in', Carbon::today()->format('Y-m-d'));
        $this->checkOut = request()->query('check_out', Carbon::tomorrow()->format('Y-m-d'));
        $this->location = request()->query('location', '');
        $this->adults = (int)request()->query('adults', 2);
        $this->children = (int)request()->query('children', 0);
        $this->rooms = (int)request()->query('rooms', 1);
    }
    
    public function updatedStarRating()
    {
        $this->resetPage();
    }
    
    public function updatedPriceRange()
    {
        $this->resetPage();
    }
    
    public function updatedSelectedAmenities()
    {
        $this->resetPage();
    }
    
    public function updatedSortBy()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $hotelsQuery = Hotel::query()->active();
        
        // Location filter
        if ($this->location) {
            $hotelsQuery->where(function($query) {
                $query->where('name', 'like', '%' . $this->location . '%')
                      ->orWhere('address', 'like', '%' . $this->location . '%');
            });
        }
        
        // Star rating filter
        if (!empty($this->starRating)) {
            $hotelsQuery->whereIn('star_rating', $this->starRating);
        }
        
        // Amenities filter
        if (!empty($this->selectedAmenities)) {
            $hotelsQuery->whereHas('amenities', function($query) {
                $query->whereIn('amenities.id', $this->selectedAmenities);
            });
        }
        
        // Apply sorting
        switch ($this->sortBy) {
            case 'price_low':
                $hotelsQuery->whereHas('rooms', function($q) {
                    $q->orderBy('base_price', 'asc');
                });
                break;
            case 'price_high':
                $hotelsQuery->whereHas('rooms', function($q) {
                    $q->orderBy('base_price', 'desc');
                });
                break;
            case 'rating':
                $hotelsQuery->withCount(['reviews as average_rating' => function($query) {
                    $query->select(DB::raw('coalesce(avg(rating),0)'));
                }])->orderByDesc('average_rating');
                break;
            default:
                // Default sorting (recommended)
                $hotelsQuery->withCount(['reviews as average_rating' => function($query) {
                    $query->select(DB::raw('coalesce(avg(rating),0)'));
                }])->orderByDesc('average_rating');
                break;
        }
        
        $hotels = $hotelsQuery->with(['amenities', 'rooms' => function($query) {
            $query->orderBy('base_price', 'asc');
        }])->paginate(10);
        
        $amenities = Amenity::all();
        
        return view('livewire.pages.hotel-list', [
            'hotels' => $hotels,
            'amenities' => $amenities,
        ]);
    }
}