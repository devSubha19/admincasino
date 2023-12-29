@extends('layouts.main')
@section('content')
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
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
<h4>Stockez Sale Report</h4>
<div class="div">

    
  <table class="table table-bordered" >
    
    <tr>
      <th scope="col">TotalPlayPoints</th>
      <th scope="col">TotalWinPoints</th>
      <th scope="col">Total Claimed</th>
      <th scope="col">Total Agent Commission</th>
      <th scope="col">Total Ntp</th>
      <th scope="col">NTP %</th>
    </tr>
   
          <tr scope="row">
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
          </tr>
  </table>
</div>
 
<div class="table-container" id="table1" >

    <table class="table table-bordered" >
        <tr>
            <th scope="col">id</th>
            <th scope="col">UserName</th>
            <th scope="col">Play Point</th>
            <th scope="col">Win Point</th>
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">Stockez Commission</th>
            <th scope="col">Net</th>
             
          </tr>
        @php
            $slno = 0;
        @endphp
      @foreach($L6mdata as $data)
              <tr scope="row">
               <td>{{++$slno}}</td>
               <td>{{$data->super_username}}</td>
               <td>{{$data->total_bet_amount}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_commission}}</td>
               <td>{{$data->total_stockez_commission}}</td>
               <td>{{$data->total_admin_commission}}</td>
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
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">Stockez Commission</th>
            <th scope="col">Net</th>
             
          </tr>
        @php
            $slno = 0;
        @endphp
      @foreach($Cmdata as $data)
              <tr scope="row">
               <td>{{++$slno}}</td>
               <td>{{$data->super_username}}</td>
               <td>{{$data->total_bet_amount}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_commission}}</td>
               <td>{{$data->total_stockez_commission}}</td>
               <td>{{$data->total_admin_commission}}</td>
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
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">Stockez Commission</th>
            <th scope="col">Net</th>
             
          </tr>
        @php
            $slno = 0;
        @endphp
      @foreach($Lmdata as $data)
              <tr scope="row">
               <td>{{++$slno}}</td>
               <td>{{$data->super_username}}</td>
               <td>{{$data->total_bet_amount}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_commission}}</td>
               <td>{{$data->total_stockez_commission}}</td>
               <td>{{$data->total_admin_commission}}</td>
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
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">Stockez Commission</th>
            <th scope="col">Net</th>
             
          </tr>
        @php
            $slno = 0;
        @endphp
      @foreach($Lwdata as $data)
              <tr scope="row">
               <td>{{++$slno}}</td>
               <td>{{$data->super_username}}</td>
               <td>{{$data->total_bet_amount}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_commission}}</td>
               <td>{{$data->total_stockez_commission}}</td>
               <td>{{$data->total_admin_commission}}</td>
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
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">Stockez Commission</th>
            <th scope="col">Net</th>
             
          </tr>
        @php
            $slno = 0;
        @endphp
      @foreach($Cwdata as $data)
              <tr scope="row">
               <td>{{++$slno}}</td>
               <td>{{$data->super_username}}</td>
               <td>{{$data->total_bet_amount}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_commission}}</td>
               <td>{{$data->total_stockez_commission}}</td>
               <td>{{$data->total_admin_commission}}</td>
              </tr>
        @endforeach
    </table>
</div>





<div class="table-container" id="table6">
    <table class="table table-bordered" >
        <tr>
            <th scope="col">id</th>
            <th scope="col">UserName</th>
            <th scope="col">Play Point</th>
            <th scope="col">Win Point</th>
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">Stockez Commission</th>
            <th scope="col">Net</th>
             
          </tr>
        @php
            $slno = 0;
        @endphp
      @foreach($Yddata as $data)
              <tr scope="row">
               <td>{{++$slno}}</td>
               <td>{{$data->super_username}}</td>
               <td>{{$data->total_bet_amount}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_commission}}</td>
               <td>{{$data->total_stockez_commission}}</td>
               <td>{{$data->total_admin_commission}}</td>
              </tr>
        @endforeach
    </table>
</div>
<div class="table-container" id="table7">
    <table class="table table-bordered" >
        <tr>
            <th scope="col">id</th>
            <th scope="col">UserName</th>
            <th scope="col">Play Point</th>
            <th scope="col">Win Point</th>
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">Stockez Commission</th>
            <th scope="col">Net</th>
             
          </tr>
        @php
            $slno = 0;
        @endphp
      @foreach($Tddata as $data)
              <tr scope="row">
               <td>{{++$slno}}</td>
               <td>{{$data->super_username}}</td>
               <td>{{$data->total_bet_amount}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_commission}}</td>
               <td>{{$data->total_stockez_commission}}</td>
               <td>{{$data->total_admin_commission}}</td>
              </tr>
        @endforeach
    </table>
</div>


    <div class="table-container" id="table8">
    <!-- Table 3 content here -->
    <table class="table table-bordered" >
        <tr>
            <th scope="col">id</th>
            <th scope="col">UserName</th>
            <th scope="col">Play Point</th>
            <th scope="col">Win Point</th>
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">Stockez Commission</th>
            <th scope="col">Net</th>
             
          </tr>
        @php
            $slno = 0;
        @endphp
      @foreach($L6mdata as $data)
              <tr scope="row">
               <td>{{++$slno}}</td>
               <td>{{$data->super_username}}</td>
               <td>{{$data->total_bet_amount}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_win}}</td>
               <td>{{$data->total_agent_commission}}</td>
               <td>{{$data->total_stockez_commission}}</td>
               <td>{{$data->total_admin_commission}}</td>
              </tr>
        @endforeach
    </table>
</div>

<script>


  
  function hideAllTables() {
      var tables = document.querySelectorAll('.table-container');
      tables.forEach(function(table) {
          table.style.display = 'none';
      });
  }
  function showTable6() {
         // Get the table with ID "table6"
         var table6 = document.getElementById('table7');

         if (table6) {
             table6.style.display = 'block';
         }
     }


  document.addEventListener('DOMContentLoaded', function() {
      hideAllTables();
       showTable6();
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