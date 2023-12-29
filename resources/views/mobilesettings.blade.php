@php 
$sno = 0;
@endphp
@extends('layouts.main')
@section('content')
<section class="main-content">
    <div class="col-md-12">
       <div class="panel panel-primary">
          <div class="panel-heading text-left">Manage Game</div>
          <div class="panel-body">
             <table id="example" class="table table-striped table-bordered" style="width:100%;table-layout:fixed;">
                  <thead>
                      <tr>
                          <th>Sno</th>
                          <th>name</th>
                          <th>Timing</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                   <tbody>
               @foreach($game as $game)             
                     @php 
                        $sno += 1; 
                     @endphp
                <tr>
                   <td>{{$sno}}</td>
                   <td>{{$game->game_name}}</td>
                  <td>{{$game->Timing}}</td>
                   <td><a class="btn btn-success" href="{{route('gamemob',['game_id'=>$game->id])}}">edit</td>
                   
                </tr>
                @endforeach 
                </tbody>
             
             </table>
          </div>
       </div>
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