<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>

            <a href="{{ route('dashboard.history') }}">STORICO</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('Stai visualizzando la lista dei ticket') }}
                </div>
                <div class="p-2 text-gray-900 dark:text-gray-100">
                    <div class="space-y-4">
                        @if ($tickets->count() > 0)
                            @foreach ($tickets as $ticket)
                                <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h2 class="text-xl font-semibold text-gray-800">{{ $ticket->titolo }}</h2>
                                        {{-- Visualizza il nome del tecnico e la data di assegnazione --}}
                                        <span class="text-sm text-gray-500">
                                            Assegnato a: {{ $ticket->technician->nome }}
                                            {{ $ticket->technician->cognome }} il:
                                            {{ $ticket->data_assegnazione->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-md text-gray-500 mb-2">{{ $ticket->commento }}</p>
                                    @if (isset($ticket->images) && $ticket->images->count() > 0)
                                        <div class="my-6">
                                            @foreach ($ticket->images as $image)
                                                <a href="{{ asset($image->file_path) }}" target="_blank"
                                                    class="inline-block">
                                                    <img src="{{ asset($image->file_path) }}"
                                                        alt="Anteprima immagine {{ $loop->iteration }}"
                                                        class="w-[50px] h-[50px] object-cover rounded hover:scale-105 transition">
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="my-3">Non ci sono foto da visualizzare</div>
                                    @endif
                                    <div class="flex items-center justify-between text-sm">
                                        <div
                                            class="px-2 py-1 rounded-full
                                            @if ($ticket->status->titolo === 'Aperto') bg-green-100 text-green-700
                                            @elseif($ticket->status->titolo === 'In Lavorazione') bg-yellow-100 text-yellow-700
                                            @else bg-red-100 text-red-700 @endif">
                                            {{ ucfirst($ticket->status->titolo) }}
                                        </div>

                                        <div class="flex gap-2">
                                            <form method="POST" action="{{ route('tickets.unassign') }}">
                                                @csrf
                                                <input type="hidden" name="technician_id"
                                                    value="{{ $ticket->technician->id }}">
                                                <button type="submit"
                                                    class="px-2 py-1 rounded-full bg-yellow-400">Rimuovi il ticket dal
                                                    tecnico</button>
                                            </form>
                                            <form method="POST" action="{{ route('tickets.close') }}">
                                                @csrf
                                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                                <button type="submit" class="px-2 py-1 rounded-full bg-red-600">Termina
                                                    Lavoro</button>
                                            </form>
                                            {{-- <button type="submit" class="px-2 py-1 rounded-full bg-blue-500">Chiudi Ticket</button> --}}
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
