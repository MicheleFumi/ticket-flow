<div id="exTechniciansModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto backdrop-blur-sm h-full w-full hidden z-50 flex items-center justify-center">
        <div class="relative p-5 border w-1/2 max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-700">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Ex Tecnici</h3>
                <button id="closeExTechniciansModal"
                    class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 text-2xl font-bold leading-none align-baseline">&times;</button>
            </div>

            <div class="mt-4 text-gray-900 dark:text-gray-100">
                <input type="text" id="exTechnicianSearchInput" placeholder="Cerca per nome, cognome o email..."
                    class="mb-4 p-2 w-full border rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">

                <div class="max-h-64 overflow-y-auto border rounded-md dark:border-gray-600">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600" id="exTechniciansTable">
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                            @if (isset($allTechnicians) && $allTechnicians->count() > 0)
                                @foreach ($allTechnicians as $technician)
                                    @if ($technician->still_active === 0)
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
                                                <form method="POST" action="{{ route('technician.restore') }}">
                                                    @csrf
                                                    <input type="hidden" name="technician_id" value="{{ $technician->id }}">
                                                    <button type="submit"
                                                        class="add-to-tech-btn bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded-md text-xs focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                                        Promuovi
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2"
                                        class="px-4 py-2 text-sm text-gray-500 text-center dark:text-gray-400">Nessun
                                        ex tecnico trovato.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>