<!-- Modale per prendere in carico-->
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