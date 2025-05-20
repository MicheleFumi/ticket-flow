<x-app-layout>
    <x-slot name="header">
        <div class="d flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lista Tecnici') }}
            </h2>
    @if(auth()->check() && auth()->user()->is_admin)
            <button id="openModalButton" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">Aggiungi Tecnico</button>
    @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if(isset($technicians) && $technicians->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome Completo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disponibilità</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($technicians as $technician)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $technician->nome }}  {{$technician->cognome}}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $technician->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap ">
                                        @if($technician->is_avaible === 1)
                                            <span class="inline-block h-3 w-3 rounded-full bg-green-500" title="Disponibile"></span>
                                        @else
                                            <span class="inline-block h-3 w-3 rounded-full bg-red-500" title="Non Disponibile"></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __("Nessun utente trovato.") }}
                    </div>
                @endif
                <div class="p-2 text-gray-900 dark:text-gray-100">
                  
                </div>
            </div>
        </div>
    </div>

    <div id="addTechnicianModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-700">
            <div class="flex justify-end items-center pb-3">
                <button id="closeModalButton" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 text-2xl font-bold leading-none align-baseline">&times;</button>
            </div>
            <div class="text-center text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-semibold leading-6 mb-4">Aggiungi Nuovo Tecnico</h3>
                {{-- searchbar utenti + lista utenti con pulsante affianco --}}
                <h2>provaaa</h2>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openModalButton = document.getElementById('openModalButton');
            const closeModalButton = document.getElementById('closeModalButton');
            const addTechnicianModal = document.getElementById('addTechnicianModal');

            //apre il modale
            openModalButton.addEventListener('click', function() {
                addTechnicianModal.classList.remove('hidden');
                addTechnicianModal.classList.add('flex', 'items-center', 'justify-center'); // Centra il modale
            });

            //chiude il modale con la X
            closeModalButton.addEventListener('click', function() {
                addTechnicianModal.classList.add('hidden');
                addTechnicianModal.classList.remove('flex', 'items-center', 'justify-center');
            });

            //Chiude il modale cliccando fuori
            addTechnicianModal.addEventListener('click', function(event) {
                if (event.target === addTechnicianModal) {
                    addTechnicianModal.classList.add('hidden');
                    addTechnicianModal.classList.remove('flex', 'items-center', 'justify-center');
                }
            });
        });
    </script>
</x-app-layout>
