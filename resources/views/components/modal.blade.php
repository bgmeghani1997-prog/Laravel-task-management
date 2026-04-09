@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

<div class="modal fade" id="{{ $name }}" tabindex="-1" aria-labelledby="{{ $name }}Label" aria-hidden="true" @if($show) data-bs-show="true" @endif>
    <div class="modal-dialog @if($maxWidth === 'lg') modal-lg @elseif($maxWidth === 'xl') modal-xl @endif">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
