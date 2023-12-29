@extends('layouts.main')
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Game</title>
    <link rel="stylesheet" href="css/style.css" />
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
@section('content')
<section class="main-content">
    <div class="col-md-12">
      <div class="panel panel-primary">
         <div class="panel-heading">
            <div class="row">
              <div class="col-xs-6 text-left">Manage Roles</div>
               
            </div>
      </div>
          <div class="panel-body">
             <table id="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                     @php 
                        $slno = 0;
                     @endphp
                      <tr>
                          <th>Sl no</th>
                          <th>Role</th>
                          <th>Admin</th>
                          <th>Superstockez</th>
                          <th>stockez</th>
                          <th>agent</th>
                      </tr>
                  </thead>
                   <tbody>                
               <tr>
                @php 
                    $slno += 1; 
                @endphp 
                <form>
                    <td>{{$slno}}</td>
                    <td></td>
                    <td style="text-align: center;"><input type="checkbox" value=""></td>
                    <td style="text-align: center;"><input type="checkbox" value=""></td>
                    <td style="text-align: center;"><input type="checkbox" value=""></td>
                    <td style="text-align: center;"><input type="checkbox" value=""></td>
                   
                </tr>
            </tbody>
         </table>
    </div>
    <a href="" class="btn btn-primary" style="margin:0 0 1rem 1rem;">Submit</a>
</form>
       </div>
<section>
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
