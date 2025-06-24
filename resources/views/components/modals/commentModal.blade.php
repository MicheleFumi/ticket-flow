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