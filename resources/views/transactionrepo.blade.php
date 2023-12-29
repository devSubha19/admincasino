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
   <h2>Transaction</h2>
   
   <form action="filtertransaction" method="GET">
   <div class="form-row">
       <div class="form-group col-md-4">
        <select name="uid" class="form-control mb-3 ch chosen-select" id="" action="filtertransaction">
                <option value ="">Search By UserName</option>
                @foreach ($usernames as $user)
                <option value ="{{$user}}">{{$user}}</option>
                @endforeach
        </select>
       </div>
       <div class="form-group col-md-4">
           <input type="date" name="fromDate" class="form-control" placeholder="From Date">
       </div>
       <div class="form-group col-md-4">
           <input type="date" name="toDate" class="form-control" placeholder="To Date" >
       </div>
       
   </div>
   <center><button type="submit" class="btn btn-primary">Search</button></center>
   </form>
   <br><br>
   <table class="table table-bordered">
   <thead>
   <tr>
       <th>Sl no.</th>
       <th>Transaction Id</th>
       <th>Transaction With</th>
       <th>Credit</th>
       <th>Debit</th>
       <th>Balance</th>
       <th>Date</th>
       <th>Type</th>
   </tr>
   </thead>
   <style>td, th{text-align: center;}</style>
   <tbody id="tableBody">
    @php
        $slno = 0;
    @endphp
    @foreach($transaction as $trns)
       <tr>
        @php
            $slno ++;
        @endphp
           <td>{{$slno}}</td>
           <td>{{$trns->id}}</td>
           @if($trns->sender_uid == $uid)
                    <td>{{$trns->receiver_uid}}</td>
                    <td><b style="font-weight: 900">---<b></td>
                    <td>{{$trns->amount}}</td>
                    <td style="color:red">-{{$trns->sender_endpoint}}</td>
                    <td>{{ Carbon\Carbon::parse($trns->created_at)->toDateString() }}</td>
                    <td>{{$trns->receiver_type}}</td>
           @else

                    <td>{{$trns->sender_uid}}</td>
                    <td>{{$trns->amount}}</td>
                    <td><b style="font-weight: 900">---<b></td>
                    <td style="color:green;">-{{$trns->receiver_endpoint}}</td>
                    <td>{{ Carbon\Carbon::parse($trns->created_at)->toDateString() }}</td>
                    <td>{{$trns->sender_type}}</td>

           @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script>
$(document).ready(function () {
    $('.chosen-select').chosen({
        search_contains: true
    });
});
    </script>
@endsection
