@extends('layouts.main')

@section('content')
  <head>
    <link rel="stylesheet" href="/css/style.css">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  </head>

  <section class="main-content" style="margin-top: -10px">
    <div class="new-cards">
      <div class="new-card1">
        <h3 id="date"></h3>
        <h1 id="time"></h1>
      </div>
      <div class="new-card2">
        <div class="summary">
          <h4 class="text">Today's Summary</h4>
          <ul>
          <a href="dashboard"> <li>View Dashboard</li> </a>
          <a href="turnoverrepo"> <li>Turnover Reports</li></a>
          </ul>
        </div>
        <div class="game-details">
          <div class="details">
            <h5>Online Players</h5>
            <h4>{{$onplayer}}</h4>
          </div>
          <div class="details">
            <h5>Play Points</h5>
            <h4>{{$playPoints}}</h4>
          </div>
          <div class="details">
            <h5>Win Points</h5>
            <h4>{{$winPoints}}</h4>
          </div>
          <div class="details">
          <h5>End Points</h5>
            <h4>{{$endPoints}}</h4>
          </div>
        </div>  
      </div>
    </div>

    <div class="chart-card">
      <div class="text">Monthly Details</div>
      <div id="area-chart"></div>
    </div>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.42.0/apexcharts.min.js"></script>
  <script src="js/index.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      showToastrMessages();
    });

    function showToastrMessages() {
      showToastr('{{ session('success') }}', 'success');
      showToastr('{{ session('error') }}', 'error');
    }

    function showToastr(message, type) {
      if (message) {
        toastr.options = {
          "closeButton": true,
          "timeOut": 3000
        };
        toastr[type](message);
      }
    }

    function updateDateTime() {
      var currentDate = new Date();

      // Display the current date
      var dateElement = document.getElementById('date');
      var options = { day: 'numeric', year: 'numeric', month: 'long', weekday: 'long' };
      var formattedDate = currentDate.toLocaleDateString('en-US', options);
      dateElement.innerText = formattedDate;

      // Display the current time
      var timeElement = document.getElementById('time');
      var hours = currentDate.getHours().toString().padStart(2, '0');
      var minutes = currentDate.getMinutes().toString().padStart(2, '0');
      var seconds = currentDate.getSeconds().toString().padStart(2, '0');
      var formattedTime = hours + ':' + minutes + ':' + seconds;
      timeElement.innerText = formattedTime;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
  </script>
@endsection
