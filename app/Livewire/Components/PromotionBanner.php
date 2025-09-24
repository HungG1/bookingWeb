<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Promotion;

class PromotionBanner extends Component
{
    public function render()
    {
        $activePromotions = Promotion::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return view('livewire.components.promotion-banner', [
            'promotions' => $activePromotions
        ]);
    }
}