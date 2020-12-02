@extends('layouts.app2')

@section('content')

<h1 class="text-center p-3">User List</h1>
<table class="table  text-center">
    <thead class="thead-dark">
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Phone</th>
        <th scope="col">Address</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users  as $user)
        <tr>
          <th scope="row">{{ $user->id }}</th>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->phone }}</td>
          <td>{{ $user->address }}</td>
          <td>
            <div class="btn-group">
              <button class="btn btn-primary"><a class="text-white" href="/manage/edit/{{ $user->id }}">Edit</a></button>
              <button class="btn btn-warning"><a class="text-white" href="/manage/delete/{{ $user->id }}">Delete</a></button>
            </div>
          </td>
        </tr>
      @endforeach
      
    </tbody>
  </table>
  
    
@endsection