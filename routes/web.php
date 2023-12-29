<?php
use App\Http\Controllers\FinanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\gameController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\doublechanceController;
use App\Http\Controllers\triplechanceController;
use App\Http\Controllers\sixteencardController;
use App\Http\Controllers\twelvecardController;
use App\Models\superstockez;


//Authentication
Route::get('/', [AuthController::class, 'login']);
Route::post('userlogin', [AuthController::class, 'UserLogin']);
Route::get('logout', [AuthController::class, 'UserLogOut'])->name('logout') ;

//api

Route::post('api/login', [AuthController::class, 'api']);


Route::middleware('auth:sanctum')->group(function () {
    Route::any('api', [AuthController::class, 'sanity']);
    Route::any('api/bet', [gameController::class, 'betapi']);
    Route::get('api/zeroto9', [gameController::class, 'zeroto9'])->name('zeroto9');  
    Route::post('api/sendsixteen', [sixteencardController::class, 'sixteenapi'])->name('sendsixteen'); 
    Route::get('api/sixteenbet', [sixteencardController::class, 'sixteenbet'])->name('sixteenbet'); 
    Route::post('api/sendtwelve', [twelvecardController::class, 'twelveapi'])->name('sendtwelve'); 
    Route::get('api/twelvebet', [twelvecardController::class, 'twelvebet'])->name('twelvebet'); 
    });

 

//dashboard
Route::get('index', [ViewController::class, 'index']);  
Route::get('/dashboard', [ViewController::class, 'dashboard']);
Route::get('mobilesettings', [gameController::class, 'mobilesettings']);


//user 
Route::get('assignrole', [UserController::class, 'assignrole'])->name('assignrole');
Route::get('count', [ViewController::class, 'getNotificationsCount'])->name('count');

//user admin
Route::get('admin', [UserController::class, 'admin'])->name('admin');
Route::get('addadmin', [UserController::class, 'addadmin'])->name('addadmin');
Route::post('saveadmin', [UserController::class, 'saveadmin'])->name('savead\min');
Route::get('editadmin', [UserController::class, 'editadmin'])->name('editadmin');
Route::post('saveeditadmin', [UserController::class, 'saveeditadmin'])->name('saveeditadmin');

//user superstockez
Route::get('superstockez', [UserController::class, 'superstockez'])->name('superstockez');
Route::get('addsuperstockez', [UserController::class, 'addsuperstockez']);
Route::post('savesuperstockez', [UserController::class, 'savesuperstockez'])->name('savesuperstockez');
Route::get('editsuperstockez', [UserController::class, 'editsuperstockez'])->name('editsuperstockez');
Route::post('saveeditsuperstockez', [UserController::class, 'saveeditsuperstockez'])->name('saveeditsuperstockez');
Route::get('deletesuperstockez', [UserController::class, 'deletesuperstockez'])->name('deletesuperstockez');
Route::get('bansuperstockez', [UserController::class, 'bansuperstockez'])->name('bansuperstockez');
Route::get('viewsuperstockez', [UserController::class, 'viewsuperstockez'])->name('viewsuperstockez');

// user stockez
Route::get('stockez', [UserController::class, 'stockez'])->name('stockez')->name('stockez');
Route::get('addstockez', [UserController::class, 'addstockez'])->name('addstockez');
Route::post('savestockez', [UserController::class, 'savestockez'])->name('savestockez');
Route::get('viewstockez', [UserController::class, 'viewstockez'])->name('viewstockez');
Route::get('editstockez', [UserController::class, 'editstockez'])->name('editstockez');
Route::get('deletestockez', [UserController::class, 'deletestockez'])->name('deletestockez');
Route::get('banstockez', [UserController::class, 'banstockez'])->name('banstockez');
Route::post('saveeditstockez', [UserController::class, 'saveeditstockez'])->name('saveeditstockez');

//user agent
Route::get('agent', [UserController::class, 'agent'])->name('agent');
Route::get('addagent', [UserController::class, 'addagent'])->name('addagent');

Route::post('saveagent', [UserController::class, 'saveagent'])->name('saveagent');
Route::get('viewagent', [UserController::class, 'viewagent'])->name('viewagent');
Route::get('editagent', [UserController::class, 'editagent'])->name('editagent');
Route::get('deleteagent', [UserController::class, 'deleteagent'])->name('deleteagent');
Route::get('banagent', [UserController::class, 'banagent'])->name('banagent');
Route::post('saveeditagent', [UserController::class, 'saveeditagent'])->name('saveeditagent');
Route::get('allowagentlogin', [UserController::class, 'allowagentlogin'])->name('allowagentlogin');
Route::get('blockagentlogin', [UserController::class, 'blockagentlogin'])->name('blockagentlogin');
Route::get('agentloginreq', [UserController::class, 'agentloginreq'])->name('agentloginreq');
Route::get('agentsalereq', [ReportController::class, 'agentsalereq'])->name('agentsalereq');
Route::get('agentpaymesetper', [UserController::class, 'agentpaymesetper'])->name('agentpaymesetper');
Route::get('agentpaymsetamt', [UserController::class, 'agentpaymsetamt'])->name('agentpaymsetamt');

