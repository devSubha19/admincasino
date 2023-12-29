@extends('layouts.main')
@section('content')
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Turnover Report</title>
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
<body>
 
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

<div class="div">
  <table class="table table-bordered" >
    
    <tr>
      <th scope="col">TotalPlayPoints</th>
      <th scope="col">TotalWinPoints</th>
      <th scope="col">EndPoint</th>
      <th scope="col">Superstockez Actual Commission</th>
      <th scope="col">Stockez Actual Commission</th>
      <th scope="col">Net</th>
    </tr>
   
          <tr scope="row">
           <td id = ""></td>
           <td id = ""></td>
           <td id = ""></td>
           <td id = ""></td>
           <td id = ""></td>
           <td id = ""></td>
          </tr>
  </table>
</div>

<h3>Agent Turnover Report</h3>
 
<div class="table-container" id="table1" >

    <table class="table table-bordered" >
      <tr>
        <th scope="col">id</th>
        <th scope="col">UserName</th>
        <th scope="col">Play Point</th>
        <th scope="col">Win Point</th>
        <th scope="col">End Point</th>
        <th scope="col">Superstockez Point</th>
        <th scope="col">Stockez Point</th>
        <th scope="col">Net</th>
        <th scope="col">Percentage</th>
        <th scope="col">Margin</th>
        <th scope="col">Type</th>
      </tr>
      @php
          $slno = 1;
      @endphp
      @foreach($L6mdata as $l6mdagt)

          <tr scope="row">
              <td>{{$slno++ }}</td>     
              <td> {{$l6mdagt->agent_user_name}}</td>
              <td id="playpoint">{{$l6mdagt->total_bet_amount}}</td>
              <td id="agtwin">{{$l6mdagt->total_agent_win}}</td>
              <td id="endpoint">{{$l6mdagt->total_end_point}}</td>
              <td id="supercom">{{$l6mdagt->total_super_commission}}</td>
              <td id="stkcom">{{$l6mdagt->total_stockez_commission}}</td>
              <td id="admcom">{{$l6mdagt->total_admin_commission}}</td>
              @if($l6mdagt->total_agent_win == 0)
                  <td>0</td>    
              @else
                  <td>{{ number_format(($l6mdagt->total_agent_win) * 100 / $l6mdagt->total_bet_amount, 2) }}</td>
              @endif
              <td>margin</td>
              <td>type</td>
          </tr>
      @endforeach
      
    </table>


</div>

<div class="table-container" id="table2">
    
    <table class="table table-bordered" >
      <tr>
        <th scope="col">id</th>
        <th scope="col">UserName</th>
        <th scope="col">Play Point</th>
        <th scope="col">Win Point</th>
        <th scope="col">End Point</th>
        <th scope="col">Superstockez Point</th>
        <th scope="col">Stockez Point</th>
        <th scope="col">Net</th>
        <th scope="col">Percentage</th>
        <th scope="col">Margin</th>
        <th scope="col">Type</th>
      </tr>
      @php
          $slno = 1;
      @endphp
      @foreach($Cmdata as $cmd)
          <tr scope="row">
              <td>{{$slno++ }}</td>
              <td>{{$cmd->agent_user_name}}</td>
              <td id="playpoint">{{$cmd->total_bet_amount}}</td>
              <td id="agtwin">{{$cmd->total_agent_win}}</td>
              <td id="endpoint">{{$cmd->total_end_point}}</td>
              <td id="supercom">{{$cmd->total_super_commission}}</td>
              <td id="stkcom">{{$cmd->total_stockez_commission}}</td>
              <td id="admcom">{{$cmd->total_admin_commission}}</td>
              @if($cmd->total_agent_win == 0)
                  <td>0</td>
              @else
                  <td>{{ number_format(($cmd->total_agent_win) * 100 / $cmd->total_bet_amount, 2) }}</td>
              @endif
              <td>margin</td>
              <td>type</td>
          </tr>
      @endforeach
      
    </table>
</div>

