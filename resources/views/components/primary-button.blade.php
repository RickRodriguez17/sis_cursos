<button {{ $attributes->merge(['type' => 'submit', 'class' => 'erp-button-primary']) }}>
    <i class="bi bi-check2"></i>{{ $slot }}
</button>
