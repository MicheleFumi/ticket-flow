<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Storico Ticket') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if ($tickets->count() > 0)
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
                        <span>{{ __('Stai visualizzando lo storico dei ticket chiusi') }}</span>
                        <div>
                            @if (auth()->user()->is_admin)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4">
                                    <div class="relative">
                                        <input type="text" id="searchInput" placeholder="Filtra per tecnico..."
                                            class="rounded-md px-3 py-1 border border-gray-300 dark:bg-gray-700 dark:text-white pr-8" />
                                        <button type="button"
                                            onclick="document.getElementById('searchInput').value='';"
                                            class="absolute right-1 top-1/2 -translate-y-1/2 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 rounded-full w-5 h-5 flex items-center justify-center text-md font-bold focus:outline-none"
                                            aria-label="Clear text input">
                                            &times;
                                        </button>
                                    </div>

                                    <div class="relative mt-2 sm:mt-0">
                                        <input type="date" id="dateInput" max="{{ now()->toDateString() }}"
                                            class="rounded-md px-3 py-1 border border-gray-300 dark:bg-gray-700 dark:text-white pr-8" />
                                        <button type="button" onclick="document.getElementById('dateInput').value='';"
                                            class="absolute right-1 top-1/2 -translate-y-1/2 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 rounded-full w-5 h-5 flex items-center justify-center text-md font-bold focus:outline-none"
                                            aria-label="Clear date input">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="p-2 text-gray-900 dark:text-gray-100 space-y-4" id="ticketContainer">
                        @foreach ($tickets as $ticket)
                            <div class="ticket-card bg-white shadow-md rounded-2xl p-4 border border-gray-200"
                                data-technician="{{ strtolower($ticket->technician->nome . ' ' . $ticket->technician->cognome) }}"
                                data-date="{{ $ticket->data_chiusura->format('Y-m-d') }}">
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="text-xl font-semibold text-gray-800">{{ $ticket->titolo }}</h2>
                                    <span class="text-sm text-gray-500">
                                        Assegnato a: {{ $ticket->technician->nome }} {{ $ticket->technician->cognome }}
                                        il: {{ $ticket->data_assegnazione->format('d/m/Y H:i') }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-end mb-2">
                                    <span class="text-sm text-gray-500">
                                        Chiuso da:
                                        {{ $ticket->closedBy ? $ticket->closedBy->nome . ' ' . $ticket->closedBy->cognome : 'N/D' }}
                                        il: {{ $ticket->data_chiusura->format('d/m/Y H:i') }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-sm">
                                    <span class="px-2 py-1 rounded-full bg-red-100 text-red-700">
                                        {{ ucfirst($ticket->status->titolo) }}
                                    </span>

                                    <div class="flex gap-2">
                                        <button type="button"
                                            class="px-2 py-1 rounded-full bg-blue-500 text-white open-modal-btn"
                                            data-modal-id="modal-{{ $ticket->id }}">
                                            Dettagli
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modale -->
                            <div id="modal-{{ $ticket->id }}"
                                class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center">
                                <div
                                    class="modal-content bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md shadow-xl relative">
                                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Dettagli Ticket
                                    </h2>
                                    <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                                        <p><strong>Titolo:</strong> {{ $ticket->titolo }}</p>
                                        <p><strong>Testo:</strong> {{ $ticket->commento }}</p>
                                        <p><strong>Aperto da:</strong> {{ $ticket->user->nome }}
                                            {{ $ticket->user->cognome }}</p>
                                        <p><strong>Data Apertura:</strong>
                                            {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                        <p><strong>Tecnico assegnato:</strong> {{ $ticket->technician->nome }}
                                            {{ $ticket->technician->cognome }}</p>
                                        <p><strong>Data Assegnazione:</strong>
                                            {{ $ticket->data_assegnazione->format('d/m/Y H:i') }}</p>
                                        <p><strong>Chiuso da:</strong>
                                            {{ $ticket->closedBy ? $ticket->closedBy->nome . ' ' . $ticket->closedBy->cognome : 'N/D' }}
                                        </p>
                                        <p><strong>Data Chiusura:</strong>
                                            {{ $ticket->data_chiusura->format('d/m/Y H:i') }}</p>
                                        <div>
                                            @if (isset($ticket->images) && $ticket->images->count())
                                                @foreach ($ticket->images as $image)
                                                    <a href="{{ asset($image->file_path) }}" target="_blank"
                                                        class="text-blue-600 hover:underline block">
                                                        Visualizza immagine {{ $loop->iteration }}
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                        @if ($ticket->note_chiusura !== null)
                                            <p><strong>Note di chiusura:</strong> {{ $ticket->note_chiusura }}</p>
                                        @else
                                            <p><strong>Note di chiusura:</strong> Non ci sono note di chiusura.</p>
                                        @endif

                                    </div>
                                    <div class="mt-4 text-right">
                                        <button
                                            class="close-modal-btn bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded">
                                            Chiudi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __('Non hai ancora chiuso un ticket.') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Modale
            document.querySelectorAll('.open-modal-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const modal = document.getElementById(button.dataset.modalId);
                    if (modal) modal.classList.remove('hidden');
                });
            });

            document.querySelectorAll('.close-modal-btn').forEach(button => {
                button.addEventListener('click', () => {
                    button.closest('.modal').classList.add('hidden');
                });
            });

            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal').forEach(modal => modal.classList.add('hidden'));
                }
            });

            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (!e.target.closest('.modal-content')) {
                        modal.classList.add('hidden');
                    }
                });
            });

            // Calendario e searchbar
            const searchInput = document.getElementById('searchInput');
            const dateInput = document.getElementById('dateInput');
            const ticketCards = document.querySelectorAll('.ticket-card');

            function filterTickets() {
                const searchValue = searchInput?.value.toLowerCase() || '';
                const selectedDate = dateInput?.value || '';

                ticketCards.forEach(card => {
                    const tech = card.dataset.technician;
                    const date = card.dataset.date;

                    const matchTech = tech.includes(searchValue);
                    const matchDate = selectedDate === '' || date === selectedDate;

                    card.style.display = (matchTech && matchDate) ? 'block' : 'none';
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterTickets);
            }

            if (dateInput) {
                dateInput.addEventListener('change', filterTickets);
            }
        });
    </script>
</x-app-layout>
