@php
   use Illuminate\Support\Facades\DB;

    @endphp
 
@extends('layouts.main')
@section('content')
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Turnover Report</title>
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
<body>
  <section class="main-content">
      <div class="col-md-12">
         
<div class="container mt-4" style="width:100% !important;position:relative !important;margin-left:px !important;">
   <h2>Game Play History</h2>
   
   <form action={{ route('filterplayerhist') }} method="GET" >
   <div class="form-row">
       <div class="form-group col-md-3">
           <select name="uname" class="form-control mb-3 ch chosen-select" id="">
               <option value ="">Search By UserName</option>
            @foreach($uname as $unm)
            <option value ="{{$unm->agent_user_name}}">{{$unm->agent_user_name}}</option>
            @endforeach 
           </select>
       </div>   
       <div class="form-group col-md-3">
        <input type="text" name="fromDate" class="form-control" placeholder="From Date" value="" onfocus="(this.type='date')" onblur="(this.type='text')" name="fromdate">
    </div>
    <div class="form-group col-md-3">
        <input type="text" name="toDate" class="form-control" placeholder="To Date" onfocus="(this.type='date')" onblur="(this.type='text')"  name="toDate">
    </div>
       <div class="form-group col-md-3">
        <select class="form-control" name="gamename">
            <option value="">Game Name</option>
            @foreach($games as $game)
            <option value="{{$game->id}}">{{$game->game_name}}</option>
            @endforeach
        </select>
        
       </div>
   </div>
   <center><button type="submit" class="btn btn-primary">Search</button></center>
   </form>
   <br><br>
   <table class="table table-bordered">
   <thead>
   <tr>
       <th>Sl no.</th>
       <th>Date</th>
       <th>User</th>
       <th>Game</th>
       <th>Ticket Id</th>
       <th>Start Point</th>
       <th>Bet</th>
       <th>Won</th>
       <th>Claim Status</th>
       <th>End Point</th>
       <th>View</th>
   </tr>
   </thead>
   <tbody id="tableBody">
    @php 
        use Illuminate\Support\Facades\game;
        $slno = 0;
    @endphp
    @foreach($playerhist as $phist)
    @php 
    $slno += 1;
@endphp
       <tr>
           <td>{{$slno}}</td>   
           <td>{{date('Y-m-d', strtotime($phist->created))}}</td>
           <td>{{$phist->agent_user_name}}</td>
            @php 
                $id = $phist->game;
                $gmname= App\Models\game::find($id);
            @endphp
           <td>{{$gmname->game_name}}</td>
           <td>{{$phist->ticket_id}}</td>
           <td>{{$phist->start_point}}</td>
           <td>{{$phist->bet_amount}}</td>
           <td>{{$phist->agent_win}}</td>
           <td>
            @if($phist->claimed_status == 0)
                <span style="color: red;">Unclaimed</span>
            @else
                <span style="color: green;">Claimed</span>
            @endif
        </td>
        
           <td>{{$phist->end_point}}</td>
           <td></td>
       </tr>    
       @endforeach
   </tbody>
   </table>
 </<section>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
 <script src="../js/index.js"></script>


 <script type="text/javascript">
 $(document).ready(function () {
     $('#example').dataTable();
 });
</script>

<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script>
$(document).ready(function () {
    // Initialize Chosen
    $('.chosen-select').chosen();
  
});
    </script>
@endsection
