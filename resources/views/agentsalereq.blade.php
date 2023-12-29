@extends('layouts.main')
@section('content')
<head>
    <style>th, td{text-align: center;}</style>
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
<h4>Agent Sale Report</h4>
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

<div class="table-container" id="table1">
  
  <table class="table table-bordered" >
    <tr>
      <th scope="col">id</th>
      <th scope="col">UserName</th>
      <th scope="col">Play Point</th>
      <th scope="col">Win Point</th>
      <th scope="col">Claim Point</th>
      <th scope="col">Agent Commission</th>
      <th scope="col">NTP</th>
      <th scope="col">NTP%</th>
      </tr>
      @php 
            $slno = 0;
            @endphp
      @foreach($L6mdata as $stockez_name => $l6)
      @php
          $TotalPlayPoint = 0;
          $TotalWinPoint = 0;
          $TotalClaimPoint = 0;
          $TotalAgtCom = 0;
          $TotalNTP = 0;
          $TotalNTPPer = 0;
          $toDate = date('Y-m-d 23:59:59');
          $fromDate = Carbon\Carbon::now()->subMonths(6)->toDateString();
      @endphp
        @foreach($l6 as $l6d)
        <tr scope="row" style="background-color:@php
                $uname = $l6d->agent_user_name;
                $agt = App\Models\agent::where('username', $uname)->first();
                if($agt){
                    if(($agt->onstatus) == 1){
                            echo "#b7eb34";
                    }
                }
        @endphp">
         <td>{{++$slno}}</td>
         <td>{{$l6d->agent_user_name}}</td>
         <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->bet_amount}}</a></td>
         <td>{{$l6d->agent_win}}</td>
         <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->agent_win}}</a></td>
         <td>{{$l6d->agent_commission}}</td>
         <td></td>
         <td></td>
        </tr>
        @php
            $TotalPlayPoint += $l6d->bet_amount;
            $TotalWinPoint += $l6d->agent_win;
            $TotalClaimPoint += $l6d->agent_win;
            $TotalAgtCom += $l6d->agent_commission;
            $TotalNTP += 0;
            $TotalNTP += 0;
        @endphp
        @endforeach
<tr style="background-color: black;color:aqua;">
    <td colspan="2">{{$stockez_name}}(Total)</td>
    <td>{{$TotalPlayPoint}}</td>
    <td>{{$TotalWinPoint}}</td>
    <td>{{$TotalClaimPoint}}</td>
    <td>{{$TotalAgtCom}}</td>
    <td>{{$TotalNTP}}</td>
    <td>{{$TotalNTP}}</td>
</tr>
@endforeach
</table>
</div>
 
<div class="table-container" id="table2" >

    <table class="table table-bordered" >
        <tr>
            <th scope="col">id</th>
            <th scope="col">UserName</th>
            <th scope="col">Play Point</th>
            <th scope="col">Win Point</th>
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">NTP</th>
            <th scope="col">NTP%</th>
          </tr>
          @php 
          $slno = 0;
          @endphp
    @foreach($Cmdata as $stockez_name => $l6)
    @php
        $TotalPlayPoint = 0;
        $TotalWinPoint = 0;
        $TotalClaimPoint = 0;
        $TotalAgtCom = 0;
        $TotalNTP = 0;
        $TotalNTPPer = 0;
        $fromDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $toDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();

    @endphp
      @foreach($l6 as $l6d)
      <tr scope="row" style="background-color:@php
              $uname = $l6d->agent_user_name;
              $agt = App\Models\agent::where('username', $uname)->first();
              if($agt){
                  if(($agt->onstatus) == 1){
                          echo "#b7eb34";
                  }
              }
      @endphp">
       <td>{{++$slno}}</td>
       <td>{{$l6d->agent_user_name}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->bet_amount}}</a></td>
       <td>{{$l6d->agent_win}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->agent_win}}</a></td>
       <td>{{$l6d->agent_commission}}</td>
       <td></td>
       <td></td>
      </tr>
      @php
          $TotalPlayPoint += $l6d->bet_amount;
          $TotalWinPoint += $l6d->agent_win;
          $TotalClaimPoint += $l6d->agent_win;
          $TotalAgtCom += $l6d->agent_commission;
          $TotalNTP += 0;
          $TotalNTP += 0;
      @endphp
      @endforeach
