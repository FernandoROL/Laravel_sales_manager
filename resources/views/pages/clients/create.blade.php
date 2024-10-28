@extends('index')

@section('content')
    <form class="form" method="POST" action="{{ route('register.client') }}">
        @csrf
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Add new client</h1>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Name</label>
                <input type="text" value="{{ @old('name') }}" class="form-control @error('name') is-invalid @enderror"
                    name="name" placeholder="Name">
                @if ($errors->has('name'))
                    <div class="invalid-feedback"> {{ $errors->first('name') }} </div>
                @endif
            </div>
            <div class="col mb-3">
                <label class="form-label">Email</label>
                <input type="text" value="{{ @old('email') }}" class="form-control  @error('email') is-invalid @enderror"
                    name="email" placeholder="email@example.com">
                @if ($errors->has('email'))
                    <div class="invalid-feedback"> {{ $errors->first('email') }} </div>
                @endif
            </div>
        </div>
        <div class="col mb-3">
            <label class="form-label">Address 1</label>
            <input type="text" value="{{ @old('adress') }}" class="form-control  @error('adress') is-invalid @enderror"
                name="adress" placeholder="1234 Example St" id="adress">
            @if ($errors->has('adress'))
                <div class="invalid-feedback"> {{ $errors->first('adress') }} </div>
            @endif
        </div>
        <div class="col mb-3">
            <label class="form-label">Address 2</label>
            <input type="text" value="{{ @old('road') }}" class="form-control  @error('road') is-invalid @enderror"
                name="road" placeholder="Apartment, studio, or floor" id="road">
            @if ($errors->has('road'))
                <div class="invalid-feedback"> {{ $errors->first('road') }} </div>
            @endif
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Neighborhood</label>
                <input type="text" value="{{ @old('neighborhood') }}"
                    class="form-control @error('neighborhood') is-invalid @enderror" name="neighborhood" id="neighborhood">
                @if ($errors->has('neighborhood'))
                    <div class="invalid-feedback"> {{ $errors->first('neighborhood') }} </div>
                @endif
            </div>
            <div class="col mb-3">
                <label class="form-label">Zip code</label>
                <input value="{{ @old('zip') }}" class="form-control  @error('zip') is-invalid @enderror" name="zip"
                    placeholder="000000" id="zip">
                @if ($errors->has('zip'))
                    <div class="invalid-feedback"> {{ $errors->first('zip') }} </div>
                @endif
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
