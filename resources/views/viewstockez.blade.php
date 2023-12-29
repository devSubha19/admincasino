@extends('layouts.main')
@section('content')

@php
    use Carbon\Carbon;
@endphp
 <section class="main-content">
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="nbox">
                <h3 class="text-center mt-4" style="color: rgb(225 56 56 / 88%); font-family:trebuchet ms">Stockez Detials</h3>
                <p class="ms-4"><b>Username:</b> {{$sup->username}}</p>
                <p><b>Name:</b> {{$sup->name}}</p>
                <p><b>Revenue:</b> {{$sup->revenue}}</p>
                <p><b>Type:</b> {{$sup->type}}</p>
                <p><b>Email:</b> {{$sup->email}}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="nbox nu">
                <div class="nbox1">
                    <h3 class="text-center mt-4" style="color:#ebad10; font-family:trebuchet ms">Credit: {{$sup->credit}}</h3>
                </div>
                <div class="nbox2">
                    @php 
                        
                        $tot = 0;
                        foreach($agent as $ag){
                            $tot += 1;
                        }
                    @endphp
                    <p><b>Total Agent:</b> {{$tot}}</p>
                    <p><b>Joined:</b> {{Carbon::parse($sup->createdate)->toDateString()}}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="nbox">
                <h3 class="text-center mt-4" style="color:teal; font-family:trebuchet ms">last week </h3>
                <p><b>Total Played:</b> </p>
                <p><b>Total Won:</b> </p>
                <p><b>End Point:</b> </p>
                <p><b>Stockez Point:</b> </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="nbox">
                <h3 class="text-center mt-4" style="color:rgb(128, 0, 128); font-family:trebuchet ms">this week </h3>
                <p><b>Total Played:</b> </p>
                <p><b>Total Won:</b> </p>
                <p><b>End Point:</b> </p>
                <p><b>Stockez Point:</b> </p>
            </div>        
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-primary">
           <div class="panel-heading text-left">Agents</div>
             <div class="panel-body">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Revenue</th>
                            <th>Type</th>
                            <th>Credit</th>
                            <th>Action</th>
                        </tr>
                        
                    </thead>
                     <tbody> 
                       
              @if($agent->isEmpty())
                 <tr>
                     <td colspan="8" style="text-align:center;">No data available</td>
                 </tr>
              @else
                 @foreach ($agent as $spz)
                  <tr>                                   
                     <td>{{$spz->id}}</td> 
                     <td><a href="{{route('viewagent', ['id'=> $spz->id])}}"><u>
                       <i class="fa-solid fa-eye"></i>{{$spz->username}}   
                     </u></a></td>
                     <td>{{$spz->name}}</td> 
                     <td>{{$spz->email}}</td> 
                     <td>{{round($spz->revenue)}}</td> 
                     <td>{{$spz->type}}</td> 
                     <td>{{round($spz->credit)}}</td> 
                    <td>
                         <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                           <div class="btn-group mr-2" role="group" aria-label="First group">  
                           <a href='{{route('editagent', ['id' => $spz->id ])}}' class="btn btn-info btn-sm">Edit</a>
                           <a href='{{route('deleteagent', ['id' => $spz->id ])}}' class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                           <a title="Transfer Point" href='{{route('transferagent', ['id' => $spz->id ])}}' class="btn btn-success btn-sm"><i class="fa fa-credit-card"></i></a>
                           <!-- <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-credit-card"></i></button> -->
                             <a title="Adjust Point" href="{{route('adjustagent', ['id' => $spz->id ])}}" class="btn btn-warning btn-sm"><i class="fa fa-credit-card"></i></a>
                          
                          @if($spz->status==0)  
                             <a href="{{route('banagent', [ 'id' => $spz->id, 'status' => $spz->status ])}}" class="btn btn-success btn-sm"><i class="fa-solid fa-check"></i></a>
                          @else
                             <a href="{{route('banagent', [ 'id' => $spz->id, 'status' => $spz->status ])}}" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i></a>   
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
    </div>
</section>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
 <script src="js/index.js"></script>
 <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
 <script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">
 <script type="text/javascript">
 $(document).ready(function () {
     $('#example').dataTable();
 });
</script>
 

@endsection



