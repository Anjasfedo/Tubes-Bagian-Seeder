<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pemasukan.index');
    }

    public function data(){
        $pemasukan = Pemasukan::orderBy('id_pemasukan', 'desc')->get();

    return datatables()
        ->of($pemasukan)
        ->addIndexColumn()
        ->addColumn('created_at', function ($pemasukan) {
            return tanggal_indonesia($pemasukan->created_at, false);
        })
        ->addColumn('nominal', function ($pemasukan) {
            return format_uang($pemasukan->nominal);
        })
        ->addColumn('aksi', function ($pemasukan) {
            return '
            <div class="btn-group">
                <button type="button" onclick="editForm(`'. route('pemasukan.update', $pemasukan->id_pemasukan) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                <button type="button" onclick="deleteData(`'. route('pemasukan.destroy', $pemasukan->id_pemasukan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        // $pemasukan = pemasukan::latest()->first() ?? new pemasukan();
        // $request['kode_pemasukan'] = 'P'. tambah_nol_didepan((int)$pemasukan->id_pemasukan +1, 6);

        $pemasukan = Pemasukan::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pemasukan = Pemasukan::find($id);

        return response()->json($pemasukan);
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
        $pemasukan = Pemasukan::find($id);
        $pemasukan->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemasukan = Pemasukan::find($id);
        $pemasukan->delete();

        return response(null, 204);
    }
}
