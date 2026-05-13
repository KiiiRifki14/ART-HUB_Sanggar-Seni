@extends('layouts.admin')

@section('title', 'CMS Landing Page – ART-HUB')
@section('page_title', 'CMS Landing Page')
@section('page_subtitle', 'Atur konten dinamis untuk halaman depan website Sanggar.')

@section('content')

@if(session('success'))
    <div class="p-4 mb-6 text-sm text-green-700 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center gap-2 font-bold">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

<form action="{{ route('admin.cms.update') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 gap-6">
        {{-- Bagian Sejarah --}}
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-6 shadow-sm">
            <div class="font-headline text-lg text-primary font-bold mb-4 flex items-center gap-2">
                <i class="bi bi-clock-history"></i> Bagian Sejarah & Pendiri
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Nama Pendiri</label>
                    <input type="text" name="history_founder_name" value="{{ $contents['history_founder_name'] ?? 'Bapa A. Kusmana' }}" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2 font-body text-sm text-on-surface">
                </div>
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Kutipan (Quote) Pendiri</label>
                    <textarea name="history_quote" rows="2" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2 font-body text-sm text-on-surface">{{ $contents['history_quote'] ?? 'Seni bukan sekadar hiburan — ia adalah napas peradaban, warisan yang wajib kita jaga dan wariskan kepada generasi mendatang.' }}</textarea>
                </div>
                <div>
                    <label class="block font-label text-xs uppercase tracking-widest text-on-surface-variant font-bold mb-2">Paragraf Sejarah</label>
                    <textarea name="history_paragraph" rows="4" class="w-full bg-surface-container-low border border-outline-variant/50 rounded-lg px-4 py-2 font-body text-sm text-on-surface">{{ $contents['history_paragraph'] ?? 'Atas visi dan dedikasi Bapa A. Kusmana, Sanggar Cahaya Gumilang lahir sebagai ruang kebudayaan yang tidak hanya melatih, tetapi juga membentuk karakter dan jiwa para seniman muda Indonesia. Setiap gerakan tari dan dentuman gamelan yang kami hadirkan adalah bentuk penghormatan nyata atas warisan beliau.' }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-white font-label text-xs font-bold uppercase tracking-widest hover:bg-primary-container transition-colors shadow-lg">
                <i class="bi bi-save me-1"></i> Simpan Perubahan CMS
            </button>
        </div>
    </div>
</form>

@endsection
