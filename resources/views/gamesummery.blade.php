@extends('layouts.main')
@section('content')
<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Game summery</title>
    <link rel="stylesheet" href="/css/style.css" />
    <link
       rel="stylesheet"
       href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js">
 </head>
<section class="main-content">
 
      <div>
     <button type="button" class="btn btn" onclick="showTable('table1')">Last 6 Month</button>
     <button type="button" class="btn btn" onclick="showTable('table2')">Current Month</button>
     <button type="button" class="btn btn" onclick="showTable('table3')">Last Month</button>
     <button type="button" class="btn btn" onclick="showTable('table4')">Last Week</button>
     <button type="button" class="btn btn" onclick="showTable('table5')">Current Week</button>
     <button type="button" class="btn btn" onclick="showTable('table6')">Yesterday</button>
     <button type="button" class="btn btn" onclick="showTable('table7')">Today</button>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
     <button type="submit" name="startDate" class="btn btn" onclick="showTable('table8')">Date Range</button>
  
 
     <button type="button" class="btn btn" onclick="printTable()">Print</button>
 </div>
    <div class="col-md-12">
       <div class="panel panel-primary">
          <div class="panel-heading text-left">Game Summery</div>
          <div class="panel-body">
            
            
            <div class="table-container" id="table1" >
             <table class="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Game</th>
                          <th>Play Points</th>
                          <th>Win Points</th>
                          <th>Total No. Bets</th>
                          <th>Avg. Bet</th>
                          <th>Percentage</th>
                      </tr>
                  </thead>
                   <tbody> 
                     @foreach ($game as $gme)
                     <tr>
                        <td>{{$gme->game_name}}</td>
                        @php 
                             $agTmBl = \Illuminate\Support\Facades\DB::table('agent_temp_bal')
                                             ->where('game',$gme->id)
                                              ->where('created', '>=', \Carbon\Carbon::now()->subMonths(5))
                                              ->get();
                              $TotalPlayPoints = 0;
                              $TotalWinPoints = 0;
                              $TotalBetNum = $agTmBl->count(); 
                           foreach($agTmBl as $agtb){
                                 $TotalPlayPoints += $agtb->bet_amount;
                                 $TotalWinPoints += $agtb->agent_win;
                           }
                              if($TotalBetNum == 0){
                                    $avg = 0;
                              }
                              else{
                                 $avg = $TotalPlayPoints/$TotalBetNum; 
                              }
                        @endphp
                        <td>{{$TotalPlayPoints}}</td>
                        <td>{{$TotalWinPoints}}</td>
                        <td>{{$TotalBetNum}}</td>
                        <td>{{$avg}}</td>
                        <td>{{0}}%</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>

            <div class="table-container" id="table2" >
             <table class="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Game</th>
                          <th>Play Points</th>
                          <th>Win Points</th>
                          <th>Total No. Bets</th>
                          <th>Avg. Bet</th>
                          <th>Percentage</th>
                      </tr>
                  </thead>
                   <tbody> 
                     @foreach ($game as $gme)
                     <tr>
                        <td>{{$gme->game_name}}</td>
                        @php 
                             $agTmBl = \Illuminate\Support\Facades\DB::table('agent_temp_bal')
                                             ->where('game', $gme->id)
                                             ->where('created', '>=', \Carbon\Carbon::now()->startOfMonth())
                                             ->get();

                              $TotalPlayPoints = 0;
                              $TotalWinPoints = 0;
                              $TotalBetNum = $agTmBl->count(); 
                           foreach($agTmBl as $agtb){
                                 $TotalPlayPoints += $agtb->bet_amount;
                                 $TotalWinPoints += $agtb->agent_win;
                           }
                              if($TotalBetNum == 0){
                                    $avg = 0;
                              }
                              else{
                                 $avg = $TotalPlayPoints/$TotalBetNum; 
                              }
                        @endphp
                        <td>{{$TotalPlayPoints}}</td>
                        <td>{{$TotalWinPoints}}</td>
                        <td>{{$TotalBetNum}}</td>
                        <td>{{$avg}}</td>
                        <td>{{0}}%</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>

            <div class="table-container" id="table3" >
             <table class="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Game</th>
                          <th>Play Points</th>
                          <th>Win Points</th>
                          <th>Total No. Bets</th>
                          <th>Avg. Bet</th>
                          <th>Percentage</th>
                      </tr>
                  </thead>
                   <tbody> 
                     @foreach ($game as $gme)
                     <tr>
                        <td>{{$gme->game_name}}</td>
                        @php 
                             $agTmBl = \Illuminate\Support\Facades\DB::table('agent_temp_bal')
                                             ->where('game', $gme->id)
                                             ->where('created', '>=', \Carbon\Carbon::now()->subMonth()->startOfMonth())
                                             ->where('created', '<', \Carbon\Carbon::now()->startOfMonth())
                                             ->get();
                              $TotalPlayPoints = 0;
                              $TotalWinPoints = 0;
                              $TotalBetNum = $agTmBl->count(); 
                           foreach($agTmBl as $agtb){
                                 $TotalPlayPoints += $agtb->bet_amount;
                                 $TotalWinPoints += $agtb->agent_win;
                           }
                              if($TotalBetNum == 0){
                                    $avg = 0;
                              }
                              else{
                                 $avg = $TotalPlayPoints/$TotalBetNum; 
                              }
                        @endphp
                        <td>{{$TotalPlayPoints}}</td>
                        <td>{{$TotalWinPoints}}</td>
                        <td>{{$TotalBetNum}}</td>
                        <td>{{$avg}}</td>
                        <td>{{0}}%</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>

            <div class="table-container" id="table4" >
             <table class="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Game</th>
                          <th>Play Points</th>
                          <th>Win Points</th>
                          <th>Total No. Bets</th>
                          <th>Avg. Bet</th>
                          <th>Percentage</th>
                      </tr>
                  </thead>
                   <tbody> 
                     @foreach ($game as $gme)
                     <tr>
                        <td>{{$gme->game_name}}</td>
                        @php 
                                  $agTmBl = \Illuminate\Support\Facades\DB::table('agent_temp_bal')
                                    ->where('game', $gme->id)
                                    ->where('created', '>=', \Carbon\Carbon::now()->subWeek()->startOfWeek())
                                    ->where('created', '<', \Carbon\Carbon::now()->startOfWeek())
                                    ->get();

                              $TotalPlayPoints = 0;
                              $TotalWinPoints = 0;
                              $TotalBetNum = $agTmBl->count(); 
                           foreach($agTmBl as $agtb){
                                 $TotalPlayPoints += $agtb->bet_amount;
                                 $TotalWinPoints += $agtb->agent_win;
                           }
                              if($TotalBetNum == 0){
                                    $avg = 0;
                              }
                              else{
                                 $avg = $TotalPlayPoints/$TotalBetNum; 
                              }
                        @endphp
                        <td>{{$TotalPlayPoints}}</td>
                        <td>{{$TotalWinPoints}}</td>
                        <td>{{$TotalBetNum}}</td>
                        <td>{{$avg}}</td>
                        <td>{{0}}%</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>

            <div class="table-container" id="table5" >
             <table class="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Game</th>
                          <th>Play Points</th>
                          <th>Win Points</th>
                          <th>Total No. Bets</th>
                          <th>Avg. Bet</th>
                          <th>Percentage</th>
                      </tr>
                  </thead>
                   <tbody> 
                     @foreach ($game as $gme)
                     <tr>
                        <td>{{$gme->game_name}}</td>
                        @php 
                        $agTmBl = \Illuminate\Support\Facades\DB::table('agent_temp_bal')
                                          ->where('game', $gme->id)
                                          ->where('created', '>=', \Carbon\Carbon::now()->startOfWeek())
                                          ->where('created', '<', \Carbon\Carbon::now()->endOfWeek())
                                          ->get();

                              $TotalPlayPoints = 0;
                              $TotalWinPoints = 0;
                              $TotalBetNum = $agTmBl->count(); 
                           foreach($agTmBl as $agtb){
                                 $TotalPlayPoints += $agtb->bet_amount;
                                 $TotalWinPoints += $agtb->agent_win;
                           }
                              if($TotalBetNum == 0){
                                    $avg = 0;
                              }
                              else{
                                 $avg = $TotalPlayPoints/$TotalBetNum; 
                              }
                        @endphp
                        <td>{{$TotalPlayPoints}}</td>
                        <td>{{$TotalWinPoints}}</td>
                        <td>{{$TotalBetNum}}</td>
                        <td>{{$avg}}</td>
                        <td>{{0}}%</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>

            <div class="table-container" id="table6" >
             <table class="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Game</th>
                          <th>Play Points</th>
                          <th>Win Points</th>
                          <th>Total No. Bets</th>
                          <th>Avg. Bet</th>
                          <th>Percentage</th>
                      </tr>
                  </thead>
                   <tbody> 
                     @foreach ($game as $gme)
                     <tr>
                        <td>{{$gme->game_name}}</td>
                        @php 
                          $agTmBl = \Illuminate\Support\Facades\DB::table('agent_temp_bal')
                                     ->where('game', $gme->id)
                                     ->whereDate('created', \Carbon\Carbon::yesterday())
                                     ->get();

                              $TotalPlayPoints = 0;
                              $TotalWinPoints = 0;
                              $TotalBetNum = $agTmBl->count(); 
                           foreach($agTmBl as $agtb){
                                 $TotalPlayPoints += $agtb->bet_amount;
                                 $TotalWinPoints += $agtb->agent_win;
                           }
                              if($TotalBetNum == 0){
                                    $avg = 0;
                              }
                              else{
                                 $avg = $TotalPlayPoints/$TotalBetNum; 
                              }
                        @endphp
                        <td>{{$TotalPlayPoints}}</td>
                        <td>{{$TotalWinPoints}}</td>
                        <td>{{$TotalBetNum}}</td>
                        <td>{{$avg}}</td>
                        <td>{{0}}%</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>

            <div class="table-container" id="table7" >
             <table class="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Game</th>
                          <th>Play Points</th>
                          <th>Win Points</th>
                          <th>Total No. Bets</th>
                          <th>Avg. Bet</th>
                          <th>Percentage</th>
                      </tr>
                  </thead>
                   <tbody> 
                     @foreach ($game as $gme)
                     <tr>
                        <td>{{$gme->game_name}}</td>
                        @php 
                        $agTmBl = \Illuminate\Support\Facades\DB::table('agent_temp_bal')
                                       ->where('game', $gme->id)
                                       ->whereDate('created', \Carbon\Carbon::today())
                                       ->get();

                              $TotalPlayPoints = 0;
                              $TotalWinPoints = 0;
                              $TotalBetNum = $agTmBl->count(); 
                           foreach($agTmBl as $agtb){
                                 $TotalPlayPoints += $agtb->bet_amount;
                                 $TotalWinPoints += $agtb->agent_win;
                           }
                              if($TotalBetNum == 0){
                                    $avg = 0;
                              }
                              else{
                                 $avg = $TotalPlayPoints/$TotalBetNum; 
                              }
                        @endphp
                        <td>{{$TotalPlayPoints}}</td>
                        <td>{{$TotalWinPoints}}</td>
                        <td>{{$TotalBetNum}}</td>
                        <td>{{$avg}}</td>
                        <td>{{0}}%</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>

            <div class="table-container" id="table8" >
             <table class="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Game</th>
                          <th>Play Points</th>
                          <th>Win Points</th>
                          <th>Total No. Bets</th>
                          <th>Avg. Bet</th>
                          <th>Percentage</th>
                      </tr>
                  </thead>
                   <tbody> 
                     @foreach ($game as $gme)
                     <tr>
                        <td>{{$gme->game_name}}</td>
                        @php 
                             $agTmBl = \Illuminate\Support\Facades\DB::table('agent_temp_bal')
                                             ->where('game',$gme->id)
                                              ->where('created', '>=', \Carbon\Carbon::now()->subMonths(5))
                                              ->get();
                              $TotalPlayPoints = 0;
                              $TotalWinPoints = 0;
                              $TotalBetNum = $agTmBl->count(); 
                           foreach($agTmBl as $agtb){
                                 $TotalPlayPoints += $agtb->bet_amount;
                                 $TotalWinPoints += $agtb->agent_win;
                           }
                              if($TotalBetNum == 0){
                                    $avg = 0;
                              }
                              else{
                                 $avg = $TotalPlayPoints/$TotalBetNum; 
                              }
                        @endphp
                        <td>{{$TotalPlayPoints}}</td>
                        <td>{{$TotalWinPoints}}</td>
                        <td>{{$TotalBetNum}}</td>
                        <td>{{$avg}}</td>
                        <td>{{0}}%</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>


                  </div>
       </div>
<section>



   <script>
      function hideAllTables() {
          var tables = document.querySelectorAll('.table-container');
          tables.forEach(function(table) {
              table.style.display = 'none';
          });
      }
      function showTable7() {
           
             var table7 = document.getElementById('table7');
    
             if (table7) {
                 table7.style.display = 'block';
             }
         }
    
    
      document.addEventListener('DOMContentLoaded', function() {
          hideAllTables();
           showTable7();
           
      });
    
      function showTable(tableId) {
          hideAllTables(); // Hide all tables first
    
          // Show the selected table
          var selectedTable = document.getElementById(tableId);
          if (selectedTable) {
              selectedTable.style.display = 'block';
          }
      }
    
      function printTable() {
         window.print();
      }
    </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
<script src="/js/index.js"></script>

<script type="text/javascript">
$(document).ready(function () {
   $('.example').dataTable();
});
</script>

<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css"> 

@endsection