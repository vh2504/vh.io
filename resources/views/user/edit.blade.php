@extends('layouts.app2')

@section('content')

<h1 class="text-center p-3">Edit User</h1>
    <div class="main ">
      @csrf
        <form action= "/manage/update/{{$user->id}}" method="POST" class="w-50 mx-auto">
            @csrf
            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" >
            </div>
            <div class="form-group">
              <label for="phone">Phone:</label>
              <input type="text" class="form-control"  id="phone" name = "phone" value="{{isset($user->phone)? $user->phone : '' }}">
            </div>
            <div class="form-group">
              <label for="address">Address:</label>
              <input type="text" class="form-control"  id="address" name = "address" value="{{isset($user->address)? $user->address : '' }}">
            </div>
            </div>
            <button class="btn btn-secondary" style="margin-left: 600px;"><a class="text-white" href="{{route('list')}}" >Cancel</a></button>
            <button type="submit" class="btn btn-primary" style="margin-left: 0x;">Edit</button>
          </form>
    </div>    
@endsection