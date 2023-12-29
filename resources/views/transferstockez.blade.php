@extends('layouts.main')
@section('content')
<?php
use App\Models\admin;
use App\Models\supertockez;
use App\Models\stockez;

?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link
       rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
       />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
 </head>
 <section class="main-content">
    <div class="col-md-12">
       <div class="panel panel-primary">
          <div class="panel-heading text-left">Transfer Credit to {{$sup_name}}</div>
          <div class="panel-body">
             <form class="form-horizontal col-md-offset-3" method="POST" action="{{route('savetransferstockez')}}">
               @csrf
               @php 
               if(session('user_type') == 'admin') {
               $user = Admin::find(session('user_id'));
               }
               if(session('user_type') == 'superstockez') {
               $user = superstockez::find(session('user_id'));
               }
               if(session('user_type') == 'stockez') {
               $user = stockez::find(session('user_id'));
               }
            @endphp
               <div class="form-group text-center">
                   <div class="col-sm-6">
                      <input type="text" class="form-control" id="username" value="available Credits:{{$user->credit}}" disabled required style="border:1px solid rgb(255, 0, 0);color:#ff000d;">
                   </div>
               </div><br><br>
               @foreach($sup as $sup)
                  <div class="form-group text-center">
                      <div class="col-sm-6">
                         <input type="text" class="form-control" id="username" value="Username: {{$sup->username}}" disabled required style="border:1px solid rgb(140, 0, 255);color:#0011ff;">
                      </div>
                  </div>

                  <div class="form-group text-center">
                      <div class="col-sm-6">
                         <input type="text" class="form-control" id="username" value="Credits: {{$sup->credit}}" disabled required style="border:1px solid rgb(140, 0, 255);color:#0011ff;">
                      </div>
                  </div>

               @endforeach
                <div class="form-group text-center">
                   <label class="control-label col-sm-2">Enter Credit Amount:</label>
                   <div class="col-sm-6">
                      <input type="text" class="form-control" id="username" placeholder="Enter Credit Amount" name="credit" required>
                   </div>
                </div>
                 <input type="hidden" name="id" value={{$id}}>
                <div class="form-group">
                   <div class="col-sm-offset-2 col-sm-2 ">
                      <input type="submit" name="submit" class="btn btn-info" value="Submit">
                      
                   </div>
                </div>
             </form>
          </div>
       </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
<script src="js/index.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function () {
     

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

<section>
@endsection   