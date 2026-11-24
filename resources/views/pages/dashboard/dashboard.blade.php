@extends('index')

@section('content')
<section class="sales-overview">
    <header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1>Painel de vendas</h1>
            <p class="period mb-0">{{ ucfirst($monthLabel) }}</p>
        </div>
        <a href="{{ route('register.salle') }}" class="btn btn-success">Registrar venda</a>
    </header>

    <dl class="sales-stats">
        <div><dt>Vendas no mês</dt><dd>{{ $summary->sales }}</dd></div>
        <div><dt>Clientes que compraram no mês</dt><dd>{{ $summary->buyers }}</dd></div>
        <div><dt>Vendas desde o início</dt><dd>{{ $totalSales }}</dd></div>
    </dl>

    <div class="sales-panels">
        <section class="sales-panel">
            <header><h2>Vendas recentes</h2><a href="{{ route('salles.index') }}">Ver todas</a></header>
            @if ($recentSales->isEmpty())
                <p>Nenhuma venda registrada. Para começar, cadastre um
                    <a href="{{ route('register.client') }}">cliente</a> e um
                    <a href="{{ route('register.product') }}">produto</a>.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr><th scope="col">Venda</th><th scope="col">Cliente</th><th scope="col">Produto</th><th scope="col">Data</th></tr></thead>
                        <tbody>
                        @foreach ($recentSales as $sale)
                            <tr>
                                <th scope="row">#{{ $sale->salleNumber }}</th>
                                <td>{{ $sale->client?->name ?? 'Cliente indisponível' }}</td>
                                <td>{{ $sale->product?->name ?? 'Produto indisponível' }}</td>
                                <td class="text-nowrap">{{ $sale->created_at?->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
        <section class="sales-panel">
            <header><h2>Mais vendidos no mês</h2></header>
            @if ($topProducts->isEmpty())
                <p>Nenhum produto vendido neste mês.</p>
            @else
                <ol class="product-ranking">
                    @foreach ($topProducts as $entry)
                        <li><span>{{ $entry->product?->name ?? 'Produto indisponível' }}</span>
                            <strong>{{ $entry->sales }} {{ $entry->sales == 1 ? 'venda' : 'vendas' }}</strong></li>
                    @endforeach
                </ol>
            @endif
        </section>
    </div>
</section>
@endsection
