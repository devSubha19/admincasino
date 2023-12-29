@extends('layouts.main')
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Game</title>
    <link rel="stylesheet" href="css/style.css" />
    <link
       rel="stylesheet"
       href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
       />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js">

 </head>
@section('content')
<section class="main-content">
    <div class="col-md-12">
      <div class="panel panel-primary">
         <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6 text-left">Admin</div>
              {{-- <div class="col-xs-6 text-right"> <a href="addadmin" class="btn btn-info">Add admin</a></div> --}}
            </div>
      </div>
          <div class="panel-body">
             <table id="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Username</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Credit</th>
                          <th>Action</th> 

                      </tr>
                      
                  </thead>
                   <tbody>                
            @if($admin->isEmpty())
               <tr>
                   <td colspan="8" style="text-align:center;">No data available</td>
               </tr>
            @else
               @foreach ($admin as $admin)
               <tr>
                   <td>{{$admin->id}}</td>
                   <td>{{$admin->username}}</td>
                   <td>{{$admin->name}}</td>
                   <td>{{$admin->email}}</td>
                   <td>{{number_format($admin->credit, 2, '.', ',')}}</td>
                   <td class="text-center"><a href="{{ route('editadmin', ['id' => $admin->id]) }}" class="btn btn-info btn-sm">Edit</a> </td>
               </tr>
            @endforeach
            @endif
                   </tbody>
             
             </table>
          </div>
       </div>
</<section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
<script src="js/index.js"></script>
<script type="text/javascript">
$(document).ready(function () {
   $('#example').dataTable();
});
</script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function () {
      $('#example').dataTable();

        @if(Session::has('success'))
            toastr.options = {
                "closeButton": true,
                "timeOut": 3000  
            };
            toastr.success("{{ session('success') }}");
        @endif

      
        @if(Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "timeOut": 3000  
            };
            toastr.error("{{ session('error') }}");
        @endif
    });
</script>





@endsection
