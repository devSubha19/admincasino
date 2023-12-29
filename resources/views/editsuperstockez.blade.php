@extends('layouts.main')
@section('content')
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css" />
    <link
       rel="stylesheet"
       href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
       />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
 </head>
 <section class="main-content">
    <div class="col-md-12">
       <div class="panel panel-primary">
          <div class="panel-heading text-left">edit Super-Stockez</div>
          <div class="panel-body">
             <form class="form-horizontal col-md-offset-3" method="POST" action="{{route('saveeditsuperstockez')}}">
               @csrf
               <input type="hidden" value="{{$id}}" name="id">
                <div class="form-group text-center">
                   <label class="control-label col-sm-2" >Username:</label>
                   <div class="col-sm-6">
                      <input type="text" class="form-control" readonly value="{{$sup->username}}" id="username" placeholder="Enter Username" name="username" required>
                   </div>
                </div>
               
                <div class="form-group">
                  <label class="control-label col-sm-2" for="pwd">Password: </label>
                  <div class="col-sm-6 pass">          
                     <input type="password" class="form-control" name="password" value="{{$sup->password}}" id="pwd" placeholder="Enter password">
                     <span class="eye" onclick="showpass('pwd')">
                       <i class="fa-solid fa-eye-slash"></i>
                     </span>
                  </div>
               </div>
               
               <div class="form-group">
                  <label class="control-label col-sm-2" for="trans_pwd">Transaction Password:</label>
                  <div class="col-sm-6 pass">          
                     <input type="password" class="form-control" name="trans_password" id="trans_pwd" placeholder="Transaction password" value="{{$sup->tsn_psd}}" required>
                     <span class="eye" onclick="showpass('trans_pwd')">
                       <i class="fa-solid fa-eye-slash"></i>
                     </span>
                  </div>
               </div>
                

                <div class="form-group">
                   <label class="control-label col-sm-2" for="pwd">Name:</label>
                   <div class="col-sm-6">          
                      <input type="text" class="form-control"  name="name" placeholder="Enter Name" value="{{$sup->name}}"  required>
                   </div>
                </div>
                <div class="form-group">
                   <label class="control-label col-sm-2" >Email:</label>
                   <div class="col-sm-6">          
                      <input type="email" class="form-control" name="email" placeholder="Enter Email" value="{{$sup->email}}" required>
                   </div>
                </div>
                 
                <div class="form-group">
                   <label class="control-label col-sm-2" >Revenue(%):</label>
                   <div class="col-sm-6">          
                      <input type="text" value="{{$sup->revenue}}" class="form-control" name="revenue" id="pwd" placeholder="Enter Revenue"  required>
                   </div>
                </div>
                <div class="form-group">
                   <label class="control-label col-sm-2" name="type" >Select Type:</label>
                   <div class="col-sm-6">
                      <select name="type" class="form-control" id="" required>
                         <option value="">Select Type</option>
                         <option value="TN2" @if($sup->type == 'TN1') selected @endif>TN1</option>
                         <option value="TN3" @if($sup->type == 'TN2') selected @endif>TN2</option>
                      </select>
                   </div>
                </div>
              
                <div class="form-group">
                   <div class="col-sm-offset-2 col-sm-2 ">
                      <input type="submit" name="submit" class="btn btn-info" value="Submit">
                      <!-- <button type="submit"  name="submitqq" ></button> -->
                   </div>
                </div>
             </form>
          </div>
       </div>
<section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
<script src="js/index.js"></script>
@endsection   