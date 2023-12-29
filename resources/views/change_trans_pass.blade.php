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
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
   </head>
   <body>
 
     <section class="main-content">
         <div class="col-md-12">
            <div class="panel panel-primary">
               <div class="panel-heading text-left">Reset Transaction Pin</div>
               <div class="panel-body">
                  
                  <form class="form-horizontal" method="post">
                     <div class="alert alert-primary" role="alert">

                   
                  </div>
                     <div class="form-group text-center">
                        <label class="control-label col-sm-2" >Password:</label>
                        <div class="col-sm-4">
                           <input type="text" class="form-control" id="password" placeholder="Enter Password" name="password" required>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Old Transaction Pin: </label>
                        <div class="col-sm-4">          
                           <input type="password" class="form-control" name="old_tran_password" id="pwd" placeholder="Enter Pin"  required>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="control-label col-sm-2" >New Transaction Pin:</label>
                        <div class="col-sm-4">          
                           <input type="password" class="form-control" name="new_tran_password" id="pwd" placeholder="Transaction Pin"  required>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Confirm Transaction Pin:</label>
                        <div class="col-sm-4">          
                           <input type="text" class="form-control"  name="confirm_tran_password" placeholder="Confirm Transaction Pin"  required>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-2 ">
                           <input type="submit" name="submit" class="btn btn-info" value="Submit">
                           <!-- <button type="submit"  name="submitqq" ></button> -->
                        </div>
                     </div>
                  </form>
               </div>
            </div>
    <section>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
    <script src="/js/index.js"></script>
@endsection 