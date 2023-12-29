@extends('layouts.main')
@section('content')
<!DOCTYPE html>
<html lang="en">
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
            <div class="panel panel-primary">
               <div class="panel-heading text-left"> Turnover Report
                  <div class="right"> 
                     <a href="<?=$baseurl?>/xs-admin/agent/add_agent.php" class="btn btn-info">Add Turnover Report</a>
                  </div>

               </div>
               <div class="panel-body">
                  <table id="example" class="table table-striped table-bordered" style="width:100%">
                       <thead>
                           <tr>
                               <th>Id</th>
                               <th>Username</th>
                               <th>Name</th>
                               <th>Email</th>
                               <th>Revenue</th>
                               <th>Type</th>
                               <th>Credit</th>
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
                        <td> 
                           <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                               <div class="btn-group mr-2" role="group" aria-label="First group">
                                 <button type="button" class="btn btn-danger btn-sm">Delete</button>
                                 <button type="button" class="btn btn-success btn-sm">TC</button>
                                 <button type="button" class="btn btn-success btn-sm">SC</button>
                                 <button type="button" class="btn btn-success btn-sm">JC</button>
                                 <button type="button" class="btn btn-success btn-sm">DC</button>
                                 <button type="button" class="btn btn-success btn-sm">16 C</button>
                                  <button type="button" class="btn btn-success btn-sm">HR</button>
                                 <button type="button" class="btn btn-info btn-sm">Edit</button>
                                  <button type="button" class="btn btn-success btn-sm"><i class="fa fa-credit-card"></i></button>
                                  <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-credit-card"></i></button>
                                  <button type="button" class="btn btn-success btn-sm"><i class="fa fa-ban" ></i></button>
                               </div>
                            </div>
                        </td>
                     </tr>
 
                            
                                
                            </tr>
                        </tbody>
                  
                  </table>
               </div>
            </div>
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
   </body>
</html>
@endsection