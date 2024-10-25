@extends('index')


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Produtos</h1>
    </div>
    <div>
        <form action="{{ route('products.index') }}" method="get">
            <input type="text", name="search" placeholder="Digite o nome">
            <button>Search</button>
            <a type="button" href="{{ route('register.product') }}" class="btn btn-success float-end btn-sm">Add Product</a>
        </form>

        <div class="table-responsive small">
            @if ($findProduct->isEmpty())
                <p>No data found</p>
            @else
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Price</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($findProduct as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ 'R$' .  '' . number_format($product->price, 2, ',', '.') }}</td>
                        <td>
                            <a href="" class="btn btn-light btn-sm">Edit</a>
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <a onclick="deletePagingRegistry('{{ route('products.delete') }}', {{ $product->id }})" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
            @endif
          </div>
    </div>
@endsection
