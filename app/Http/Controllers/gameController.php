<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\admin;
use App\Models\agent;
use App\Models\agent_temp_bal;
use App\Models\admin_commission;
use App\Models\super_commission;
use App\Models\stockez_commission;
use App\Models\agent_commission;

use App\Models\game;
use App\Models\games_data;
use App\Models\remaining;
use App\Models\stockez;
use App\Models\superstockez;
use App\Models\player_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class gameController extends Controller
{
    public function game(Request $request)
    {
        $game_id = $request->game_id;
        $game = game::find($game_id);
        return view('game', compact('game', 'game_id'));
    }

    public function gamemob(Request $request)
    {
        $game_id = $request->game_id;
        $game = game::find($game_id);
        return view('gamemob', compact('game', 'game_id'));
    }


    public function saveeditgamemob(Request $request)
    {
        $game_id = $request->game_id;
        $timing = $request->timing;
        $game = game::find($game_id);
        $game->Timing = $timing;
        $game->save();
        $game = DB::SELECT("SELECT * FROM game where id ='8' || id = '9' ");
        session()->put('mobile',true);
        return view('mobilesettings',compact('game'));
    }
    public function saveeditgame(Request $request)
    {
        $name = $request->gamename;
        $description = $request->description;
        $height = $request->height;
        $width = $request->width;
        $bonus = $request->bonus;
        $settings = $request->setting;
        $percentage = $request->percentage;
        $agent_id = $request->user;
        $agent_setting = $request->agent_setting;

        $game = game::find($request->game_id);
        $game->game_name = $name;
        $game->game_description = $description;
        $game->height = $height;
        $game->width = $width;
        $game->bonus = $bonus;
        $game->settings = $settings;
        $game->percentage = $percentage;
        $game->agent = $agent_id;
        $game->agent_setting = $agent_setting;
        $game->update();
       
        if (session()->has('game')) {
            return redirect('mnggame');
        }
    
        if (session()->has('mobile')) {
            return redirect('mobilesettings');
        }
       
    }

    public function mnggame(Request $request)
    {
        $game = game::all();
        session()->put('game',true);
        return view('managegame', compact('game'));
    }

    public function gamesummery(Request $request)
    {
        $game = game::all();
        return view('gamesummery', compact('game'));
    }

    public function playerhistory(Request $request)
    {   
        $gameIds = DB::table('agent_temp_bal')
    ->whereIn('game', function ($query) {
        $query->select('game')->from('agent_temp_bal')->groupBy('game');
    })
    ->pluck('game')
    ->toArray();

    $games = DB::table('game')
    ->whereIn('id', $gameIds)
    ->get();

    $uname = DB::select("SELECT DISTINCT agent_user_name FROM agent_temp_bal");
 
    $playerhist = agent_temp_bal::all();
        return view('playerhistory',compact('games','uname','playerhist'));
    }

    public function filterplayerhist(Request $request)
    {   
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $username = $request->input('uname');
        $gamename = $request->input('gamename');
 
        $query = agent_temp_bal::query()
        ->when($fromDate, function ($query, $fromDate) {
            return $query->whereDate('created', '>=', $fromDate);
        })
        ->when($toDate, function ($query, $toDate) {
            return $query->whereDate('created', '<=', $toDate);
        })
        ->when($username, function ($query, $username) {
            return $query->where('agent_user_name', $username);
        })
        ->when($gamename, function ($query, $gamename) {
            return $query->where('game', $gamename);
        });
    
    $playerhist = $query->get();
    $games = game::all();
    $uname = DB::table('agent_temp_bal')->select('agent_user_name')->distinct()->get();
        return view('playerhistory',compact('games','uname','playerhist'));
    }
    

    public function settings(Request $request)
    {
        return view('settings');
    }

public function generateTicketId(){
    $latestTicket = games_data::orderBy('id', 'desc')->first();

    if ($latestTicket) {
        $lastTicketId = $latestTicket->id + 1;
    } else {
         
        $lastTicketId = 1;
    }

    $prefix = 'TKZTN';
    $zerosCount = max(0, 4 - strlen((string)$lastTicketId));
    $formattedNumber = str_repeat('0', $zerosCount) . $lastTicketId;
    $newTicketId = $prefix . $formattedNumber;

    return $newTicketId;
}

    

    public function betapi(Request $request)
    {
        try {
            $uname = $request->input('agent_user_name');
            $bet_amount = $request->input('bet_amount');
            $bet_number =json_encode( $request->input('bet_number'));
            $start_point = $request->input('start_point');
            $end_point = $request->input('end_point');
           
             
            $games_data = new games_data;
            $games_data->agent_user_name = $uname;
            $games_data->game_id = 1;
            $games_data->bet_amount = $bet_amount;
            $games_data->bet_number = $bet_number;
            $games_data->start_point = $start_point;
            $games_data->end_point = $end_point;
            $games_data->ticket_id = gameController::generateTicketId();
            $games_data->save();
            $ticketId = $games_data->ticket_id;
            return response()->json(['status' => true, 'Ticket_id' => $ticketId, 'type' => $bet_number]);
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   public function mobilesettings(Request $request){
        $game = DB::SELECT("SELECT * FROM game where id ='8' || id = '9' ");
        session()->put('mobile',true);
        return view('mobilesettings',compact('game'));      
    }

    public function zeroto9(Request $request)
    {    
        $agent_win = new agent_temp_bal;
        $datetime = date("Y-m-d H:i:s", time());
        $game_id = 1;
        $triple_chance = DB::select("SELECT * FROM games_data where game_id='$game_id' & active_status = '1' ");
        $isActive = games_data::where('active_status', 1)->exists();

        if (!$isActive) {
            return "no records";
        }
        function findNumberNear($arr, $target, $threshold)
        {
            $closest = null;
            $minDifference = PHP_INT_MAX;

            foreach ($arr as $value) {
                $difference = abs($value - $target);

                if ($difference <= $threshold) {
                    return $value;
                }

                if ($difference < $minDifference) {
                    $minDifference = $difference;
                    $closest = $value;
                }   
            }

            return $closest;
        }
        
        $agent = DB::SELECT("SELECT agent_user_name FROM games_data WHERE agent_user_name IN(SELECT agent_user_name FROM games_data) group by agent_user_name");
        $adminrev = 15;
        $userRev = [];
        $mult9 = [];
        $withbonus = [];
        $win = 0;
        foreach ($agent as $agt) { 
            $agent = agent::where('username', $agt->agent_user_name)->with('stoCkez')->first();
            $agent = json_decode($agent, true);
            $sup = superstockez::where('id', $agent['sto_ckez']['superstockez'])->first();
            $stockez_rev = $agent['sto_ckez']['revenue'];
            $sup_rev = $sup->revenue;
            $agent_rev = $agent['revenue'];
            $totalpercentage = ($stockez_rev + $sup_rev + $agent_rev +  $adminrev);

            foreach ($triple_chance as $trpl) {
                if ($trpl->agent_user_name == $agent['username']) {
                    
                    $betamt = $trpl->bet_amount;
                    $send_stockez = ($betamt * $stockez_rev / 100); 
                    $send_super = ($betamt * $sup_rev / 100); 
                    $send_admin = ($betamt * $adminrev / 100);
                    $send_agent = ($betamt * $agent_rev / 100);
                    
                    $admin_commission = new admin_commission;
                    $super_commission = new super_commission;
                    $stockez_commission = new stockez_commission;
                    $agent_commission = new agent_commission; 

                    $admin_comm = admin::latest()->first();
                    $sStockez = stockez::find($agent['sto_ckez']['id']);
                    $sSup = superstockez::find($sup->id);
                    $Aagent = agent::find($agent['id']);

                    $sStockez->credit += $send_stockez;
                    $sSup->credit += $send_super;
                    $Aagent->credit += $send_agent;
                    $admin_comm->credit += $send_admin;
                    

            $admin_commission->admin_username  = $admin_comm->username;
            $admin_commission->name = $admin_comm->name;
            $admin_commission->agent_username = $agent['username'];
            $admin_commission->bet_amoeunt = $trpl->bet_amount;
            $admin_commission->admin_commission = $send_admin;
            $admin_commission->game_name = 'zero to 9';
            $admin_commission->ticket_id = $trpl->ticket_id;
             
            
            $super_commission->super_username  = $sSup->username;
            $super_commission->name = $sSup->name;
            $super_commission->agent_username = $agent['username'];
            $super_commission->bet_amount = $trpl->bet_amount;
            $super_commission->super_commission = $send_super;
            $super_commission->game_name = 'zero to 9';
            $super_commission->ticket_id = $trpl->ticket_id;

            
            $stockez_commission->stockez_username  = $sStockez->username;
            $stockez_commission->name = $sStockez->name;
            $stockez_commission->agent_username = $agent['username'];
            $stockez_commission->bet_amount = $trpl->bet_amount;
            $stockez_commission->stockez_commission = $send_stockez;
            $stockez_commission->game_name = 'zero to 9';
            $stockez_commission->ticket_id = $trpl->ticket_id;            

            
            $agent_commission->name = $Aagent['name'];
            $agent_commission->agent_username = $Aagent['username'];
            $agent_commission->bet_amount = $trpl->bet_amount;
            $agent_commission->game_name = 'zero to 9';
            $agent_commission->ticket_id = $trpl->ticket_id;            
            $agent_commission->agent_commission = $send_agent;            

                    $sStockez->save();
                    $sSup->save();
                    $Aagent->save();
                    $admin_comm->save();

                    $admin_commission->save();
                    $super_commission->save();
                    $stockez_commission->save();
                    $agent_commission->save();

                        }
            }

            $userRev[$agt->agent_user_name] = $totalpercentage;
        }
    
        $bet_amt_commission = [];

        $totalBetAmount = 0;
 
        foreach ($triple_chance as $triple) {
            $totalBetAmount += $triple->bet_amount;     

            $betnum = json_decode($triple->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $betNumber = array_combine($keys, $betnum);  

            foreach ($betNumber as $key => $value) {
                 

                    if (!isset($totalBetNumber[$key])) {
                        $totalBetNumber[$key] = 0;
                    }
                    $totalBetNumber[$key] += $value;

               
            }
          

            foreach ($userRev as $key => $value) {
                
                if ($key == $triple->agent_user_name) {

                    $bet_amt_commission[] = $triple->bet_amount - ($triple->bet_amount * $value / 100);

                }

            }

        }   
       
 
        $mnggame = game::find(1);

        if ($mnggame->next_bonus != null) {
            $bonus = $mnggame->next_bonus;
        } else {
            $bonus = 1;
        }

        $mnggame->next_bonus = $mnggame->bonus;
        $mnggame->bonus = null;
        $mnggame->save();

        foreach ($totalBetNumber as $key => $value) {
            $mult9[] = $value * 9;
            $withbonus[] = $value * 9 * $bonus;
        }

        $lastRow = remaining::latest()->first();
        if ($lastRow === null) {
            $remaining = 0;
        } else {
            $remaining = $lastRow->remaining_amount;
            
        }
        

        $calculatedPoint = array_sum($bet_amt_commission);
       
        $verylow_calculatedPoint = $calculatedPoint;
        $calculatedPoint += $remaining;

        $filteredMult9 = array_filter($mult9, function ($value) {
            return $value !== 0;
        });
        
 
        $filteredBonus = array_filter($withbonus, function ($value) {
            return $value !== 0;
        });
        

        
         $minMult9 = empty($filteredMult9) ? 0 : min($filteredMult9);
         $maxMult9 = empty($filteredMult9) ? 0 : max($filteredMult9);
        $keysOfMinValue = (array_keys($mult9, $minMult9))[0];
        $keysOfMaxValue = (array_keys($mult9, $maxMult9))[0];

        $minwithbonus = empty($filteredBonus) ? 0 : min($filteredBonus);
        $maxwihtbonus = empty($filteredBonus) ? 0 : max($filteredBonus);
        $keysOfMinwithbonus = (array_keys($withbonus, $minwithbonus))[0];
        $keysOfMaxwithbonus = (array_keys($withbonus, $maxwithbonus))[0];
        
        if ($mnggame->settings == 'Very Low') {

            if ($minMult9 >= $verylow_calculatedPoint) {
                if ($bonus == 1) {

                    $extra = $verylow_calculatedPoint * 150 / 100;
                    $admin = admin::latest()->first();
                    $admin->credit -= $extra;
                    $verylow_calculatedPoint += $extra;

                    $winNumber = $keysOfMinValue;
                    $verylow_calculatedPoint -= $minMult9;
                    $admin->credit += $verylow_calculatedPoint;
                    $admin->save();
                    foreach ($triple_chance as $ngame) {
                        $betnum = json_decode($ngame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $ngame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $ngame->ticket_id; 
                            $agent_win->start_point = $ngame->start_point;
                            $agent_win->end_point = $ngame->end_point;
                            $agent_win->bet_amount = $ngame->bet_amount;

                            
                            $admi = admin_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $ngame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }

                    }
                } else {
                    if ($minwithbonus < $verylow_calculatedPoint) {
                        $winNumber = $keysOfMinwithbonus;
                        $verylow_calculatedPoint -= $minwithbonus;
                        $admin = admin::latest()->first();
                        $admin->credit += $verylow_calculatedPoint;
                        $admin->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;


                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    } else {
                        $extra = $verylow_calculatedPoint * 150 / 100;

                        $admin = admin::latest()->first();
                        $admin->credit -= $extra;

                        $verylow_calculatedPoint += $extra;

                        $winNumber = $keysOfMinValue;
                        $verylow_calculatedPoint -= $minMult9;
                        $admin->credit += $verylow_calculatedPoint;
                        $admin->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            
                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;

                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }

            } else {
                if ($bonus == 1) {

                    $winNumber = $keysOfMinValue;
                    $verylow_calculatedPoint -= $minMult9;
                    $admin = admin::latest()->first();
                    $admin->credit += $verylow_calculatedPoint;
                    $admin->save();

                    foreach ($triple_chance as $newgame) {
                        $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                    }
                } else //if bonus then
                {
                    if ($minwithbonus >= $verylow_calculatedPoint) {
                        $winNumber = $keysOfMinValue;
                        $verylow_calculatedPoint -= $minMult9;
                        $admin = admin::latest()->first();
                        $admin->credit += $verylow_calculatedPoint;
                        $admin->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {

                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    } else {
                        $winNumber = $keysOfMinwithbonus;
                        $verylow_calculatedPoint -= $minwithbonus;
                        $admin = admin::latest()->first();
                        $admin->credit += $verylow_calculatedPoint;
                        $admin->save();
                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }
            }
        } else if ($mnggame->settings == "Not Set") {
            if ($minMult9 >= $calculatedPoint) {
                if ($bonus == 1) {

                    $extra = $calculatedPoint * 150 / 100;
                    $calculatedPoint += $extra;
                    foreach ($mult9 as $key => $value) {
                        if ($value < $calculatedPoint) {
                            $valLessCalc[] = $value;
                        }
                    }

                    $maxval = max($valLessCalc);
                    $keysofmaxval = (array_keys($mult9, $maxval))[0];

                    $winNumber = $keysofmaxval;
                    $calculatedPoint -= $maxval;

                    $remm = new remaining;
                    $remm->game_id = $game_id;
                    $remm->remaining_amount += $calculatedPoint;
                    $remm->save();

                    foreach ($triple_chance as $ngame) {
                        $betnum = json_decode($ngame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $ngame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $ngame->ticket_id; 
                            $agent_win->start_point = $ngame->start_point;
                            $agent_win->end_point = $ngame->end_point;
                            $agent_win->bet_amount = $ngame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $ngame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }

                    }
                } else {
                    if ($minwithbonus < $calculatedPoint) {

                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $maxval = max($valLessCalc);
                        $keysofmaxval = (array_keys($withbonus, $maxval))[0];

                        $winNumber = $keysofmaxval;
                        $calculatedPoint -= $maxval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    } else {
                        $extra = $calculatedPoint * 150 / 100;
                        $calculatedPoint += $extra;

                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $maxval = max($valLessCalc);
                        $keysofmaxval = (array_keys($mult9, $maxval))[0];

                        $winNumber = $keysofmaxval;
                        $calculatedPoint -= $maxval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }

            } else {
                if ($bonus == 1) {

                    foreach ($mult9 as $key => $value) {
                        if ($value < $calculatedPoint) {
                            $valLessCalc[] = $value;
                        }
                    }

                    $maxval = max($valLessCalc);
                    $keysofmaxval = (array_keys($mult9, $maxval))[0];

                    $winNumber = $keysofmaxval;
                    $calculatedPoint -= $maxval;

                    $remm = new remaining;
                    $remm->game_id = $game_id;
                    $remm->remaining_amount += $calculatedPoint;
                    $remm->save();

                    foreach ($triple_chance as $newgame) {
                        $betnum = json_decode($newgame->bet_number,true);
                        $keys = range(0, count($betnum) - 1);
                        $bet_num = array_combine($keys, $betnum);  

                        $agent_win = new agent_temp_bal;
                        $agent_win->agent_user_name = $newgame->agent_user_name;
                        $agent_win->game = $game_id; 
                        $agent_win->ticket_id = $newgame->ticket_id; 
                        $agent_win->start_point = $newgame->start_point;
                        $agent_win->end_point = $newgame->end_point;
                        $agent_win->bet_amount = $newgame->bet_amount;

                        $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                        
                        foreach ($bet_num as $index => $val) {
                            
                                if ($index == $winNumber) {
                                    $agent_get = $val * 9 * $bonus;
                                    $agent_win->winvalue = $index;
                                    $agent_win->agent_win = $agent_get;
                                    $agent_win->save();
                              
                            }
                        }
                    }
                } else //if bonus then
                {
                    if ($minwithbonus >= $calculatedPoint) {
                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $maxval = max($valLessCalc);
                        $keysofmaxval = (array_keys($mult9, $maxval))[0];

                        $winNumber = $keysofmaxval;
                        $calculatedPoint -= $maxval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;
                            
                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;

                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    } else {
                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $maxval = max($valLessCalc);
                        $keysofmaxval = (array_keys($withbonus, $maxval))[0];

                        $winNumber = $keysofmaxval;
                        $calculatedPoint -= $maxval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;
                            
                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;

                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }
            }
        } else if ($mnggame->settings == "Medium") {
            if ($minMult9 >= $calculatedPoint) {
                if ($bonus == 1) {

                    $extra = $calculatedPoint * 150 / 100;
                    $calculatedPoint += $extra;

                    $halfcalc = $calculatedPoint / 2;

                    foreach ($mult9 as $key => $value) {
                        if ($value < $calculatedPoint) {
                            $valLessCalc[] = $value;
                        }
                    }

                    $winval = findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                    $keysofwinval = (array_keys($mult9, $winval))[0];

                    $winNumber = $keysofwinval;
                    $calculatedPoint -= $winval;

                    $remm = new remaining;
                    $remm->game_id = $game_id;
                    $remm->remaining_amount += $calculatedPoint;
                    $remm->save();

                    foreach ($triple_chance as $ngame) {
                        $betnum = json_decode($ngame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;    
                            $agent_win->agent_user_name = $ngame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $ngame->ticket_id; 
                            $agent_win->start_point = $ngame->start_point;
                            $agent_win->end_point = $ngame->end_point;
                            $agent_win->bet_amount = $ngame->bet_amount;
                            
                            $admi = admin_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $ngame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }

                    }
                } else {
                    if ($minwithbonus < $calculatedPoint) {

                        $halfcalc = $calculatedPoint / 2;

                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                        $keysofwinval = (array_keys($withbonus, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    } else {
                        $extra = $calculatedPoint * 150 / 100;
                        $calculatedPoint += $extra;

                        $halfcalc = $calculatedPoint / 2;

                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                        $keysofwinval = (array_keys($mult9, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }

            } else {
                if ($bonus == 1) {

                    $halfcalc = $calculatedPoint / 2;

                    foreach ($mult9 as $key => $value) {
                        if ($value < $calculatedPoint) {
                            $valLessCalc[] = $value;
                        }
                    }

                    $winval = findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                    $keysofwinval = (array_keys($mult9, $winval))[0];

                    $winNumber = $keysofwinval;
                    $calculatedPoint -= $winval;

                    $remm = new remaining;
                    $remm->game_id = $game_id;
                    $remm->remaining_amount += $calculatedPoint;
                    $remm->save();

                    foreach ($triple_chance as $newgame) {
                        $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;
                            
                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;

                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                    }
                } else //if bonus then
                {
                    if ($minwithbonus >= $calculatedPoint) {
                        $halfcalc = $calculatedPoint / 2;

                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                        $keysofwinval = (array_keys($mult9, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;                
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;


                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    } else {
                        $halfcalc = $calculatedPoint / 2;

                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                        $keysofwinval = (array_keys($withbonus, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }
            }
        } else if ($mnggame->settings == "Percentage") {
            $percent = $mnggame->percentage;
            if ($percent < 45) {
                $percent = 45;

            } else if ($percent > 80) {
                $percent = 80;
            } else {
                $percent = $mnggame->percentage;
            }

            if ($minMult9 >= $calculatedPoint) {
                if ($bonus == 1) {

                    $extra = $calculatedPoint * 150 / 100;
                    $calculatedPoint += $extra;

                    $percalc = $calculatedPoint * $percent / 100;

                    foreach ($mult9 as $key => $value) {
                        if ($value < $calculatedPoint) {
                            $valLessCalc[] = $value;
                        }
                    }

                    $winval = findNumberNear($valLessCalc, $percalc, $percalc);
                    $keysofwinval = (array_keys($mult9, $winval))[0];

                    $winNumber = $keysofwinval;
                    $calculatedPoint -= $winval;

                    $remm = new remaining;
                    $remm->game_id = $game_id;
                    $remm->remaining_amount += $calculatedPoint;
                    $remm->save();

                    foreach ($triple_chance as $ngame) {
                        $betnum = json_decode($ngame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $ngame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $ngame->ticket_id; 
                            $agent_win->start_point = $ngame->start_point;
                            $agent_win->end_point = $ngame->end_point;
                            $agent_win->bet_amount = $ngame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $ngame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }

                    }
                } else {
                    if ($minwithbonus < $calculatedPoint) {

                        $percalc = $calculatedPoint * $percent / 100;

                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = findNumberNear($valLessCalc, $percalc, $percalc);
                        $keysofwinval = (array_keys($withbonus, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    } else {
                        $extra = $calculatedPoint * 150 / 100;
                        $calculatedPoint += $extra;

                        $percalc = $calculatedPoint * $percent / 100;

                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = findNumberNear($valLessCalc, $percalc, $percalc);
                        $keysofwinval = (array_keys($mult9, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }

            } else {
                if ($bonus == 1) {

                    $percalc = $calculatedPoint * $percent / 100;

                    foreach ($mult9 as $key => $value) {
                        if ($value < $calculatedPoint) {
                            $valLessCalc[] = $value;
                        }
                    }

                    $winval = findNumberNear($valLessCalc, $percalc, $percalc);
                    $keysofwinval = (array_keys($mult9, $winval))[0];

                    $winNumber = $keysofwinval;
                    $calculatedPoint -= $winval;

                    $remm = new remaining;
                    $remm->game_id = $game_id;
                    $remm->remaining_amount += $calculatedPoint;
                    $remm->save();

                    foreach ($triple_chance as $newgame) {
                        $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                    }
                } else //if bonus then
                {
                    if ($minwithbonus >= $calculatedPoint) {
                        $percalc = $calculatedPoint * $percent / 100;

                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = findNumberNear($valLessCalc, $perfcalc, $perfcalc);
                        $keysofwinval = (array_keys($mult9, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    } else {
                        $percalc = $calculatedPoint * $percent / 100;

                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = findNumberNear($valLessCalc, $percalc, $percalc);
                        $keysofwinval = (array_keys($withbonus, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }
            }
        } else if ($mnggame->settings == "Multiple Agent") {

            if ($minMult9 >= $calculatedPoint) {
                if ($bonus == 1) {

                    $extra = $calculatedPoint * 150 / 100;
                    $calculatedPoint += $extra;
                    foreach ($mult9 as $key => $value) {
                        if ($value < $calculatedPoint) {
                            $valLessCalc[] = $value;
                        }
                    }

                    $maxval = max($valLessCalc);
                    $keysofmaxval = (array_keys($mult9, $maxval))[0];

                    $winNumber = $keysofmaxval;
                    $calculatedPoint -= $maxval;

                    $remm = new remaining;
                    $remm->game_id = $game_id;
                    $remm->remaining_amount += $calculatedPoint;
                    $remm->save();

                    foreach ($triple_chance as $ngame) {
                        $betnum = json_decode($ngame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                       
                            $agent_win->agent_user_name = $ngame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $ngame->ticket_id; 
                            $agent_win->start_point = $ngame->start_point;
                            $agent_win->end_point = $ngame->end_point;
                            $agent_win->bet_amount = $ngame->bet_amount;
                            
                            $admi = admin_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $ngame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;

                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }

                    }
                } else {
                    if ($minwithbonus < $calculatedPoint) {

                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $maxval = max($valLessCalc);
                        $keysofmaxval = (array_keys($withbonus, $maxval))[0];

                        $winNumber = $keysofmaxval;
                        $calculatedPoint -= $maxval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    } else {
                        $extra = $calculatedPoint * 150 / 100;
                        $calculatedPoint += $extra;

                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $maxval = max($valLessCalc);
                        $keysofmaxval = (array_keys($mult9, $maxval))[0];

                        $winNumber = $keysofmaxval;
                        $calculatedPoint -= $maxval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }

            } else {
                if ($bonus == 1) {

                    foreach ($mult9 as $key => $value) {
                        if ($value < $calculatedPoint) {
                            $valLessCalc[] = $value;
                        }
                    }

                    $maxval = max($valLessCalc);
                    $keysofmaxval = (array_keys($mult9, $maxval))[0];

                    $winNumber = $keysofmaxval;
                    $calculatedPoint -= $maxval;

                    $remm = new remaining;
                    $remm->game_id = $game_id;
                    $remm->remaining_amount += $calculatedPoint;
                    $remm->save();

                    foreach ($triple_chance as $newgame) {
                        $betnum = json_decode($newgame->bet_number,true);
                        $keys = range(0, count($betnum) - 1);
                        $bet_num = array_combine($keys, $betnum);  

                        $agent_win = new agent_temp_bal;
                        $agent_win->agent_user_name = $newgame->agent_user_name;
                        $agent_win->game = $game_id; 
                        $agent_win->ticket_id = $newgame->ticket_id; 
                        $agent_win->start_point = $newgame->start_point;
                        $agent_win->end_point = $newgame->end_point;
                        $agent_win->bet_amount = $newgame->bet_amount;

                        $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                        
                        foreach ($bet_num as $index => $val) {
                            
                                if ($index == $winNumber) {
                                    $agent_get = $val * 9 * $bonus;
                                    $agent_win->winvalue = $index;
                                    $agent_win->agent_win = $agent_get;
                                    $agent_win->save();
                              
                            }
                        }
                    }
                } else //if bonus then
                {
                    if ($minwithbonus >= $calculatedPoint) {
                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $maxval = max($valLessCalc);
                        $keysofmaxval = (array_keys($mult9, $maxval))[0];

                        $winNumber = $keysofmaxval;
                        $calculatedPoint -= $maxval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  
                            

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        
                        }
                    } else {
                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $maxval = max($valLessCalc);
                        $keysofmaxval = (array_keys($withbonus, $maxval))[0];

                        $winNumber = $keysofmaxval;
                        $calculatedPoint -= $maxval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                         $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }
            }
        } else {    //"setting = "agent" 
              
            $id = $mnggame->agent;
            $agent = agent::find($id);
            $ag_uname = $agent->username;
            $ag_filter = games_data::where('agent_user_name', $ag_uname)->get();
            if ($ag_filter->isEmpty()) {
                $ran = games_data::inRandomOrder()->first();
                $ran = $ran->agent_user_name; 
                $ag_filter = games_data::where('agent_user_name', $ran)->get();
            }
            
            $totalagentbet = [];

            foreach ($ag_filter as $agf) {
               
                $betnum = json_decode($agf->bet_number,true); 
                            $keys = range(0, count($betnum) - 1);
                            $betx = array_combine($keys, $betnum);  

                foreach ($betx as $key => $value) {
                    
                        if (!isset($totalagentbet[$key])) {
                            $totalagentbet[$key] = 0;
                        }

                        $totalagentbet[$key] += $value;
                    }
               
            }
            
             
            

            if ($minMult9 >= $calculatedPoint) {
                if ($bonus == 1) {

                    $extra = $calculatedPoint * 150 / 100;
                    $calculatedPoint += $extra;
                    
                    $nmult9 = $mult9; 
                    foreach ($nmult9 as $key => $value) {
                        if ($value > $calculatedPoint) {
                            unset($nmult9[$key]);
                        }
                    } 
                    $common_key = array_intersect_key($nmult9, $totalagentbet);
             
                    if ($mnggame->agent_setting == "Win") {
                        if (empty($common_key)) {
                            $winval = array_rand($nmult9);
                            $keysofwinval = (array_keys($mult9, $winval))[0];
                        } else {
                            $maxcommon = max($common_key);
                            $keysofwinval = (array_keys($common_key, $maxcommon))[0];
                            $winval = $mult9[$keysofwinval];
                            
                        }

                    } else {
                        if (empty($common_key)) {
                            $winval = array_rand($nmult9);
                            $keysofwinval = (array_keys($mult9, $winval))[0];
                        } else {
                            $mincommon = min($common_key);
                            $keysofwinval = (array_keys($common_key, $mincommon))[0];
                            $winval = $mult9[$keysofwinval];
                        }
                    }

                    $winNumber = $keysofwinval;
                    $calculatedPoint -= $winval;

                    $remm = new remaining;
                    $remm->game_id = $game_id;
                    $remm->remaining_amount += $calculatedPoint;
                    $remm->save();

                    foreach ($triple_chance as $ngame) {
                        $betnum = json_decode($ngame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $ngame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $ngame->ticket_id; 
                            $agent_win->start_point = $ngame->start_point;
                            $agent_win->end_point = $ngame->end_point;
                            $agent_win->bet_amount = $ngame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $ngame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $ngame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }

                    }
                } else {
                    if ($minwithbonus < $calculatedPoint) {

                        $wbons = $withbonus;
                        foreach ($wbons as $key => $value) {

                            if ($value > $calculatedPoint) {
                                    unset($wbons[$key]);
                            }
                        }
                        $common_key = array_intersect_key($totalagentbet, $wbons);

                        if ($mnggame->agent_setting == "Win") {
                            if (empty($common_key)) {
                                $winval = array_rand($wbons);
                                $keysofwinval = (array_keys($withbonus, $winval))[0];
                            } else {
                                $maxcommon = max($common_key);
                                $keysofwinval = (array_keys($common_key, $maxcommon))[0];
                                $winval = $withbonus[$keysofwinval];

                            }

                        } else {
                            if (empty($common_key)) {
                                $winval = array_rand($wbons);
                                $keysofwinval = (array_keys($withbonus, $winval))[0];
                            } else {
                                $mincommon = min($common_key);
                                $keysofwinval = (array_keys($common_key, $mincommon))[0];
                                $winval = $withbonus[$keysofwinval];
                            }
                        }

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    } else {
                        $extra = $calculatedPoint * 150 / 100;
                        $calculatedPoint += $extra;
                  
                        $nmult9 = $mult9; 
                        foreach ($nmult9 as $key => $value) {
                            if ($value > $calculatedPoint) {
                                unset($nmult9[$key]);
                            }
                        } 
                        $common_key = array_intersect_key($nmult9, $totalagentbet);
                 
                        if ($mnggame->agent_setting == "Win") {
                            if (empty($common_key)) {
                                $winval = array_rand($nmult9);
                                $keysofwinval = (array_keys($mult9, $winval))[0];
                            } else {
                                $maxcommon = max($common_key);
                                $keysofwinval = (array_keys($common_key, $maxcommon))[0];
                                $winval = $mult9[$keysofwinval];
                                
                            }
    
                        } else {
                            if (empty($common_key)) {
                                $winval = array_rand($nmult9);
                                $keysofwinval = (array_keys($mult9, $winval))[0];
                            } else {
                                $mincommon = min($common_key);
                                $keysofwinval = (array_keys($common_key, $mincommon))[0];
                                $winval = $mult9[$keysofwinval];
                            }
                        }
    
                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;
    
                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index; 
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }

            } else {
                if ($bonus == 1) {
                    $nmult9 = $mult9; 
                    foreach ($nmult9 as $key => $value) {
                        if ($value > $calculatedPoint) {
                            unset($nmult9[$key]);
                        }
                    } 
                    $common_key = array_intersect_key($nmult9, $totalagentbet);
                    if ($mnggame->agent_setting == "Win") {
                        if (empty($common_key)) {
                            $winval = array_rand($nmult9);
                            $keysofwinval = (array_keys($mult9, $winval))[0];
                        } else {
                            $maxcommon = max($common_key);
                            
                            $keysofwinval = (array_keys($common_key, $maxcommon))[0];
                            $winval = $mult9[$keysofwinval];
                            
                        }

                    } else {
                        if (empty($common_key)) {
                            $winval = array_rand($nmult9);
                            $keysofwinval = (array_keys($mult9, $winval))[0];
                        } else {
                            $mincommon = min($common_key);
                            $keysofwinval = (array_keys($common_key, $mincommon))[0];
                            $winval = $mult9[$keysofwinval];
                        }
                    }

                    $winNumber = $keysofwinval;
                    $calculatedPoint -= $winval;

                    $remm = new remaining;
                    $remm->game_id = $game_id;
                    $remm->remaining_amount += $calculatedPoint;
                    $remm->save();

                    foreach ($triple_chance as $newgame) {
                        $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                    }
                } else //if bonus then
                {
                    if ($minwithbonus >= $calculatedPoint) {
                        $nmult9 = $mult9; 
                        foreach ($nmult9 as $key => $value) {
                            if ($value > $calculatedPoint) {
                                unset($nmult9[$key]);
                            }
                        } 
                        $common_key = array_intersect_key($nmult9, $totalagentbet);
                 
                        if ($mnggame->agent_setting == "Win") {
                            if (empty($common_key)) {
                                $winval = array_rand($nmult9);
                                $keysofwinval = (array_keys($mult9, $winval))[0];
                            } else {
                                $maxcommon = max($common_key);
                                $keysofwinval = (array_keys($common_key, $maxcommon))[0];
                                $winval = $mult9[$keysofwinval];
                                
                            }
    
                        } else {
                            if (empty($common_key)) {
                                $winval = array_rand($nmult9);
                                $keysofwinval = (array_keys($mult9, $winval))[0];
                            } else {
                                $mincommon = min($common_key);
                                $keysofwinval = (array_keys($connon_key, $mincommon))[0];
                                $winval = $mult9[$keysofwinval];
                            }
                        }
    
                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;
    
                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  

                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    } else {

                        $wbons = $withbonus;
                        foreach ($wbons as $key => $value) {

                            if ($value > $calculatedPoint) {
                                    unset($wbons[$key]);
                            }
                        }
                        $common_key = array_intersect_key($totalagentbet, $wbons);

                        if ($mnggame->agent_setting == "Win") {
                            if (empty($common_key)) {
                                $winval = array_rand($wbons);
                                $keysofwinval = (array_keys($withbonus, $winval))[0];
                            } else {
                                $maxcommon = max($common_key);
                                $keysofwinval = (array_keys($common_key, $maxcommon))[0];
                                $winval = $withbonus[$keysofwinval];

                            }

                        } else {
                            if (empty($common_key)) {
                                $winval = array_rand($wbons);
                                $keysofwinval = (array_keys($withbonus, $winval))[0];
                            } else {
                                $mincommon = min($common_key);
                                $keysofwinval = (array_keys($common_key, $mincommon))[0];
                                $winval = $withbonus[$keysofwinval];
                            }
                        }

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        $remm = new remaining;
                        $remm->game_id = $game_id;
                        $remm->remaining_amount += $calculatedPoint;
                        $remm->save();

                        foreach ($triple_chance as $newgame) {
                            $betnum = json_decode($newgame->bet_number,true);
                            $keys = range(0, count($betnum) - 1);
                            $bet_num = array_combine($keys, $betnum);  
                            
                            
                            $agent_win = new agent_temp_bal;
                            $agent_win->agent_user_name = $newgame->agent_user_name;
                            $agent_win->game = $game_id; 
                            $agent_win->ticket_id = $newgame->ticket_id; 
                            $agent_win->start_point = $newgame->start_point;
                            $agent_win->end_point = $newgame->end_point;
                            $agent_win->bet_amount = $newgame->bet_amount;

                            $admi = admin_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $supa = super_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $stke = stockez_commission::where('ticket_id', $newgame->ticket_id)->first();
                            $agte = agent_commission::where('ticket_id', $newgame->ticket_id)->first();
                            
                            $agent_win->agent_commission = $agte->agent_commission;
                            $agent_win->admin_commission = $admi->admin_commission;
                            $agent_win->super_commission = $supa->super_commission;
                            $agent_win->stockez_commission = $stke->stockez_commission;
                            $agent_win->super_username = $supa->super_username;
                            $agent_win->stockez_username = $stke->stockez_username;
                            
                            foreach ($bet_num as $index => $val) {
                                
                                    if ($index == $winNumber) {
                                        $agent_get = $val * 9 * $bonus;
                                        $agent_win->winvalue = $index;
                                        $agent_win->agent_win = $agent_get;
                                        $agent_win->save();
                                  
                                }
                            }
                        }
                    }
                }
            }

        }
       
        
        games_data::where('active_status', 1)
        ->where('create_at', '>', $datetime)
        ->update(['active_status' => 0]);
        
        return response()->json(['status' => true, 'WinNumber' => $winNumber]);
    }

}
