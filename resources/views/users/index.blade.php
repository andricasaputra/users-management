@extends('layouts.app')

@section('title', 'Users')

@section('page-title', 'Users Management')

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
            <h3 style="display: inline" class="mb-0">Users List</h3>
            <a href="{{ route('pegawai.index') }}" class="btn btn-primary float-right mr-3"> <i class="fa fa-user"></i> Pegawai</a>
            <a href="{{ route('users.create') }}" class="btn btn-primary float-right mr-3"> <i class="fa fa-plus"></i> Tambah user baru</a>
          @endrole
        </div>
        <table class="table align-items-center table-flush text-center" id="users">
            <thead class="thead-light">
              <tr>
                <th scope="col">No</th>
                <th scope="col">Username</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
      </div>
    </div>

@endsection

@section('scripts')
  <script>
    $(document).ready(function () {

        const token = '{{ auth()->user()->api_token }}';

        const table = $('#users').DataTable({

            "processing": true,
            "serverSide": true,
            "ajax":{
               "url": "{{ route('users.api.table') }}",
               "method" : "POST",
               "dataType": "json",
               "headers": {
                  "Accept": "application/json",
                  "Authorization": "Bearer " + token
              },
            },
            "columns": [
              { "data": "DT_RowIndex", orderable: false, searchable: false },
              { "data": "username" },
              { "data": "action" }
            ],
        });

        $(document).on('click', '.delete-user-btn', e => {

          e.preventDefault();

          if (!confirm('Yakin ingin menghapur data ini?')) return;
          
          window.location = '{{ route('users.destroy') }}/' +  e.target.dataset.id;

        });

    });/*End Ready*/

  </script>
@endsection