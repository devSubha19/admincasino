@extends('layouts.main')
@section('content')
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css" />
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

 <section class="main-content">
    <div class="col-md-12">
       <div class="panel panel-primary">
          <div class="panel-heading text-left"> Stocekz
             <div class="right"> 
                <a href="addstockez" class="btn btn-info">Add Stocekz</a>
             </div>

          </div>
          <div class="panel-body">
             <table id="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Username</th>
                          <th>Name</th>
                          <th>SuperStockez</th>
                          <th>Email</th>
                          <th>Revenue</th>
                          <th>Type</th>
                          <th>Credit</th>
                          <th>Action</th>
                      </tr>
                      
                  </thead>
                   <tbody> 
                     
            @if(empty($stockez))
               <tr>
                   <td colspan="9" style="text-align:center;">No data available</td>
               </tr>
            @else
               @foreach ($stockez as $spz)
                <tr>                                   
                   <td>{{$spz['id']}}</td> 
                   <td><a href="{{route('viewstockez', ['id'=> $spz['id']])}}"><u>
                     <i class="fa-solid fa-eye"></i>{{$spz['username']}}   
                   </u></a></td>
                   <td>{{$spz['name']}}</td> 
                   <td>{{$spz['super_stockez']['username']}}</td> 
                   <td>{{$spz['email']}}</td> 
                   <td>{{$spz['revenue']}}</td> 
                   <td>{{$spz['type']}}</td> 
                   <td>{{$spz['credit']}}</td> 
                  <td>
                       <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                         <div class="btn-group mr-2" role="group" aria-label="First group">  
                         <a href='{{route('editstockez', ['id' => $spz['id']])}}' class="btn btn-info btn-sm">Edit</a>
                         <a href='{{route('deletestockez', ['id' => $spz['id']])}}' class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                         <a title="Transfer Point" href='{{route('transferstockez', ['id' => $spz['id'] ])}}' class="btn btn-success btn-sm"><i class="fa fa-credit-card"></i></a>
                         <!-- <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-credit-card"></i></button> -->
                           <a title="Adjust Point" href="{{route('adjuststockez', ['id' => $spz['id'] ])}}" class="btn btn-warning btn-sm"><i class="fa fa-credit-card"></i></a>
                        
                        @if($spz['status']==0)  
                           <a href="{{route('banstockez', [ 'id' => $spz['id'], 'status' => $spz['status'] ])}}" class="btn btn-success btn-sm"><i class="fa-solid fa-check"></i></a>
                        @else
                           <a href="{{route('banstockez', [ 'id' => $spz['id'], 'status' => $spz['status'] ])}}" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i></a>   
                        @endif 
                         </div>
                       </div>
                   </td>
                </tr>
            @endforeach
            @endif
                   </tbody>
             
             </table>
          </div>
       </div>
</section>

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



