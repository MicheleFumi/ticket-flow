<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between mb-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lista Ticket') }}
            </h2>

            @if ($technician->is_admin && !isset($ticket['technician_id']))
                <div>
                    <button id="openModalButton"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Prendi In Carico
                    </button>

                    <button id="openAssignTicketTechnicianSearchModalButton"
                        class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Assegna
                        Tecnico</button>
                </div>
            @else
                @if ($technician->is_available === 0)
                    <button disabled
                        class="bg-blue-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed opacity-70 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Non Puoi Prendere Altri Ticket
                    </button>
                @endif
            @endif


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
                        <div class="bg-white shadow-md rounded-2xl p-4 border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h2 class="text-xl font-semibold text-gray-800">{{ $ticket['titolo'] }}</h2>
                                <div>

                                    <div class="text-sm text-gray-500"> Creato da: {{ $ticket->user->nome }}
                                        {{ $ticket->user->cognome }} il:
                                        {{ $ticket['created_at']->format('d/m/Y H:i') }}
                                    </div>

                                    @if ($ticket->status_id === 2)
                                        <div class="text-sm text-gray-500"> Preso in carico da:
                                            {{ $ticket->technician->nome }}
                                            {{ $ticket->technician->cognome }} il:
                                            {{ $ticket['data_assegnazione']->format('d/m/Y H:i') }}
                                        </div>
                                    @elseif($ticket->status_id === 3)
                                        <div class="text-sm text-gray-500"> Preso in carico da:
                                            {{ $ticket->technician->nome }}
                                            {{ $ticket->technician->cognome }} il:
                                            {{ $ticket['data_assegnazione']->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="text-sm text-gray-500"> Chiuso da:
                                            {{ $ticket->technician->nome }}
                                            {{ $ticket->technician->cognome }} il:
                                            {{ $ticket['data_chiusura']->format('d/m/Y H:i') }}
                                        </div>
                                    @endif

                                </div>
                            </div>
                            <p class="text-md text-gray-500 mb-2">{{ $ticket['commento'] }}</p>
                            @if (isset($ticket->images) && $ticket->images->count() > 0)
                                <div class="my-6">
                                    @foreach ($ticket->images as $image)
                                        <a href="{{ asset($image->file_path) }}" target="_blank" class="inline-block">
                                            <img src="{{ asset($image->file_path) }}"
                                                alt="Anteprima immagine {{ $loop->iteration }}"
                                                class="w-[100px] h-[100px] object-cover rounded hover:scale-105 transition">
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="my-3 text-black">Non ci sono foto da visualizzare</div>
                            @endif

                            <div class="flex items-center justify-between text-sm">
                                <div>
                                    <span
                                        class="px-2 py-1 rounded-full 
                                    @if ($ticket->status->titolo === 'Aperto') bg-green-100 text-green-700
                                    @elseif($ticket->status->titolo === 'In Lavorazione') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($ticket->status->titolo) }}
                                    </span>
                                    @if ($technician->is_admin && $ticket->is_reported)
                                        <span>
                                            <span
                                                class="inline-flex items-center overflow-hidden rounded-full bg-yellow-100 text-yellow-700 ms-2 px-2 py-1"
                                                onclick="openReportModal('{{ $ticket->commento_report }}')">
                                                <i
                                                    class="bi bi-exclamation-triangle-fill mr-1 not-italic hover:underline">
                                                    Visualizza Dettagli Segnalazione</i>

                                            </span>
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 ml-auto">
                                    @if ($technician->is_admin)
                                        <button id="openDeleteModalButton"
                                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-3 rounded">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    @endif
                                    @if (!$ticket->is_reported)
                                        <button id="openReportTicketModalButton"
                                            class="bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded text-sm items-center gap-2">
                                            <i class="bi bi-flag"></i>
                                        </button>
                                    @endif


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modale messaggio report -->
        <div id="commentModal"
            class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 hidden">

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md p-6 sm:p-8 relative">

                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-yellow-500"></i>
                    Segnalazione ticket
                </h2>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 font-semibold">Motivo della segnalazione:
                    </p>
                    <p id="reportReason" class="text-gray-700 dark:text-gray-200 text-base not-sr-onlyitalic">
                        {{-- Viene riempito via JS --}}
                    </p>
                </div>

                <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1 border-t pt-3 mt-3">
                    @if ($ticket->reportatoDa)
                        <div>
                            <span class="font-semibold">Tecnico:</span>
                            {{ $ticket->reportatoDa->nome }} {{ $ticket->reportatoDa->cognome }}
                        </div>
                    @else
                        <div class="not-italic text-gray-400">Tecnico non trovato</div>
                    @endif

                    <div>
                        <span class="font-semibold">Data:</span>
                        {{ \Carbon\Carbon::parse($ticket->report_date)->format('d/m/Y H:i') }}
                    </div>
                </div>

                <button onclick="closeReportModal()"
                    class="mt-6 bg-red-600 hover:bg-red-700 transition-all text-white font-semibold py-2 px-5 rounded-full shadow-md">
                    Chiudi
                </button>
            </div>
        </div>


        <!-- Modale per reportare ticket -->
        <div id="reportTicketModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
            <div class="relative p-5 border w-1/2 max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-700">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Segnala Ticket</h3>
                    <button id="closeReportTicketModalButton"
                        class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 text-2xl font-bold leading-none align-baseline">&times;</button>
                </div>

                <form method="POST" action="{{ route('tickets.report', $ticket) }}">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                    <div class="mt-4 text-gray-900 dark:text-gray-100">
                        <label for="commento_report" class="block mb-2 text-sm font-medium">Motivo della
                            segnalazione</label>
                        <input type="text" name="commento_report" id="commento_report"
                            placeholder="Scrivi il motivo della segnalazione..."
                            class="mb-4 p-2 w-full border rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500"
                            required>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md">
                                Invia Segnalazione
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modale di conferma TECNICO-->
        <div id="takeInChargeModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-auto my-auto mt-40 shadow-xl scale-95 transition-all duration-300">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">Vuoi prendere in
                    carico questo ticket?</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6 text-center">
                    Una volta preso in carico dovrai contattare il tuo supervisore per rimuoverlo.
                </p>
                <div class="flex justify-center space-x-4">
                    <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
                        @csrf
                        <input type="hidden" name="technician_id">
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                            Conferma
                        </button>
                    </form>
                    <button id="closeModalButton"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                        Annulla
                    </button>
                </div>
            </div>
        </div>

        <!-- Modale di assegnazione tecnico ADMIN ONLY-->
        <div id="assignTicketTechnicianSearchModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
            <div class="relative p-5 border w-1/2 max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-700">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Assegna Tecnico</h3>
                    <button id="closeAssignTicketTechnicianSearchModalButton"
                        class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 text-2xl font-bold leading-none align-baseline">&times;</button>
                </div>

                <div class="mt-4 text-gray-900 dark:text-gray-100">
                    <input type="text" id="assignTicketTechnicianSearchInput"
                        placeholder="Cerca per nome, cognome o email..."
                        class="mb-4 p-2 w-full border rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">

                    <div class="max-h-64 overflow-y-auto border rounded-md dark:border-gray-600">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600"
                            id="assignTicketTechniciansTable">
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                                @if (isset($technicianList) && $technicianList->count() > 0)
                                    @foreach ($technicianList as $technician)
                                        <tr class="technician-row">
                                            <td
                                                class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <span class="technician-name">{{ $technician->nome }}</span> <span
                                                    class="technician-lastname">{{ $technician->cognome ?? '' }}</span>
                                                <br>
                                                <span
                                                    class="text-xs text-gray-500 dark:text-gray-400">{{ $technician->email }}</span>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                                                <form method="POST"
                                                    action="{{ route('tickets.assignTo', $ticket) }}">
                                                    @csrf
                                                    <input type="hidden" name="technician_id"
                                                        value="{{ $technician->id }}">
                                                    <input type="hidden" name="ticket_id"
                                                        value="{{ $ticket->id }}">
                                                    <button type="submit"
                                                        class="remove-tech-btn bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded-md text-xs focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                                                        data-technician-id="{{ $technician->id }}">
                                                        Assegna
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2"
                                            class="px-4 py-2 text-sm text-gray-500 text-center dark:text-gray-400">
                                            Nessun tecnico trovato.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modale Conferma Eliminazione Ticket -->
        <div id="deleteTicketModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-auto my-auto mt-40 shadow-xl scale-95 transition-all duration-300">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 text-center">Sei sicuro di voler
                    eliminare questo ticket?</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6 text-center">
                    Questa azione è irreversibile. Il ticket non sarà più visibile.
                </p>
                <div class="flex justify-center space-x-4">
                    <form method="POST" action="{{ route('tickets.delete', $ticket) }}">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Elimina
                        </button>
                    </form>
                    <button id="closeDeleteModalButton"
                        class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                        Annulla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        /* MODALE PER LETTURA MOTIVO REPORT TICKET */
        function openReportModal(reason) {
            const modal = document.getElementById("commentModal");
            const reasonText = document.getElementById("reportReason");

            reasonText.textContent = reason;
            modal.classList.remove("hidden");
        }

        function closeReportModal() {
            const modal = document.getElementById("commentModal");
            modal.classList.add("hidden");
        }


        /* MODALE PER PRESA IN CARICO TICKET DEL TECNICO */
        document.addEventListener('DOMContentLoaded', function() {
            const openModalButton = document.getElementById('openModalButton');
            const closeModalButton = document.getElementById('closeModalButton');
            const takeInChargeModal = document.getElementById('takeInChargeModal');

            openModalButton.addEventListener('click', function() {
                takeInChargeModal.classList.remove('hidden');
                takeInChargeModal.classList.add('flex', 'items-center', 'justify-center');
            });

            closeModalButton.addEventListener('click', function() {
                takeInChargeModal.classList.add('hidden');
                takeInChargeModal.classList.remove('flex', 'items-center', 'justify-center');
            });

            takeInChargeModal.addEventListener('click', function(event) {
                if (event.target === takeInChargeModal) {
                    takeInChargeModal.classList.add('hidden');
                    takeInChargeModal.classList.remove('flex', 'items-center', 'justify-center');
                }
            });
        });

        /* MODALE PER ASSEGNAZIONE TICKET FATTO DA ADMIN SOLO */

        document.addEventListener('DOMContentLoaded', function() {
            // Elementi modale ricerca tecnico assegnazione ticket
            const openAssignTicketTechnicianSearchModalButton = document.getElementById(
                'openAssignTicketTechnicianSearchModalButton');
            const closeAssignTicketTechnicianSearchModalButton = document.getElementById(
                'closeAssignTicketTechnicianSearchModalButton');
            const assignTicketTechnicianSearchModal = document.getElementById('assignTicketTechnicianSearchModal');
            const assignTicketTechnicianSearchInput = document.getElementById('assignTicketTechnicianSearchInput');
            const assignTicketTechniciansTableBody = document.querySelector('#assignTicketTechniciansTable tbody');

            openAssignTicketTechnicianSearchModalButton.addEventListener('click', function() {
                assignTicketTechnicianSearchModal.classList.remove('hidden');
                assignTicketTechnicianSearchModal.classList.add('flex', 'items-center', 'justify-center');
            });

            closeAssignTicketTechnicianSearchModalButton.addEventListener('click', function() {
                assignTicketTechnicianSearchModal.classList.add('hidden');
                assignTicketTechnicianSearchModal.classList.remove('flex', 'items-center',
                    'justify-center');
            });

            assignTicketTechnicianSearchModal.addEventListener('click', function(event) {
                if (event.target === assignTicketTechnicianSearchModal) {
                    assignTicketTechnicianSearchModal.classList.add('hidden');
                    assignTicketTechnicianSearchModal.classList.remove('flex', 'items-center',
                        'justify-center');
                }
            });

            assignTicketTechnicianSearchInput.addEventListener('keyup', function() {
                const searchValue = assignTicketTechnicianSearchInput.value.toLowerCase();
                const technicianRows = assignTicketTechniciansTableBody.querySelectorAll('.technician-row');

                technicianRows.forEach(row => {
                    const technicianName = row.querySelector('.technician-name').textContent
                        .toLowerCase();
                    const technicianLastname = row.querySelector('.technician-lastname').textContent
                        .toLowerCase();
                    const technicianEmail = row.querySelector('span.text-xs').textContent
                        .toLowerCase();

                    if (
                        technicianName.includes(searchValue) ||
                        technicianLastname.includes(searchValue) ||
                        technicianEmail.includes(searchValue)
                    ) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });

        /* MODALE PER RIMOZIONE TICKET FATTO DA ADMIN SOLO */
        document.getElementById('openDeleteModalButton')?.addEventListener('click', function() {
            document.getElementById('deleteTicketModal').classList.remove('hidden');
        });
        document.getElementById('closeDeleteModalButton')?.addEventListener('click', function() {
            document.getElementById('deleteTicketModal').classList.add('hidden');
        });

        /* MODALE PER ASSEGNAZIONE REPORT TICKET */
        document.getElementById('openReportTicketModalButton')?.addEventListener('click', function() {
            document.getElementById('reportTicketModal').classList.remove('hidden');
        });
        document.getElementById('closeReportTicketModalButton')?.addEventListener('click', function() {
            document.getElementById('reportTicketModal').classList.add('hidden');
        });
    </script>
</x-app-layout>
