<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista Ticket') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <!-- FILTRI E MESSAGGIO -->
                <div class="p-6 text-gray-900 dark:text-gray-100 flex items-center justify-between flex-wrap">

                    <div>
                        @if ($tickets->where('status.titolo', 'Aperto')->count() > 0)
                            {{ __('Stai visualizzando la lista dei ticket') }}
                        @else
                            {{ __('Non ci sono nuovi ticket') }}
                        @endif
                    </div>

                    <div class="flex gap-6 items-center">
                        <!-- Filtro report -->
                        <div class="flex flex-col">
                            <label for="reportFilter" class="mb-1 text-gray-700 dark:text-gray-300">
                                Filtra per report
                            </label>
                            <select id="reportFilter"
                                class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 px-2 py-1">
                                <option value="all">Tutti</option>
                                <option value="reported">Solo reportati</option>
                                <option value="not_reported">Non reportati</option>
                            </select>
                        </div>

                        <!-- Filtro data relativa -->
                        <div class="flex flex-col">
                            <label for="dateFilter" class="mb-1 text-gray-700 dark:text-gray-300">
                                Filtra per data
                            </label>
                            <select id="dateFilter"
                                class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 px-2 py-1">
                                <option value="all">Tutti</option>
                                <option value="7">Ultima settimana</option>
                                <option value="14">Ultime 2 settimane</option>
                                <option value="30">Ultimo mese</option>
                            </select>
                        </div>
                    </div>

                </div>


                <!-- LISTA TICKET -->
                <div class="p-2 text-gray-900 dark:text-gray-100">
                    <div class="space-y-4">

                        @if (!$technician->is_admin)
                            @foreach ($tickets as $ticket)
                                @if ($ticket->status->titolo === 'Aperto')
                                    <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 ticket-card"
                                        data-is-reported="{{ $ticket->is_reported ? 'true' : 'false' }}"
                                        data-date="{{ $ticket->created_at->format('Y-m-d') }}">
                                        <div class="flex items-center justify-between mb-2">
                                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $ticket['titolo'] }}
                                            </h2>
                                            <div>
                                                <span class="text-sm text-gray-500 dark:text-gray-200">Aperto da:

                                                    {{ $ticket->user->nome }} {{ $ticket->user->cognome }}

                                                </span>
                                                <span class="text-sm text-gray-500 dark:text-gray-200">Il:
                                                    {{ $ticket['created_at']->format('d/m/Y H:i') }}
                                                </span>
                                            </div>


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
                                    <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 ticket-card"
                                        data-is-reported="{{ $ticket->is_reported ? 'true' : 'false' }}"
                                        data-date="{{ $ticket->created_at->format('Y-m-d') }}">
                                        <div class="flex items-center justify-between mb-2">
                                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $ticket['titolo'] }}</h2>
                                            <div>
                                                <span class="text-sm text-gray-500 dark:text-gray-200">Aperto da:

                                                    {{ $ticket->user->nome }} {{ $ticket->user->cognome }}

                                                </span>
                                                <span class="text-sm text-gray-500 dark:text-gray-200">Il:
                                                    {{ $ticket['created_at']->format('d/m/Y H:i') }}
                                                </span>
                                            </div>
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
                                                    <span id="reportBadge"
                                                        class="inline-flex items-center rounded-full bg-yellow-100 text-yellow-700 mx-2 px-2 py-1 text-sm transition-all duration-300 overflow-hidden cursor-default"
                                                        style="width: 1.8rem;">
                                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                                        <span id="reportText"
                                                            class="ml-2 whitespace-nowrap opacity-0 transition-opacity duration-300">Ticket
                                                            segnalato</span>
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

<script>
    const reportFilter = document.getElementById('reportFilter');
    const dateFilter = document.getElementById('dateFilter');
    const ticketCards = document.querySelectorAll('.ticket-card');

    function filterTickets() {
        const reportValue = reportFilter.value;
        const dateValue = dateFilter.value;
        const now = new Date();

        ticketCards.forEach(card => {
            const isReported = card.dataset.isReported === 'true';
            const createdDate = new Date(card.dataset.date);

            // filtro report
            const matchReport =
                reportValue === 'all' ||
                (reportValue === 'reported' && isReported) ||
                (reportValue === 'not_reported' && !isReported);

            // filtro data
            let matchDate = true;
            if (dateValue !== 'all') {
                const daysAgo = parseInt(dateValue);
                const diffTime = now - createdDate;
                const diffDays = diffTime / (1000 * 60 * 60 * 24);
                matchDate = diffDays <= daysAgo;
            }

            card.style.display = (matchReport && matchDate) ? 'block' : 'none';
        });
    }

    reportFilter.addEventListener('change', filterTickets);
    dateFilter.addEventListener('change', filterTickets);
</script>
