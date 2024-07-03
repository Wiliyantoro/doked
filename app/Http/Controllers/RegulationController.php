<?php

namespace App\Http\Controllers;

use App\Models\Mengingat;
use App\Models\Menimbang;
use App\Models\Memutuskan;
use App\Models\Regulation;
use Illuminate\Http\Request;
use App\Models\SubMemutuskan;
use Barryvdh\DomPDF\Facade\Pdf;

class RegulationController extends Controller
{
    public function index()
    {
        $regulations = Regulation::with('menimbang', 'mengingat', 'memutuskan.subMemutuskan')->get();
        return view('regulations.index', compact('regulations'));
    }

    public function create()
    {
        return view('regulations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Perdes,Perkel,Keputusan Perbekel',
            'regulation_number' => 'required|string|max:255',
            'menimbang.*' => 'required|string',
            'mengingat.*' => 'required|string',
            'memutuskan.*.title' => 'required|string',
            'memutuskan.*.content' => 'required|string',
            'memutuskan.*.sub.*' => 'nullable|string',
        ]);

        $regulation = Regulation::create([
            'name' => $request->name,
            'type' => $request->type,
            'regulation_number' => $request->regulation_number,
        ]);

        foreach ($request->menimbang as $content) {
            $regulation->menimbang()->create(['content' => $content]);
        }

        foreach ($request->mengingat as $content) {
            $regulation->mengingat()->create(['content' => $content]);
        }

        foreach ($request->memutuskan as $putusan) {
            $memutuskan = $regulation->memutuskan()->create([
                'title' => $putusan['title'],
                'content' => $putusan['content'],
            ]);

            foreach ($putusan['sub'] as $subContent) {
                if ($subContent) {
                    $memutuskan->subMemutuskan()->create(['content' => $subContent]);
                }
            }
        }

        return redirect()->route('regulations.index')->with('success', 'Peraturan berhasil dibuat.');
    }

    public function edit(Regulation $regulation)
    {
        return view('regulations.edit', compact('regulation'));
    }

    public function update(Request $request, Regulation $regulation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Perdes,Perkel,Keputusan Perbekel',
            'regulation_number' => 'required|string|max:255',
            'menimbang.*' => 'required|string',
            'mengingat.*' => 'required|string',
            'memutuskan.*.title' => 'required|string',
            'memutuskan.*.content' => 'required|string',
            'memutuskan.*.sub.*' => 'nullable|string',
        ]);

        $regulation->update([
            'name' => $request->name,
            'type' => $request->type,
            'regulation_number' => $request->regulation_number,
        ]);

        $regulation->menimbang()->delete();
        foreach ($request->menimbang as $content) {
            $regulation->menimbang()->create(['content' => $content]);
        }

        $regulation->mengingat()->delete();
        foreach ($request->mengingat as $content) {
            $regulation->mengingat()->create(['content' => $content]);
        }

        $regulation->memutuskan()->delete();
        foreach ($request->memutuskan as $putusan) {
            $memutuskan = $regulation->memutuskan()->create([
                'title' => $putusan['title'],
                'content' => $putusan['content'],
            ]);

            foreach ($putusan['sub'] as $subContent) {
                if ($subContent) {
                    $memutuskan->subMemutuskan()->create(['content' => $subContent]);
                }
            }
        }

        return redirect()->route('regulations.index')->with('success', 'Peraturan berhasil diperbarui.');
    }

    public function destroy(Regulation $regulation)
    {
        $regulation->delete();
        return redirect()->route('regulations.index')->with('success', 'Peraturan berhasil dihapus.');
    }

    public function showPdf(Regulation $regulation)
    {
        $pdf = Pdf::loadView('regulations.pdf', compact('regulation'));
        return $pdf->download('regulation.pdf');
    }

    public function show(Regulation $regulation)
    {
        $regulation->load('menimbang', 'mengingat', 'memutuskan.subMemutuskan');
        return view('regulations.show', compact('regulation'));
    }

    public function addMenimbang(Request $request, Regulation $regulation)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $regulation->menimbang()->create($request->only('content'));

        return redirect()->back()->with('success', 'Menimbang berhasil ditambah');
    }

    public function editMenimbang(Request $request, Menimbang $menimbang)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $menimbang->update($request->only('content'));

        return redirect()->back()->with('success', 'Menimbang berhasil diubah');
    }

    public function addMengingat(Request $request, Regulation $regulation)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $regulation->mengingat()->create($request->only('content'));

        return redirect()->back()->with('success', 'Mengingat berhasil ditambah');
    }

    public function editMengingat(Request $request, Mengingat $mengingat)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $mengingat->update($request->only('content'));

        return redirect()->back()->with('success', 'Mengingat berhasil diubah');
    }

    public function addMemutuskan(Request $request, Regulation $regulation)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $regulation->memutuskan()->create($request->only('title', 'content'));

        return redirect()->back()->with('success', 'Putusan berhasil ditambah');
    }

    public function editMemutuskan(Request $request, Memutuskan $memutuskan)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $memutuskan->update($request->only('title', 'content'));

        return redirect()->back()->with('success', 'Putusan berhasil diubah');
    }

    public function addSubMemutuskan(Request $request)
    {
    $request->validate([
        'memutuskan_id' => 'required|exists:memutuskan,id',
        'content' => 'required',
    ]);

    $subMemutuskan = new SubMemutuskan();
    $subMemutuskan->memutuskan_id = $request->memutuskan_id;
    $subMemutuskan->content = $request->content;
    $subMemutuskan->save();

    return redirect()->back()->with('success', 'Sub Memutuskan berhasil ditambahkan');
    }

    public function editSubMemutuskan(Request $request, SubMemutuskan $sub)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $sub->update($request->only('content'));

        return redirect()->back()->with('success', 'Sub Putusan berhasil diubah');
    }

    public function deleteMenimbang($id)
    {
        $menimbang = Menimbang::findOrFail($id);
        $menimbang->delete();
        
        return redirect()->back()->with('success', 'Menimbang berhasil dihapus.');
    }

    public function deleteMengingat($id)
    {
        $mengingat = Mengingat::findOrFail($id);
        $mengingat->delete();
        
        return redirect()->back()->with('success', 'Mengingat berhasil dihapus.');
    }

    public function deleteMemutuskan($id)
    {
        $memutuskan = Memutuskan::findOrFail($id);
        $memutuskan->delete();
        
        return redirect()->back()->with('success', 'Memutuskan berhasil dihapus.');
    }

    public function deleteSubMemutuskan($id)
    {
        $sub = SubMemutuskan::findOrFail($id);
        $sub->delete();
        
        return redirect()->back()->with('success', 'Sub Memutuskan berhasil dihapus.');
    }

}
