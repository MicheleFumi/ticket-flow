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
                    {{ __('Stai visualizzando la lista dei ticket') }}
                </div>
                <div class="p-2 text-gray-900 dark:text-gray-100">
                    <div class="space-y-4">
                        @foreach ($tickets as $ticket)
                            <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="text-xl font-semibold text-gray-800">{{ $ticket['titolo'] }}</h2>
                                    <span
                                        class="text-sm text-gray-500">{{ $ticket['created_at']->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span
                                        class="px-2 py-1 rounded-full
            @if ($ticket->status->titolo === 'Aperto') bg-green-100 text-green-700
            @elseif($ticket->status->titolo === 'In Lavorazione') bg-yellow-100 text-yellow-700
            @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($ticket->status->titolo) }}
                                    </span>
                                    <a href="{{ route('tickets.show', $ticket) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                        Vedi dettagli →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
