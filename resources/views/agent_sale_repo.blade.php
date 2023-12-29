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
          <div class="panel-heading text-left">
            <h4> agent sales Details from {{$fromDate}} to {{$toDate}}</h4>
              
          </div>
          <div class="panel-body">
             <table id="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Unique id</th>
                          <th>Draw Date</th>
                          <th>Draw Time</th>
                          <th>Entry Time</th>
                          <th>Bet Amount</th>
                          <th>win amount</th>
                          <th>Claimed</th>
                      </tr>
                  </thead>
                   <tbody> 
                    @php $slno = 0 @endphp
        @foreach($agentSale as $ok)
         <tr>
            <td>{{++$slno}}</td>
            <td><a href="">{{$ok->ticket_id}}</a></td>
            <td>{{ \Carbon\Carbon::parse($ok->created)->toDateString() }}</td>
<td>{{ \Carbon\Carbon::parse($ok->created)->format('h:i:A') }}</td>
            <td>{{ \Carbon\Carbon::parse($ok->created)->toDateString() }}</td>
            <td>{{$ok->bet_amount}}</td>
            <td>{{$ok->agent_win}}</td>
            <td>@if($ok->claimed_status == 0) no @else yes @endif </td>
         </tr>
         @endforeach 
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
