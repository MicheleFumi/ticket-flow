<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Storico') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('Stai visualizzando lo storico dei ticket chiusi') }}
                </div>
                <div class="p-2 text-gray-900 dark:text-gray-100">
                    <div class="space-y-4">
                        @if ($tickets->count() > 0)
                            @foreach ($tickets as $ticket)
                                <div x-data="{ openModal_{{ $ticket->id }}: false }" class="bg-white shadow-md rounded-2xl p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h2 class="text-xl font-semibold text-gray-800">{{ $ticket->titolo }}</h2>
                                        <span class="text-sm text-gray-500">
                                            Assegnato a: {{ $ticket->technician->nome }} {{ $ticket->technician->cognome }} il: {{ $ticket->data_assegnazione->format('d/m/Y H:i') }}
                                        </span>
                                    </div>

                                    {{-- COMMENTO RIMOSSO DALLA CARD PRINCIPALE --}}

                                    <div class="flex items-center justify-end mb-2">
                                        <span class="text-sm text-gray-500">
                                            Chiuso da: {{ $ticket->closedBy ? $ticket->closedBy->nome . ' ' . $ticket->closedBy->cognome : 'N/D' }} il: {{ $ticket->data_chiusura->format('d/m/Y H:i') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between text-sm">
                                        <span
                                            class="px-2 py-1 rounded-full
                                            @if ($ticket->status->titolo === 'Aperto') bg-green-100 text-green-700
                                            @elseif($ticket->status->titolo === 'In Lavorazione') bg-yellow-100 text-yellow-700
                                            @else bg-red-100 text-red-700 @endif">
                                            {{ ucfirst($ticket->status->titolo) }}
                                        </span>

                                        <div class="flex gap-2">
                                            <button @click="openModal_{{ $ticket->id }} = true"
                                                    type="button"
                                                    class="px-2 py-1 rounded-full bg-blue-500 text-white">
                                                Dettagli
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Modale -->
                                    <div x-show="openModal_{{ $ticket->id }}"
                                         x-cloak
                                         @keydown.escape.window="openModal_{{ $ticket->id }} = false"
                                         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                                        <div @click.away="openModal_{{ $ticket->id }} = false"
                                             class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md shadow-xl">
                                            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Dettagli Ticket</h2>
                                            <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                                                <p><strong>Titolo:</strong> {{ $ticket->titolo }}</p>
                                                <p><strong>Testo:</strong> {{ $ticket->commento }}</p>
                                                <p><strong>Aperto da:</strong> User #{{ $ticket->user_id }}</p>
                                                <p><strong>Data Apertura:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                                <p><strong>Tecnico assegnato:</strong> {{ $ticket->technician->nome }} {{ $ticket->technician->cognome }}</p>
                                                <p><strong>Data Assegnazione:</strong> {{ $ticket->data_assegnazione->format('d/m/Y H:i') }}</p>
                                                <p><strong>Chiuso da:</strong> {{ $ticket->closedBy ? $ticket->closedBy->nome . ' ' . $ticket->closedBy->cognome : 'N/D' }}</p>
                                                <p><strong>Data Chiusura:</strong> {{ $ticket->data_chiusura->format('d/m/Y H:i') }}</p>
                                                <div>
                                                    @if (isset($ticket->images) && $ticket->images->count())
                                                        @foreach ($ticket->images as $image)
                                                            <a href="{{ asset($image->file_path) }}" target="_blank" class="text-blue-600 hover:underline block">
                                                                Visualizza immagine {{ $loop->iteration }}
                                                            </a>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="mt-4 text-right">
                                                <button @click="openModal_{{ $ticket->id }} = false"
                                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded">
                                                    Chiudi
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                {{ __("Nessun ticket trovato con lo stato 'In Lavorazione' o assegnato a te.") }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
