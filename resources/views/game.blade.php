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
                  <form class="form-horizontal col-md-offset-3"  method="post" action="saveeditgame">
                     @csrf
                     <div class="form-group text-center">
                        <label class="control-label col-sm-2">Name:</label>
                        <div class="col-sm-6">
                           <input type="text" class="form-control"  placeholder="Enter game name" value="{{$game->game_name}}" name="gamename" required>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Description: </label>
                        <div class="col-sm-6">          
                           <input type="text" value="{{$game->game_description}}"  class="form-control" name="description" id="pwd" placeholder="Enter Game Description"  required>
                        </div>
                     </div>
                    
                     <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Height:</label>
                        <div class="col-sm-6">          
                           <input type="text" class="form-control"  value="{{$game->height}}" name="height" placeholder="200"  required>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="control-label col-sm-2" >Width:</label>
                        <div class="col-sm-6">          
                           <input type="text" class="form-control" value="{{$game->width}}" name="width" placeholder="200" required>
                        </div>
                     </div>
                       <div class="form-group">
                        <label class="control-label col-sm-2" for="pwd">Bonus:</label>
                        <div class="col-sm-6 " >          
                           <input type="radio" name="bonus" @if($game->bonus == '2') checked @endif value="2"> 2X &nbsp;&nbsp;&nbsp;
                           <input type="radio" name="bonus" @if($game->bonus == '3') checked @endif value="3"> 3X &nbsp;&nbsp;&nbsp;
                           <input type="radio" name="bonus" @if($game->bonus == '4') checked @endif value="4"> 4X &nbsp;&nbsp;&nbsp;
                           <input type="radio" name="bonus" @if($game->bonus == '5') checked @endif value="5"> 5X &nbsp;&nbsp;&nbsp;
                           <input type="radio" name="bonus" @if($game->bonus == '7') checked @endif value="7"> 7X &nbsp;&nbsp;&nbsp;
                           <input type="radio" name="bonus" @if($game->bonus == '8') checked @endif value="8"> 8X &nbsp;&nbsp;&nbsp;
                           <input type="radio" name="bonus" @if($game->bonus == '10') checked  @endif value="10"> 10X &nbsp;&nbsp;&nbsp;
                        </div>
                        </div> 

                        <div class="form-group">
                        <label class="control-label col-sm-2" >Setting:</label>
                           <div class="col-sm-6">          
                              <select name="setting" class="form-control" id="setting_click">
                              <option @if($game->settings == 'Not Set') selected @endif value="Not Set">Not Set</option>
                              <option @if($game->settings == 'Very Low') selected @endif value="Very Low">Very Low</option>
                              <option @if($game->settings == 'Medium') selected @endif value="Medium">Medium</option>
                              <option @if($game->settings == 'Percentage') selected @endif value="Percentage">Percentage</option>
                              <option @if($game->settings == 'Multiple Agent') selected @endif value="Multiple Agent">Multiple Agent</option>
                              <option @if($game->settings == 'Agent Settings') selected @endif value="Agent Settings">Agent Settings</option>
                           </select>
                           </div>
                        </div>

                     <div class="form-group" id="percentage_div" >
                        <label class="control-label col-sm-2" >Percentage (%):</label>
                        <div class="col-sm-6">          
                           <input type="number" class="form-control" name="percentage" value={{$game->percentage}}  placeholder="Enter Number"  >
                        </div>
                     </div>
                        <div calss="container mt-5" id="agent_details"> 
                        <h2>Agent Details</h2> 
                        <br>                     
                          <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Agent</label>
                            <div class="col-sm-6">
                              <select name="user" class="form-control ch" id="" required>
                                 <option disabled>Select a Agent</option>
                                 @php 
                                 $agent = App\Models\agent::all();
                                 @endphp
                                 @foreach ($agent as $ag)
                                     <option value="{{$ag->id}}" @if(($game->agent) == $ag->id) selected @endif>{{$ag->username}}</option>
                                 @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Settings</label>
                            <div class="col-sm-6">
                              <select calss="form-control" name="agent_setting">
                                 <option value="win" @if($game->agent_setting == 'win') selected @endif>Wining</option> 
                                 <option value="loss" @if($game->agent_setting == 'loss') selected @endif>Loss</option> 
                              </select>
                            </div>
                          </div>
                        </div>
                        <input type="hidden" value="{{$game_id}}" name='game_id'>
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
                <script src="../js/index.js"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
                <script>
                  $(document).ready(function(){

                           $('#percentage_div').hide();
                           $('#agent_details').hide();


                           function showDivsAccordingToSettings() {
                               var settingValue = "{{$game->settings}}";
                           
                               if (settingValue === 'Percentage') {
                                   $('#percentage_div').show();
                               } else if (settingValue === 'Agent Settings') {
                                   $('#agent_details').show();
                               }
                           }
                        

                           showDivsAccordingToSettings();
                        

                           $("#setting_click").change(function() {
                               var setting_dd = $('option:selected', this).text();
                           
                               if (setting_dd === '') {
                                   $('#percentage_div').hide();
                                   $('#agent_details').hide();
                               } else if (setting_dd === "Percentage") {
                                   $('#percentage_div').show();
                                   $('#agent_details').hide(); 
                               } else if (setting_dd === "Agent Settings") {
                                   $('#agent_details').show();
                                   $('#percentage_div').hide(); 
                               } else {
                                   $('#percentage_div').hide();
                                   $('#agent_details').hide();
                               }
                           });
                  });

 </script>
@endsection