<tr style="background-color: black;color:aqua;">
  <td colspan="2">{{$stockez_name}}(Total)</td>
  <td>{{$TotalPlayPoint}}</td>
  <td>{{$TotalWinPoint}}</td>
  <td>{{$TotalClaimPoint}}</td>
  <td>{{$TotalAgtCom}}</td>
  <td>{{$TotalNTP}}</td>
  <td>{{$TotalNTP}}</td>
</tr>
@endforeach
    </table>
</div>

<div class="table-container" id="table3">
    
    <table class="table table-bordered" >
        <tr>
            <th scope="col">id</th>
            <th scope="col">UserName</th>
            <th scope="col">Play Point</th>
            <th scope="col">Win Point</th>
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">NTP</th>
            <th scope="col">NTP%</th>
          </tr>
          @php 
          $slno = 0;
          @endphp
    @foreach($Lmdata as $stockez_name => $l6)
    @php
        $TotalPlayPoint = 0;
        $TotalWinPoint = 0;
        $TotalClaimPoint = 0;
        $TotalAgtCom = 0;
        $TotalNTP = 0;
        $TotalNTPPer = 0;
        $fromDate = \Carbon\Carbon::now()->subMonth()->startOfMonth()->toDateString();
$toDate = \Carbon\Carbon::now()->subMonth()->endOfMonth()->toDateString();


    @endphp
      @foreach($l6 as $l6d)
      <tr scope="row" style="background-color:@php
              $uname = $l6d->agent_user_name;
              $agt = App\Models\agent::where('username', $uname)->first();
              if($agt){
                  if(($agt->onstatus) == 1){
                          echo "#b7eb34";
                  }
              }
      @endphp">
       <td>{{++$slno}}</td>
       <td>{{$l6d->agent_user_name}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->bet_amount}}</a></td>
       <td>{{$l6d->agent_win}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->agent_win}}</a></td>
       <td>{{$l6d->agent_commission}}</td>
       <td></td>
       <td></td>
      </tr>      @php
          $TotalPlayPoint += $l6d->bet_amount;
          $TotalWinPoint += $l6d->agent_win;
          $TotalClaimPoint += $l6d->agent_win;
          $TotalAgtCom += $l6d->agent_commission;
          $TotalNTP += 0;
          $TotalNTP += 0;
      @endphp
      @endforeach
<tr style="background-color: black;color:aqua;">
  <td colspan="2">{{$stockez_name}}(Total)</td>
  <td>{{$TotalPlayPoint}}</td>
  <td>{{$TotalWinPoint}}</td>
  <td>{{$TotalClaimPoint}}</td>
  <td>{{$TotalAgtCom}}</td>
  <td>{{$TotalNTP}}</td>
  <td>{{$TotalNTP}}</td>
</tr>
@endforeach
    </table>
</div>

<div class="table-container" id="table4">
    <!-- Table 3 content here -->
    <table class="table table-bordered" >
        <tr>
            <th scope="col">id</th>
            <th scope="col">UserName</th>
            <th scope="col">Play Point</th>
            <th scope="col">Win Point</th>
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">NTP</th>
            <th scope="col">NTP%</th>
          </tr>
          @php 
          $slno = 0;
          @endphp
    @foreach($Lwdata as $stockez_name => $l6)
    @php
        $TotalPlayPoint = 0;
        $TotalWinPoint = 0;
        $TotalClaimPoint = 0;
        $TotalAgtCom = 0;
        $TotalNTP = 0;
        $TotalNTPPer = 0;
        $fromDate = \Carbon\Carbon::now()->subWeek()->startOfWeek()->toDateString();
