@extends('adminlte::page')

@section('title', 'Keuangan Kapal | Pendapatan')

@section('content_header')
<h5 class="pl-3"><b>Pendapatan</b></h5>
@endsection

@section('content')
<!-- <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
<meta name="csrf-token" content="{{ csrf_token() }}" />
@if(!empty(Auth::user()->id_perusahaan))
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm">
                <div class="row justify-content-start pl-2 pt-2">
                    <a href="{{ route('list_proyek') }}"><button type="button" class="btn btn-sm btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Kembali</button></a>
                    <a href="#"><button type="button" class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> Pendapatan</button></a>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="card-header">
        <div class="row">
            <div class="col-sm">
                @if(Auth::user()->role == 1 || Auth::user()->role == 2)
                <div class="row justify-content-start pl-2 pt-2">
                    <a href="#"><button type="button" class="btn btn-sm btn-primary mr-2 " data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus"></i> Tambah</button></a>
                </div>
                @endif
            </div>
        </div>
    </div> --}}
    <!-- /.card-header -->

    <div class="card-body ">
        <div class="dataTables_wrapper">
            <table id="myTable" class="display table table-stripped table-hover table-condensed table-sm dataTable">
                <thead>
                    <tr style="text-align: center">
                        <th scope="col">Jenis Proyek</th>
                        <th scope="col">Nama Pendapatan</th>
                        <th scope="col">Pendapatan Proyek</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($akunTransaksiProjeks as $projeks)
                    <tr style="text-align: center">
                        <td>
                            Pendapatan
                        </td>
                        <td>
                            {{ $projeks->namaManajemen }}
                        </td>
                        <td>
                            Jumlah
                        </td>
                        <td>
                            <button id="bEdit" type="button" class="btn btn-sm btn-link p-0 mx-1" data-toggle="modal" data-target="#editModal"><i class="fas fa-pencil-alt" > </i></button>
                            <button id="bElim" type="button" class="btn btn-sm btn-link p-0 mx-1" ><i class="fas fa-trash-alt" > </i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pendapatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-transaksi" method="post" action="{{ route('management_projek.pendapatan.edit',['id_proyek' => 1]) }}">
                        @csrf
                        <input id="edit-id" name="id" type="hidden" class="form-control">
                        <div class="form-group">
                            <label for="edit-namapendapatan">Nama Pendapatan</label>
                            <input autocomplete="off" type="text" id="edit-namapendapatan" name="namapendapatan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="edit-pendapatanproyek">Pendapatan Proyek</label>
                            <input autocomplete="off" type="number" id="edit-pendapatanproyek" name="pendapatanproyek" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="edit-transaksi">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
    </div>
    <!-- /.card-footer -->
</div>
@endif
@endsection

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<style>
.content {
    font-size: 12px;
}
</style>
<meta name="csrf-token" content="{{ Session::token() }}">
@endsection

@section('js')
<script src="https://unpkg.com/autonumeric"></script>
<script type="text/javascript">
    $(document).ready(function() {
            $('#myTable').DataTable( {

                order: [[0, 'asc'], [1, 'asc'], [2, 'asc']],
                rowGroup: {
                    dataSrc: [ 0 ] //parents
                },
                columnDefs: [ {
                    targets: [ 0 ], //hiden header coloum (dibalik" sama aja)
                    visible: false
                } ]
            } );
        } );
</script>

<script src="{{ asset('js/bootstable-list-proyek.js') }}"></script>

@endsection