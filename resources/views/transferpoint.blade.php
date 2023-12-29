@extends('layouts.main')

@section('content')
@include('trans_style')
<section class="main-content"">
    <div class="col-md-12" >
        <div class="panel panel-primary">
            <div class="panel-heading text-left">Transfer point</div>
            <div class="panel-body">
                
                @if(session('user_type') == 'admin')
                <div class="form-group" >
                    <div class="col-sm-6">
                        <select name="user" class="form-control ch" id="" required>
                            <option value=""></option>
                            @foreach($superstockez as $sup)
                                <option value="{{$sup->id}},sup1">{{$sup->username}}</option>
                            @endforeach
                            @foreach ($stockez as $stk)
                                <option value="{{$stk->id}},stk2">{{$stk->username}}</option>
                            @endforeach
                            @foreach ($agent as $agt)
                                <option value="{{$agt->id}},agt3">{{$agt->username}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br><br>
             @endif

                @if(session('user_type') == 'superstockez')
                <div class="form-group" >
                    <div class="col-sm-6">
                        <select name="user" class="form-control ch" id="" required>
                            <option value=""></option>
                            @foreach ($stockez as $stk)
                                <option value="{{$stk->id}},stk2">{{$stk->username}}</option>
                            @endforeach
                            @foreach ($agent as $agt)
                                <option value="{{$agt->id}},agt3">{{$agt->username}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br><br>
                @endif
                
                @if(session('user_type') == 'stockez')
                <div class="form-group" >
                    <div class="col-sm-6">
                        <select name="user" class="form-control ch" id="" required>
                            <option value=""></option>
 
                            @foreach ($agent as $agt)
                                <option value="{{$agt->id}},agt3">{{$agt->username}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br><br>
                @endif
             

                    <div class="row" style="margin-left: 20%" id="userrow">
                        <div class="col-md-12">
                            <div class="player-box1">
                                <div class="player-dtl1">
                                    <span class="details1">
                                        <h3 style="text-align:center;"><b>User Type: <span id="ajutype"></span></b></h3>
                                        <p><b>Name:</b> <span id="ajname"></span></p>
                                        <p><b>username:</b> <span id="ajuname"></span></p>
                                        <p><b>Credit:</b> <span id="ajcredit"></span></p><br>
                                        <div class="col-sm-6">
                                            <a href="transferagent?id=1" class="btn btn-danger" id="transfer">Transfer Credits</a>
                                        </div>
                                        <div class="col-sm-6">
                                            <a href="" class="btn btn-primary" id="adjust">Adjust Credits</a>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
              
            </div>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
<script src="sjs/index.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
<script src="js/index.js"></script>
<script>
    $(document).ready(function(){
        $('.ch').chosen();  
    });
    function loadData() {
    $('.ch').on('change', function () {
        var selectedOption = $(this).val();
       
        $.ajax({
            url: "{{route('get.user.data')}}",
            type: "get",
            data: {
                _token: '{{ csrf_token() }}',
                selectedOption : selectedOption,
            },
            success: function(data){
                var user = data.user;
                var usertype = data.usertype;
                
                $('#userrow').show();
                $('#ajutype').text(usertype);
                $('#ajname').text(user.name);
                $('#ajuname').text(user.username);
                $('#ajcredit').text(user.credit);
                $('#transfer').attr('href', data.transferlink);
                $('#adjust').attr('href', data.adjustlink);
            }
        })
    });
    }

    loadData();
</script>
@endsection