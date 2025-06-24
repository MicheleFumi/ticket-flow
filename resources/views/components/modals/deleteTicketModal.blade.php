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