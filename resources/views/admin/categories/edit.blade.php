<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kategória szerkesztése') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Név</label>
                            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            @error('slug')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Leírás</label>
                            <textarea name="description" rows="4" class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Sorrend</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            @error('sort_order')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Házas kép pozicionálás -->
                        <div class="mb-6 border-t pt-6">
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_visible_on_house" value="1" 
                                           {{ old('is_visible_on_house', $category->is_visible_on_house) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 shadow-sm mr-2">
                                    <span class="text-sm font-bold text-gray-700">Megjelenítés a házas képen</span>
                                </label>
                            </div>

                            <div id="position-section" class="{{ old('is_visible_on_house', $category->is_visible_on_house) ? '' : 'hidden' }}">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pozíció a házas képen</label>
                                <p class="text-sm text-gray-600 mb-4">Kattintson a képre a kategória pozíciójának beállításához.</p>
                                
                                <div class="relative inline-block" style="max-width: 800px;">
                                    <img src="{{ asset('images/greenhousecar-2-1.svg') }}" alt="Ház" id="house-image" class="cursor-crosshair w-full border-2 border-gray-300 rounded" style="display: block; max-width: 100%; height: auto;">
                                    
                                    @if($category->position_x && $category->position_y)
                                    <div id="position-marker" class="absolute w-8 h-8 bg-black rounded-full flex items-center justify-center transform -translate-x-1/2 -translate-y-1/2 pointer-events-none"
                                         style="left: {{ $category->position_x }}; top: {{ $category->position_y }};">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                <input type="hidden" name="position_x" id="position_x" value="{{ old('position_x', $category->position_x) }}">
                                <input type="hidden" name="position_y" id="position_y" value="{{ old('position_y', $category->position_y) }}">

                                <div class="mt-4 text-sm text-gray-600">
                                    <span id="position-display">
                                        @if($category->position_x && $category->position_y)
                                            Jelenlegi pozíció: {{ $category->position_x }}, {{ $category->position_y }}
                                        @else
                                            Még nincs beállítva pozíció
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Frissítés
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-900">Mégse</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Házas kép pozicionálás
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.querySelector('input[name="is_visible_on_house"]');
            const positionSection = document.getElementById('position-section');
            const houseImage = document.getElementById('house-image');
            const positionX = document.getElementById('position_x');
            const positionY = document.getElementById('position_y');
            const positionDisplay = document.getElementById('position-display');
            let marker = document.getElementById('position-marker');

            // Checkbox toggle
            checkbox?.addEventListener('change', function() {
                if (this.checked) {
                    positionSection.classList.remove('hidden');
                } else {
                    positionSection.classList.add('hidden');
                }
            });

            // Házas képre kattintás
            houseImage?.addEventListener('click', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                // Százalékban számoljuk
                const xPercent = ((x / rect.width) * 100).toFixed(2) + '%';
                const yPercent = ((y / rect.height) * 100).toFixed(2) + '%';
                
                // Hidden mezők frissítése
                positionX.value = xPercent;
                positionY.value = yPercent;
                
                // Display frissítése
                positionDisplay.textContent = `Jelenlegi pozíció: ${xPercent}, ${yPercent}`;
                
                // Marker létrehozása vagy frissítése
                if (!marker) {
                    marker = document.createElement('div');
                    marker.id = 'position-marker';
                    marker.className = 'absolute w-8 h-8 bg-black rounded-full flex items-center justify-center transform -translate-x-1/2 -translate-y-1/2 pointer-events-none';
                    marker.innerHTML = '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>';
                    houseImage.parentElement.appendChild(marker);
                }
                
                marker.style.left = xPercent;
                marker.style.top = yPercent;
            });
        });
    </script>
</x-app-layout>