$toDate = \Carbon\Carbon::now()->subWeek()->endOfWeek()->toDateString();

    @endphp
      @foreach($l6 as $l6d)
      <tr scope="row" style="background-color:@php
              $uname = $l6d->agent_user_name;
              $agt = App\Models\agent::where('username', $uname)->first();
              if($agt){
                  if(($agt->onstatus) == 1){
                          echo "#b7eb34";
                  }
              }
      @endphp">
       <td>{{++$slno}}</td>
       <td>{{$l6d->agent_user_name}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->bet_amount}}</a></td>
       <td>{{$l6d->agent_win}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->agent_win}}</a></td>
       <td>{{$l6d->agent_commission}}</td>
       <td></td>
       <td></td>
      </tr>
      </tr>
      @php
          $TotalPlayPoint += $l6d->bet_amount;
          $TotalWinPoint += $l6d->agent_win;
          $TotalClaimPoint += $l6d->agent_win;
          $TotalAgtCom += $l6d->agent_commission;
          $TotalNTP += 0;
          $TotalNTP += 0;
      @endphp
      @endforeach
<tr style="background-color: black;color:aqua;">
  <td colspan="2">{{$stockez_name}}(Total)</td>
  <td>{{$TotalPlayPoint}}</td>
  <td>{{$TotalWinPoint}}</td>
  <td>{{$TotalClaimPoint}}</td>
  <td>{{$TotalAgtCom}}</td>
  <td>{{$TotalNTP}}</td>
  <td>{{$TotalNTP}}</td>
</tr>
@endforeach
    </table>
</div>


<div class="table-container" id="table5">
    <table class="table table-bordered" >
        <tr>
            <th scope="col">id</th>
            <th scope="col">UserName</th>
            <th scope="col">Play Point</th>
            <th scope="col">Win Point</th>
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">NTP</th>
            <th scope="col">NTP%</th>
          </tr>
          @php 
          $slno = 0;
          @endphp
    @foreach($Cwdata as $stockez_name => $l6)
    @php
        $TotalPlayPoint = 0;
        $TotalWinPoint = 0;
        $TotalClaimPoint = 0;
        $TotalAgtCom = 0;
        $TotalNTP = 0;
        $TotalNTPPer = 0;
        $fromDate = \Carbon\Carbon::now()->startOfWeek()->toDateString();
$toDate = \Carbon\Carbon::now()->endOfWeek()->toDateString();


    @endphp
      @foreach($l6 as $l6d)
      <tr scope="row" style="background-color:@php
              $uname = $l6d->agent_user_name;
              $agt = App\Models\agent::where('username', $uname)->first();
              if($agt){
                  if(($agt->onstatus) == 1){
                          echo "#b7eb34";
                  }
              }
      @endphp">
       <td>{{++$slno}}</td>
       <td>{{$l6d->agent_user_name}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->bet_amount}}</a></td>
       <td>{{$l6d->agent_win}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->agent_win}}</a></td>
       <td>{{$l6d->agent_commission}}</td>
       <td></td>
       <td></td>
      </tr>
      @php
          $TotalPlayPoint += $l6d->bet_amount;
          $TotalWinPoint += $l6d->agent_win;
          $TotalClaimPoint += $l6d->agent_win;
          $TotalAgtCom += $l6d->agent_commission;
          $TotalNTP += 0;
          $TotalNTP += 0;
      @endphp
      @endforeach
<tr style="background-color: black;color:aqua;">
  <td colspan="2">{{$stockez_name}}(Total)</td>
  <td>{{$TotalPlayPoint}}</td>
  <td>{{$TotalWinPoint}}</td>
  <td>{{$TotalClaimPoint}}</td>
  <td>{{$TotalAgtCom}}</td>
  <td>{{$TotalNTP}}</td>
  <td>{{$TotalNTP}}</td>
</tr>
@endforeach
    </table>
</div>

<div class="table-container" id="table6">
    <!-- Table 3 content here -->
    <table class="table table-bordered" >
        <tr>
            <th scope="col">id</th>
            <th scope="col">UserName</th>
            <th scope="col">Play Point</th>
            <th scope="col">Win Point</th>
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">NTP</th>
            <th scope="col">NTP%</th>
          </tr>
          @php 
          $slno = 0;
          @endphp
    @foreach($Yddata as $stockez_name => $l6)
    @php
        $TotalPlayPoint = 0;
        $TotalWinPoint = 0;
        $TotalClaimPoint = 0;
        $TotalAgtCom = 0;
        $TotalNTP = 0;
        $TotalNTPPer = 0;
        $fromDate = date('Y-m-d', strtotime('yesterday'));
