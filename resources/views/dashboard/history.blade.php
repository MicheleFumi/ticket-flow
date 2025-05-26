<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Storico') }}
            </h2>

            {{-- <a href="{{route("dashboard.history")}}">STORICO</a> --}}
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
                                <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h2 class="text-xl font-semibold text-gray-800">{{ $ticket->titolo }}</h2>
                                        <span class="text-sm text-gray-500">
                                            Assegnato a: {{ $ticket->technician->nome }} {{ $ticket->technician->cognome }} il: {{ $ticket->data_assegnazione->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-md text-gray-500 mb-2">{{ $ticket->commento }}</p>
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
                                            {{-- <form method="POST" action="{{route("tickets.unassign")}}">
                                                @csrf
                                                <input type="hidden" name="technician_id" value="{{ $ticket->technician->id }}">
                                                <button type="submit" class="px-2 py-1 rounded-full bg-yellow-400">Rimuovi il ticket dal tecnico</button>
                                            </form>
                                            <form method="POST" action="{{route("tickets.close")}}">
                                                @csrf
                                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                                <button type="submit" class="px-2 py-1 rounded-full bg-red-600">Termina Lavoro</button>
                                            </form> --}}
                                            <button type="button" class="px-2 py-1 rounded-full bg-blue-500">Dettagli</button>
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