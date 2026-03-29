@props(['id', 'title', 'amountLabel', 'amountValue', 'actionUrl', 'actionText' => 'Lanjutkan'])

<!-- Komponen Modal Glassmorphism & Gold Theme -->
<div id="{{ $id }}" class="modal-backdrop">
    <div class="modal-content card-gold relative">
        <button type="button" onclick="closeModal('{{ $id }}')" style="position: absolute; top: 1.5rem; right: 1.5rem; background: transparent; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted); transition: 0.3s; padding: 0.5rem;" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-muted)'">&times;</button>
        
        <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 2rem;">
            <div style="background: var(--gold-glow); padding: 1rem; border-radius: 12px; border: 1px solid var(--gold-dark);">
                <i class="ph-fill ph-vault" style="color: var(--gold-primary); font-size: 2.2rem;"></i>
            </div>
            <div>
                <h2 class="title-gold" style="margin: 0; font-size: 1.5rem; line-height: 1.2;">{{ $title }}</h2>
                <p class="text-muted" style="margin: 0; font-size: 0.9rem;">Verifikasi Logika Basis Data 2</p>
            </div>
        </div>

        <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; text-align: center;">
            <p style="margin: 0 0 0.5rem 0; color: var(--text-main); font-size: 0.95rem;">{{ $amountLabel }}</p>
            <h1 style="color: var(--success); font-size: 2.5rem; margin: 0; letter-spacing: 1px; text-shadow: 0 0 10px var(--success-glow);">{{ $amountValue }}</h1>
            <p style="margin: 1.5rem 0 0 0; font-size: 0.85rem; color: var(--gold-light); display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="ph ph-lock-key"></i> Sistem akan otomatis mengunci Laba Pimpinan (30%) ke brankas digital. Lanjutkan?
            </p>
        </div>

        <form action="{{ $actionUrl }}" method="POST" style="display: flex; gap: 1rem;">
            @csrf
            <button type="button" onclick="closeModal('{{ $id }}')" class="btn btn-outline" style="flex: 1;">Batal</button>
            <button type="submit" class="btn btn-gold" style="flex: 1;"><i class="ph ph-check-circle"></i> {{ $actionText }}</button>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
    function openModal(id) {
        document.getElementById(id).classList.add('is-active');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('is-active');
    }
</script>
