@extends('layouts.master')

@section('title')
    Transaksi Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Pembelian</li>
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }
    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }
    .table-pembelian tbody tr:last-child {
        display: none;
    }
    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('content')
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                    <table>
                        <tr>
                            <td>Supplier</td>
                            <td>: {{ $supplier->nama }}</td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>: {{ $supplier->telepon }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: {{ $supplier->alamat }}</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body">
                    
                    <form class="form-bahan">
                        @csrf
                        <div class="form-group row">
                            <label for="kode_bahan" class="col-lg-2">Kode bahan</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}">
                                    <input type="hidden" name="id_bahan" id="id_bahan">
                                    <input type="text" class="form-control" name="kode_bahan" id="kode_bahan">
                                    <span class="input-group-btn">
                                        <button onclick="tampilBahan()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                        <table class="table table-stiped table-bordered table-pembelian">
                            <thead>
                                <th width="5%">No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th width="15%">Jumlah</th>
                                <th>Subtotal</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </thead>
                        </table>
                    
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="tampil-bayar bg-primary"></div>
                                <div class="tampil-terbilang"></div>
                            </div>
                            <div class="col-lg-4">
                                <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="post">
                                    @csrf
                                    <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                                    <input type="hidden" name="total" id="total">
                                    <input type="hidden" name="total_item" id="total_item">
                                    <input type="hidden" name="bayar" id="bayar">
        
                                    <div class="form-group row">
                                        <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="totalrp" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                        <div class="col-lg-8">
                                            <input type="number" name="diskon" id="diskon" class="form-control" value="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="bayarrp" class="form-control">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
                </div>
              </div>
            </div>
          </div>
@endsection

@includeIf('pembelian_detail.bahan')

@push('scripts')
    <script>
        let table, table2;

        $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('pembelian_detail.data', $id_pembelian) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_bahan'},
                {data: 'nama_bahan'},
                {data: 'harga_beli'},
                {data: 'jumlah'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
        })
        .on('draw.dt', function () {
            loadForm($('#diskon').val());
        });
        table2 = $('.table-bahan').DataTable();

        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());
            if (jumlah < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }
            if (jumlah > 1000) {
                $(this).val(1000);
                alert('Jumlah tidak boleh lebih dari 1000');
                return;
            }
            $.post(`{{ url('/pembelian_detail') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload();
                    });
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        });

        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }
            loadForm($(this).val());
        });
        $('.btn-simpan').on('click', function () {
            $('.form-pembelian').submit();
        });
            
        });

        function tampilBahan(){
            $('#modal-bahan').modal('show');
        }

        function hideBahan() {
        $('#modal-bahan').modal('hide');
        }

        function pilihBahan(id, kode) {
        $('#id_bahan').val(id);
        $('#kode_bahan').val(kode);
        hideBahan();
        tambahBahan();
        }

        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
        }

        function tambahBahan() {
        $.post('{{ route('pembelian_detail.store') }}', $('.form-bahan').serialize())
            .done(response => {
                table.ajax.reload(() => loadForm($('#diskon').val()));
                
            })
            .fail(errors => {
                alert('Tidak dapat menyimpan data');
                return;
            });
        }

        function loadForm(diskon = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());
        $.get(`{{ url('/pembelian_detail/loadform') }}/${diskon}/${$('.total').text()}`)
            .done(response => {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#bayarrp').val('Rp. '+ response.bayarrp);
                $('#bayar').val(response.bayar);
                $('.tampil-bayar').text('Rp. '+ response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);
            })
            .fail(errors => {
                alert('Tidak dapat menampilkan data');
                return;
            })
    }
        
    </script>
@endpush