<div class="table-container" id="table3">
    <!-- Table 3 content here -->
    <table class="table table-bordered" >
      <tr>
        <th scope="col">id</th>
        <th scope="col">UserName</th>
        <th scope="col">Play Point</th>
        <th scope="col">Win Point</th>
        <th scope="col">End Point</th>
        <th scope="col">Superstockez Point</th>
        <th scope="col">Stockez Point</th>
        <th scope="col">Net</th>
        <th scope="col">Percentage</th>
        <th scope="col">Margin</th>
        <th scope="col">Type</th>

      </tr>
      @php
      $slno = 1;
  @endphp
  @foreach($Lmdata as $lmd)
      <tr scope="row">
          <td>{{$slno++ }}</td>
          <td> {{$lmd->agent_user_name}}</td>
          <td id="playpoint">{{$lmd->total_bet_amount}}</td>
          <td id="agtwin">{{$lmd->total_agent_win}}</td>
          <td id="endpoint">{{$lmd->total_end_point}}</td>
          <td id="supercom">{{$lmd->total_super_commission}}</td>
          <td id="stkcom">{{$lmd->total_stockez_commission}}</td>
          <td id="admcom">{{$lmd->total_admin_commission}}</td>
          @if($lmd->total_agent_win == 0)
              <td>0</td>
          @else
              <td>{{ number_format(($lmd->total_agent_win) * 100 / $lmd->total_bet_amount, 2) }}</td>
          @endif
          <td>margin</td>
          <td>type</td>
      </tr>
  @endforeach
  
    </table>
</div>


<div class="table-container" id="table4">
    <table class="table table-bordered" >
      <tr>
        <th scope="col">id</th>
        <th scope="col">UserName</th>
        <th scope="col">Play Point</th>
        <th scope="col">Win Point</th>
        <th scope="col">End Point</th>
        <th scope="col">Superstockez Point</th>
        <th scope="col">Stockez Point</th>
        <th scope="col">Net</th>
        <th scope="col">Percentage</th>
        <th scope="col">Margin</th>
        <th scope="col">Type</th>

      </tr>
      @php
      $slno = 1;
  @endphp
  @foreach($Lwdata as $lwd)
      <tr scope="row">
          <td>{{$slno++ }}</td>
          <td> {{$lwd->agent_user_name}}</td>
          <td id="playpoint">{{$lwd->total_bet_amount}}</td>
          <td id="agtwin">{{$lwd->total_agent_win}}</td>
          <td id="endpoint">{{$lwd->total_end_point}}</td>
          <td id="supercom">{{$lwd->total_super_commission}}</td>
          <td id="stkcom">{{$lwd->total_stockez_commission}}</td>
          <td id="admcom">{{$lwd->total_admin_commission}}</td>
          @if($lwd->total_agent_win == 0)
              <td>0</td>
          @else
              <td>{{ number_format(($lwd->total_agent_win) * 100 / $lwd->total_bet_amount, 2) }}</td>
          @endif
          <td>margin</td>
          <td>type</td>
      </tr>
  @endforeach
    </table>
</div>

<div class="table-container" id="table5">
    <!-- Table 3 content here -->
    <table class="table table-bordered" >
      <tr>  
        <th scope="col">id</th>
        <th scope="col">UserName</th>
        <th scope="col">Play Point</th>
        <th scope="col">Win Point</th>
        <th scope="col">End Point</th>
        <th scope="col">Superstockez Point</th>
        <th scope="col">Stockez Point</th>
        <th scope="col">Net</th>
        <th scope="col">Percentage</th>
        <th scope="col">Margin</th>
        <th scope="col">Type</th>
      </tr>
      @php
      $slno = 1;
  @endphp
  @foreach($Cwdata as $cwd)
      <tr scope="row">
          <td>{{$slno++ }}</td>
          <td>{{$cwd->agent_user_name}}</td>
          <td id="playpoint">{{$cwd->total_bet_amount}}</td>
          <td id="agtwin">{{$cwd->total_agent_win}}</td>
          <td id="endpoint">{{$cwd->total_end_point}}</td>
          <td id="supercom">{{$cwd->total_super_commission}}</td>
          <td id="stkcom">{{$cwd->total_stockez_commission}}</td>
          <td id="admcom">{{$cwd->total_admin_commission}}</td>
          @if($cwd->total_agent_win == 0)
              <td>0</td>
          @else
              <td>{{ number_format(($cwd->total_agent_win) * 100 / $cwd->total_bet_amount, 2) }}</td>
          @endif
          <td>margin</td>
          <td>type</td>
      </tr>
  @endforeach
  
    </table>
