@extends('main')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Users</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('users')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Users</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Total Users</h5>
              <a class="btn btn-primary" href="{{route('user_add')}}">Add User</a>&emsp;
              <!-- Table with stripped rows -->
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
               
                  @foreach($users as $user)
                  <tr>
                    <th scope="row">{{$user->id}}</th>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->phone}}</td>
                    <td>
                      <a class="btn btn-info" href="{{route('user_edit',$user->id)}}">edit</a>&emsp;
                      <a class="btn btn-danger" href="{{route('user_delete',$user->id)}}">delete</a>&emsp;
                    </td>
                  </tr>
                  @endforeach
                  
                </tbody>
              </table>
              {{ $users->links() }}
              <!-- End Table with stripped rows -->

            </div>
        </div>
</main><!-- End #main -->

@endsection