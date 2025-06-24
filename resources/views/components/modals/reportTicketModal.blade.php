<!-- Modale per reportare ticket -->
        <div id="reportTicketModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto backdrop-blur-sm h-full w-full hidden z-50 flex items-center justify-center">
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