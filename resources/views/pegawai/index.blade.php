@extends('layouts.app')

@section('title', 'Daftar Pegawai')

@section('page-title', 'Daftar Pegawai')

@section('styles')
 <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
@endsection

@section('content')
    <div class=" bg-gradient-primary  pt-md-6"> </div>

    <div class="col">
      @include('includes/message')
      <div class="card shadow mt-4">
        <div class="card-header border-0 ">
          @role('administrator')
            <h3 style="display: inline" class="mb-0">List Daftar Pegawai</h3>
            <a href="{{ route('users.index') }}" class="btn btn-primary float-right"> <i class="fa fa-user"></i> User management</a>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success float-right mr-3" data-toggle="modal" data-target="#exampleModal">
              <i class="fa fa-user"></i> Perbarui massal data pegawai
            </button>
          @endrole
        </div>
        <table class="table align-items-center table-flush" id="pegawai">
            <thead class="thead-light">
              <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">NIP</th>
                <th scope="col">Jabatan</th>
                <th scope="col">Golongan</th>
                <th scope="col">Image</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
      </div>
    </div>
   
  <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="fetch-sim-asn" method="POST">

             <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Perbarui data pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row" style="margin-bottom: 30px">
                  <div class="col-md-12">
                      <div class="alert alert-info" style="font-weight: 500">
                        <b>Info!</b> Disarankan untuk memperbarui data pegawai maksimal 5 pegawai per request
                        <br>
                        <b>Info!</b> Data akan diambil dari SIM-ASN Kementan secara massal
                        
                      </div>  
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label for="halaman_awal">Dari</label>
                    <input type="number" name="dari" min="1" class="form-control" placeholder="Dari pegawai ke">
                  </div>

                  <div class="col-md-6">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" min="1" class="form-control" placeholder="Jumlah Pegawai yang akan di ambil datanya">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" id="btn-perbarui" class="btn btn-primary">Perbarui</button>
              </div>
          </form>
        </div>
      </div>
    </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(function () {

        const token = '{{ auth()->user()->api_token }}';

        const table = $('#pegawai').DataTable({

            "processing": true,
            "serverSide": true,
            "ajax":{
               "url": "{{ route('pegawai.api.table') }}",
               "method" : "POST",
               "dataType": "json",
               "headers": {
                  "Accept": "application/json",
                  "Authorization": "Bearer " + token
              },
            },
            "columns": [
              { "data": "DT_RowIndex", orderable: false, searchable: false },
              { "data": "pegawai.nama" },
              { "data": "pegawai.nip" },
              { "data": "pegawai.jabatan" },
              { "data": "pegawai.gol_akhir" },
              { "data": "pegawai.image" },
              { "data": "action" }
            ],
            columnDefs: [
            { targets: 2,
              render: function(data) {
                return `
                <a href='{{ route('pegawai.detail') }}/${data}'>${data}</a>
                `
              }
            }, 
            { targets: 5,
              render: function(data) {
                return `
                <div class="circular--portrait">
                  <img src="http://simasn.pertanian.go.id/simasn/fotoprofil/${data}" />
                </div>
                `
              }
            }   
          ]  
    
        });

        $('#fetch-sim-asn').submit(function(e){
            e.preventDefault();

            const dari = $('input[name="dari"]').val();
            const jumlah = $('input[name="jumlah"]').val();

            $.ajax({
                url : '{{ route('pegawai.fetch') }}',
                method : 'POST',
                data : {
                    dari :  dari,
                    jumlah : jumlah
                },
                beforeSend: function(){
                    $('#btn-perbarui').text('Loading...');
                    $('#btn-perbarui').prop('disabled', 'disabled');
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function(response){
                
                $('#preloader').remove();
                $('#btn-perbarui').text('Perbarui');
                $('#btn-perbarui').prop('disabled', false);
                $('#exampleModal').modal('hide');

                $('#message-flash').html(`
                    <div class="alert ${response.alert}">${response.message}</div>
                `);

                table.ajax.reload(null, false);

            }); 
        });

    });/*End Ready*/


  </script>
@endsection