<?php

namespace App\Http\Controllers;
use App\Models\agent_commission;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\commission; 
use App\Models\admin; 
use App\Models\superstockez; 
use App\Models\stockez; 
use App\Models\trans_master; 
use App\Models\agent_temp_bal;
use App\Models\admin_commission; 
use App\Models\super_commission; 
use App\Models\stockez_commission; 

class ReportController extends Controller
{
    
    public function turnoverrepo(Request $request){
        // Set the timezone for this specific method
        \Config::set('app.timezone', 'Asia/Kolkata');
    
        // Retrieve data
        $todayDate = now('Asia/Kolkata')->toDateString();
        $Tddata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereDate('created', $todayDate)
            ->groupBy('stockez_username')
            ->get();
    
        $Yddata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereDate('created', now()->subDay())
            ->groupBy('stockez_username')
            ->get();
    
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $Cwdata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereBetween('created', [$startOfWeek, $endOfWeek])
            ->groupBy('stockez_username')
            ->get();
    
        $startOfLastWeek = now()->startOfWeek()->subWeek();
        $endOfLastWeek = now()->endOfWeek()->subWeek();
        $Lwdata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereBetween('created', [$startOfLastWeek, $endOfLastWeek])
            ->groupBy('stockez_username')
            ->get();
    
        $Cmdata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereYear('created', now()->year)
            ->whereMonth('created', now()->month)
            ->groupBy('stockez_username')
            ->get();
    
        $Lmdata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereYear('created', now()->subMonth()->year)
            ->whereMonth('created', now()->subMonth()->month)
            ->groupBy('stockez_username')
            ->get();
    
        $L6mdata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->where('created', '>=', now()->subMonths(5))
            ->groupBy('stockez_username')
            ->get();
        //   echo "<pre>";print_r($Tddata);echo "</pre>";die();
        return view('turnoverrepo', compact('Tddata', 'Yddata', 'Cwdata', 'Lwdata', 'Cmdata', 'Lmdata', 'L6mdata'));
    }    

