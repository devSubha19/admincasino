<?php use App\Models\superstockez; ?>
@extends('layouts.main')
@section('content')
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js">
    <style>
        .nt{
            display: none; 
        }
    </style>
</head>
<section class="main-content">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading text-left"> Login Request
            </div>
            <div class="panel-body">    
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        @php 
                            $slno=0;
                        @endphp
                        <tr>
                            <th>Sl no.</th>
                            <th>id</th>
                            <th>Username</th>
                            <th>SuperStockez</th>
                            <th>Stockez</th>
                            <th>Credit</th>
                            <th>Operating system</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody> 
                        @if(empty($agent))
                            <tr>
                                <td colspan="8" style="text-align:center;">No data available</td>
                            </tr>
                        @else
                        @php 
                            $slno = 0;
                        @endphp
                            @foreach ($agent as $spz)
                                <tr>  
                                    @php
                                        $stockez = $spz['sto_ckez']['username'];
                                        $superstockez = superstockez::where('id', $spz['sto_ckez']['superstockez'])->first();                                        
                                        $slno+=1;
                                    @endphp
                                    <td>{{$slno}}</td>
                                    <td>{{$spz['id']}}</td>
                                    <td>{{$spz['username']}}</td>
                                    <td>{{$superstockez->username}}</td>
                                    <td>{{$stockez}}</td>
                                    <td>{{$spz['credit']}}</td>
                                    <td>{{$spz['os']}}</td>
                                    <td align="center">
                                        <a href="{{route('allowagentlogin', ['id' => $spz['id']])}}" class="btn btn-success btn-sm">Allow</a>
                                        <a href="{{route('blockagentlogin', ['id' => $spz['id']])}}" class="btn btn-danger btn-sm">Block</a>
                                    </td> 
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
<script src="js/index.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#example')) {
        $('#example').DataTable().destroy();
    }
    $('#example').DataTable({
        "processing": true,
        "serverSide": false,
        "columns": [
            { "data": "id" },
            { "data": "username" },
        ],
        "data": <?php echo json_encode($agent ?? []) ?>,
        $('#example tbody').html('<tr><td colspan="8" style="text-align:center;">No data available</td></tr>');
    });
});

</script>

<script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">
@endsection
