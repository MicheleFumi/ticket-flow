<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                <div
                    class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="text-gray-900 dark:text-gray-100 text-lg font-medium">
                        @if ($visibleTickets->count() > 0)
                            {{ __('Stai visualizzando la lista dei ticket aperti.') }}
                        @else
                            <p class="text-lg">Non ci sono nuovi ticket aperti. Ottimo lavoro! 🎉</p>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 items-center w-full sm:w-auto">
                        <div class="relative w-full sm:w-auto">
                            <input type="text" id="userSearch" placeholder="Cerca utente..."
                                class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <button type="button"
                                onclick="document.getElementById('userSearch').value=''; filterTickets();"
                                class="absolute right-3 top-1/2 -translate-y-1/2 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-600 dark:text-gray-200 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold focus:outline-none transition duration-150 ease-in-out">
                                &times;
                            </button>
                        </div>

                        <div class="relative w-full sm:w-auto">
                            <select id="dateFilter"
                                class="w-full px-4 py-2 rounded-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out appearance-none pr-8">
                                <option value="all">Tutte le date</option>
                                <option value="7">Ultima settimana</option>
                                <option value="14">Ultime 2 settimane</option>
                                <option value="30">Ultimo mese</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                <!-- Icona per la select, se necessaria -->
                            </div>
                        </div>

                        <div class="relative w-full sm:w-auto">
                            <select id="reopenFilter"
                                class="w-full px-4 py-2 rounded-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out appearance-none pr-8">
                                <option value="all">Tutti i ticket</option>
                                <option value="reopened">Solo riaperti</option>
                                <option value="not_reopened">Non riaperti</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                <!-- Icona per la select, se necessaria -->
                            </div>
                        </div>


                        @if (isset($technician) && ($technician->is_admin || $technician->is_superAdmin))
                            <div class="relative w-full sm:w-auto">
                                <select id="reportFilter"
                                    class="w-full px-4 py-2 rounded-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out appearance-none pr-8">
                                    <option value="all">Tutte le segnalazioni</option>
                                    <option value="reported">Solo segnalati</option>
                                    <option value="not_reported">Non segnalati</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                    <!-- Icona per la select, se necessaria -->
                                </div>
                            </div>
                        @endif

                        <!-- NUOVO PULSANTE RESET FILTRI -->
                        <a href="{{ route('tickets.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-full shadow-md hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out whitespace-nowrap">
                            Reset Filtri
                        </a>
                        <!-- FINE NUOVO PULSANTE -->
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="ticketContainer">


                    @forelse ($visibleTickets as $ticket)
                        <div class="ticket-card bg-white dark:bg-gray-700 shadow-lg rounded-2xl p-6 border border-gray-200 dark:border-gray-600 flex flex-col justify-between transform hover:scale-105 transition-all duration-300 ease-in-out"
                            data-is-reported="{{ $ticket->is_reported ? 'true' : 'false' }}"
                            data-is-reopened="{{ $ticket->is_reopened ? 'true' : 'false' }}"
                            data-date="{{ $ticket->created_at->format('Y-m-d') }}"
                            data-userfullname="{{ strtolower($ticket->user->nome . ' ' . $ticket->user->cognome) }}">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">
                                    {{ $ticket['titolo'] }}</h3>
                                <p class="text-gray-700 dark:text-gray-300 text-base mb-4 line-clamp-3">
                                    {{ $ticket->commento }}</p>
                            </div>

                            <div
                                class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-2">
                                <p>
                                    <strong>Aperto da:</strong>
                                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ $ticket->user->nome }}
                                        {{ $ticket->user->cognome }}</span>
                                </p>
                                <p>
                                    <strong>Data apertura:</strong>
                                    <span
                                        class="font-medium text-gray-800 dark:text-gray-200">{{ $ticket['created_at']->format('d/m/Y H:i') }}</span>
                                </p>
                            </div>

                            <div class="mt-5 flex items-center justify-between">
                                <div>
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if ($ticket->is_reopened && $ticket->status->titolo === 'Aperto') bg-yellow-400 text-black dark:bg-yellow-400 dark:text-black
                                        @elseif ($ticket->status->titolo === 'Aperto') bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100
                                        @elseif ($ticket->status->titolo === 'In Lavorazione') bg-yellow-100 text-yellow-700 dark:bg-yellow-700 dark:text-yellow-100
                                        @else bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100 @endif">

                                        @if ($ticket->is_reopened && $ticket->status->titolo === 'Aperto')
                                            Riaperto
                                        @else
                                            {{ ucfirst($ticket->status->titolo) }}
                                        @endif
                                    </span>

                                    @if ($ticket->is_reported)
                                        <span
                                            class="relative group ms-2 px-2.5 py-0.5 rounded-full bg-yellow-100 text-yellow-700 inline-flex items-center justify-center">
                                            <i class="bi bi-exclamation-triangle-fill text-xs"></i>
                                            <span
                                                class="absolute -top-8 left-1/2 -translate-x-1/2 scale-0 group-hover:scale-100 transition-transform bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-md z-10 whitespace-nowrap">
                                                Ticket reportato
                                            </span>
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('tickets.show', $ticket) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-full shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                                    Dettagli
                                    <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-8 text-center text-gray-600 dark:text-gray-400">
                            <p class="text-lg">Non ci sono ticket aperti che corrispondono ai criteri di filtro.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="{{ asset('js/ticket-index-page.js') }}"></script>
