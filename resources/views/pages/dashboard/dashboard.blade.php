@extends('index')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-5 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>
    <div class="row d-flex gap-5 px-5">
        <div class="card mb-5" style="width: 18rem;">
            <img src="images/mm.png" class="card-img-top mt-3" alt="...">
            <div class="card-body">
                <h5 class="card-title">Clients</h5>
                <p class="card-text">Clients page stats:</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Client count: {{ $totalClients }}</li>
                <li class="list-group-item">Last client: {{ $lastClient }}</li>
            </ul>
            <div class="card-body">
                <a href="{{ route('clients.index') }}" class="card-link">Clients page</a>
            </div>
        </div>

        <div class="card mb-5" style="width: 18rem;">
            <img src="images/mm.png" class="card-img-top mt-3" alt="...">
            <div class="card-body">
                <h5 class="card-title">Products</h5>
                <p class="card-text">Products page stats:</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Product count: {{ $totalProducts }}</li>
                <li class="list-group-item">Highest price: {{ 'R$' . '' . number_format($highestValueProduct, 2, ',', '.') }}
                </li>
                <li class="list-group-item">Average price: {{ 'R$' . '' . number_format($productMean, 2, ',', '.') }}</li>
            </ul>
            <div class="card-body">
                <a href="{{ route('products.index') }}" class="card-link">Products page</a>
            </div>
        </div>
        <div class="card mb-5" style="width: 18rem;">
            <img src="images/mm.png" class="card-img-top mt-3" alt="...">
            <div class="card-body">
                <h5 class="card-title">Sales</h5>
                <p class="card-text">Sales page stats:</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Sales made: {{ $totalSales }}</li>
                <li class="list-group-item">Last sale number: {{ $lastSale }}</li>
                <li class="list-group-item">Most sold: {{ $mostSold }}</li>
            </ul>
            <div class="card-body">
                <a href="{{ route('salles.index') }}" class="card-link">Sales page</a>
            </div>
        </div>

        <div class="card mb-5" style="width: 18rem;">
            <img src="images/mm.png" class="card-img-top mt-3" alt="...">
            <div class="card-body">
                <h5 class="card-title">Users</h5>
                <p class="card-text">Users page stats:</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">User count: {{ $totalUsers }}</li>
                <li class="list-group-item">Last registered: {{ $lastRegistered }}</li>
            </ul>
            <div class="card-body">
                <a href="#" class="card-link">Users page</a>
            </div>
        </div>
    </div>
@endsection
