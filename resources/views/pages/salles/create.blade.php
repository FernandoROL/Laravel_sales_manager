@extends('index')

@section('content')
    <form class="form" method="POST" action="{{ route('register.salle') }}">
        @csrf
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Add new sale</h1>
        </div>
        <div class="col mb-3">
            <label class="form-label">Sale Number</label>
            <input name="salleNumber" value="{{ $findSalleNumber }}" disabled class="form-control @error('salleNumber') is-invalid @enderror"
                name="salleNumber" placeholder="1234">
            @if ($errors->has('salleNumber'))
                <div class="invalid-feedback"> {{ $errors->first('salleNumber') }} </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Client</label>
                <select name="clientId" class="form-select mb-3" aria-label="Client ID">
                    <option selected disabled>Select the client ID</option>
                    @foreach ($findClient as $item)
                        <option value="{{ $item->id }}"> {{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Product</label>
                <select name='productId' class="form-select mb-3" aria-label="Product ID">
                    <option selected disabled>Select the product ID</option>
                    @foreach ($findProduct as $item)
                        <option value="{{ $item->id }}"> {{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
