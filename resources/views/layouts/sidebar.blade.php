<div class="sidebar">
  <div class="profile">
    @if(session('user_id'))
        @if(session('user_type') == 'admin')  
            @php
                $user = App\Models\admin::find(session('user_id'));
            @endphp
        @elseif (session('user_type') == 'superstockez')
            @php  
                $user = App\Models\superstockez::find(session('user_id'));
            @endphp
        @else
            @php  
                $user = App\Models\stockez::find(session('user_id'));
            @endphp
        @endif

        @if($user)
            @php
                $fullNameArray = explode(' ', $user->name);
                $firstName = $fullNameArray[0];
            @endphp
            <span class="greet">Hello {{ $firstName }} <i class="fas fa-smile-beam"></i></span>
        @endif
    @endif
  </div>
 
    <ul class="grid">
      <li class="grid-item">
        <i class="fa-solid fa-user-doctor"></i>
        <a href="admin">Admin</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-user"></i>
       <a href="superstockez">Super Stockez</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-user"></i>
        <a href="stockez">Stockez</a>
      </li>
      <li class="grid-item">
        <i class="fa-regular fa-user"></i>
        <a href="agent">Agents</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-arrow-right-to-bracket"></i>
        <a href="agentloginreq">Login Requests</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-arrow-right-arrow-left"></i>
        <a href="transferpoint">Transfer Point</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-gauge"></i>
        <a href="dashboard">Dashboard</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-gamepad"></i>
        <a href="mnggame">Manage Games</a> 
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-chart-area"></i>
        <a href="gamesummery">Game Summary</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <a href="playerhistory">Player History</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-chart-pie"></i>
        <a href="turnoverrepo">Turnover Report</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-money-bill"></i>
        <a href="transactionrepo">Transaction Report</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-money-bill"></i>
        <a href="commissionpayoutrepo">Commission Payout Report</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-money-bill"></i>
        <a href="transactionallrepo">Transaction All Report</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-key"></i>
        <a href="chngtranspass">Change Transaction</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-gear"></i>
        <a href="settings">Settings</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-address-card"></i>
        <a href="assignrole">Assign Roles</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-money-bill"></i>
        <a href="agentsalereq">Agent Sales Request</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-money-bill"></i>
        <a href="stockezsalerepo">Stockez Sales Report</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-chart-pie"></i>
        <a href="superstockezsalerepo">Superstockez Sales Report</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-gamepad"></i>
        <a href="resulthistory">Result History</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-gamepad"></i>
        <a href="agentpaymesetper">Agent Payment Settings - %</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-gamepad"></i>
        <a href="agentpaymsetamt">Agent Payment Settings - Amount</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-money-bill"></i><a href="unclaimedrepo">Unclaimed Report</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-money-bill"></i><a href="commissionrepo">Commission Report</a>
      </li>
      <li class="grid-item">
        <i class="fa-solid fa-money-bill"></i><a href="mobilesettings">Mobile Settings</a>
      </li>
      <br /><br /><br /><br /><br><br><br><br><br>
    </ul>
  </div>-

  <script>
    function handleClick(event) {
      const clickedUrl = event.target.getAttribute('href');
      const listItems = document.querySelectorAll('.grid-item');
      listItems.forEach(li => {
        li.classList.remove('active');
      });

      event.target.parentElement.classList.add('active');
      localStorage.setItem('lastClickedUrl', clickedUrl);
    }

    const anchorElements = document.querySelectorAll('.grid-item a');
    anchorElements.forEach(anchor => {
      anchor.addEventListener('click', handleClick);

      const lastClickedUrl = localStorage.getItem('lastClickedUrl');
      if (lastClickedUrl && anchor.getAttribute('href') === lastClickedUrl) {
        anchor.parentElement.classList.add('active');
      }
    });
  </script>
