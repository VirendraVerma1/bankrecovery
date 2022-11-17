@extends('main')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
      <h1>Data</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('users')}}">Dashboard</a></li>
          <li class="breadcrumb-item active">Data</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
            <div class="card-body">
                <form action="{{ route('import') }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" 
                           class="form-control">
                    <br>
                    <button class="btn btn-success">
                          Import User Data
                       </button>
                    <!-- <a class="btn btn-warning" 
                       href="{{ route('export-users') }}">
                              Export User Data
                      </a> -->
                </form>
            </div>





            <div class="card">
            <div class="card-body">
              <h5 class="card-title">Data</h5>
              <!-- Table with stripped rows -->
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">File Name</th>
                    <th scope="col">Uploaded Date</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
               
                  @foreach($excelfile as $data)
                  <tr>
                    <th scope="row">{{$data->id}}</th>
                    <td>{{$data->storedname}}</td>
                    <td>{{$data->created_at}}</td>
                    <td>
                      <!-- <a class="btn btn-info" href="{{route('user_edit',$data->id)}}">edit</a>&emsp; -->
                      <a class="btn btn-danger" href="{{route('data_delete_file',$data->id)}}">delete</a>&emsp;
                    </td>
                  </tr>
                  @endforeach
                  
                </tbody>
              </table>
              {{ $excelfile->links() }}

              {{-- 
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Aggrement No</th>
                    <th scope="col">Region</th>
                    <th scope="col">Branch</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">GV in Lakhs</th>
                    <th scope="col">Make/Model No.</th>
                    <th scope="col">Regdnum</th>
                    <th scope="col">Chasisnum</th>
                    <th scope="col">Enginenum</th>
                    <th scope="col">RRM mail id</th>
                    <th scope="col">Expired Date</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
               
                  @foreach($all_data as $data)
                  <tr>
                    <th scope="row">{{$data->id}}</th>
                    <td>{{$data->agreementno}}</td>
                    <td>{{$data->region}}</td>
                    <td>{{$data->branch}}</td>
                    <td>{{$data->customername}}</td>
                    <td>{{$data->gv}}</td>
                    <td>{{$data->make_model}}</td>
                    <td>{{$data->regdnum}}</td>
                    <td>{{$data->chasisnum}}</td>
                    <td>{{$data->enginenum}}</td>
                    <td>{{$data->rrmname}}</td>
                    <td>{{$data->rrmemail}}</td>
                    <td>{{$data->expirydate}}</td>
                    <td>
                      <a class="btn btn-danger" href="{{route('data_delete',$data->id)}}">delete</a>&emsp;
                    </td>
                  </tr>
                  @endforeach
                  
                </tbody>
              </table> 

              {{ $all_data->links() }}
              --}}

              
              <!-- End Table with stripped rows -->

            </div>
        </div>


</main>
@endsection