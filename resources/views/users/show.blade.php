<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dettagli Utente: {{ $user->nome }} {{ $user->cognome }}
        </h2>
    </x-slot>

    <div class="py-12 @container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Informazioni Utente</h3>
                <p class="text-gray-700 dark:text-gray-200"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="text-gray-700 dark:text-gray-200"><strong>Telefono:</strong> {{ $user->telefono }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Ticket</h3>

                <div class="mb-4">
                    <label for="filter" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Filtra per stato:</label>
                    <select id="filter" class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                        <option value="tutti">Tutti</option>
                        <option value="aperti">Aperti</option>
                        <option value="in_lavorazione">In Lavorazione</option>
                        <option value="chiusi">Chiusi</option>
                    </select>
                </div>

                <div id="ticket-list">
                    @foreach($ticketsByStatus as $status => $tickets)
                        <div class="ticket-group" data-status="{{ $status }}">
                            <h4 class="font-semibold text-md mb-2 text-gray-800 dark:text-gray-100 capitalize">{{ str_replace('_', ' ', $status) }}</h4>
                            @forelse($tickets as $ticket)
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="block p-4 mb-2 border rounded-md bg-gray-100 dark:bg-gray-700 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                    <p><strong>Titolo:</strong> {{ $ticket->titolo }}</p>
                                    <div class="mt-2">
                                        <strong>Stato:</strong>
                                        <div class="inline-block px-2 py-1 rounded-full text-sm font-medium
                                            @if ($ticket->status->titolo === 'Aperto') bg-green-100 text-green-700
                                            @elseif($ticket->status->titolo === 'In Lavorazione') bg-yellow-100 text-yellow-700
                                            @else bg-red-100 text-red-700 @endif">
                                            {{ ucfirst($ticket->status->titolo) }}
                                        </div>
                                    </div>
                                    <p><strong>Creato il:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                </a>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">Nessun ticket.</p>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('filter').addEventListener('change', function () {
            const selected = this.value;
            document.querySelectorAll('.ticket-group').forEach(group => {
                group.style.display = (selected === 'tutti' || group.dataset.status === selected) ? 'block' : 'none';
            });
        });
    </script>
</x-app-layout>
