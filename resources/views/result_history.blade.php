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
   <h2>Result History</h2>
   
   <form action="" method="GET">
   <div class="form-row">
       <div class="form-group col-md-4">
           <input type="text" name="search" id="searchInput" class="form-control mb-3" placeholder="Search by Username" value="">
       </div>
       <div class="form-group col-md-4">
           <input type="date" name="fromDate" class="form-control" placeholder="From Date" value="">
       </div>
       <div class="form-group col-md-4">
           <select class="form-control" name="gamename">
               <option value="Game Name" >Game Name</option>
                
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
       <th>Game</th>
        <th>Win Position</th>
        <th>Details</th>
   </tr>
   </thead>
   <tbody id="tableBody">
       <tr>
            
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
       </tr>
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
@endsection
