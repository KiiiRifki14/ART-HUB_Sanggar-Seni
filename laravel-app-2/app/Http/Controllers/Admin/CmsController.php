<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteContent;

class CmsController extends Controller
{
    public function index()
    {
        $contents = SiteContent::pluck('value', 'key')->toArray();
        return view('admin.cms.index', compact('contents'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            SiteContent::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return redirect()->back()->with('success', 'Konten Landing Page berhasil diperbarui!');
    }
}
