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
          <div class="panel-heading text-left"> Agent
             <div class="right"> 
                <a href="addagent" class="btn btn-info">Add Agent</a>
             </div>

          </div>
          <div class="panel-body">
             <table id="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Username</th>
                          <th>Name</th>
                          <th>Stockez</th>
                          <th>Email</th>
                          <th>Revenue</th>
                          <th>Type</th>
                          <th>Credit</th>
                          <th>Action</th>
                      </tr>
                      
                  </thead>
                   <tbody> 
                     
            @if(empty($agent))
               <tr>
                   <td colspan="9" style="text-align:center;">No data available</td>
               </tr>
            @else
               @foreach ($agent as $spz)
                <tr>                                   
                   <td>{{$spz['id']}}</td> 
                   <td><a href="{{route('viewagent', ['id'=> $spz['id']])}}"><u>
                     <i class="fa-solid fa-eye"></i>{{$spz['username']}}   
                   </u></a></td>
                   <td>{{$spz['name']}}</td> 
                   <td>{{$spz['sto_ckez']['username']}}</td> 
                   <td>{{$spz['email']}}</td> 
                   <td>{{round($spz['revenue'])}}</td> 
                   <td>{{$spz['type']}}</td> 
                   <td>{{round($spz['credit'])}}</td> 
                  <td>
                       <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                         <div class="btn-group mr-2" role="group" aria-label="First group">  
                         <a href='{{route('editagent', ['id' => $spz['id']])}}' class="btn btn-info btn-sm">Edit</a>
                         <a href='{{route('deleteagent', ['id' => $spz['id']])}}' class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                         <a title="Transfer Point" href='{{route('transferagent', ['id' => $spz['id'] ])}}' class="btn btn-success btn-sm"><i class="fa fa-credit-card"></i></a>
                         <!-- <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-credit-card"></i></button> -->
                           <a title="Adjust Point" href="{{route('adjustagent', ['id' => $spz['id'] ])}}" class="btn btn-warning btn-sm"><i class="fa fa-credit-card"></i></a>
                        
                        @if($spz['status']==0)  
                           <a href="{{route('banagent', [ 'id' => $spz['id'], 'status' => $spz['status'] ])}}" class="btn btn-success btn-sm"><i class="fa-solid fa-check"></i></a>
                        @else
                           <a href="{{route('banagent', [ 'id' => $spz['id'], 'status' => $spz['status'] ])}}" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i></a>   
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

 
 
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">

@include('toast')
@endsection
