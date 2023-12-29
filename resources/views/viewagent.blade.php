@extends('layouts.main')
@section('content')

@php
    use Carbon\Carbon;
@endphp
 <section class="main-content">
    <div class="col-md-12">
        <div class="col-md-3">
            <div class="nbox">
                <h3 class="text-center mt-4" style="color: rgb(225 56 56 / 88%); font-family:trebuchet ms">Agent Detials</h3>
                <p class="ms-4"><b>Username:</b> {{$agent->username}}</p>
                <p><b>Name:</b> {{$agent->name}}</p>
                <p><b>Revenue:</b> {{$agent->revenue}}</p>    
                <p><b>Type:</b> {{$agent->type}}</p>
                <p><b>Email:</b> {{$agent->email}}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="nbox nu">
                <div class="nbox1">
                    <h3 class="text-center mt-4" style="color:#ebad10; font-family:trebuchet ms">Credit: {{$agent->credit}}</h3>
                </div>
             
            </div>
        </div>
        <div class="col-md-3">
            <div class="nbox">
                <h3 class="text-center mt-4" style="color:teal; font-family:trebuchet ms">last week </h3>
                <p><b>Total Played:</b> </p>
                <p><b>Total Won:</b> </p>
                <p><b>End Point:</b> </p>
                 
            </div>
        </div>
        <div class="col-md-3">
            <div class="nbox">
                <h3 class="text-center mt-4" style="color:rgb(128, 0, 128); font-family:trebuchet ms">this week </h3>
                <p><b>Total Played:</b> </p>
                <p><b>Total Won:</b> </p>
                <p><b>End Point:</b> </p>
                 
            </div>        
        </div>
    </div>
@endsection 