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
 
    
     <section class="main-content" style="margin-top:-10px">
         <div class="col-md-12">
            <div class="panel panel-primary">
               <div class="panel-heading text-left">Game</div>
               <div class="panel-body">
                  <form class="form-horizontal col-md-offset-3"  method="post" action="saveeditgamemob">
                     @csrf
                     <div class="form-group text-center">   
                        <label class="control-label col-sm-2">Name:</label>
                        <div class="col-sm-6">
                           <input type="text" class="form-control"  placeholder="Enter game name" value="{{$game->game_name}}" name="gamename" required>
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="control-label col-sm-2" >Timing:</label>
                           <div class="col-sm-6">          
                              <select name="timing" class="form-control" id="setting_click">
                              <option @if($game->Timing == '3') selected @endif value="3">3 minutes</option>
                              <option @if($game->Timing == '5') selected @endif value="5">5 minutes</option>
                              <option @if($game->Timing == '10') selected @endif value="10">10 minutes</option>
                              <option @if($game->Timing == '15') selected @endif value="15">15 minutes</option>
                              
                           </select>
                           </div>
                        </div>
                   
                        <input type="hidden" value="{{$game_id}}" name='game_id'>
                     <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-2 ">
                           <input type="submit" name="submit" class="btn btn-info" value="Submit">
                            
                        </div>
                     </div>

                  </form>
                </div>
            </div>
            <section>
                
                <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
                <script src="../js/index.js"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
 
@endsection