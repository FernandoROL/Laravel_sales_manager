@extends('index')

@section('content')
    <form class="form" method="POST" action="{{ route('update.user', $findUser->id) }}">
        @csrf
        @method('PUT')
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Update user</h1>
        </div>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" value="{{ isset($findUser->name) ? $findUser->name : old('name') }}" class="form-control @error('name') is-invalid @enderror" name="name">
            @if ($errors->has('name'))
              <div class="invalid-feedback"> {{$errors->first('name')}} </div>
            @endif
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input value="{{ isset($findUser->email) ? $findUser->email : old('email') }}" class="form-control  @error('email') is-invalid @enderror" name="email" placeholder="email@example.com">
            @if ($errors->has('email'))
              <div class="invalid-feedback"> {{$errors->first('email')}} </div>
            @endif
          </div>
          <div class="mb-3">
            <label class="form-label">New password</label>
            <input type="password" class="form-control  @error('password') is-invalid @enderror" name="password">
            @if ($errors->has('password'))
              <div class="invalid-feedback"> {{$errors->first('password')}} </div>
            @endif
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>        
    </form>
@endsection