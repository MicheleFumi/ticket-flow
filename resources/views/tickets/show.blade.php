<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between mb-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lista Ticket') }}
            </h2>
            <!-- Bottone che apre il modale -->
            <button id="openModalButton"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                Prendi In Carico
            </button>
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
                                <span class="text-sm text-gray-500">{{ $ticket['created_at']->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-md text-gray-500 mb-2">{{ $ticket['commento'] }}</p>
                            <div class="flex items-center justify-between text-sm">
                                <span
                                    class="px-2 py-1 rounded-full
                                    @if ($ticket->status->titolo === 'Aperto') bg-green-100 text-green-700
                                    @elseif($ticket->status->titolo === 'In Lavorazione') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($ticket->status->titolo) }}
                                </span>
                                <form action="{{route("tickets.delete", $ticket)}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                    <button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-3 rounded"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modale di conferma -->
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
    </div>

    <!-- Script -->
    <script>
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
    </script>
</x-app-layout>
