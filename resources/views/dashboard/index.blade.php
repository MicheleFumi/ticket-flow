<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>

            <a class="text-black dark:text-white hover:underline" href="{{ route('dashboard.history') }}">STORICO
                TICKET</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if ($tickets->count() > 0)
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __('Stai visualizzando la tua Dashboard') }}
                    </div>
                    <div class="p-2 text-gray-900 dark:text-gray-100">
                        <div class="space-y-4">

                            @foreach ($tickets as $ticket)
                                <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h2 class="text-xl font-semibold text-gray-800">{{ $ticket->titolo }}</h2>
                                        {{-- Visualizza il nome del tecnico e la data di assegnazione --}}
                                        <div>
                                            <div class="text-sm text-gray-500">
                                                Creato da: {{ $ticket->user->nome }}
                                                {{ $ticket->user->cognome }} il:
                                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Assegnato a: {{ $ticket->technician->nome }}
                                                {{ $ticket->technician->cognome }} il:
                                                {{ $ticket->data_assegnazione->format('d/m/Y H:i') }}
                                            </div>
                                        </div>

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
                                        <div class="my-3 text-black">Non ci sono foto da visualizzare</div>
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
                                                <button type="submit" class="px-2 py-1 rounded bg-yellow-500">Rimuovi
                                                    Assegnazione
                                                    Ticket</button>
                                            </form>

                                            <!-- Bottone che apre il modale -->
                                            <button type="button"
                                                class="px-2 py-1 rounded bg-red-600 text-white close-ticket-btn"
                                                data-ticket-id="{{ $ticket->id }}"
                                                data-ticket-title="{{ $ticket->titolo }}">
                                                Chiudi Ticket
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modale per la chiusura ticket -->
                                <div id="modal-{{ $ticket->id }}"
                                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-lg w-full p-6 relative">
                                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                            Chiudi Ticket: <span class="modal-ticket-title"></span>
                                        </h3>
                                        <form method="POST" action="{{ route('tickets.close') }}">
                                            @csrf
                                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                                            <label for="note_chiusura_{{ $ticket->id }}"
                                                class="block text-gray-700 dark:text-gray-300 mb-2">
                                                Note di chiusura (opzionali)
                                            </label>
                                            <textarea name="note_chiusura" id="note_chiusura_{{ $ticket->id }}" rows="4"
                                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                                                placeholder="Inserisci eventuali note per la chiusura del ticket..."></textarea>

                                            <div class="mt-6 flex justify-end gap-3">
                                                <button type="button"
                                                    class="px-4 py-2 rounded-md bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 modal-close-btn">
                                                    Annulla
                                                </button>
                                                <button type="submit"
                                                    class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
                                                    Conferma chiusura
                                                </button>
                                            </div>
                                        </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.close-ticket-btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const ticketId = btn.getAttribute('data-ticket-id');
                    const ticketTitle = btn.getAttribute('data-ticket-title');
                    const modal = document.getElementById(`modal-${ticketId}`);
                    if (modal) {
                        // Imposto il titolo nel modale
                        modal.querySelector('.modal-ticket-title').textContent = ticketTitle;
                        // Mostro il modale
                        modal.classList.remove('hidden');
                    }
                });
            });

            const modals = document.querySelectorAll('[id^="modal-"]');
            modals.forEach(modal => {
                // Chiudo il modale se clicco su Annulla
                modal.querySelector('.modal-close-btn').addEventListener('click', () => {
                    modal.classList.add('hidden');
                });

                // Chiudo il modale se clicco fuori dal contenuto
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });

                // Chiudo il modale con ESC
                document.addEventListener('keydown', (e) => {
                    if (e.key === "Escape" && !modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</x-app-layout>
