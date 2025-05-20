<x-app-layout>
    <x-slot name="header">
        <div class="d flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lista Tecnici') }}
            </h2>
    @if(auth()->check() && auth()->user()->is_admin)
            <button class="bg-blue-500 px-4 py-2 rounded">Aggiungi Tecnico</button>
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
                                    {{-- <td class="px-6 py-4 whitespace-nowrap">{{ $technician->is_avaible }}</td> --}}
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
</x-app-layout>
