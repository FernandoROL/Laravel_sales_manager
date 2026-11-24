<nav id="sidebarMenu" class="app-nav collapse d-md-block" aria-label="Menu">
    @foreach (['dashboard.index' => 'Painel', 'clients.index' => 'Clientes', 'products.index' => 'Produtos', 'salles.index' => 'Vendas', 'users.index' => 'Usuários'] as $route => $label)
        <a href="{{ route($route) }}" @if (request()->routeIs($route)) aria-current="page" @endif>{{ $label }}</a>
    @endforeach
</nav>
