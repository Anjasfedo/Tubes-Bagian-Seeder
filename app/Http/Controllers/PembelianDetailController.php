<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bahan;
use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\PembelianDetail;

class PembelianDetailController extends Controller
{
    public function index()
    {
        $id_pembelian = session('id_pembelian');
        $bahan = Bahan::orderBy('nama_bahan')->get();
        $supplier = Supplier::find(session('id_supplier'));
        $diskon = Pembelian::find($id_pembelian)->diskon ?? 0;

        if (! $supplier) {
            abort(404);
        }

        return view('pembelian_detail.index', compact('id_pembelian', 'bahan', 'supplier'));
    }

    public function data($id)
    {
        $detail = PembelianDetail::with('bahan')
        ->where('id_pembelian', $id)
        ->get();
        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $key => $item) {
            $row = array();
            $row['DT_RowIndex'] = $key+1;
            $row['kode_bahan'] = '<span class="label label-success">'. $item->bahan['kode_bahan'] .'</span';
            $row['nama_bahan'] = $item->bahan['nama_bahan'];
            $row['harga_beli']  = 'Rp. '. format_uang($item->harga_beli);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_pembelian_detail .'" value="'. $item->jumlah .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('pembelian_detail.destroy', $item->id_pembelian_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_beli * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_bahan' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_bahan' => '',
            'harga_beli'  => '',
            'jumlah'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_bahan', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $bahan = Bahan::where('id_bahan', $request->id_bahan)->first();
        if (! $bahan) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new PembelianDetail();
        $detail->id_pembelian = $request->id_pembelian;
        $detail->id_bahan = $bahan->id_bahan;
        $detail->harga_beli = $bahan->harga_beli;
        $detail->jumlah = 1;
        $detail->subtotal = $bahan->harga_beli;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PembelianDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_beli * $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = PembelianDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon, $total)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $data  = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
        ];

        return response()->json($data);
    }
}
