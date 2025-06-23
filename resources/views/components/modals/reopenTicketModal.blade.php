@props(['ticket'])

<div id="reopenTicketModal" class="fixed z-50 inset-0 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>

        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full z-50">
            <form action="{{ route('tickets.reopen', $ticket->id) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <h2 class="text-xl font-bold text-gray-800">Riapri Ticket</h2>
                <p class="text-gray-600 text-sm">Inserisci una motivazione per la riapertura del ticket. Questo campo è obbligatorio.</p>

                <textarea name="ragione_riapertura" rows="4" required
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                    placeholder="Motivazione riapertura..."></textarea>

                <div class="flex justify-end space-x-2 pt-4">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
                        Conferma
                    </button>
                    <button type="button" onclick="closeReopenModal()"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">
                        Annulla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
