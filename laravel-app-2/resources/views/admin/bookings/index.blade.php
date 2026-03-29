@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Bookings & Plotting</h1>
        <button class="bg-[#d4af37] hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
            + Tambah Manual
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">ID Booking</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Klien</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tanggal Dibuat</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50 border-b border-gray-200">
                    <td class="px-5 py-4 text-sm text-gray-900">
                        #{{ $booking->id }}
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-900">
                        {{ $booking->client->name ?? 'Nama Klien' }}
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-900">
                        {{ $booking->created_at->format('d M Y') }}
                    </td>
                    <td class="px-5 py-4 text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold text-yellow-900 leading-tight">
                            <span aria-hidden class="absolute inset-0 bg-[#d4af37] opacity-50 rounded-full"></span>
                            <span class="relative">{{ ucfirst($booking->status) }}</span>
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-900">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold">Lihat Detail & Plotting</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-sm text-center text-gray-500">
                        Belum ada data booking masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection