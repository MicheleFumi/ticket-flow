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
    