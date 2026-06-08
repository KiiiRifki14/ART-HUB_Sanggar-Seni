@extends('layouts.admin')
@section('title', 'Edit Sewaan Vendor – ART-HUB')
@section('page_title', 'Edit Sewaan Vendor')
@section('page_subtitle', 'Perbarui detail data penyewaan kostum dari vendor eksternal.')

@section('content')
<div class="max-w-2xl mx-auto" x-data="vendorManager">
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/30 shadow-sm p-5 md:p-6">

        <div class="flex items-center gap-3 mb-5 pb-3 border-b border-outline-variant/20">
            <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary flex-shrink-0">
                <i class="bi bi-pencil-square text-lg"></i>
            </div>
            <div>
                <h2 class="font-headline text-base text-secondary font-bold">Edit Penyewaan Vendor</h2>
                <p class="font-body text-[0.7rem] text-on-surface-variant">Perbarui detail penyewaan kostum eksternal yang sudah dicatat.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.costumes.update-rental', $rental->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                {{-- Pilih Event --}}
                <div class="md:col-span-2">
                    <label class="font-label text-xs font-bold text-on-surface block mb-1 ml-1">Untuk Event / Acara</label>
                    <select name="event_id" class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors appearance-none" required>
                        <option value="" disabled>Pilih Event yang sedang aktif</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id', $rental->event_id) == $event->id ? 'selected' : '' }}>
                                {{ $event->booking->client_name }} - {{ $event->booking->event_name }} ({{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('event_id') <p class="text-red-500 text-[0.68rem] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Pilih Vendor & Tombol Tambah --}}
                <div>
                    <label class="font-label text-xs font-bold text-on-surface block mb-1 ml-1">Vendor Penyedia</label>
                    <div class="flex gap-1.5">
                        <select name="costume_vendor_id" id="vendor_select" class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors appearance-none" required>
                            <option value="" disabled>Pilih Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('costume_vendor_id', $rental->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" @click="showVendorModal = true" class="px-3 bg-secondary/10 hover:bg-secondary/20 text-secondary border border-secondary/20 rounded-lg transition-colors font-bold tooltip flex items-center justify-center" title="Tambah Vendor Baru">
                            <i class="bi bi-plus-lg text-xs"></i>
                        </button>
                    </div>
                    @error('costume_vendor_id') <p class="text-red-500 text-[0.68rem] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tipe Kostum --}}
                <div>
                    <label class="font-label text-xs font-bold text-on-surface block mb-1 ml-1">Tipe/Jenis Kostum</label>
                    <input type="text" name="costume_type" placeholder="Misal: Kostum Gatotkaca"
                           class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors"
                           value="{{ old('costume_type', $rental->costume_type) }}" required>
                    @error('costume_type') <p class="text-red-500 text-[0.68rem] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="font-label text-xs font-bold text-on-surface block mb-1 ml-1">Jumlah Set</label>
                    <input type="number" name="quantity" min="1" placeholder="0"
                           class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors"
                           value="{{ old('quantity', $rental->quantity) }}" required>
                    @error('quantity') <p class="text-red-500 text-[0.68rem] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Harga Sewa --}}
                <div>
                    <label class="font-label text-xs font-bold text-on-surface block mb-1 ml-1">Biaya Sewa (Rp)</label>
                    <input type="number" name="rental_cost" placeholder="Contoh: 150000"
                           class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors"
                           value="{{ old('rental_cost', $rental->rental_cost) }}" required>
                    @error('rental_cost') <p class="text-red-500 text-[0.68rem] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Batas Pengembalian --}}
                <div class="md:col-span-2">
                    <label class="font-label text-xs font-bold text-on-surface block mb-1 ml-1">Batas Waktu Pengembalian</label>
                    <input type="date" name="due_date"
                           class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors"
                           value="{{ old('due_date', $rental->due_date) }}" required>
                    <p class="text-[9px] text-on-surface-variant mt-1.5 ml-1 italic">*Keterlambatan akan dikenakan denda otomatis oleh sistem Rp 50.000/hari.</p>
                    @error('due_date') <p class="text-red-500 text-[0.68rem] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Status Info --}}
                @if($rental->status === 'rented')
                <div class="md:col-span-2 p-3 rounded-lg bg-orange-50 border border-orange-200">
                    <p class="font-label text-[0.65rem] font-bold text-orange-600 uppercase tracking-wider mb-0.5">Status Saat Ini</p>
                    <p class="font-body text-xs text-orange-700">Kostum masih dalam status <strong>DIPINJAM</strong>. Anda hanya bisa edit detail sewaan ini.</p>
                </div>
                @elseif($rental->returned_date)
                <div class="md:col-span-2 p-3 rounded-lg bg-green-50 border border-green-200">
                    <p class="font-label text-[0.65rem] font-bold text-green-600 uppercase tracking-wider mb-0.5">Status Saat Ini</p>
                    <p class="font-body text-xs text-green-700">Kostum sudah dikembalikan pada <strong>{{ \Carbon\Carbon::parse($rental->returned_date)->format('d M Y') }}</strong>.</p>
                </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="pt-4.5 border-t border-outline-variant/20 flex items-center justify-between">
                <div>
                    {{-- Info Sewaan --}}
                    <div class="text-[10px] text-on-surface-variant font-label leading-tight">
                        <p>Status: <span class="font-bold text-secondary">{{ ucfirst($rental->status) }}</span></p>
                        <p>Dibuat: {{ $rental->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('admin.costumes.index') }}" class="px-4 py-2 rounded-lg border border-outline-variant/50 font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-secondary text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-secondary-container transition-colors shadow-md flex items-center gap-1.5">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- MODAL TAMBAH VENDOR (Disembunyikan secara default) --}}
    <div x-show="showVendorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-transition.opacity style="display: none;">
        <div @click.away="showVendorModal = false" class="bg-surface-container-lowest p-5 rounded-xl w-full max-w-sm shadow-2xl border border-outline-variant/30 transform transition-all" x-transition.scale.95>
            <h3 class="font-headline text-base font-bold text-on-surface mb-0.5">Tambah Vendor Baru</h3>
            <p class="font-body text-[0.7rem] text-on-surface-variant mb-4">Vendor ini akan langsung tersedia di daftar pilihan.</p>

            <input type="text" x-model="vendorName" placeholder="Masukkan nama vendor..." class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-3 py-2 font-body text-xs focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors mb-4" @keydown.enter.prevent="saveVendor">

            <div class="flex justify-end gap-2">
                <button type="button" @click="showVendorModal = false" class="px-3 py-1.5 rounded-lg font-label text-[0.65rem] font-bold uppercase tracking-widest text-on-surface-variant hover:bg-surface-container transition-colors">Batal</button>
                <button type="button" @click="saveVendor" :disabled="isSubmitting || vendorName.trim() === ''" class="px-3.5 py-1.5 rounded-lg bg-secondary text-white font-label text-[0.65rem] font-bold uppercase tracking-widest hover:bg-secondary-container transition-colors disabled:opacity-50 flex items-center gap-1.5">
                    <span x-show="isSubmitting" class="animate-spin inline-block w-2.5 h-2.5 border-2 border-white/30 border-t-white rounded-full"></span>
                    <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- SCRIPT AJAX UNTUK SIMPAN VENDOR --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('vendorManager', () => ({
                showVendorModal: false,
                vendorName: '',
                isSubmitting: false,

                saveVendor() {
                    if (this.vendorName.trim() === '') return;

                    this.isSubmitting = true;

                    fetch('{{ route("admin.costumes.store-vendor-api") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ name: this.vendorName })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tambahkan option baru ke dalam elemen <select>
                            const select = document.getElementById('vendor_select');
                            const newOption = new Option(data.vendor.name, data.vendor.id, true, true);
                            select.add(newOption);

                            // Reset modal
                            this.vendorName = '';
                            this.showVendorModal = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan vendor.');
                    })
                    .finally(() => {
                        this.isSubmitting = false;
                    });
                }
            }));
        });
    </script>
</div>
@endsection
