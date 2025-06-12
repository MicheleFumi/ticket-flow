<div id="createNewTechnicianModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
        <div class="relative p-5 border w-1/2 max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-700">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Crea Nuovo Tecnico</h3>
                <button id="closeCreateNewTechnicianModal"
                    class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 text-2xl font-bold leading-none align-baseline">&times;</button>
            </div>

            <div class="mt-4 text-gray-900 dark:text-gray-100">
                <form method="POST" action="{{ route('technician.create') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="new_technician_nome"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                        <input type="text" id="new_technician_nome" name="nome" value="{{ old('nome') }}"
                            required autocomplete="given-name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100
                            @error('nome') border-red-500 @enderror">
                        @error('nome')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="new_technician_cognome"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cognome</label>
                        <input type="text" id="new_technician_cognome" name="cognome"
                            value="{{ old('cognome') }}" required autocomplete="family-name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100
                            @error('cognome') border-red-500 @enderror">
                        @error('cognome')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="new_technician_email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" id="new_technician_email" name="email" value="{{ old('email') }}"
                            required autocomplete="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100
                            @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ 'Email già esistente o non valida' }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="new_technician_email_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conferma Email</label>
                        <input type="email" id="new_technician_email_confirmation" name="email_confirmation"
                            value="{{ old('email_confirmation') }}" required autocomplete="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100
                            @error('email_confirmation') border-red-500 @enderror">
                        @error('email_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ 'Le email non corrispondono' }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="new_technician_telefono"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefono
                        </label>
                        <input type="text" id="new_technician_telefono" name="telefono"
                            value="{{ old('telefono') }}" required autocomplete="tel"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100
                            @error('telefono') border-red-500 @enderror">
                        @error('telefono')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="new_technician_password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <input type="password" id="new_technician_password" name="password" required
                            autocomplete="new-password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100
                            @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">
                                {{ 'La password deve contenere almeno 8 caratteri, un numero e una lettera maiuscola.' }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="new_technician_password_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conferma
                            Password</label>
                        <input type="password" id="new_technician_password_confirmation"
                            name="password_confirmation" required autocomplete="new-password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100
                            @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ 'Le password non corrispondono' }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                            Crea Tecnico
                        </button>
                        <button type="button" id="cancelCreateNewTechnicianButton"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-100">
                            Annulla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    