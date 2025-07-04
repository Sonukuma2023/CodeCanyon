<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex bg-gray-100 mt-4 px-6 gap-4">
        <!-- Main Content -->
        <main class="flex-1 p-6 bg-white rounded-lg shadow">
            <p class="text-gray-600">You're logged in {{ Auth::user()->name }}!</p>
        </main>
    </div>
</x-app-layout>
