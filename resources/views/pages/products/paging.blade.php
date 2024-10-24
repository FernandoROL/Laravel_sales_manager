@extends('index');


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Produtos</h1>
    </div>
    <div>
        <form action="" method="get">
            <input type="text", name="pesquisar" placeholder="Digite o nome">
            <button>Pesquisar</button>
            <a type="button" href="" class="btn btn-success float-end">Include Product</a>
        </form>

        <div class="table-responsive small">
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
                            <a href="" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
    </div>
@endsection