//user finance
Route::get('transfersuperstockez', [FinanceController::class, 'transfersuperstockez'])->name('transfersuperstockez');
Route::get('adjustsuperstockez', [FinanceController::class, 'adjustsuperstockez'])->name('adjustsuperstockez');
Route::post('savetransfersuperstockez', [FinanceController::class, 'savetransfersuperstockez'])->name('savetransfersuperstockez');
Route::post('savesuperadjuststockez', [FinanceController::class, 'saveadjustsuperstockez'])->name('saveadjustsuperstockez');

//stockez finance
Route::get('transferstockez', [FinanceController::class, 'transferstockez'])->name('transferstockez');
Route::get('adjuststockez', [FinanceController::class, 'adjuststockez'])->name('adjuststockez');
Route::post('savetransferstockez', [FinanceController::class, 'savetransferstockez'])->name('savetransferstockez');
Route::post('saveadjuststockez', [FinanceController::class, 'saveadjuststockez'])->name('saveadjuststockez');

//agent finance
Route::get('transferagent', [FinanceController::class, 'transferagent'])->name('transferagent');
Route::get('adjustagent', [FinanceController::class, 'adjustagent'])->name('adjustagent');
Route::post('savetransferagent', [FinanceController::class, 'savetransferagent'])->name('savetransferagent');
Route::post('saveadjustagent', [FinanceController::class, 'saveadjustagent'])->name('saveadjustagent');

//finance
Route::get('transferpoint', [FinanceController::class, 'transferpoint'])->name('transferpoint');

//getuserdata
Route::get('getUserData', [UserController::class, 'getUserData'])->name('get.user.data');

//games
Route::get('game', [gameController::class, 'game'])->name('game');  
Route::post('saveeditgamemob', [gameController::class, 'saveeditgamemob'])->name('saveeditgamemob');
Route::get('gamemob', [gameController::class, 'gamemob'])->name('gamemob');
Route::post('saveeditgame', [gameController::class, 'saveeditgame'])->name('saveeditgame');
Route::get('mnggame', [gameController::class, 'mnggame'])->name('mnggame');
Route::get('gamesummery', [gameController::class, 'gamesummery'])->name('gamesummery');
Route::get('playerhistory', [gameController::class, 'playerhistory'])->name('playerhistory');
Route::get('filterplayerhist', [gameController::class, 'filterplayerhist'])->name('filterplayerhist');
Route::get('settings', [gameController::class, 'settings'])->name('settings');


//Reports
Route::get('turnoverrepo', [ReportController::class, 'turnoverrepo'])->name('turnoverrepo');
Route::get('stockez_turn_repo', [ReportController::class, 'stockez_turn_repo'])->name('stockez_turn_repo');
Route::get('agent_turn_repo', [ReportController::class, 'agent_turn_repo'])->name('agent_turn_repo');
Route::get('agent_sale_repo', [ReportController::class, 'agent_sale_repo'])->name('agent_sale_repo');
Route::get('transactionrepo', [ReportController::class, 'transactionrepo'])->name('transactionrepo');
Route::get('filtertransaction', [ReportController::class, 'filtertransaction'])->name('filtertransaction');
Route::get('transactionallrepo', [ReportController::class, 'transactionallrepo'])->name('transactionallrepo');
Route::get('commissionpayoutrepo', [ReportController::class, 'commissionpayoutrepo'])->name('commissionpayoutrepo');
Route::get('chngtranspass', [ReportController::class, 'chngtranspass'])->name('chngtranspass');
Route::get('stockezsalerepo', [ReportController::class, 'stockezsalerepo'])->name('stockezsalerepo');
Route::get('superstockezsalerepo', [ReportController::class, 'superstockezsalerepo'])->name('superstockezsalerepo');
Route::get('resulthistory', [ReportController::class, 'resulthistory'])->name('resulthistory');
Route::get('unclaimedrepo', [ReportController::class, 'unclaimedrepo'])->name('unclaimedrepo');
Route::get('commissionrepo', [ReportController::class, 'commissionrepo'])->name('commissionrepo');

//game

Route::get('senddouble', [doublechanceController::class, 'doublebet'])->name('senddouble');
Route::get('doublechance', [doublechanceController::class, 'game'])->name('doublechance');
Route::get('triplechance', [triplechanceController::class, 'triplechance'])->name('triplechance');

?>