    public function generateDataQuery($timeframe, $stk_uid = null) {
        $query = DB::table('agent_temp_bal')
            ->select('stockez_username', 'agent_user_name',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(admin_commission) as total_admin_commission')
            );
    
        switch ($timeframe) {
            case 'today':
                $query->whereDate('created', now('Asia/Kolkata')->toDateString());
                break;
            case 'yesterday':
                $query->whereDate('created', now('Asia/Kolkata')->subDay()->toDateString());
                break;
            case 'current_week':
                $query->whereBetween('created', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('created', [now()->startOfWeek()->subWeek(), now()->endOfWeek()->subWeek()]);
                break;
            case 'current_month':
                $query->whereYear('created', now()->year)
                    ->whereMonth('created', now()->month);
                break;
            case 'last_month':
                $query->whereYear('created', now()->subMonth()->year)
                    ->whereMonth('created', now()->subMonth()->month);
                break;
            case 'last_six_months':
                $query->where('created', '>=', now()->subMonths(5));
                break;
            default:
                break;
        }
    
        if ($stk_uid) {
            $query->where('stockez_username', $stk_uid);
        }
    
        return $query->groupBy('stockez_username', 'agent_user_name')->get();
    }
    
    

    public function stockez_turn_repo(Request $request) {
        $stk_uid = $request->uname; 
        \Config::set('app.timezone', 'Asia/Kolkata');
        
        $Tddata = $this->generateDataQuery('today', $stk_uid);
        $Yddata = $this->generateDataQuery('yesterday', $stk_uid);
        $Cwdata = $this->generateDataQuery('current_week', $stk_uid);
        $Lwdata = $this->generateDataQuery('last_week', $stk_uid);
        $Cmdata = $this->generateDataQuery('current_month', $stk_uid);
        $Lmdata = $this->generateDataQuery('last_month', $stk_uid);
        $L6mdata = $this->generateDataQuery('last_six_months', $stk_uid);
         
        return view('stockezturnrepo', compact('Tddata', 'Yddata', 'Cwdata', 'Lwdata', 'Cmdata', 'Lmdata', 'L6mdata'));
    }

    public function transactionrepo(Request $request){
        if(session('user_id')){
            if(session('user_type') == 'admin'){
                $user_id = admin::find(session('user_id'));
                $uid = $user_id->username; 
            }
            else if(session('user_type') == 'stockez'){
                $user_id = stockez::find(session('user_id'));  
                $uid = $user_id->username; 
            }       

            else{
                $user_id = superstockez::find(session('user_id'));
                $uid = $user_id->username; 
            }
        }
        $transaction = trans_master::where('sender_uid', $uid)
                                ->orWhere('receiver_uid', $uid)->get();

        $usernames = trans_master::where(function ($query) use ($uid) {
            $query->where('sender_uid', '!=', $uid)
                  ->orWhere('receiver_uid', '!=', $uid);  
        })
        ->select('sender_uid', 'receiver_uid')
        ->get()
        ->flatMap(function ($item) use ($uid) {
            return collect([$item->sender_uid, $item->receiver_uid]);
        })
        ->reject(function ($username) use ($uid) {
            return $username == $uid;
        })
        ->unique()
        ->values()
        ->toArray();
                         
        return view('transactionrepo', compact('transaction','uid','usernames'));
    }
    public function generateDataQuery2($agt_uid, $timeframe) {
        $query = DB::table('agent_temp_bal')
            ->select('agent_user_name',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->where('agent_user_name', $agt_uid);
    
        switch ($timeframe) {
            case 'today':
                $query->whereDate('created', now('Asia/Kolkata')->toDateString());
                break;
            case 'yesterday':
                $query->whereDate('created', now('Asia/Kolkata')->subDay()->toDateString());
                break;
            case 'current_week':
                $query->whereBetween('created', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('created', [now()->startOfWeek()->subWeek(), now()->endOfWeek()->subWeek()]);
                break;
            case 'current_month':
                $query->whereYear('created', now()->year)
                    ->whereMonth('created', now()->month);
                break;
            case 'last_month':
                $query->whereYear('created', now()->subMonth()->year)
                    ->whereMonth('created', now()->subMonth()->month);
                break;  
            case 'last_six_months':
                $query->where('created', '>=', now()->subMonths(5));
                break;
            default:
                break;
        }
    
     
        $query->groupBy('agent_user_name');
    
        return $query->get();
    }
    
    
    public function agent_turn_repo(Request $request) {
        $agt_uid = $request->uname; 
        \Config::set('app.timezone', 'Asia/Kolkata');
        
        $Tddata = $this->generateDataQuery2($agt_uid, 'today');
        $Yddata = $this->generateDataQuery2($agt_uid, 'yesterday');
        $Cwdata = $this->generateDataQuery2($agt_uid, 'current_week');
        $Lwdata = $this->generateDataQuery2($agt_uid, 'last_week');
        $Cmdata = $this->generateDataQuery2($agt_uid, 'current_month');
        $Lmdata = $this->generateDataQuery2($agt_uid, 'last_month');
        $L6mdata = $this->generateDataQuery2($agt_uid, 'last_six_months');
 
        return view('agentturnrepo', compact('Tddata', 'Yddata', 'Cwdata', 'Lwdata', 'Cmdata', 'Lmdata', 'L6mdata'));
    }
    
    public function generateDataQuery3( $timeframe) {
        $query = DB::table('agent_temp_bal')
        ->select('stockez_username', 'agent_user_name',
            DB::raw('SUM(bet_amount) as bet_amount'),
            DB::raw('SUM(agent_win) as agent_win'),
            DB::raw('SUM(end_point) as end_point'),
            DB::raw('SUM(super_commission) as super_commission'),
            DB::raw('SUM(stockez_commission) as stockez_commission'),
            DB::raw('SUM(admin_commission) as admin_commission'),
            DB::raw('SUM(agent_commission) as agent_commission')
        );
    
        switch ($timeframe) {
            case 'today':
                $query->whereDate('created', now('Asia/Kolkata')->toDateString());
                break;
            case 'yesterday':
                $query->whereDate('created', now('Asia/Kolkata')->subDay()->toDateString());
                break;
            case 'current_week':
                $query->whereBetween('created', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('created', [now()->startOfWeek()->subWeek(), now()->endOfWeek()->subWeek()]);
                break;
            case 'current_month':
                $query->whereYear('created', now()->year)
                    ->whereMonth('created', now()->month);
                break;
            case 'last_month':
                $query->whereYear('created', now()->subMonth()->year)
                    ->whereMonth('created', now()->subMonth()->month);
                break;
            case 'last_six_months':
                $query->where('created', '>=', now()->subMonths(5));
                break;
            default:
                break;
        }
    
    
        return $query->groupBy('stockez_username', 'agent_user_name')->get();
    }
    
    
    public function agentsalereq(Request $request) {

        \Config::set('app.timezone', 'Asia/Kolkata');
        
        $Tddata = $this->generateDataQuery3 ('today');
        $Yddata = $this->generateDataQuery3('yesterday');
        $Cwdata = $this->generateDataQuery3( 'current_week');
        $Lwdata = $this->generateDataQuery3 ('last_week');
        $Cmdata = $this->generateDataQuery3 ('current_month');
        $Lmdata = $this->generateDataQuery3('last_month');
        $L6mdata = $this->generateDataQuery3('last_six_months');
        
        function groupdata($data){
            $groupedData = [];
            foreach($data as $d){
                $groupedData[$d->stockez_username][] = $d;
            }
            return $groupedData;
        }
        $Tddata = groupdata($Tddata);
        $Yddata = groupdata($Yddata);
        $Cwdata = groupdata($Cwdata);
        $Lwdata = groupdata($Lwdata);
        $Cmdata = groupdata($Cmdata);
        $Lmdata = groupdata($Lmdata);
        $L6mdata = groupdata($L6mdata);
        // echo "<pre>";print_r($L6mdata);echo "</pre>";   die();
        return view('agentsalereq', compact('Tddata', 'Yddata', 'Cwdata', 'Lwdata', 'Cmdata', 'Lmdata', 'L6mdata'));
    }


    public function agent_sale_repo(Request $request){
        $toDate = $request->toDate;
        $fromDate  = $request->fromDate;
        $uname = $request->agentuname;
         
        $agentSale = DB::select("SELECT * FROM agent_temp_bal WHERE created BETWEEN ? AND ? AND agent_user_name = ?", [$fromDate, $toDate, $uname]);

         
        return view('agent_sale_repo', compact('toDate', 'fromDate', 'agentSale'));
    }
    public function filtertransaction(Request $request){ 
    if(session('user_id')){
        if(session('user_type') == 'admin'){
            $user_id = admin::find(session('user_id'));
            $uid = $user_id->username; 
        }
        else if(session('user_type') == 'stockez'){
            $user_id = stockez::find(session('user_id'));
            $uid = $user_id->username; 
        }

        else{
            $user_id = superstockez::find(session('user_id'));
            $uid = $user_id->username; 
        }
    }

    $usernames = trans_master::where(function ($query) use ($uid) {
        $query->where('sender_uid', '!=', $uid)
              ->orWhere('receiver_uid', '!=', $uid);  
    })
    ->select('sender_uid', 'receiver_uid')
    ->get()
    ->flatMap(function ($item) use ($uid) {
        return collect([$item->sender_uid, $item->receiver_uid]);
    })
    ->reject(function ($username) use ($uid) {
        return $username == $uid;
    })
    ->unique()
    ->values()
    ->toArray();
    
        $username = $request->uid; 
        $fromDate = $request->fromDate; 
        $toDate = $request->toDate; 

        $transaction = trans_master::query()
        ->when($uid && $username, function ($query) use ($uid, $username) {
            $query->where(function ($query) use ($uid, $username) {
                $query->where(function ($query) use ($uid, $username) {
                    $query->where('sender_uid', $username)
                        ->where('receiver_uid', $uid);
                })
                ->orWhere(function ($query) use ($uid, $username) {
                    $query->where('sender_uid', $uid)
                        ->where('receiver_uid', $username);
                });
            });
        })
        ->when($fromDate, function ($query, $fromDate) {
            return $query->whereDate('created_at', '>=', $fromDate);
        })
        ->when($toDate, function ($query, $toDate) {
            return $query->whereDate('created_at', '<=', $toDate);
        })
        ->get();
        
    return view('transactionrepo', compact('transaction', 'uid', 'usernames'));


    }


    public function transactionallrepo(Request $request){
        $username = $request->username;
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        if(session('user_id')){
            if(session('user_type') == 'admin'){
                $user_id = admin::find(session('user_id'));
                $uid = $user_id->username; 

                $transQuery = trans_master::query();

                $transQuery->when($username, function ($query) use ($username) {
                    return $query->where(function ($nestedQuery) use ($username) {
                        $nestedQuery->where('receiver_uid', $username)
                                    ->orWhere('sender_uid', $username);
                });
                })->when($fromDate, function ($query) use ($fromDate) {
                    return $query->whereDate('created_at', '>=', $fromDate);
                })->when($toDate, function ($query) use ($toDate) {
                    return $query->whereDate('created_at', '<=', $toDate);
                });
                
                $trans_all = $transQuery->get();
                
//  $x = "  SELECT *
// FROM trans_master
// WHERE
 
//     IF($username IS NOT NULL,
//         (receiver_uid = $username OR sender_uid = $username),
//         1)  

//     AND IF($fromDate IS NOT NULL,
//         DATE(created_at) >= DATE('$fromDate'),
//         1)  

//     AND IF($toDate IS NOT NULL,
//         DATE(created_at) <= DATE('$toDate'),
//         1)  
// ";

            }
            else if(session('user_type') == 'stockez'){
                $user_id = stockez::find(session('user_id'));
                $uid = $user_id->username;

                $transQuery = trans_master::query();

                $transQuery->where(function ($query) use ($uid) {
                    $query->where('sender_uid', $uid)
                        ->orWhere('receiver_uid', $uid)
                        ->orWhere(function ($nestedQuery) {
                            $nestedQuery->where('sender_uid', 'LIKE', 'AGT%')
                                ->orWhere('receiver_uid', 'LIKE', 'AGT%');
                        });
                });
            
                $transQuery->when($username, function ($query) use ($username) {
                    return $query->where(function ($nestedQuery) use ($username) {
                        $nestedQuery->where('receiver_uid', $username)
                            ->orWhere('sender_uid', $username);
                    });
                })->when($fromDate, function ($query) use ($fromDate) {
                    return $query->whereDate('created_at', '>=', $fromDate);
                })->when($toDate, function ($query) use ($toDate) {
                    return $query->whereDate('created_at', '<=', $toDate);
                });
            
                $trans_all = $transQuery->get();
            }
    
            else{
                $user_id = superstockez::find(session('user_id'));
                $uid = $user_id->username; 
                
                $transQuery = trans_master::query();

                $transQuery->where(function ($query) use ($uid) {
                    $query->where('sender_uid', $uid)
                        ->orWhere('receiver_uid', $uid)
                        ->orWhere(function ($nestedQuery) {
                            $nestedQuery->where(function ($query) {
                                $query->where('sender_uid', 'LIKE', 'AGT%')
                                    ->orWhere('receiver_uid', 'LIKE', 'AGT%')
                                    ->orWhere('sender_uid', 'LIKE', 'STK%')
                                    ->orWhere('receiver_uid', 'LIKE', 'STK%');
                            });
                        });
                });
                
                $transQuery->when($username, function ($query) use ($username) {
                    return $query->where(function ($nestedQuery) use ($username) {
                        $nestedQuery->where('receiver_uid', $username)
                            ->orWhere('sender_uid', $username);
                    });
                })->when($fromDate, function ($query) use ($fromDate) {
                    return $query->whereDate('created_at', '>=', $fromDate);
                })->when($toDate, function ($query) use ($toDate) {
                    return $query->whereDate('created_at', '<=', $toDate);
                });
                
                $trans_all = $transQuery->get();
            }      
        }   
        
        return view('transaction_all_repo', compact('trans_all'));
    }

    public function commissionpayoutrepo(Request $request){ 
        
          
                // Fetch data from AdminCommission table
$adminData = admin_commission::select(
    'id',
    'admin_username as username',
    'name as admin_name',
    'agent_username',
    'bet_amount',
    'admin_commission',
    'created_at',
    'game_name',
    'ticket_id'
);

  
$superData = super_commission::select(
    'id',
    'super_username as username',
    'name as super_name',
    'agent_username',
    'bet_amount',
    'super_commission',
    'created_at',
    'game_name',
    'ticket_id'
); 
$stockezData = stockez_commission::select(
    'id',
    'stockez_username as username',
    'name as stockez_name',
    'agent_username',
    'bet_amount',
    'stockez_commission',
    'created_at',
    'game_name',
    'ticket_id'
);
$agentData = agent_commission::select(
    'id',
    'agent_username as username',
    'name as stockez_name',
    'agent_username',
    'bet_amount',
    'agent_commission',
    'created_at',
    'game_name',
    'ticket_id'
);
$commissions = $adminData->union($superData)->union($stockezData)->union($agentData)->get();

        return view('coimmission_payout',compact('commissions'));
    } 

    public function chngtranspass(Request $request){
        return view('change_trans_pass');
    }

    public function stockezsalerepo(Request $request){
        // Set the timezone for this specific method
        \Config::set('app.timezone', 'Asia/Kolkata');
    
        // Retrieve data
        $todayDate = now('Asia/Kolkata')->toDateString();
        $Tddata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'),
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereDate('created', $todayDate)
            ->groupBy('stockez_username')
            ->get();
    
        $Yddata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereDate('created', now()->subDay())
            ->groupBy('stockez_username')
            ->get();
    
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $Cwdata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereBetween('created', [$startOfWeek, $endOfWeek])
            ->groupBy('stockez_username')
            ->get();
    
        $startOfLastWeek = now()->startOfWeek()->subWeek();
        $endOfLastWeek = now()->endOfWeek()->subWeek();
        $Lwdata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereBetween('created', [$startOfLastWeek, $endOfLastWeek])
            ->groupBy('stockez_username')
            ->get();
    
        $Cmdata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereYear('created', now()->year)
            ->whereMonth('created', now()->month)
            ->groupBy('stockez_username')
            ->get();
    
        $Lmdata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereYear('created', now()->subMonth()->year)
            ->whereMonth('created', now()->subMonth()->month)
            ->groupBy('stockez_username')
            ->get();
    
        $L6mdata = DB::table('agent_temp_bal')
            ->select('stockez_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->where('created', '>=', now()->subMonths(5))
            ->groupBy('stockez_username')
            ->get();
        //   echo "<pre>";print_r($Tddata);echo "</pre>";die();
        return view('stockez_sales_report', compact('Tddata', 'Yddata', 'Cwdata', 'Lwdata', 'Cmdata', 'Lmdata', 'L6mdata'));
    }    


    public function superstockezsalerepo(Request $request){
        \Config::set('app.timezone', 'Asia/Kolkata');
    
        // Retrieve data
        $todayDate = now('Asia/Kolkata')->toDateString();
        $Tddata = DB::table('agent_temp_bal')
            ->select('super_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'),
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereDate('created', $todayDate)
            ->groupBy('super_username')
            ->get();
    
        $Yddata = DB::table('agent_temp_bal')
            ->select('super_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereDate('created', now()->subDay())
            ->groupBy('super_username')
            ->get();
    
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $Cwdata = DB::table('agent_temp_bal')
            ->select('super_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereBetween('created', [$startOfWeek, $endOfWeek])
            ->groupBy('super_username')
            ->get();
    
        $startOfLastWeek = now()->startOfWeek()->subWeek();
        $endOfLastWeek = now()->endOfWeek()->subWeek();
        $Lwdata = DB::table('agent_temp_bal')
            ->select('super_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereBetween('created', [$startOfLastWeek, $endOfLastWeek])
            ->groupBy('super_username')
            ->get();
    
        $Cmdata = DB::table('agent_temp_bal')
            ->select('super_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereYear('created', now()->year)
            ->whereMonth('created', now()->month)
            ->groupBy('super_username')
            ->get();
    
        $Lmdata = DB::table('agent_temp_bal')
            ->select('super_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->whereYear('created', now()->subMonth()->year)
            ->whereMonth('created', now()->subMonth()->month)
            ->groupBy('super_username')
            ->get();
    
        $L6mdata = DB::table('agent_temp_bal')
            ->select('super_username',
                DB::raw('SUM(bet_amount) as total_bet_amount'),
                DB::raw('SUM(agent_win) as total_agent_win'),
                DB::raw('SUM(end_point) as total_end_point'),
                DB::raw('SUM(super_commission) as total_super_commission'),
                DB::raw('SUM(stockez_commission) as total_stockez_commission'),
                DB::raw('SUM(agent_commission) as total_agent_commission'), 
                DB::raw('SUM(admin_commission) as total_admin_commission')
            )
            ->where('created', '>=', now()->subMonths(5))
            ->groupBy('super_username')
            ->get();
        //   echo "<pre>";print_r($Tddata);echo "</pre>";die();
        return view('superstock_sale_repo', compact('Tddata', 'Yddata', 'Cwdata', 'Lwdata', 'Cmdata', 'Lmdata', 'L6mdata'));
         
    }
    public function resulthistory(Request $request){
        return view('result_history');
    }

    public function unclaimedrepo(Request $request){
        $unclaimed = agent_temp_bal::all();  
        $unclaimed_total = agent_temp_bal::where('claimed_status', 0)->sum('agent_win');  
        return view('unclaimedrepo', compact('unclaimed', 'unclaimed_total'));
    }

    public function commissionrepo(Request $request){

        return view('commissionrepo');
    }
}
