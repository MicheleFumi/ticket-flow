@php
    $bgClass = match (true) {
        $isAdmin && $isSuperadmin => 'bg-yellow-400 dark:text-black text-white',
        $isAdmin && !$isSuperadmin => 'bg-orange-500 text-black',
        default => 'bg-rose-400 text-white',
    };

    $label = match (true) {
        $isAdmin && $isSuperadmin => 'Super Amministratore',
        $isAdmin && !$isSuperadmin => 'Amministratore',
        default => 'Tecnico',
    };
@endphp

<div class="rounded-full px-2 py-1 text-xs inline-block text-center min-w-[140px] max-w-[140px] {{ $bgClass }}">
    {{ $label }}
</div>
