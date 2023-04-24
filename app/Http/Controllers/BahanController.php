<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;
USE App\Models\Jenis;

class BahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenis = Jenis::all()->pluck('nama_jenis', 'id_jenis');

        return view('bahan.index', compact('jenis'));
    }

    public function data(){
        $bahan = Bahan::leftJoin('jenis', 'jenis.id_jenis', 'bahan.id_jenis')
        ->select('bahan.*', 'nama_jenis')
        // ->orderBy('kode_bahan', 'asc')
        ->get();

    return datatables()
        ->of($bahan)
        ->addIndexColumn()
        ->addColumn('select_all', function ($bahan) {
            return '
                <input type="checkbox" name="id_bahan[]" value="'. $bahan->id_bahan .'">
            ';
        })
        ->addColumn('kode_bahan', function ($bahan) {
            return $bahan->kode_bahan;
        })
        ->addColumn('harga_beli', function ($bahan) {
            return format_uang($bahan->harga_beli);
        })
        ->addColumn('aksi', function ($bahan) {
            return '
            <div class="btn-group">
                <button type="button" onclick="editForm(`'. route('bahan.update', $bahan->id_bahan) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                <button type="button" onclick="deleteData(`'. route('bahan.destroy', $bahan->id_bahan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            </div>
            ';
        })
        ->rawColumns(['aksi', 'select_all'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bahan = Bahan::latest()->first() ?? new bahan();
        $request['kode_bahan'] = 'Bahan'. tambah_nol_didepan((int)$bahan->id_bahan +1, 6);

        $bahan = Bahan::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bahan = Bahan::find($id);

        return response()->json($bahan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bahan = Bahan::find($id);
        $bahan->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bahan = Bahan::find($id);
        $bahan->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_bahan as $id) {
            $bahan = Bahan::find($id);
            $bahan->delete();
        }

        return response(null, 204);
    }
}
