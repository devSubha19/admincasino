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
          <div class="panel-heading text-left"> Unclaimed Report</div>
          <div class="panel-body">
            <h3>Total Unclaimed: {{$unclaimed_total}}</h3>
             <table id="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
        
                         <th>Username</th>
                         <th>Game</th>
                         <th>Time</th>
                         <th>Bet</th>
                         <th>Won</th>
                         <th>Claimed Status</th>
                      </tr>
                      
                  </thead>
                   <tbody> 
                  @foreach($unclaimed as $un)
                <tr>                                   
                 <td>{{$un->agent_user_name}}</td>
                 <td>
                  @php
                     $game = App\Models\game::find($un->game);
                     echo $game->game_name;
                  @endphp
                 </td>
                 <td>{{$un->created}}</td>
                 <td>{{$un->bet_amount}}</td>
                 <td>{{$un->agent_win}}</td>
                 <td>@if($un->claimed_status == 0) <span style="color: red">Unclaimed </span> @else <span style="color:green"> Claimed </span>@endif</td>
                </tr>
                @endforeach
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
@endsection



