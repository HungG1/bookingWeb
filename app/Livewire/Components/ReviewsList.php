<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithPagination;

class ReviewsList extends Component
{
    use WithPagination;

    public $hotel;
    public $filter = 'all';
    public $sortBy = 'newest';

    protected $queryString = [
        'filter' => ['except' => 'all'],
        'sortBy' => ['except' => 'newest']
    ];

    public function mount($hotel)
    {
        $this->hotel = $hotel;
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function render()
    {
        $reviews = $this->hotel->reviews()
            ->when($this->filter !== 'all', function($query) {
                return $query->where('rating', $this->filter);
            })
            ->when($this->sortBy === 'newest', function($query) {
                return $query->orderBy('created_at', 'desc');
            })
            ->when($this->sortBy === 'highest', function($query) {
                return $query->orderBy('rating', 'desc');
            })
            ->when($this->sortBy === 'lowest', function($query) {
                return $query->orderBy('rating', 'asc');
            })
            ->paginate(5);

        $ratingBreakdown = [
            5 => $this->hotel->reviews->where('rating', 5)->count(),
            4 => $this->hotel->reviews->where('rating', 4)->count(),
            3 => $this->hotel->reviews->where('rating', 3)->count(),
            2 => $this->hotel->reviews->where('rating', 2)->count(),
            1 => $this->hotel->reviews->where('rating', 1)->count(),
        ];

        return view('livewire.components.reviews-list', [
            'reviews' => $reviews,
            'ratingBreakdown' => $ratingBreakdown
        ]);
    }
}
