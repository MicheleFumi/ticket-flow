<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Storico Ticket') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                @if ($tickets->where('status_id', 3)->count())
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-end items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        {{-- Search input tecnico --}}
                        <div class="relative w-full sm:w-auto">
                            <input
                                type="text"
                                id="searchInput"
                                placeholder="Cerca tecnico..."
                                class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <button
                                type="button"
                                onclick="document.getElementById('searchInput').value=''; filterTickets();"
                                class="absolute right-3 top-1/2 -translate-y-1/2 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-600 dark:text-gray-200 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold focus:outline-none transition duration-150 ease-in-out"
                            >
                                &times;
                            </button>
                        </div>

                        {{-- Filtro data --}}
                        <div class="relative w-full sm:w-auto">
                            <input
                                type="date"
                                id="dateInput"
                                max="{{ now()->toDateString() }}"
                                class="w-full px-4 py-2 pr-10 rounded-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                            />
                            <button
                                type="button"
                                onclick="document.getElementById('dateInput').value=''; filterTickets();"
                                class="absolute right-3 top-1/2 -translate-y-1/2 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-600 dark:text-gray-200 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold focus:outline-none transition duration-150 ease-in-out"
                            >
                                &times;
                            </button>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="ticketContainer">
                        @foreach ($tickets->where('status_id', 3) as $ticket)
                            <div
                                class="ticket-card bg-white dark:bg-gray-700 shadow-lg rounded-2xl p-6 border border-gray-200 dark:border-gray-600 flex flex-col justify-between transform hover:scale-105 transition-all duration-300 ease-in-out"
                                data-date="{{ $ticket->latestLog?->data_chiusura?->format('Y-m-d') ?? '' }}"
                                data-technician="{{ $ticket->latestLog && $ticket->latestLog->technician ? strtolower($ticket->latestLog->technician->nome . ' ' . $ticket->latestLog->technician->cognome) : '' }}"
                            >
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">{{ $ticket->titolo }}</h3>
                                    <p class="text-gray-700 dark:text-gray-300 text-base mb-4 line-clamp-3">{{ $ticket->commento }}</p>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                    <p>
                                        <strong>Chiuso da:</strong>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $ticket->latestLog?->technicianWhoClosed ? $ticket->latestLog->technicianWhoClosed->nome . ' ' . $ticket->latestLog->technicianWhoClosed->cognome : 'N/A' }}</span>
                                    </p>
                                    <p>
                                        <strong>Data chiusura:</strong>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $ticket->latestLog?->data_chiusura?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                                    </p>
                                    <p>
                                        <strong>Assegnato a:</strong>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $ticket->latestLog?->technician ? $ticket->latestLog->technician->nome . ' ' . $ticket->latestLog->technician->cognome : 'N/A' }}</span>
                                    </p>
                                    <p>
                                        <strong>Data assegnazione:</strong>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $ticket->latestLog?->data_assegnazione?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                                    </p>
                                </div>

                                <div class="mt-5 text-right">
                                    <a
                                        href="{{ route('tickets.show', $ticket->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-full shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out"
                                    >
                                        Dettagli
                                        <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-gray-600 dark:text-gray-400">
                        <p class="text-lg">Non hai ancora chiuso alcun ticket. <br> Forza, c'è sempre qualcosa da sistemare! 💪</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('dateInput');
            const searchInput = document.getElementById('searchInput');
            const ticketCards = document.querySelectorAll('.ticket-card');

            function filterTickets() {
                const selectedDate = dateInput.value.toLowerCase();
                const searchText = searchInput.value.toLowerCase();

                ticketCards.forEach(card => {
                    const cardDate = card.dataset.date.toLowerCase();
                    const technicianName = card.dataset.technician.toLowerCase();

                    const matchDate = !selectedDate || cardDate === selectedDate;
                    const matchTechnician = !searchText || technicianName.includes(searchText);

                    card.style.display = (matchDate && matchTechnician) ? 'flex' : 'none'; // Use flex for card display
                });
            }

            dateInput?.addEventListener('change', filterTickets);
            searchInput?.addEventListener('input', filterTickets);
        });
    </script>
</x-app-layout>