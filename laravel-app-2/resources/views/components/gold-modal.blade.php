@props(['id', 'title', 'amountLabel', 'amountValue', 'actionUrl', 'actionText' => 'Lanjutkan'])

{{-- Komponen Modal Premium dengan Backdrop Blur & Gradasi Emas --}}
<div id="{{ $id }}" class="gold-modal-backdrop">
    <div class="gold-modal-box">
        {{-- Close button --}}
        <button type="button" onclick="closeModal('{{ $id }}')" class="gold-modal-close" aria-label="Tutup modal">
            <i class="bi bi-x-lg"></i>
        </button>

        {{-- Header --}}
        <div style="display:flex;gap:14px;align-items:center;margin-bottom:20px">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,rgba(197,160,40,0.2),rgba(139,26,42,0.15));border:1px solid rgba(197,160,40,0.3);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#C5A028;flex-shrink:0">
                <i class="bi bi-safe2-fill"></i>
            </div>
            <div>
                <h2 style="font-family:'Cormorant Garamond',serif;font-size:1.25rem;font-weight:700;color:#1A1817;margin:0;line-height:1.2">{{ $title }}</h2>
                <p style="margin:3px 0 0;font-size:0.7rem;color:#847B78">Verifikasi Logika Basis Data — Konfirmasi Tindakan</p>
            </div>
        </div>

        {{-- Amount display --}}
        <div style="background:linear-gradient(135deg,rgba(139,26,42,0.04),rgba(197,160,40,0.06));border:1px solid rgba(197,160,40,0.2);border-radius:14px;padding:20px;margin-bottom:20px;text-align:center">
            <p style="margin:0 0 6px;font-size:0.8rem;color:#847B78;text-transform:uppercase;letter-spacing:0.1em;font-weight:600">{{ $amountLabel }}</p>
            <div style="font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:700;color:#16a34a;margin:0;letter-spacing:0.5px">{{ $amountValue }}</div>
            <p style="margin:12px 0 0;font-size:0.72rem;color:#C5A028;display:flex;align-items:center;justify-content:center;gap:6px">
                <i class="bi bi-lock-fill" style="font-size:0.65rem"></i>
                Sistem akan otomatis mengunci Laba Pimpinan (30%) ke brankas digital. Lanjutkan?
            </p>
        </div>

        {{-- Action buttons --}}
        <form action="{{ $actionUrl }}" method="POST" style="display:flex;gap:10px">
            @csrf
            <button type="button" onclick="closeModal('{{ $id }}')"
                    style="flex:1;padding:12px;border-radius:10px;border:1.5px solid rgba(139,26,42,0.2);background:transparent;color:#8B1A2A;font-family:'Inter',sans-serif;font-weight:700;font-size:0.88rem;cursor:pointer;transition:all 0.35s cubic-bezier(0.16,1,0.3,1)"
                    onmouseover="this.style.background='rgba(139,26,42,0.05)'"
                    onmouseout="this.style.background='transparent'">
                Batal
            </button>
            <button type="submit"
                    style="flex:1;padding:12px;border-radius:10px;background:linear-gradient(135deg,#8B1A2A,#5C0E19);border:1px solid rgba(197,160,40,0.3);color:#fcd400;font-family:'Inter',sans-serif;font-weight:700;font-size:0.88rem;cursor:pointer;box-shadow:0 4px 16px rgba(139,26,42,0.3);transition:all 0.35s cubic-bezier(0.16,1,0.3,1);display:flex;align-items:center;justify-content:center;gap:7px"
                    onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 24px rgba(139,26,42,0.4)'"
                    onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 16px rgba(139,26,42,0.3)'">
                <i class="bi bi-check-circle-fill" style="font-size:0.9rem"></i>
                {{ $actionText }}
            </button>
        </form>
    </div>
</div>

<style>
.gold-modal-backdrop {
    display: none; position: fixed; inset: 0; z-index: 9900;
    background: rgba(30, 13, 10, 0.55);
    backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    align-items: center; justify-content: center;
    padding: 16px;
    animation: modalBgFade 0.3s cubic-bezier(0.16, 1, 0.3, 1) both;
}
.gold-modal-backdrop.is-active {
    display: flex;
}
.gold-modal-box {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(197,160,40,0.2);
    box-shadow: 0 24px 64px rgba(30,13,10,0.2), 0 0 0 1px rgba(197,160,40,0.08);
    padding: 24px;
    width: 100%; max-width: 420px;
    position: relative;
    animation: modalBoxIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
}
.gold-modal-close {
    position: absolute; top: 14px; right: 14px;
    width: 30px; height: 30px; border-radius: 8px;
    background: rgba(0,0,0,0.05); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem; color: #847B78;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.gold-modal-close:hover { background: rgba(239,68,68,0.1); color: #dc2626; }
@keyframes modalBgFade {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes modalBoxIn {
    from { opacity: 0; transform: scale(0.94) translateY(8px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
</style>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('is-active');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    const el = document.getElementById(id);
    el.style.opacity = '0';
    el.style.transition = 'opacity 0.2s';
    setTimeout(() => {
        el.classList.remove('is-active');
        el.style.opacity = '';
        el.style.transition = '';
        document.body.style.overflow = '';
    }, 200);
}
</script>
