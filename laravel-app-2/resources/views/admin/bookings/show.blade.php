@extends('layouts.admin')

@section('title', 'Detail Booking – ART-HUB')
@section('page_title', 'Detail Booking')
@section('page_subtitle', 'Verifikasi detail pementasan dan kalkulasi laba.')

@section('content')

{{-- BACK NAV --}}
<div class="flex items-center gap-2 mb-5 subtitle-gold">
    <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-1.5 hover:text-[#8B1A2A] transition-colors" style="text-decoration:none;">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar
    </a>
    <span>/</span>
    <span style="color:#1A1817; font-weight:700;">Booking #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
</div>

<div class="flex flex-col lg:flex-row gap-6 items-start mb-8">

    {{-- KIRI: INFO BOOKING --}}
    <div class="flex-grow w-full space-y-5">
        <div class="card-gold overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color:rgba(197,160,40,0.2);">
                <h3 class="title-gold flex items-center gap-2" style="font-size:1.3rem;">
                    <i data-lucide="receipt" class="w-5 h-5 text-yellow-600"></i> Detail Pesanan
                </h3>
                @if($booking->status === 'pending')
                <button type="button" onclick="document.getElementById('modalEditJadwal').classList.remove('hidden');document.getElementById('modalEditJadwal').classList.add('flex');" class="arh-btn-secondary py-1 px-3 flex items-center gap-1 text-xs">
                    <i data-lucide="calendar-clock" class="w-3 h-3"></i> Nego Jadwal
                </button>
                @endif
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                <div>
                    <div class="subtitle-gold mb-1">Klien</div>
                    <div class="title-gold" style="font-size:1.2rem;">{{ $booking->client_name ?? $booking->client->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="subtitle-gold mb-1">Kontak Klien</div>
                    <div style="font-family:'Inter',sans-serif; color:#1A1817; font-weight:500;">{{ $booking->client_phone ?? '-' }}</div>
                </div>
                
                <div class="md:col-span-2"><hr style="border-color:rgba(197,160,40,0.2); margin:0;"></div>

                <div>
                    <div class="subtitle-gold mb-1">Jenis Pementasan</div>
                    <div style="font-family:'Inter',sans-serif; color:#1A1817; font-weight:700; text-transform:capitalize;">{{ str_replace('_', ' ', $booking->event_type) }}</div>
                </div>
                <div>
                    <div class="subtitle-gold mb-1">Tanggal</div>
                    <div style="font-family:'Inter',sans-serif; color:#1A1817; font-weight:700;">{{ \Carbon\Carbon::parse($booking->event_date)->isoFormat('dddd, D MMMM Y') }}</div>
                </div>
                
                <div class="md:col-span-2"><hr style="border-color:rgba(197,160,40,0.2); margin:0;"></div>

                <div>
                    <div class="subtitle-gold mb-1">Waktu</div>
                    <div style="font-family:'Inter',sans-serif; color:#1A1817; font-weight:700;">{{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->event_end)->format('H:i') }}</div>
                </div>
                <div>
                    <div class="subtitle-gold mb-1">Venue</div>
                    <div style="font-family:'Inter',sans-serif; color:#1A1817; font-weight:700;">{{ $booking->venue }}</div>
                </div>

                <div class="md:col-span-2"><hr style="border-color:rgba(197,160,40,0.2); margin:0;"></div>

                <div>
                    <div class="subtitle-gold mb-1">Sumber Booking</div>
                    <span class="badge-gold">
                        {{ strtoupper($booking->booking_source) }}
                    </span>
                </div>
                <div>
                    <div class="subtitle-gold mb-1">Status</div>
                    @php
                        $sm = [
                            'pending'   => ['label'=>'PENDING',   'cls'=>'badge-gold'],
                            'dp_paid'   => ['label'=>'DP DIBAYAR',   'cls'=>'badge-maroon'],
                            'confirmed' => ['label'=>'DIKONFIRMASI', 'cls'=>'badge-maroon'],
                            'completed' => ['label'=>'SELESAI', 'cls'=>'badge-green'],
                            'cancelled' => ['label'=>'BATAL', 'cls'=>'badge-gold border-red-500 text-red-600'],
                        ];
                        $st = $sm[$booking->status] ?? ['label'=>strtoupper($booking->status),'cls'=>'badge-gold'];
                    @endphp
                    <span class="{{ $st['cls'] }}">
                        {{ $st['label'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: PANEL TINDAK LANJUT / KEUANGAN --}}
    <div class="w-full lg:w-96 flex-shrink-0">
        
        {{-- PANEL KONFIRMASI JADWAL (Ditampilkan jika belum dikonfirmasi) --}}
        @if($booking->status === 'pending' && !$booking->is_admin_confirmed)
        <div class="card-gold sticky top-24 overflow-hidden mb-6">
            <div style="background:linear-gradient(135deg, #8B1A2A, #5C0E19); padding:16px 20px; border-bottom:1px solid rgba(197,160,40,0.3);">
                <div class="subtitle-gold" style="color:#fcd400; display:flex; align-items:center; gap:8px;">
                    <i data-lucide="calendar-check" class="w-4 h-4"></i> Konfirmasi Jadwal
                </div>
            </div>
            <div class="p-5 space-y-4">
                @php
                    $warn = $booking->smart_warning ?? (object)['class'=>'secondary', 'message'=>'Pengecekan tidak tersedia.'];
                    $warnColor = match($warn->class) {
                        'success' => 'bg-green-500/10 border-green-500/20 text-green-700',
                        'warning' => 'bg-orange-500/10 border-orange-500/20 text-orange-700',
                        'danger'  => 'bg-red-500/10 border-red-500/20 text-red-700',
                        default   => 'bg-gray-500/10 border-gray-500/20 text-gray-700',
                    };
                    $warnIcon = match($warn->class) {
                        'success' => 'check-circle-2',
                        'warning' => 'alert-triangle',
                        'danger'  => 'x-circle',
                        default   => 'info',
                    };
                @endphp
                <div class="p-4 rounded-xl border {{ $warnColor }} mb-4 text-sm font-medium flex items-start gap-3">
                    <i data-lucide="{{ $warnIcon }}" class="w-5 h-5 mt-0.5 flex-shrink-0"></i>
                    <div style="line-height: 1.4;">{{ $warn->message }}</div>
                </div>

                <p class="text-xs text-on-surface-variant font-body mb-2">Tentukan apakah sanggar dapat melayani pesanan ini berdasarkan ketersediaan personel di atas.</p>

                <div class="flex gap-2">
                    <button type="button" onclick="document.getElementById('modalTolakBooking').classList.remove('hidden');document.getElementById('modalTolakBooking').classList.add('flex');" class="flex-1 py-2.5 rounded-lg border border-red-500/30 text-red-600 font-label text-xs font-bold uppercase tracking-widest hover:bg-red-50 transition-colors">
                        Tolak
                    </button>
                    <form action="{{ route('admin.bookings.accept', $booking->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Anda yakin ingin MENERIMA booking ini? Klien akan diizinkan membayar DP.')">
                        @csrf
                        <button type="submit" class="w-full py-2.5 rounded-lg bg-green-600 text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-green-700 transition-colors shadow-sm">
                            Terima
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Tolak --}}
        <div id="modalTolakBooking" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-md" onclick="this.parentElement.classList.add('hidden');this.parentElement.classList.remove('flex');"></div>
            <div class="relative w-full max-w-md card-gold overflow-hidden">
                <div class="px-6 py-5 border-b flex justify-between items-center" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
                    <h5 class="title-gold flex items-center gap-2" style="font-size:1.2rem; color:#8B1A2A;">
                        <i data-lucide="x-circle" class="w-5 h-5"></i> Tolak Booking
                    </h5>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('modalTolakBooking').classList.add('hidden');document.getElementById('modalTolakBooking').classList.remove('flex');">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST">
                    @csrf
                    <div class="p-6">
                        <label class="block subtitle-gold mb-1.5 ml-1">Alasan Penolakan</label>
                        <textarea name="admin_note" rows="3" class="w-full p-3 rounded-xl border border-outline-variant/30 bg-surface-container-lowest font-body text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all" placeholder="Contoh: Personel tidak mencukupi di tanggal tersebut..." required></textarea>
                    </div>
                    <div class="px-6 py-4 border-t flex justify-end gap-3" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
                        <button type="button" class="arh-btn-secondary py-2" onclick="document.getElementById('modalTolakBooking').classList.add('hidden');document.getElementById('modalTolakBooking').classList.remove('flex');">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-red-700 transition-all shadow-sm">Konfirmasi Tolak</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- PANEL KALKULASI LABA (Ditampilkan jika SUDAH dikonfirmasi, ATAU status bukan pending) --}}
        @if($booking->is_admin_confirmed || $booking->status !== 'pending')
        <div class="card-gold sticky top-24 overflow-hidden">
            <div style="background:linear-gradient(135deg, #8B1A2A, #5C0E19); padding:16px 20px; border-bottom:1px solid rgba(197,160,40,0.3);">
                <div class="subtitle-gold" style="color:#fcd400; display:flex; align-items:center; gap:8px;">
                    <i data-lucide="calculator" class="w-4 h-4"></i> Kalkulasi Laba
                </div>
            </div>

            <div class="p-5 space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span style="font-family:'Inter',sans-serif; color:#504442; font-weight:600;">Total Harga (Kontrak)</span>
                    <div class="text-right">
                        <span class="title-gold" style="font-size:1.3rem;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        @if($booking->status === 'pending')
                            <button type="button" onclick="document.getElementById('modalUpdateHarga').classList.remove('hidden');document.getElementById('modalUpdateHarga').classList.add('flex');" class="subtitle-gold mt-1 hover:text-[#8B1A2A] flex items-center justify-end gap-1" style="text-decoration:underline; font-size:0.65rem; background:none; border:none; cursor:pointer;"><i data-lucide="pencil" class="w-3 h-3"></i> Nego</button>
                        @endif
                    </div>
                </div>
                
                <div class="flex justify-between items-center text-sm p-3 rounded-xl" style="background:rgba(197,160,40,0.05); border:1px solid rgba(197,160,40,0.2);">
                    <span class="subtitle-gold" style="color:#8B1A2A;">DP Masuk (50%)</span>
                    <span class="title-gold" style="color:#8B1A2A; font-size:1.2rem;">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                </div>
                
                <hr style="border-color:rgba(197,160,40,0.2); border-style:dashed; margin:16px 0;">
                
                {{-- PREVIEW SARAN KALKULASI (bukan form, hanya info) --}}
                @if($booking->status === 'pending')
                @php
                    $saranProfit = round($booking->total_price * 0.30);
                    $saranOps    = $booking->dp_amount - $saranProfit;
                    $saranOps    = max(0, $saranOps);
                    $saranBuffer = round($saranOps * 0.10);
                @endphp
                <div class="p-4 rounded-xl" style="background:rgba(139,26,42,0.03); border:1px solid rgba(139,26,42,0.1);">
                    <div class="subtitle-gold mb-2 flex items-center gap-1.5" style="color:#8B1A2A;">
                        <i data-lucide="lightbulb" class="w-4 h-4"></i> Saran Kalkulasi Otomatis (30%)
                    </div>
                    <div class="space-y-2 mt-3">
                        <div class="flex justify-between items-center text-xs" style="font-family:'Inter',sans-serif;">
                            <span style="color:#847B78;">Saran Fixed Profit</span>
                            <span style="font-weight:700; color:#8B1A2A;">Rp {{ number_format($saranProfit, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs" style="font-family:'Inter',sans-serif;">
                            <span style="color:#847B78;">Estimasi Ops Budget</span>
                            <span style="font-weight:700; color:#1A1817;">Rp {{ number_format($saranOps, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs" style="font-family:'Inter',sans-serif;">
                            <span style="color:#847B78;">Safety Buffer (10%)</span>
                            <span style="font-weight:700; color:#16a34a;">Rp {{ number_format($saranBuffer, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <p class="subtitle-gold mt-3" style="font-size:0.6rem; text-transform:none; letter-spacing:normal;">💡 Nilai ini hanya saran. Nominal akhir diisi saat konfirmasi DP di menu <strong>Booking Index</strong>.</p>
                </div>
                @else
                <div class="flex justify-between items-center text-sm">
                    <span style="font-family:'Inter',sans-serif; color:#504442; font-weight:600; display:flex; align-items:center; gap:6px;"><i data-lucide="lock" class="w-4 h-4 text-yellow-600"></i> Fixed Profit (Dikunci)</span>
                    <span class="title-gold" style="font-size:1.1rem;">Rp {{ number_format($booking->event->financialRecord->fixed_profit ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span style="font-family:'Inter',sans-serif; color:#504442; font-weight:600;">Budget Operasional</span>
                    <span class="title-gold" style="font-size:1.1rem; color:#1A1817;">Rp {{ number_format($booking->event->financialRecord->operational_budget ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span style="font-family:'Inter',sans-serif; color:#504442; font-weight:600;">Safety Buffer (10%)</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($booking->event->financialRecord->safety_buffer_amt ?? 0, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>

            <div class="p-5 border-t" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
                @if($booking->status === 'pending')
                <a href="{{ route('admin.bookings.dp_verification') }}" class="arh-btn-primary w-full flex justify-center py-3" style="background:linear-gradient(135deg, #fcd400, #C5A028); color:#1A1817; border:none; text-align:center;">
                    <i data-lucide="shield-check" class="w-4 h-4 mr-2 inline-block"></i> Lanjut → Cek Bukti DP
                </a>
                @elseif(in_array($booking->status, ['dp_paid','confirmed','paid_full','completed']))
                <div class="p-4 text-center rounded-xl mb-4" style="background:rgba(22,163,74,0.05); border:1px solid rgba(22,163,74,0.2); color:#16a34a;">
                    <i data-lucide="check-circle-2" class="w-8 h-8 mx-auto mb-2"></i>
                    <div style="font-weight:700; font-family:'Inter',sans-serif; margin-bottom:4px;">Laba Telah Terkunci Aman</div>
                    <div class="subtitle-gold" style="font-size:0.65rem; color:#16a34a;">Waktu DP: {{ $booking->dp_paid_at ? \Carbon\Carbon::parse($booking->dp_paid_at)->format('d M Y H:i') : '-' }}</div>
                </div>
                
                {{-- PENGECEKAN PELUNASAN --}}
                @if(in_array($booking->status, ['dp_paid', 'confirmed']))
                    @if($booking->full_payment_proof)
                        <div class="p-4 rounded-xl mb-4 text-center" style="background:rgba(59,130,246,0.05); border:1px solid rgba(59,130,246,0.2);">
                            <div class="subtitle-gold mb-2" style="color:#3b82f6;">Bukti Pelunasan Klien Menunggu Konfirmasi</div>
                            <a href="{{ asset('storage/' . $booking->full_payment_proof) }}" target="_blank" class="block mb-3">
                                <img src="{{ asset('storage/' . $booking->full_payment_proof) }}" alt="Bukti Pelunasan" class="w-full h-32 object-cover rounded-lg border border-outline-variant/30 hover:opacity-80 transition-opacity">
                            </a>
                            <div class="subtitle-gold" style="font-size:0.65rem; text-transform:none; letter-spacing:normal;">Silakan proses pelunasan ini di menu <strong>Pelacakan Pembayaran</strong>.</div>
                        </div>
                    @else
                        <div class="p-4 rounded-xl mb-4 text-center" style="background:rgba(197,160,40,0.02); border:1px solid rgba(197,160,40,0.15);">
                            <div class="subtitle-gold" style="font-size:0.65rem; text-transform:none; letter-spacing:normal;">Sisa pembayaran / pelunasan klien dipantau di menu <strong>Pelacakan Pembayaran</strong>.</div>
                        </div>
                    @endif

                    {{-- Form Pelunasan Cash Langsung --}}
                    <div class="mt-4 p-4 rounded-xl text-center" style="background:rgba(16,185,129,0.05); border:1px solid rgba(16,185,129,0.2);">
                        <div class="subtitle-gold mb-2" style="color:#059669;">Pelunasan Tunai (Cash)</div>
                        <p class="subtitle-gold mb-3" style="font-size:0.6rem; text-transform:none; letter-spacing:normal;">Klik tombol di bawah jika klien membayar sisa pelunasan secara cash langsung ke sanggar.</p>
                        <form action="{{ route('admin.bookings.full_cash_payment', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengonfirmasi pelunasan cash/tunai untuk pesanan ini?')">
                            @csrf
                            <button type="submit" class="arh-btn-primary w-full flex justify-center py-2.5" style="background:#059669; color:#fff; border:none;">
                                <i data-lucide="coins" class="w-4 h-4 mr-2 inline-block"></i> Konfirmasi Lunas Cash
                            </button>
                        </form>
                    </div>
                @elseif($booking->status === 'paid_full' || $booking->status === 'completed')
                    <div class="p-4 text-center rounded-xl mb-4" style="background:rgba(197,160,40,0.05); border:1px solid rgba(197,160,40,0.2); color:#C5A028;">
                        <i data-lucide="check-square" class="w-8 h-8 mx-auto mb-2 text-yellow-600"></i>
                        <div style="font-weight:700; font-family:'Inter',sans-serif; margin-bottom:4px; color:#1A1817;">Pesanan Telah Lunas 100%</div>
                        <div class="subtitle-gold" style="font-size:0.65rem;">Waktu Lunas: {{ $booking->full_paid_at ? \Carbon\Carbon::parse($booking->full_paid_at)->format('d M Y H:i') : '-' }}</div>
                    </div>
                @endif
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal Update Harga Nego --}}
{{-- Modal Update Harga Nego --}}
@if($booking->status === 'pending')
<div id="modalUpdateHarga" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md" onclick="this.parentElement.classList.add('hidden');this.parentElement.classList.remove('flex');"></div>
    <div class="relative w-full max-w-md card-gold overflow-hidden">
        <div class="px-6 py-5 border-b" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02); display:flex; justify-content:space-between; align-items:center;">
            <h5 class="title-gold flex items-center gap-2" style="font-size:1.2rem;">
                <i data-lucide="pencil" class="w-5 h-5 text-yellow-600"></i> Update Harga Nego
            </h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                onclick="document.getElementById('modalUpdateHarga').classList.add('hidden');document.getElementById('modalUpdateHarga').classList.remove('flex');">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.bookings.update_price', $booking->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="p-6">
                <p class="subtitle-gold mb-5" style="text-transform:none; letter-spacing:normal;">Tentukan harga akhir (Deal Price) dengan Klien sebelum DP dikonfirmasi.</p>
                <div>
                    <label class="block subtitle-gold mb-1.5 ml-1">Total Harga Akhir (Rp)</label>
                    <input type="number" name="total_price" class="input-gold" style="font-size:1.2rem; font-weight:700; color:#8B1A2A;" value="{{ $booking->total_price }}" required>
                </div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end gap-3" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
                <button type="button" class="arh-btn-secondary py-2"
                    onclick="document.getElementById('modalUpdateHarga').classList.add('hidden');document.getElementById('modalUpdateHarga').classList.remove('flex');">Batal</button>
                <button type="submit" class="arh-btn-primary py-2">Simpan Harga Nego</button>
            </div>
        </form>
    </div>
</div>
@endif


{{-- Modal Edit Jadwal --}}
@if($booking->status === 'pending')
<div id="modalEditJadwal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md" onclick="this.parentElement.classList.add('hidden');this.parentElement.classList.remove('flex');"></div>
    <div class="relative w-full max-w-md card-gold overflow-hidden">
        <div class="px-6 py-5 border-b flex justify-between items-center" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
            <h5 class="title-gold flex items-center gap-2" style="font-size:1.2rem;">
                <i data-lucide="calendar-clock" class="w-5 h-5 text-yellow-600"></i> Ubah Jadwal
            </h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors"
                onclick="document.getElementById('modalEditJadwal').classList.add('hidden');document.getElementById('modalEditJadwal').classList.remove('flex');">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.bookings.update_schedule', $booking->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="p-6 space-y-4">
                <p class="subtitle-gold" style="text-transform:none; letter-spacing:normal;">Sesuaikan jadwal pementasan berdasarkan kesepakatan dengan klien, agar personel dapat dialokasikan dengan tepat.</p>
                <div>
                    <label class="block subtitle-gold mb-1.5 ml-1">Tanggal Pementasan</label>
                    <input type="date" name="event_date" class="input-gold" value="{{ \Carbon\Carbon::parse($booking->event_date)->format('Y-m-d') }}" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block subtitle-gold mb-1.5 ml-1">Waktu Mulai</label>
                        <input type="time" name="event_start" class="input-gold" value="{{ \Carbon\Carbon::parse($booking->event_start)->format('H:i') }}" required>
                    </div>
                    <div>
                        <label class="block subtitle-gold mb-1.5 ml-1">Waktu Selesai</label>
                        <input type="time" name="event_end" class="input-gold" value="{{ \Carbon\Carbon::parse($booking->event_end)->format('H:i') }}" required>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end gap-3" style="border-color:rgba(197,160,40,0.2); background:rgba(197,160,40,0.02);">
                <button type="button" class="arh-btn-secondary py-2"
                    onclick="document.getElementById('modalEditJadwal').classList.add('hidden');document.getElementById('modalEditJadwal').classList.remove('flex');">Batal</button>
                <button type="submit" class="arh-btn-primary py-2">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    // Tombol Nego: buka modal update harga
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-confirm]').forEach(function (el) {
            el.addEventListener('submit', function (e) {
                var msg = el.getAttribute('data-confirm');
                if (msg && !confirm(msg)) e.preventDefault();
            });
        });
    });
</script>
@endpush
