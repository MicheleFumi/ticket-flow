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

                                    <div class="flex justify-end w-full">
                                        <button
                                            id="openLogsModalButton"
                                            class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-1 px-3 rounded shadow-md transition duration-300 ease-in-out mt-1">
                                            Visualizza tutti i Logs
                                        </button>
                                    </div>


                                    {{-- @if ($ticket->status_id === 2)
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
                                    @endif --}}

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
        <x-modals.commentModal :ticket="$ticket"/>

        <!-- Modale per reportare ticket -->
        <x-modals.reportTicketModal :ticket="$ticket"/>

        <!-- Modale per prendere in carico-->
        <x-modals.takeInChargeModal :ticket="$ticket"/>

        <!-- Modale di assegnazione tecnico ADMIN ONLY-->
        <x-modals.assignTicketTechnicianSearchModal :technicianList="$technicianList" :ticket="$ticket"/>

        <!-- Modale Conferma Eliminazione Ticket -->
        <x-modals.deleteTicketModal :ticket="$ticket"/>

        <!-- Modale per visualizzare i logs del ticket -->
        <x-modals.logsModal :logs="$logs" :ticket="$ticket"/>

    </div>

    <script src="{{ asset('js/ticket-show-page.js') }}"></script>
</x-app-layout>
