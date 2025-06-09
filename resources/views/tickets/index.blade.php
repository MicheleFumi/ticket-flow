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

                    @if ($tickets->where('status.titolo', 'Aperto')->count() > 0)
                        {{ __('Stai visualizzando la lista dei ticket') }}
                    @else
                        {{ __('Non ci sono nuovi ticket') }}
                    @endif

                </div>
                <div class="p-2 text-gray-900 dark:text-gray-100">
                    <div class="space-y-4">
                        @if (!$technician->is_admin)
                            @foreach ($tickets as $ticket)
                                @if ($ticket->status->titolo === 'Aperto')
                                    <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <h2 class="text-xl font-semibold text-gray-800">{{ $ticket['titolo'] }}</h2>
                                            <span
                                                class="text-sm text-gray-500">{{ $ticket['created_at']->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <div>
                                                <span
                                                    class="px-2 py-1 rounded-full
                                    @if ($ticket->status->titolo === 'Aperto') bg-green-100 text-green-700
                                    @elseif($ticket->status->titolo === 'In Lavorazione') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                                    {{ ucfirst($ticket->status->titolo) }}
                                                </span>
                                                @if ($ticket->is_reported)
                                                    <span
                                                        class="relative group ms-2 px-2 py-1 rounded-full bg-yellow-100 text-yellow-700">
                                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                                        <span
                                                            class="absolute -top-8 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-transform bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-md z-10 whitespace-nowrap">
                                                            Ticket reportato
                                                        </span>
                                                    </span>
                                                @endif
                                            </div>
                                            <a href="{{ route('tickets.show', $ticket) }}"
                                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                Vedi dettagli →
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            @foreach ($allTickets as $ticket)
                                @if ($ticket->status->titolo === 'Aperto')
                                    <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <h2 class="text-xl font-semibold text-gray-800">{{ $ticket['titolo'] }}</h2>
                                            <span
                                                class="text-sm text-gray-500">{{ $ticket['created_at']->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <div>
                                                <span
                                                    class="px-2 py-1 rounded-full
                                    @if ($ticket->status->titolo === 'Aperto') bg-green-100 text-green-700
                                    @elseif($ticket->status->titolo === 'In Lavorazione') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                                    {{ ucfirst($ticket->status->titolo) }}
                                                </span>
                                                @if ($ticket->is_reported)
                                                    <span
                                                        class="inline-flex items-center overflow-hidden rounded-full bg-yellow-100 text-yellow-700 transition-all duration-300 group max-w-[2.2rem] hover:max-w-xs cursor-default px-2 py-1">
                                                        <i class="bi bi-exclamation-triangle-fill mr-1"></i>
                                                        <span
                                                            class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                            Ticket reportato
                                                        </span>
                                                    </span>
                                                @endif
                                            </div>
                                            <a href="{{ route('tickets.show', $ticket) }}"
                                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                Vedi dettagli →
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
