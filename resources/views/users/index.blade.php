<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lista Utenti') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 @container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">

                    <!-- Searchbar -->
                    <div class="p-4">
                        <input type="text" id="userSearch" placeholder="Cerca per nome o cognome"
                            class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white"
                        >
                    </div>

                    @if(isset($users) && $users->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome Completo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket Totali</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                            {{ $user->nome }} {{ $user->cognome }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                            {{ $user->tickets->count() }}
                                        </td>
                                        <td>
                                            <a href="{{ route('users.show', $user->id) }}" class="px-2 py-1 rounded-full bg-blue-500 text-white">Dettagli</a>
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

                </div>
            </div>
        </div>
    </div>

    <script>
        //searchbar
        document.getElementById('userSearch').addEventListener('input', function () {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const fullName = row.cells[0].textContent.toLowerCase();
                row.style.display = fullName.includes(query) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
