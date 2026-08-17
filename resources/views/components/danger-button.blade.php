<button {{ $attributes->merge(['type' => 'submit', 'class' => 'erp-button-danger']) }}>
    <i class="bi bi-trash3"></i>{{ $slot }}
</button>