</div>

  
<div class="table-container" id="table6">
    <table class="table table-bordered" >
        <thead>
      <tr>
        <th scope="col">id</th>
        <th scope="col">UserName</th>
        <th scope="col">Play Point</th>
        <th scope="col">Win Point</th>
        <th scope="col">End Point</th>
        <th scope="col">Superstockez Point</th>
        <th scope="col">Stockez Point</th>
        <th scope="col">Net</th>
        <th scope="col">Percentage</th>
        <th scope="col">Margin</th>
        <th scope="col">Type</th>
      </tr>
    </thead>
    <tbody>
        @if(count($Yddata) === 0)
      <tr><td colspan="11">No data available</td></tr>
  @else
      @php
          $slno = 1;
      @endphp
      @foreach($Yddata as $ydt)
          <tr scope="row">
              <td>{{$slno++ }}</td>
              <td>{{ $ydt->agent_user_name }}</td>
              <td id="playpoint">{{ $ydt->total_bet_amount }}</td>
              <td id="agtwin">{{ $ydt->total_agent_win }}</td>
              <td id="endpoint">{{ $ydt->total_end_point }}</td>
              <td id="supercom">{{ $ydt->total_super_commission }}</td>
              <td id="stkcom">{{ $ydt->total_stockez_commission }}</td>
              <td id="admcom">{{ $ydt->total_admin_commission }}</td>
              @if($ydt->total_agent_win == 0)
                  <td>0</td>
              @else
                  <td>{{ number_format(($ydt->total_agent_win) * 100 / $ydt->total_bet_amount, 2) }}</td>
              @endif
              <td>margin</td>
              <td>type</td>
          </tr>
      @endforeach
  @endif
    </tbody>
    </table>
</div>


<div class="table-container" id="table7">
    <!-- Table 3 content here -->
    <table class="table table-bordered" >
      <tr>
        <th scope="col">id</th>
        <th scope="col">UserName</th>
        <th scope="col">Play Point</th>
        <th scope="col">Win Point</th>
        <th scope="col">End Point</th>
        <th scope="col">Superstockez Point</th>
        <th scope="col">Stockez Point</th>
        <th scope="col">Net</th>
        <th scope="col">Percentage</th>
        <th scope="col">Margin</th>
        <th scope="col">Type</th>
      </tr>
      @php
          $slno = 1;
      @endphp
      @foreach($Tddata as $tdt)
          <tr scope="row">
              <td>{{$slno++ }}</td>
              <td>  {{$tdt->agent_user_name}}</td>
              <td id="playpoint">{{$tdt->total_bet_amount}}</td>
              <td id ="agtwin">{{$tdt->total_agent_win}}</td>
              <td id ="endpoint">{{$tdt->total_end_point}}</td>
              <td id ="supercom">{{$tdt->total_super_commission}}</td>
              <td id ="stkcom">{{$tdt->total_stockez_commission}}</td>
              <td id ="admcom">{{$tdt->total_admin_commission}}</td>
              @if($tdt->total_agent_win == 0)
                  <td>0</td>
              @else
                  <td>{{ number_format(($tdt->total_agent_win) * 100 / $tdt->total_bet_amount, 2) }}</td>
              @endif
              <td>margin</td>
              <td>type</td>
          </tr>
      @endforeach
      
    </table>
</div>


<div class="table-container" id="table8">

    <table class="table table-bordered" >
      <tr>
        <th scope="col">id</th>
        <th scope="col">UserName</th>
        <th scope="col">Play Point</th>
        <th scope="col">Win Point</th>
        <th scope="col">End Point</th>
        <th scope="col">Superstockez Point</th>
        <th scope="col">Stockez Point</th>
        <th scope="col">Net</th>
        <th scope="col">Percentage</th>
        <th scope="col">Margin</th>
        <th scope="col">Type</th>

      </tr>
      
            <tr scope="row">
            <td>hello</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
 

    </table>
</div>



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

      if (selectedTable.rows.length === 0) {
            var newRow = selectedTable.insertRow(0);  
            var cell = newRow.insertCell(0);
            cell.colSpan = 11;  
            cell.innerHTML = 'No data available';
        }
  }



  function printTable() {
     window.print();
  }
</script>

  </<section>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
  <script src="/js/index.js"></script>


  <script type="text/javascript">
  $(document).ready(function () {
      $('#example').dataTable();
  });
</script>


<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">
@endsection