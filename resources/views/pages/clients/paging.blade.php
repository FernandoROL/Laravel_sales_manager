@extends('index')


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Clients</h1>
    </div>
    <div>
        <form action="{{ route('clients.index') }}" method="get">
            <input type="text", name="search" placeholder="Digite o nome">
            <button>Search</button>
            <a type="button" href="{{ route('register.client') }}" class="btn btn-success float-end btn-sm">Add Client</a>
        </form>

        <div class="table-responsive small">
            @if ($findClient->isEmpty())
                <p>No data found</p>
            @else
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Adress</th>
                  <th>Road</th>
                  <th>Zip Code</th>
                  <th>Neighborhood</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($findClient as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->adress }}</td>
                        <td>{{ $client->road }}</td>
                        <td>{{ $client->zip }}</td>
                        <td>{{ $client->neighborhood }}</td>
                        <td>
                            <a href=" {{ route('update.client', $client->id )}}" class="btn btn-light btn-sm">Edit</a>
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <a onclick="deletePagingRegistry('{{ route('clients.delete') }}', {{ $client->id }})" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            @endif
          </div>
    </div>
@endsection
