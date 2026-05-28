<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteContent;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{
    public function index()
    {
        $contents = SiteContent::pluck('value', 'key')->toArray();
        return view('admin.cms.index', compact('contents'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sanggar_logo'  => 'nullable|image|mimes:png,svg,jpg,jpeg|max:1024',
            'hero_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'founder_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'sanggar_logo.image'  => 'Logo harus berupa gambar.',
            'sanggar_logo.max'    => 'Ukuran logo maksimal 1MB.',
            'hero_image.image'    => 'File harus berupa gambar.',
            'hero_image.max'      => 'Ukuran gambar hero maksimal 3MB.',
            'founder_photo.image' => 'File harus berupa gambar.',
            'founder_photo.max'   => 'Ukuran foto pendiri maksimal 2MB.',
        ]);

        // Kolom teks biasa (bukan file)
        $textFields = [
            'sanggar_name', 'hero_tagline', 'hero_description',
            'history_founder_name', 'history_quote', 'history_paragraph',
            'footer_address', 'footer_email', 'footer_tagline', 'footer_copyright',
            'founder_photo_active',
        ];

        foreach ($textFields as $key) {
            if ($request->has($key)) {
                SiteContent::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->input($key)]
                );
            }
        }

        // Upload Logo Sanggar
        if ($request->hasFile('sanggar_logo')) {
            $oldPath = SiteContent::where('key', 'sanggar_logo')->value('value');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('sanggar_logo')->store('cms', 'public');
            SiteContent::updateOrCreate(['key' => 'sanggar_logo'], ['value' => $path]);
        }

        // Upload Hero Image
        if ($request->hasFile('hero_image')) {
            $oldPath = SiteContent::where('key', 'hero_image')->value('value');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('hero_image')->store('cms', 'public');
            SiteContent::updateOrCreate(['key' => 'hero_image'], ['value' => $path]);
        }

        // Upload Foto Pendiri
        if ($request->hasFile('founder_photo')) {
            $oldPath = SiteContent::where('key', 'founder_photo')->value('value');
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('founder_photo')->store('cms', 'public');
            SiteContent::updateOrCreate(['key' => 'founder_photo'], ['value' => $path]);
        }

        \Illuminate\Support\Facades\Cache::forget('site_contents');
        return redirect()->back()->with('success', 'Konten Landing Page berhasil diperbarui!');
    }
}
