@extends('layouts.main')
@section('content')
    @php
        use Carbon\Carbon;
        use App\Models\superstockez;
        $slno = 0;
    @endphp

    <style>
        .tdy-summery {
            height: 10rem;
        }

        .contbox {
            height: 90%;
            width: 100%;
            background-color: #cbe2e2;
            display: flex;
            align-items: center;
        }

        .contbox div {
            height: 100%;
            text-align: center
        }

        .status-logo {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 5px;
}
    </style>

    <section class="main-content">
        <div class="col-md-12 tdy-summery ">
            <div class="row">
                <div class="col-md-6">
                    <h4 style="font-family: verdana; text-align: left;">Today's Summary({{ date('d-m-Y') }})</h4>
                </div>
                <h4 id="real-time-clock" style="text-align: right;margin-right:1rem; font-family: verdana;"></h4>
            </div>

            <div class="row contbox">
                <div class="col-md-2">
                    <h5>Total Play Points</h5>
                    <p>{{$totalPlayPoints}}</p>
                </div>      
                <div class="col-md-2">
                    <h5>Total Win Points</h5>
                    <p>{{$totalWinPoints}}</p>
                </div>
                <div class="col-md-2">
                    <h5>End Points</h5>
                    <p>{{$EndPoints}}</p>
                </div>
                <div class="col-md-2">
                    <h5>Average Bet</h5>
                    <p>{{$averageBet}}</p>
                </div>
                <div class="col-md-2">
                    <h5>Super Stockez</h5>
                    <p>{{$SuperStockez}}</p>
                </div>
                <div class="col-md-2">
                    <h5>Total Profit</h5>
                    <p>{{$totalProfit}}</p>
                </div>
            </div>
        </div>
      <br><br>
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading text-left">Online Players</div>
                <div class="panel-body">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sno</th>
                                <th>Id</th>
                                <th>UserName</th>
                                <th>SuperStokez</th>
                                <th>Stockez</th>
                                <th>Credit</th>
                                <th>Device</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(empty($agent))
                            <tr>
                                <td colspan="7" style="text-align:center;">No data available</td>
                            </tr>
                         @else
                            @foreach ($agent as $spz)
                             <tr>  
                                 @php
                                     
                                     $stockez = $spz['sto_ckez']['username'];
                                     $superstockez = superstockez::where('id', $spz['sto_ckez']['superstockez'])->first();
                                     $slno += 1;
                                 @endphp
                                 <td>{{$slno}}</td>                               
                                <td>{{$spz['id']}}</td> 
                                <td>
                                    <span class="status-logo" style="background-color: 
                                        @if($spz['onstatus'] == 1)
                                            green
                                        @elseif($spz['onstatus'] == 0 && now()->diffInMinutes($spz['updated_at']) <= 15)
                                            orange
                                        @else
                                            red
                                        @endif;">
                                    </span>
                                    {{ $spz['username'] }}
                                </td>
                                <td>{{$superstockez->username}}</td> 
                                <td>{{$stockez}}</td> 
                                <td>{{$spz['credit']}}</td> 
                                <td class="device-logo" style="text-align: center">
                                    @if($spz['os'] == 'windows')
                                        <img src="{{(URL('img/window-logo.png '))}}    " alt="Windows Logo" style="50px;width:50px;">
                                    @elseif($spz['os'] == 'linux')
                                        <img src="{{ asset('img/linux-logo.png') }}" alt="Linux Logo" style="50px;width:50px;">      
                                    @elseif($spz['os'] == 'mac')
                                        <img src="{{ asset('img/mac-logo.png') }}" alt="Mac Logo" style="50px;width:50px;">
                                    @elseif($spz['os'] == 'ios' || $spz['os'] == 'android')
                                        <img src="{{ asset('img/mobile-logo.png') }}" alt="Mobile Logo" style="50px;width:50px;">   
                                    @else
                                        Unknown device
                                    @endif
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
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">
<script type="text/javascript">
        $(document).ready(function () {
            $('#example').dataTable();
        });
</script>
<script>
    function updateClock() {
        var now = new Date();
        var hours = now.getHours().toString().padStart(2, '0');
        var minutes = now.getMinutes().toString().padStart(2, '0');
        var seconds = now.getSeconds().toString().padStart(2, '0');
        var milliseconds = now.getMilliseconds().toString().padStart(3, '0'); // Milliseconds
        var timeString = hours + ':' + minutes + ':' + seconds + '.' + milliseconds;
        document.getElementById('real-time-clock').textContent = timeString;
        setTimeout(updateClock, 100);
    }
    window.onload = function () {
        updateClock();
    };
</script>
@endsection