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
    <form action="" method="GET">
        <div class="form-row">
            <div class="form-group col-md-6">
                <input type="text" name="search" id="searchInput" class="form-control mb-3" placeholder="Block Percentage" value="">
            </div>
            <div class="form-group col-md-6">
                <input type="text" name="fromDate" class="form-control" placeholder="Unblock Percentage" value="" >
            </div>
        </div>
        <center><button type="submit" class="btn btn-primary">Update All</button></center>
        </form>
    <div class="col-md-12">
       <div class="panel panel-primary">
          <div class="panel-heading text-left"> Agent Payment Setting
              

          </div>
          <div class="panel-body">
             <table id="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Username</th>
                          <th>Name</th>
                          <th>Stockez</th>
                          <th>Block</th>
                          <th>Unblock</th>
                          <th>Status</th>
                          <th>Action</th>
                      </tr>
                      
                  </thead>
                   <tbody> 
           
                <tr>                                   
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
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



