<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <a class="text-black dark:text-white hover:underline" href="{{ route('dashboard.history') }}">
                STORICO TICKET
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if ($tickets->count() > 0)
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __('Stai visualizzando la tua Dashboard') }}
                    </div>
                    <div class="p-2 text-gray-900 dark:text-gray-100 space-y-4">
                        @foreach ($tickets as $ticket)
                            <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="text-xl font-semibold text-gray-800">{{ $ticket->titolo }}</h2>
                                    <div class="text-sm text-gray-500">
                                        Creato da: {{ $ticket->user->nome }} {{ $ticket->user->cognome }} il:
                                        {{ $ticket->created_at->format('d/m/Y H:i') }} <br>
                                        Assegnato a: {{ $ticket->latestLog->technician->nome }} {{ $ticket->latestLog->technician->cognome }}
                                        il:
                                        {{ $ticket->latestLog->data_assegnazione->format('d/m/Y H:i') }}
                                    </div>
                                </div>

                                <p class="text-md text-gray-500 mb-2">{{ $ticket->commento }}</p>

                                @if ($ticket->images && $ticket->images->count() > 0)
                                    <div class="my-6 flex flex-wrap gap-2">
                                        @foreach ($ticket->images as $image)
                                            <a href="{{ asset($image->file_path) }}" target="_blank">
                                                <img src="{{ asset($image->file_path) }}"
                                                    alt="Img {{ $loop->iteration }}"
                                                    class="w-[50px] h-[50px] object-cover rounded hover:scale-105 transition" />
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="my-3 text-black">Non ci sono foto da visualizzare</div>
                                @endif

                                <div class="flex items-center justify-between text-sm mt-4">
                                    <div
                                        class="px-2 py-1 rounded-full
                                        @if ($ticket->status->titolo === 'Aperto') bg-green-100 text-green-700
                                        @elseif($ticket->status->titolo === 'In Lavorazione') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($ticket->status->titolo) }}
                                    </div>

                                    <div class="flex gap-2">
                                        <button type="button"
                                            class="px-2 py-1 rounded bg-yellow-500 text-gray-900 remove-technician-btn"
                                            data-ticket-id="{{ $ticket->id }}"
                                            data-technician-id="{{ $ticket->latestLog->technician->id }}"
                                            data-technician-name="{{ $ticket->latestLog->technician->nome }} {{ $ticket->latestLog->technician->cognome }}"> 
                                            Rimuovi Assegnazione
                                        </button>


                                        <button type="button"
                                            class="px-2 py-1 rounded bg-red-600 text-white close-ticket-btn"
                                            data-ticket-id="{{ $ticket->id }}"
                                            data-ticket-title="{{ $ticket->titolo }}">
                                            Chiudi Ticket
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __("Nessun ticket trovato con lo stato 'In Lavorazione' o assegnato a te.") }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modali conferma --}}
    @foreach ($tickets as $ticket)
        <div id="modal-{{ $ticket->id }}"
            class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-lg w-full p-6 relative">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                    Chiudi Ticket: <span class="modal-ticket-title"></span>
                </h3>
                <form method="POST" action="{{ route('tickets.close') }}">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                    <label for="note_chiusura_{{ $ticket->id }}" class="block text-gray-700 dark:text-gray-300 mb-2">
                        Note di chiusura (opzionali)
                    </label>
                    <textarea name="note_chiusura" id="note_chiusura_{{ $ticket->id }}" rows="4"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                        placeholder="Inserisci eventuali note..."></textarea>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button"
                            class="px-4 py-2 rounded-md bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 modal-close-btn">
                            Annulla
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
                            Conferma chiusura
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <div id="removeTechnicianModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm">
        <div
            class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-auto my-auto mt-40 shadow-xl scale-95 transition-all duration-300">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">
                Vuoi rimuovere questo tecnico?
            </h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6 text-center">
                Una volta rimosso, non sarà più assegnato a questo ticket.
            </p>

            <div class="flex justify-center space-x-4">
                <form id="removeTechnicianForm" method="POST" action="{{ route('tickets.unassign') }}"> 
                    @csrf
                    <input type="hidden" name="technician_id" id="technicianId">
                    <input type="hidden" name="ticket_id" id="ticketId">
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                            Conferma
                    </button>
                </form>
                <button id="closeRemoveTechnicianModal"
                    class="bg-red-500 hover:bg-red-600 text-gray-50 font-semibold py-2 px-4 rounded">
                    Annulla
                </button>
            </div>
        </div>
    </div>


    <script>

        //modale chiusura ticket
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.close-ticket-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.ticketId;
                    const title = button.dataset.ticketTitle;
                    const modal = document.getElementById(`modal-${id}`);
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.querySelector('.modal-ticket-title').textContent = title;
                    }
                });
            });

            document.querySelectorAll('.modal-close-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const modal = button.closest('[id^="modal-"]');
                    if (modal) modal.classList.add('hidden');
                });
            });

            document.querySelectorAll('[id^="modal-"]').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) modal.classList.add('hidden');
                });
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === "Escape") {
                    document.querySelectorAll('[id^="modal-"]').forEach(modal => {
                        if (!modal.classList.contains('hidden')) modal.classList.add('hidden');
                    });
                }
            });
        });

        //modale rimozione tecnico
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.remove-technician-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const ticketId = button.dataset.ticketId;
                    const technicianId = button.dataset.technicianId;

                    document.getElementById('technicianId').value = technicianId;
                    document.getElementById('ticketId').value = ticketId;

                    document.getElementById('removeTechnicianModal').classList.remove('hidden');
                });
            });

            document.getElementById('closeRemoveTechnicianModal').addEventListener('click', () => {
                document.getElementById('removeTechnicianModal').classList.add('hidden'); 
            });

            document.getElementById('removeTechnicianModal').addEventListener('click',(e)=>{
                if (e.target===document.getElementById('removeTechnicianModal')) {
                    document.getElementById('removeTechnicianModal').classList.add('hidden'); 
                }
            });

            document.addEventListener('keydown',(e)=>{
                if (e.key==='Escape') {
                    document.getElementById('removeTechnicianModal').classList.add('hidden'); 
                }
            });
        });


    </script>
</x-app-layout>
