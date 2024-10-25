@extends('index')

@section('content')
    <form class="form" method="POST" action="{{ route('register.product') }}">
        @csrf
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Add new product</h1>
        </div>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name">
            @if ($errors->has('name'))
              <div class="invalid-feedback"> {{$errors->first('name')}} </div>
            @endif
          </div>
          <div class="mb-3">
            <label class="form-label">Price</label>
            <input class="form-control  @error('price') is-invalid @enderror" name="price">
            @if ($errors->has('price'))
              <div class="invalid-feedback"> {{$errors->first('price')}} </div>
            @endif
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>        
    </form>
@endsection
