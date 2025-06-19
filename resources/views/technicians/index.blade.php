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
                        Admin
                    </button>
                @endif
                @if (auth()->check() && auth()->user()->is_admin)
                    <button id="openCreateNewTechnicianModalButton"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-2 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">Aggiungi
                        Tecnico
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12 @container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                {{-- searchbar --}}
                <div class="p-6">
                    <input type="text" id="technicianSearchBar"
                        placeholder="Cerca tecnici per nome, cognome o email..."
                        class="p-2 w-full border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                </div>

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
                                        Ruolo
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
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200" id="techniciansTableBody">
                                @foreach ($technicians as $technician)
                                    <tr class="technician-row">
                                        <td class="px-6 py-4 whitespace-nowrap dark:text-white">

                                            <div>
                                                <span class="technician-name">{{ $technician->nome }}</span>
                                                <span class="technician-lastname">{{ $technician->cognome }}</span>
                                            </div>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap dark:text-white w-1 text-center">
                                            <x-role-badge :user="$technician" />

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap dark:text-white technician-email">
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
                                                                    class="openRemoveAdminModalButton min-w-[160px] bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 mx-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                                    Rimuovi Admin
                                                                </button>
                                                            @endif

                                                            @if (!$technician->is_superadmin && !$technician->is_admin)
                                                                <button data-id="{{ $technician->id }}"
                                                                    data-nome="{{ $technician->nome }}"
                                                                    data-cognome="{{ $technician->cognome }}"
                                                                    class="openRemoveTechnicianModalButton min-w-[160px] bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 mx-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                                    Rimuovi Tecnico
                                                                </button>
                                                            @endif

                                                            {{-- admin --}}
                                                        @elseif (auth()->user()->is_admin)
                                                            @if (!$technician->is_admin && !$technician->is_superadmin)
                                                                <button data-id="{{ $technician->id }}"
                                                                    data-nome="{{ $technician->nome }}"
                                                                    data-cognome="{{ $technician->cognome }}"
                                                                    class="openRemoveTechnicianModalButton min-w-[160px] bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 mx-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
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

            @if (auth()->check() && auth()->user()->is_superadmin)
                <div class="flex justify-center mt-6">
                    <button id="openExTechniciansModalButton"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Visualizza ex tecnici
                    </button>
                </div>
            @endif
        </div>
    </div>


    {{-- Modale per Aggiungere Admin --}}
    <x-modals.addAdminModal :nonAdminTechnicians="$nonAdminTechnicians" />

    {{--  Modale per ex tecnici --}}
    <x-modals.exTechniciansModal :allTechnicians="$allTechnicians" />


    {{-- Modale per Rimuovere Admin --}}
    <x-modals.removeAdminModal :technicians="$technicians" />

    {{-- Modale per creare un Tecnico --}}
    <x-modals.createNewTechnicianModal />


    {{-- Modale per Rimuovere Tecnico --}}
    <x-modals.removeTechnicianModal :technicians="$technicians" />


    <script src="{{ asset('js/technician-page.js') }}"></script>
</x-app-layout>
