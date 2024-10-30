@extends('index')


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Users</h1>
    </div>
    <div>
        <form action="{{ route('users.index') }}" method="get">
            <input type="text", name="search" placeholder="Digite o nome">
            <button>Search</button>
            <a type="button" href="{{ route('register.user') }}" class="btn btn-success float-end btn-sm">Add User</a>
        </form>

        <div class="table-responsive small">
            @if ($findUser->isEmpty())
                <p>No data found</p>
            @else
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($findUser as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href=" {{ route('update.user', $user->id )}}" class="btn btn-light btn-sm">Edit</a>
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <a onclick="deletePagingRegistry('{{ route('users.delete') }}', {{ $user->id }})" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            @endif
          </div>
    </div>
@endsection
