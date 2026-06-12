{{-- Global Primary Button — Maroon & Gold Design System --}}
<button {{ $attributes->merge([
    'type'  => 'submit',
    'class' => 'arh-btn-primary',
]) }}>
    {{ $slot }}
</button>

<style>
/* Defined once globally — reusable everywhere */
.arh-btn-primary {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 10px 22px;
    background: linear-gradient(135deg, #8B1A2A, #5C0E19);
    color: #fcd400;
    border: 1px solid rgba(197, 160, 40, 0.3);
    border-radius: 10px;
    font-family: 'Inter', 'Manrope', sans-serif;
    font-weight: 700; font-size: 0.82rem;
    text-transform: uppercase; letter-spacing: 0.08em;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(139, 26, 42, 0.25);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    text-decoration: none; white-space: nowrap;
}
.arh-btn-primary:hover {
    background: linear-gradient(135deg, #A82335, #70111F);
    transform: translateY(-1px);
    box-shadow: 0 8px 22px rgba(139, 26, 42, 0.35);
    color: #fcd400;
}
.arh-btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(139, 26, 42, 0.2);
}
.arh-btn-primary:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(197, 160, 40, 0.3), 0 4px 14px rgba(139, 26, 42, 0.25);
}
</style>
