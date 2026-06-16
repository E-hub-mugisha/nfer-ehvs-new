@php
    $icons = [
        'pending'      => 'bi-hourglass-split',
        'under-review' => 'bi-search',
        'resolved'     => 'bi-check-circle-fill',
        'rejected'     => 'bi-x-circle-fill',
    ];
    $icon  = $icons[$status] ?? 'bi-circle';
    $label = ucfirst(str_replace('_', ' ', $status));
@endphp

<span class="status-badge status-badge--{{ $status }}">
    <i class="bi {{ $icon }}"></i>
    {{ $label }}
</span>