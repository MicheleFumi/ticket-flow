<!-- Modale per visualizzare i logs del ticket -->
<div id="logsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-full max-w-2xl mx-auto p-6 relative">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Logs del Ticket</h2>

        <button id="closeLogsModalButton" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-2xl">
            &times;
        </button>

        @if($logs->isEmpty())
            <p class="text-gray-600 dark:text-gray-300">Nessun log disponibile per questo ticket.</p>
        @else
            <ul class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                @foreach ($logs as $log)
                    <li class="border rounded-lg p-4 bg-gray-100 dark:bg-gray-800 text-sm text-gray-800 dark:text-gray-200">
                        <div class="flex flex-wrap gap-2 mb-2">
                            @if (!$log->chiuso_da && !$log->data_chiusura && !$log->riaperto_da_user && !$log->riaperto_da_admin)
                                <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Prima Apertura</span>
                            @endif
                            @if (!$log->chiuso_da && !$log->data_chiusura && $log->riaperto_da_user)
                                <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Riaperto dal Cliente</span>
                            @endif
                            @if (!$log->chiuso_da && !$log->data_chiusura && $log->riaperto_da_admin)
                                <span class="inline-block bg-orange-100 text-orange-800 text-xs font-semibold px-2.5 py-0.5 rounded">Riaperto da Admin</span>
                            @endif
                            @if ($log->chiuso_da && $log->data_chiusura)
                                <span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Chiuso</span>
                            @endif
                        </div>

                        @if ($log->assegnato_a && $log->data_assegnazione)
                            <p>
                                <strong>Assegnato a:</strong>
                                {{ optional($log->assignedTechnician)->nome }} {{ optional($log->assignedTechnician)->cognome }}
                                il {{ $log->data_assegnazione->format('d/m/Y H:i') }}
                            </p>
                        @endif

                        @if ($log->riaperto_da_user && $log->data_riapertura)
                            <p>
                                <strong>Riaperto da utente:</strong>
                                {{ optional($log->userWhoReopened)->nome }} {{ optional($log->userWhoReopened)->cognome }}
                                il {{ $log->data_riapertura->format('d/m/Y H:i') }}
                            </p>
                        @endif

                        @if ($log->riaperto_da_admin && $log->data_riapertura)
                            <p>
                                <strong>Riaperto da admin:</strong>
                                {{ optional($log->adminWhoReopened)->nome }} {{ optional($log->adminWhoReopened)->cognome }}
                                il {{ $log->data_riapertura->format('d/m/Y H:i') }}
                            </p>
                        @endif

                        @if ($log->note_riapertura)
                            <p class="mt-1">
                                <strong>Note riapertura:</strong>
                                {{ $log->note_riapertura }}
                            </p>
                        @endif

                        @if ($log->chiuso_da && $log->data_chiusura)
                            <p>
                                <strong>Chiuso da:</strong>
                                {{ optional($log->technicianWhoClosed)->nome }} {{ optional($log->technicianWhoClosed)->cognome }}
                                il {{ $log->data_chiusura->format('d/m/Y H:i') }}
                            </p>
                        @endif

                        @if ($log->note_chiusura)
                            <p class="mt-1">
                                <strong>Note chiusura:</strong>
                                {{ $log->note_chiusura }}
                            </p>
                        @endif

                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 italic">
                            Log registrato il {{ $log->created_at->format('d/m/Y H:i') }}
                        </p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

