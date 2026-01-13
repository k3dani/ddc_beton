<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Statisztikák</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-800">{{ $stats['locations'] }}</div>
                            <div class="text-sm text-blue-600">Telephelyek összesen</div>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-800">{{ $stats['active_locations'] }}</div>
                            <div class="text-sm text-green-600">Aktív telephelyek</div>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-purple-800">{{ $stats['products'] }}</div>
                            <div class="text-sm text-purple-600">Termékek összesen</div>
                        </div>
                        <div class="bg-orange-100 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-orange-800">{{ $stats['active_products'] }}</div>
                            <div class="text-sm text-orange-600">Aktív termékek</div>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mb-4 mt-8">Gyors linkek</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.locations.index') }}" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200">
                            <h4 class="font-semibold text-gray-800">Telephelyek kezelése</h4>
                            <p class="text-sm text-gray-600">Telephelyek hozzáadása, szerkesztése, törlése</p>
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200">
                            <h4 class="font-semibold text-gray-800">Termékek kezelése</h4>
                            <p class="text-sm text-gray-600">Termékek és árak kezelése</p>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200">
                            <h4 class="font-semibold text-gray-800">Kategóriák kezelése</h4>
                            <p class="text-sm text-gray-600">Termékkategóriák szervezése</p>
                        </a>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('homepage') }}" class="text-blue-600 hover:text-blue-800">
                            ← Vissza a publikus oldalra
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