$toDate = date('Y-m-d');


    @endphp
      @foreach($l6 as $l6d)
      <tr scope="row" style="background-color:@php
              $uname = $l6d->agent_user_name;
              $agt = App\Models\agent::where('username', $uname)->first();
              if($agt){
                  if(($agt->onstatus) == 1){
                          echo "#b7eb34";
                  }
              }
      @endphp">
       <td>{{++$slno}}</td>
       <td>{{$l6d->agent_user_name}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->bet_amount}}</a></td>
       <td>{{$l6d->agent_win}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->agent_win}}</a></td>
       <td>{{$l6d->agent_commission}}</td>
       <td></td>
       <td></td>
      </tr>
      @php
          $TotalPlayPoint += $l6d->bet_amount;
          $TotalWinPoint += $l6d->agent_win;
          $TotalClaimPoint += $l6d->agent_win;
          $TotalAgtCom += $l6d->agent_commission;
          $TotalNTP += 0;
          $TotalNTP += 0;
      @endphp
      @endforeach
<tr style="background-color: black;color:aqua;">
  <td colspan="2">{{$stockez_name}}(Total)</td>
  <td>{{$TotalPlayPoint}}</td>
  <td>{{$TotalWinPoint}}</td>
  <td>{{$TotalClaimPoint}}</td>
  <td>{{$TotalAgtCom}}</td>
  <td>{{$TotalNTP}}</td>
  <td>{{$TotalNTP}}</td>
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
            <th scope="col">NTP</th>
            <th scope="col">NTP%</th>
          </tr>
          @php 
          $slno = 0;
          @endphp
    @foreach($Tddata as $stockez_name => $l6)
    @php
        $TotalPlayPoint = 0;
        $TotalWinPoint = 0;
        $TotalClaimPoint = 0;
        $TotalAgtCom = 0;
        $TotalNTP = 0;
        $TotalNTPPer = 0;
        $fromDate = date('Y-m-d 00:00:00'); // Start of today
$toDate = date('Y-m-d 23:59:59'); // End of today

 

    @endphp
      @foreach($l6 as $l6d)
      <tr scope="row" style="background-color:@php
              $uname = $l6d->agent_user_name;
              $agt = App\Models\agent::where('username', $uname)->first();
              if($agt){
                  if(($agt->onstatus) == 1){
                          echo "#b7eb34";
                  }
              }
      @endphp">
       <td>{{++$slno}}</td>
       <td>{{$l6d->agent_user_name}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->bet_amount}}</a></td>
       <td>{{$l6d->agent_win}}</td>
       <td><a href="{{route("agent_sale_repo", ['agentuname' => $l6d->agent_user_name, 'fromDate' => $fromDate, 'toDate' => $toDate])}}">{{$l6d->agent_win}}</a></td>
       <td>{{$l6d->agent_commission}}</td>
       <td></td>
       <td></td>
      </tr>
      </tr>
      @php
          $TotalPlayPoint += $l6d->bet_amount;
          $TotalWinPoint += $l6d->agent_win;
          $TotalClaimPoint += $l6d->agent_win;
          $TotalAgtCom += $l6d->agent_commission;
          $TotalNTP += 0;
          $TotalNTP += 0;
      @endphp
      @endforeach
<tr style="background-color: black;color:aqua;">
  <td colspan="2">{{$stockez_name}}(Total)</td>
  <td>{{$TotalPlayPoint}}</td>
  <td>{{$TotalWinPoint}}</td>
  <td>{{$TotalClaimPoint}}</td>
  <td>{{$TotalAgtCom}}</td>
  <td>{{$TotalNTP}}</td>
  <td>{{$TotalNTP}}</td>
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
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">NTP</th>
            <th scope="col">NTP%</th>
          </tr>
      
              <tr scope="row">
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




<div class="table-container" id="table9">
    <!-- Table 3 content here -->
    <table class="table table-bordered" >
        <tr>
            <th scope="col">id</th>
            <th scope="col">UserName</th>
            <th scope="col">Play Point</th>
            <th scope="col">Win Point</th>
            <th scope="col">Claim Point</th>
            <th scope="col">Agent Commission</th>
            <th scope="col">NTP</th>
            <th scope="col">NTP%</th>
          </tr>
      
              <tr scope="row">
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