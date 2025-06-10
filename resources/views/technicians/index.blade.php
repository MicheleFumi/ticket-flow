<x-app-layout>
    <x-slot name="header">
        <div class="d flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lista Tecnici') }}
            </h2>
            <div class="d flex items-center gap-2">
                @if (auth()->check() && auth()->user()->is_superadmin)
                    <button id="openAddAdminModalButton"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-2 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">Aggiungi
                        Admin</button>
                @endif
                @if (auth()->check() && auth()->user()->is_admin)
                    <button id="openAddTechnicianModalButton"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-2 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">Aggiungi
                        Tecnico</button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12 @container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    @if (isset($technicians) && $technicians->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nome Completo
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th
                                        class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Disponibilità
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Azioni
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @foreach ($technicians as $technician)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                                            {{ $technician->nome }} {{ $technician->cognome }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap dark:text-white">
                                            {{ $technician->email }}
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap ">
                                            @if ($technician->is_available === 1)
                                                <span class="inline-block h-3 w-3 rounded-full bg-green-500"
                                                    title="Disponibile"></span>
                                            @else
                                                <span class="inline-block h-3 w-3 rounded-full bg-red-500"
                                                    title="Non Disponibile"></span>
                                            @endif
                                        </td>

                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <div class="flex justify-end space-x-2">

                                                @if (auth()->check())
                                                    <div class="flex justify-end space-x-2">
                                                        {{-- superadmin --}}
                                                        @if (auth()->user()->is_superadmin)
                                                            @if ($technician->is_admin && !$technician->is_superadmin)
                                                                <button data-id="{{ $technician->id }}"
                                                                    data-nome="{{ $technician->nome }}"
                                                                    data-cognome="{{ $technician->cognome }}"
                                                                    class="openRemoveAdminModalButton bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 mx-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                                    Rimuovi Admin
                                                                </button>
                                                            @endif

                                                            @if (!$technician->is_superadmin && !$technician->is_admin)
                                                                <button data-id="{{ $technician->id }}"
                                                                    data-nome="{{ $technician->nome }}"
                                                                    data-cognome="{{ $technician->cognome }}"
                                                                    class="openRemoveTechnicianModalButton bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 mx-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                                    Rimuovi Tecnico
                                                                </button>
                                                            @endif

                                                            {{-- admin --}}
                                                        @elseif (auth()->user()->is_admin)
                                                            @if (!$technician->is_admin && !$technician->is_superadmin)
                                                                <button data-id="{{ $technician->id }}"
                                                                    data-nome="{{ $technician->nome }}"
                                                                    data-cognome="{{ $technician->cognome }}"
                                                                    class="openRemoveTechnicianModalButton bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 mx-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                                    Rimuovi Tecnico
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __('Nessun tecnico trovato.') }}
                        </div>
                    @endif
                </div>
                <div class="p-2 text-gray-900 dark:text-gray-100">

                </div>
            </div>
        </div>
    </div>


    {{-- Modale per Aggiungere Admin --}}
    <div id="addAdminModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
        <div class="relative p-5 border w-1/2 max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-700">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Aggiungi Admin</h3>
                <button id="closeAddAdminModal"
                    class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 text-2xl font-bold leading-none align-baseline">&times;</button>
            </div>

            <div class="mt-4 text-gray-900 dark:text-gray-100">
                <input type="text" id="adminSearchInput" placeholder="Cerca per nome, cognome o email..."
                    class="mb-4 p-2 w-full border rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">

                <div class="max-h-64 overflow-y-auto border rounded-md dark:border-gray-600">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600" id="adminAddTable">
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                            @if (isset($nonAdminTechnicians) && $nonAdminTechnicians->count() > 0)
                                @foreach ($nonAdminTechnicians as $technician)
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
                                            <form method="POST" action="{{ route('technician-to-admin') }}">
                                                @csrf
                                                <input type="hidden" name="technician_id"
                                                    value="{{ $technician->id }}">
                                                <button type="submit"
                                                    class="add-to-tech-btn bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded-md text-xs focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                    Promuovi
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2"
                                        class="px-4 py-2 text-sm text-gray-500 text-center dark:text-gray-400">Nessun
                                        tecnico trovato da promuovere.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modale per Rimuovere Admin --}}
    <div id="removeAdminModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
        <div class="relative p-5 border w-1/2 max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-700">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Rimuovi Ruolo
                    Amministratore</h3>
                <button id="closeRemoveAdminModalButton"
                    class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 text-2xl font-bold leading-none align-baseline">&times;</button>
            </div>

            <div class="mt-4 text-gray-900 dark:text-gray-100 text-center">
                <p class="text-base">Sei sicuro di voler rimuovere il ruolo di amministratore per
                    <strong id="adminNameToRemove"></strong>?
                </p>

                <div class="mt-6 flex justify-center space-x-4">
                    <form id="confirmRemoveAdminForm" method="POST" action="{{ route('admin-to-technician') }}">
                        @csrf
                        <input type="hidden" name="technician_id" id="hiddenAdminIdToRemove">
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                            Sì
                        </button>
                    </form>
                    <button type="button" id="cancelRemoveAdminButton"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-100">
                        No
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modale per Aggiungere Tecnico --}}
    <div id="addTechnicianModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
        <div class="relative p-5 border w-1/2 max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-700">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Aggiungi Nuovo Tecnico
                </h3>
                <button id="closeAddModalButton"
                    class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 text-2xl font-bold leading-none align-baseline">&times;</button>
            </div>

            <div class="mt-4 text-gray-900 dark:text-gray-100">
                <input type="text" id="userSearchInput" placeholder="Cerca per nome o cognome..."
                    class="mb-4 p-2 w-full border rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">

                <div class="max-h-64 overflow-y-auto border rounded-md dark:border-gray-600">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600" id="usersTable">
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                            @if (isset($users) && $users->count() > 0)
                                @foreach ($users as $user)
                                    <tr class="user-row">
                                        <td
                                            class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <span class="user-name">{{ $user->nome }}</span> <span
                                                class="user-lastname">{{ $user->cognome ?? '' }}</span>
                                            <br>
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</span>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium">
                                            <form method="POST" action="{{ route('user-to-technician') }}">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <button type="submit"
                                                    class="add-to-tech-btn bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded-md text-xs focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                    Aggiungi
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2"
                                        class="px-4 py-2 text-sm text-gray-500 text-center dark:text-gray-400">Nessun
                                        utente trovato.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modale per Rimuovere Tecnico --}}
    <div id="removeTechnicianModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
        <div class="relative p-5 border w-1/2 max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-700">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Rimuovi Tecnico</h3>
                <button id="closeRemoveTechnicianModalButton"
                    class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 text-2xl font-bold leading-none align-baseline">&times;</button>
            </div>

            <div class="mt-4 text-gray-900 dark:text-gray-100 text-center">
                <p class="text-base">Sei sicuro di voler rimuovere il ruolo di tecnico per
                    <strong id="technicianNameToRemove"></strong>?
                </p>

                <div class="mt-6 flex justify-center space-x-4">
                    <form id="confirmRemoveTechnicianForm" method="POST"
                        action="{{ route('technician-to-user') }}">
                        @csrf
                        <input type="hidden" name="technician_id" id="hiddenTechnicianIdToRemove">
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                            Sì
                        </button>
                    </form>
                    <button type="button" id="cancelRemoveTechnicianButton"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-100">
                        No
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Elementi Aggiungi Tecnico
            const openAddModalButton = document.getElementById('openAddTechnicianModalButton');
            const closeAddModalButton = document.getElementById('closeAddModalButton');
            const addTechnicianModal = document.getElementById('addTechnicianModal');
            const userSearchInput = document.getElementById('userSearchInput');
            const usersTableBody = document.querySelector('#usersTable tbody');

            //Elementi Rimuovi Tecnico
            const openRemoveTechnicianModalButtons = document.querySelectorAll('.openRemoveTechnicianModalButton');
            const closeRemoveTechnicianModalButton = document.getElementById('closeRemoveTechnicianModalButton');
            const removeTechnicianModal = document.getElementById('removeTechnicianModal');
            const technicianNameToRemove = document.getElementById('technicianNameToRemove');
            const hiddenTechnicianIdToRemove = document.getElementById('hiddenTechnicianIdToRemove');
            const cancelRemoveTechnicianButton = document.getElementById('cancelRemoveTechnicianButton');


            // Elementi Aggiungi Admin
            const openAddAdminModalButton = document.getElementById('openAddAdminModalButton');
            const closeAddAdminModalButton = document.getElementById('closeAddAdminModal');
            const addAdminModal = document.getElementById('addAdminModal');
            const adminSearchInput = document.getElementById('adminSearchInput');
            const adminAddTableBody = document.querySelector('#adminAddTable tbody');

            // Elementi Rimuovi Admin
            const openRemoveAdminModalButtons = document.querySelectorAll('.openRemoveAdminModalButton');
            const removeAdminModal = document.getElementById('removeAdminModal');
            const closeRemoveAdminModalButton = document.getElementById('closeRemoveAdminModalButton');
            const cancelRemoveAdminButton = document.getElementById('cancelRemoveAdminButton');
            const adminNameToRemove = document.getElementById('adminNameToRemove');
            const hiddenAdminIdToRemove = document.getElementById('hiddenAdminIdToRemove');


            // --- MODAL FUNCTIONS ---
            function openModal(modalElement) {
                modalElement.classList.remove('hidden');
                modalElement.classList.add('flex', 'items-center', 'justify-center');
            }

            function closeModal(modalElement) {
                modalElement.classList.add('hidden');
                modalElement.classList.remove('flex', 'items-center', 'justify-center');
            }

            // Aggiungi Tecnico
            openAddModalButton.addEventListener('click', () => openModal(addTechnicianModal));
            closeAddModalButton.addEventListener('click', () => closeModal(addTechnicianModal));
            addTechnicianModal.addEventListener('click', (event) => {
                if (event.target === addTechnicianModal) {
                    closeModal(addTechnicianModal);
                }
            });

            // Rimuovi Tecnico
            openRemoveTechnicianModalButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const nome = button.getAttribute('data-nome');
                    const cognome = button.getAttribute('data-cognome');

                    technicianNameToRemove.textContent = `${nome} ${cognome}`;
                    hiddenTechnicianIdToRemove.value = id;
                    openModal(removeTechnicianModal);
                });
            });

            closeRemoveTechnicianModalButton.addEventListener('click', () => {
                closeModal(removeTechnicianModal);
                technicianNameToRemove.textContent = '';
                hiddenTechnicianIdToRemove.value = '';
            });

            cancelRemoveTechnicianButton.addEventListener('click', () => {
                closeModal(removeTechnicianModal);
                technicianNameToRemove.textContent = '';
                hiddenTechnicianIdToRemove.value = '';
            });

            removeTechnicianModal.addEventListener('click', (event) => {
                if (event.target === removeTechnicianModal) {
                    closeModal(removeTechnicianModal);
                    technicianNameToRemove.textContent = '';
                    hiddenTechnicianIdToRemove.value = '';
                }
            });


            // Aggiungi Admin
            openAddAdminModalButton.addEventListener('click', () => openModal(addAdminModal));
            closeAddAdminModalButton.addEventListener('click', () => closeModal(addAdminModal));
            addAdminModal.addEventListener('click', (event) => {
                if (event.target === addAdminModal) {
                    closeModal(addAdminModal);
                }
            });

            // Rimuovi Admin
            openRemoveAdminModalButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const nome = button.getAttribute('data-nome');
                    const cognome = button.getAttribute('data-cognome');

                    adminNameToRemove.textContent = `${nome} ${cognome}`;
                    hiddenAdminIdToRemove.value = id;
                    openModal(removeAdminModal);
                });
            });

            closeRemoveAdminModalButton.addEventListener('click', () => {
                closeModal(removeAdminModal);
                adminNameToRemove.textContent = '';
                hiddenAdminIdToRemove.value = '';
            });

            cancelRemoveAdminButton.addEventListener('click', () => {
                closeModal(removeAdminModal);
                adminNameToRemove.textContent = '';
                hiddenAdminIdToRemove.value = '';
            });

            removeAdminModal.addEventListener('click', (event) => {
                if (event.target === removeAdminModal) {
                    closeModal(removeAdminModal);
                    adminNameToRemove.textContent = '';
                    hiddenAdminIdToRemove.value = '';
                }
            });


            // searchbar

            // utenti
            userSearchInput.addEventListener('keyup', function() {
                const searchValue = userSearchInput.value.toLowerCase();
                const userRows = usersTableBody.querySelectorAll('.user-row');

                userRows.forEach(row => {
                    const userName = row.querySelector('.user-name').textContent.toLowerCase();
                    const userLastname = row.querySelector('.user-lastname').textContent
                        .toLowerCase();
                    const userEmail = row.querySelector('span.text-xs').textContent.toLowerCase();

                    if (userName.includes(searchValue) || userLastname.includes(searchValue) ||
                        userEmail.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // tecnici
            adminSearchInput.addEventListener('keyup', function() {
                const searchValue = adminSearchInput.value.toLowerCase();
                const adminRows = adminAddTableBody.querySelectorAll('.technician-row');

                adminRows.forEach(row => {
                    const technicianName = row.querySelector('.technician-name').textContent
                        .toLowerCase();
                    const technicianLastname = row.querySelector('.technician-lastname').textContent
                        .toLowerCase();
                    const technicianEmail = row.querySelector('span.text-xs').textContent
                        .toLowerCase();

                    if (technicianName.includes(searchValue) || technicianLastname.includes(
                            searchValue) || technicianEmail.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>
