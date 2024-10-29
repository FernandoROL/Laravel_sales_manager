@extends('index')


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Sales</h1>
    </div>
    <div>
        <form action="{{ route('salles.index') }}" method="get">
            <input type="text", name="search" placeholder="Digite o nome">
            <button>Search</button>
            <a type="button" href="{{ route('register.salle') }}" class="btn btn-success float-end btn-sm">Make sale</a>
        </form>

        <div class="table-responsive small">
            @if ($findSalle->isEmpty())
                <p>No data found</p>
            @else
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Sale Number</th>
                  <th>Client</th>
                  <th>Product</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($findSalle as $salle)
                    <tr>
                        <td>{{ $salle->salleNumber }}</td>
                        <td>{{ $salle->client->name }}</td>
                        <td>{{ $salle->product->name }}</td>
                        <td>
                          <a href="{{ route('SendEmailRecipt.salle', $salle->id) }}" type="button" class="btn btn-light btn-sm">Teste</a>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            @endif
          </div>
    </div>
@endsection
