<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Stai visualizzando la lista dei ticket") }}
                </div>
                <div class="p-2 text-gray-900 dark:text-gray-100">
                  <div class="space-y-4">
    @foreach ($tickets as $ticket)
        <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $ticket["titolo"] }}</h2>
            <p class="text-gray-600 mb-1">{{ $ticket["commento"] }}</p>
            <div class="flex items-center justify-between text-sm text-gray-500">
                <span class="px-2 py-1 rounded-full 
                    @if($ticket['stato'] === 'aperto') bg-red-100 text-red-700 
                    @elseif($ticket['stato'] === 'in lavorazione') bg-yellow-100 text-yellow-700 
                    @else bg-green-100 text-green-700 
                    @endif">
                    {{ ucfirst($ticket["stato"]) }}
                </span>
                <span>{{ $ticket["data"] }}</span>
            </div>
        </div>
    @endforeach
</div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
