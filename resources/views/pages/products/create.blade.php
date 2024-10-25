@extends('index')

@section('content')
    <form class="form" method="POST" action="{{ route('register.product') }}">
        @csrf
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Add new product</h1>
        </div>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name">
          </div>
          <div class="mb-3">
            <label class="form-label">Price</label>
            <input class="form-control" name="price">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>        
    </form>
@endsection
