@props(['status'])

@if ($status === 'infected')
    <span class="text-xs text-red-500">(diblokir: terdeteksi virus)</span>
@elseif ($status === 'failed')
    <span class="text-xs text-red-500">(gagal diproses, silakan upload ulang)</span>
@else
    <span class="text-xs text-yellow-500">(processing...)</span>
@endif
