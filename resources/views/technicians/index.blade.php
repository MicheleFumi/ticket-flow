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

    <div id="addTechnicianModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
        <div class="relative p-5 border w-1/2 max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-700"> 
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Aggiungi Nuovo Tecnico</h3>
                <button id="closeModalButton" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 text-2xl font-bold leading-none align-baseline">&times;</button>
            </div>

            <div class="mt-4 text-gray-900 dark:text-gray-100">
                <input type="text" id="userSearchInput" placeholder="Cerca per nome o cognome..." class="mb-4 p-2 w-full border rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">

                <div class="max-h-64 overflow-y-auto border rounded-md dark:border-gray-600"> 
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600" id="usersTable">
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                            @if(isset($users) && $users->count() > 0)
                                @foreach($users as $user)
                                    <tr class="user-row">
                                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <span class="user-name">{{ $user->nome }}</span> <span class="user-lastname">{{ $user->cognome ?? '' }}</span> 
                                            <br>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</span>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                                            <form method="POST" action="{{route("user-to-technician")}}">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <button type="submit" class="add-to-tech-btn bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded-md text-xs focus:outline-none focus:shadow-outline transition duration-150 ease-in-out" data-user-id="{{ $user->id }}">
                                                    Aggiungi
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" class="px-4 py-2 text-sm text-gray-500 text-center dark:text-gray-400">Nessun utente trovato.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openModalButton = document.getElementById('openModalButton');
            const closeModalButton = document.getElementById('closeModalButton');
            const addTechnicianModal = document.getElementById('addTechnicianModal');
            const userSearchInput = document.getElementById('userSearchInput');
            const usersTableBody = document.querySelector('#usersTable tbody');

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

            //searchbar
            userSearchInput.addEventListener('keyup', function() {
                const searchValue = userSearchInput.value.toLowerCase();
                const userRows = usersTableBody.querySelectorAll('.user-row');

                userRows.forEach(row => {
                    const userName = row.querySelector('.user-name').textContent.toLowerCase();
                    const userLastname = row.querySelector('.user-lastname').textContent.toLowerCase(); 
                    const userEmail = row.querySelector('span.text-xs').textContent.toLowerCase();

                    if (userName.includes(searchValue) || userLastname.includes(searchValue) || userEmail.includes(searchValue)) {
                        row.style.display = ''; // Mostra la riga
                    } else {
                        row.style.display = 'none'; // Nasconde la riga
                    }
                });
            });
        });
    </script>
</x-app-layout>
