<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceCatalogController extends Controller
{
    public function index()
    {
        $catalogs = ServiceCatalog::orderBy('sort_order')->orderBy('id')->paginate(10);
        return view('admin.catalogs.index', compact('catalogs'));
    }

    public function create()
    {
        return view('admin.catalogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'required|string',
            'detail'         => 'nullable|string',
            'price'          => 'required|integer|min:0',
            'badge'          => 'nullable|string|max:50',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
            'max_personnel'  => 'nullable|integer|min:0',
            'specialty_type' => 'required|in:penari,pemusik,gabungan',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'         => 'Nama katalog wajib diisi.',
            'description.required'  => 'Deskripsi wajib diisi.',
            'price.required'        => 'Harga wajib diisi.',
            'price.integer'         => 'Harga harus berupa angka.',
            'specialty_type.required' => 'Tipe personel wajib dipilih.',
            'image.image'           => 'File harus berupa gambar.',
            'image.max'             => 'Ukuran gambar maksimal 2MB.',
        ]);

        $validated['is_active']     = $request->boolean('is_active', true);
        $validated['sort_order']    = $request->input('sort_order', 0);
        $validated['max_personnel'] = $request->input('max_personnel', 0);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('catalogs', 'public');
        }

        ServiceCatalog::create($validated);

        return redirect()->route('admin.catalogs.index')
            ->with('success', "Katalog \"{$validated['name']}\" berhasil ditambahkan!");
    }

    public function edit(ServiceCatalog $catalog)
    {
        return view('admin.catalogs.edit', compact('catalog'));
    }

    public function update(Request $request, ServiceCatalog $catalog)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'required|string',
            'detail'         => 'nullable|string',
            'price'          => 'required|integer|min:0',
            'badge'          => 'nullable|string|max:50',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
            'max_personnel'  => 'nullable|integer|min:0',
            'specialty_type' => 'required|in:penari,pemusik,gabungan',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['is_active']     = $request->boolean('is_active', true);
        $validated['sort_order']    = $request->input('sort_order', 0);
        $validated['max_personnel'] = $request->input('max_personnel', 0);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($catalog->image && Storage::disk('public')->exists($catalog->image)) {
                Storage::disk('public')->delete($catalog->image);
            }
            $validated['image'] = $request->file('image')->store('catalogs', 'public');
        }

        $catalog->update($validated);

        return redirect()->route('admin.catalogs.index')
            ->with('success', "Katalog \"{$catalog->name}\" berhasil diperbarui!");
    }

    public function destroy(ServiceCatalog $catalog)
    {
        if ($catalog->image && Storage::disk('public')->exists($catalog->image)) {
            Storage::disk('public')->delete($catalog->image);
        }
        $name = $catalog->name;
        $catalog->delete();

        return redirect()->route('admin.catalogs.index')
            ->with('success', "Katalog \"{$name}\" berhasil dihapus.");
    }

    public function toggleActive(ServiceCatalog $catalog)
    {
        $catalog->update(['is_active' => !$catalog->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $catalog->is_active,
            'message'   => $catalog->is_active
                ? "Katalog \"{$catalog->name}\" sekarang aktif."
                : "Katalog \"{$catalog->name}\" disembunyikan dari landing page.",
        ]);
    }
}
