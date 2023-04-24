<div class="modal fade" id="modal-bahan" tabindex="-1" role="dialog" aria-labelledby="modal-bahan">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih bahan</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-bahan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga Beli</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($bahan as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td><span class="label label-success">{{ $item->kode_bahan }}</span></td>
                                <td>{{ $item->nama_bahan }}</td>
                                <td>{{ $item->harga_beli }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                    onclick="pilihBahan('{{ $item->id_bahan }}', '{{ $item->kode_bahan }}')">
                                    <i class="fa fa-check-circle"></i>
                                    Pilih
                                </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>