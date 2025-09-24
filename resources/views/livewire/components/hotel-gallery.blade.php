<div class="relative">
    <!-- Main Gallery Grid -->
    <div class="grid grid-cols-4 gap-2 relative">
        @foreach($hotel->images as $index => $image)
            <div class="{{ $index === 0 ? 'col-span-2 row-span-2' : '' }} overflow-hidden rounded-lg cursor-pointer"
                wire:click="selectImage({{ $index }})">
                <img src="{{ $image }}" 
                     alt="Gallery image {{ $index + 1 }}" 
                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
            </div>
        @endforeach
    </div>

    <!-- Gallery Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" 
         wire:click="$set('showModal', false)">
        <div class="relative max-w-6xl w-full" @click.stop>
            <button class="absolute top-4 right-4 text-white hover:text-gray-300" 
                    wire:click="$set('showModal', false)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <!-- Navigation Buttons -->
            <button class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300"
                    wire:click="previousImage">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            
            <button class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300"
                    wire:click="nextImage">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            
            <!-- Main Image -->
            <img src="{{ $hotel->images[$selectedImage] }}" 
                 alt="Gallery image {{ $selectedImage + 1 }}"
                 class="w-full max-h-[80vh] object-contain">
        </div>
    </div>
    @endif
</div>
