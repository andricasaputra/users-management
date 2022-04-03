@extends('layouts.app')

@section('title', 'Roles')

@section('page-title', 'Roles')

@section('content')

<div class=" bg-gradient-primary  pt-md-6"> </div>

    <div class="col">
        @include('includes/message')
        <div class="card shadow mt-4">
            <h1>
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>
                        <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a>
                        <a href="{{ route('roles.create') }}" class="btn btn-success" ull-right>Add Role</a>
                    </div>
                </div>
            </h1>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Permissions</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>

                                <td>{{ $role->name }}</td>

                                <td>{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                                <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-info btn-xs" style="margin-right: 3px;">Edit</a>

                                {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id] ]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs confirm']) !!}
                                {!! Form::close() !!}

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No roles available</td> 
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
   
@endsection()

@section('scripts')
    <script>
        $('.confirm').on('click', function (e) {
            if (confirm('Apakah anda yakin akan menghapus data ini?')) {
                return true;
            }
            else {
                return false;
            }
        });
    </script>
@endsection()