<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\admin;
use App\Models\agent;
use App\Models\commission;
use App\Models\agent_temp_bal as agtmpbl;
use App\Models\double_chance as dbl;
use App\Models\double_chance;
use App\Models\game;
use App\Models\remaining_double_chance as rmdbl;
use App\Models\stockez;
use App\Models\superstockez;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\admin_commission;
use App\Models\super_commission;
use App\Models\stockez_commission;
use App\Models\agent_commission;
use App\Models\remaining_double_chance;
class doublechanceController extends Controller
{

    public function findNumberNear($arr, $target, $threshold)
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

    private function generateTicketId()
    {
        $latestTicket = double_chance::orderBy('id', 'desc')->first();
    
        if ($latestTicket) {
            $lastTicketId = $latestTicket->ticket_id;
            $prefix = substr($lastTicketId, 0, 5);   
            $number = intval(substr($lastTicketId, 5));
    
            if ($prefix === 'TKDBL' && $number < 99999) {
                $number++;
            } else {
                $prefix = 'TKDBL';
                $number = 100000;
            }
        } else {
            $prefix = 'TKDBL';
            $number = 1;
        }
    
        $formattedNumber = str_pad($number, 5, '0', STR_PAD_LEFT);
        $newTicketId = $prefix . $formattedNumber;
        return $newTicketId;
    }
    

    public function doublebet(Request $request){
        try {
            
            $uname = $request->input('agent_user_name');
            $andar_bet_number = json_encode($request->input('andar_bet_number'));
            $andar_bet_amount = $request->input('andar_bet_amount');
            $bahar_bet_number = json_encode($request->input('bahar_bet_number'));
            $bahar_bet_amount = $request->input('bahar_bet_amount');
            $double_bet_number = json_encode($request->input('double_bet_number'));
            $double_bet_amount = $request->input('double_bet_amount');
            $start_point = $request->input('start_point');
            $end_point = $request->input('end_point');
            $ticketId = doublechanceController::generateTicketId();
            
             
            $newdouble = new double_chance;
            $newdouble->game_id = 2;
            $newdouble->agent_user_name = $uname;
            $newdouble->andar_bet_number = $andar_bet_number;
            $newdouble->andar_bet_amount = $andar_bet_amount;
            $newdouble->bahar_bet_number = $bahar_bet_number;
            $newdouble->bahar_bet_amount = $bahar_bet_amount;
            $newdouble->double_bet_number = $double_bet_number;
            $newdouble->double_bet_amount = $double_bet_amount;
            $newdouble->start_point = $start_point; 
            $newdouble->end_point = $end_point; 
            $newdouble->ticket_id = $ticketId; 
            
            $newdouble->save();
            return response()->json(['status' => true, 'Ticket_id' => $ticketId]);
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }   
    
    private function calculateBet($type,$Bets){
        $bettypeAmt = $type.'_bet_amount';
        $bettypeNum = $type.'_bet_number';
        $totalBetNum = [];
        $totalBetAmt = 0;
        foreach($Bets as $bet){
            $b_conv = json_decode($bet->$bettypeNum,true);
            $keys = range(0, count($b_conv) - 1);
            $betNum = array_combine($keys, $b_conv);  
            foreach($betNum as $key => $value){
                if(!isset($totalBetNum[$key])){
                    $totalBetNum[$key] = 0;
                }
                $totalBetNum[$key] += $value;
            }
            $totalBetAmt += $bet->$bettypeAmt;
        }   
            $newarray = (object)['totalBetNum' =>$totalBetNum, 'totalBetAmt' => $totalBetAmt];
            return $newarray;
    }

    
    private function sendcommission($type, $Bets){
            function user_comm($type, $user, $betAmount, $agent_uname, $userrev, $unames){
                    $send = $betAmount * $userrev / 100;
                    // $user_comm = DB::table($user)
                    // ->where('username', $unames)
                    // ->update(['credit' => DB::raw('credit + ' . $send)]);
                    
                     
                    $value = (object)['uname' =>$unames, 'send' => $send];
                    return $value;

            }

            function bettype_comm($type,$btamt, $uname, $allrev, $alluname){
               $super_send = user_comm($type, 'superstockez', $btamt, $uname, $allrev->sup_rev, $alluname->super);
                $admin_send = user_comm($type, 'admin', $btamt, $uname, $allrev->admin_rev, $alluname->admin);
                $agent_send = user_comm($type, 'agent', $btamt, $uname, $allrev->agent_rev, $alluname->agent);
                $stockez_send = user_comm($type, 'stockez', $btamt, $uname, $allrev->stockez_rev, $alluname->stockez);
                
                $send = [$super_send->uname => $super_send->send, $admin_send->uname => $admin_send->send, $agent_send->uname => $agent_send->send, $stockez_send->uname => $stockez_send->send];
                 return $send;
            }

            function SaveUserCommissions($totalUserCom, $bet_amount, $ticket_id, $agent_uname){
                 
                foreach($totalUserCom as $uname => $value){
                         
                        if(substr($uname, 0, 3) == 'STK'){
                                $com = new stockez_commission;
                                $name = stockez::where('username', $uname)->first()->name;
                                $com->name = $name; 
                                $com->stockez_username = $uname;
                                $com->agent_username = $agent_uname;
                                $com->bet_amount = $bet_amount;
                                $com->stockez_commission = $value;
                                $com->game_name = 'double chance';
                                $com->ticket_id  = $ticket_id; 

                                // $com->save();   

                        }
                        else if(substr($uname, 0, 3) == 'SUP'){
                             $com = new super_commission;
                             $com->super_username = $uname;
                             $name = superstockez::where('username', $uname)->first()->name;
                             $com->name = $name; 
                             $com->agent_username = $agent_uname;
                             $com->bet_amount = $bet_amount;
                             $com->super_commission = $value;
                             $com->game_name = 'double chance';
                             $com->ticket_id  = $ticket_id; 
                             
                            //  $com->save();
                        }
                        else if(substr($uname, 0, 3) == 'AGT'){
                            $com = new agent_commission;
                            $com->agent_username = $uname;
                            $name = agent::where('username', $uname)->first()->name;
                             $com->name = $name; 
                            $com->agent_commission = $value;
                            $com->game_name = 'double chance';
                            $com->ticket_id  = $ticket_id; 
                            $com->bet_amount = $bet_amount; 
                            
                            // $com->save();
                        }
                        else{
                            $com = new admin_commission;
                            $com->admin_username = $uname;
                            $name = admin::where('username', $uname)->first()->name;
                             $com->name = $name; 
                            $com->agent_username = $agent_uname;
                            $com->bet_amount = $bet_amount;
                            $com->admin_commission = $value;
                            $com->game_name = 'double chance';
                            $com->ticket_id  = $ticket_id; 
                            
                            // $com->save();
                        }
                }
            }

            function TotalUserComm($andar_commission, $bahar_commission, $double_commission, $bet_amount, $ticket_id, $agent_uname){
                    $commissionArrays = [$andar_commission, $bahar_commission, $double_commission];
                    $totalUserCom = [];
                    foreach($commissionArrays as $commissionArray ){
                            foreach($commissionArray as $user => $sendvalue){
                                if(!isset($totalUserCom[$user])){
                                        $totalUserCom[$user] = 0;
                                }
                                $totalUserCom[$user] += $sendvalue;
                            }
                        }
                       
                        
                         SaveUserCommissions($totalUserCom, $bet_amount, $ticket_id, $agent_uname);
                }

            function sendusercommission($uname, $Bets, $allrev, $alluname){
                $totalCommission = [];
                foreach($Bets as $bet){
                    if($bet->agent_user_name == $uname){
                            $andar_commission = bettype_comm('andar', $bet->andar_bet_amount, $uname,$allrev, $alluname);
                            $bahar_commission = bettype_comm('bahar', $bet->bahar_bet_amount, $uname,$allrev, $alluname);
                            $double_commission = bettype_comm('double', $bet->double_bet_amount, $uname,$allrev, $alluname);

                             $bet_amount = $bet->andar_bet_amount + $bet->bahar_bet_amount + $bet->double_bet_amount; 
                             $ticket_id = $bet->ticket_id;
                             $agent_uname = $bet->agent_user_name; 

                            TotalUserComm( $andar_commission, $bahar_commission, $double_commission, $bet_amount, $ticket_id, $agent_uname);
                    }
                     
                }

               
        }

        $agent = double_chance::select('agent_user_name')
        ->whereIn('agent_user_name', function($query){
            $query->select('agent_user_name')
            ->from('double_chance');
        })
        ->groupBy('agent_user_name')
        ->get();
            $userRev = [];
            foreach($agent as $ag){
                $agent = agent::where('username', $ag->agent_user_name)->with('stoCkez')->first();
                $agent = json_decode($agent,true);
                $sup = superstockez::find($agent['sto_ckez']['superstockez']);  
                $admin_uname = admin::latest()->first();
                $admin_uname = $admin_uname->username;

                $agent_rev = $agent['revenue'];
                $stockez_rev = $agent['sto_ckez']['revenue'];
                $sup_rev = $sup->revenue;
                $admin_rev = 15;
                $totalPercentage = ($agent_rev + $stockez_rev + $sup_rev);
                
                $userRev[$ag->agent_user_name]  = $totalPercentage;
                $allrev = (object)['agent_rev' => $agent_rev, 'stockez_rev' => $stockez_rev, 'sup_rev' => $sup_rev, 'admin_rev' => $admin_rev];
                $alluname = (object)['agent' => $agent['username'], 'stockez' => $agent['sto_ckez']['username'], 'admin' => $admin_uname, 'super' => $sup->username];
                sendusercommission($ag['agent_user_name'], $Bets, $allrev, $alluname);
            }
            return $userRev; 
     
}

    private function commissionSub($type, $Bets, $userRev){
         
        $type = $type.'_bet_amount';
        foreach($Bets as $bet){
            foreach($userRev as $uname => $revenue){
                if($uname == $bet->agent_user_name){

                        $bet_sub_comm[]  = $bet->$type - ($bet->$type * $revenue / 100);
                }
            }
        }
        $totalComm = array_sum($bet_sub_comm);
        return $totalComm; 
    }

   private function gameReqMntCalc($all, $type, $totalBetNum, $multiple){
            $mnggame = game::find(2);
            $calculatedPoint = $all;
              
                if($mnggame->next_bonus != null){
                    $bonus = $mnggame->next_bonus;
                }
                else{
                    $bonus = 1; 
                }
                if($type == 'double'){
                    $mnggame->next_bonus = $mnggame->bonus;
                    $mnggame->bonus = null;
                    $mnggame->save();
                }
            
            $lastRow = remaining_double_chance::latest()->first();
            if($lastRow != null){
                $remaining = $lastRow->$type.'_remaining';
            }
            else{
                $remaining = 0;
            }
        
            $verylow_calculatedPoint = $calculatedPoint;
            $calculatedPoint += $remaining;

            foreach($totalBetNum as $key => $value){
                $mult[] = $value * $multiple;
                $withBonus[] =$value * $multiple * $bonus; 
            }

            $filteredMult = array_filter($mult, function($value){
                    return $value !== 0;
            });

            $filteredbonus = array_filter($withBonus, function($value){
                return $value !== 0;
        });

            $minMult = empty($filteredMult) ? 0 : min($filteredMult);
            $maxMult = empty($filteredMult) ? 0 : max($filteredMult);
            $keysofMin = (array_keys($mult, $minMult))[0];
            $keysofMax = (array_keys($mult, $maxMult))[0];
            
            $minBonus = empty($filteredbonus) ? 0 : min($filteredbonus);
            $maxBonus = empty($filteredbonus) ? 0 : max($filteredbonus);

            $keysofminBonus = (array_keys($withBonus, $minBonus))[0];
            $keysofmaxBonus = (array_keys($withBonus, $maxBonus))[0];

            $newarr = (object)['calculatedPoint' => $calculatedPoint, 'verylow_calcpoint' => $verylow_calculatedPoint, 'bonus' => $bonus, 'mult' => $mult, 'mult_withbonus' => $withBonus, 'minMult' => $minMult, 'maxMult' => $maxMult, 'keysofMin' => $keysofMin, 'keysofMax' => $keysofMax, 'minBonus' => $minBonus, 'maxbonus' => $maxBonus, 'keysofminBonus' => $keysofminBonus, 'keysofmaxbonus' => $keysofmaxBonus, 'filteredMult' => $filteredMult, 'filteredBonus' => $filteredbonus]; 
            return $newarr; 
    }       
     public function game(){
        $game_id = 2;
        $Bets = double_chance::where('game_id', $game_id)->where('active_status', 1)->get();
         
            $bahar_total = doublechanceController::calculateBet('bahar',$Bets);
            $andar_total = doublechanceController::calculateBet('andar',$Bets);
            $double_total = doublechanceController::calculateBet('double',$Bets);
           $userRev =  doublechanceController::sendcommission('new', $Bets);
                        
        
        $andarCalc = doublechanceController::commissionSub('andar', $Bets, $userRev);
        $baharCalc = doublechanceController::commissionSub('bahar', $Bets, $userRev);
        $doubleCalc = doublechanceController::commissionSub('double', $Bets, $userRev);

        
        $andarCp = doublechanceController::gameReqMntCalc($andarCalc, 'andar', $andar_total->totalBetNum, 9);
        $baharCp = doublechanceController::gameReqMntCalc($baharCalc, 'bahar', $bahar_total->totalBetNum, 9);
        $doubleCp = doublechanceController::gameReqMntCalc($doubleCalc, 'double', $double_total->totalBetNum, 99);
         
         echo  "$doubleCp->minMult : calculated_point = $doubleCp->calculatedPoint"; //whoareyou    
        $mnggame = game::find(2);
        
        
        
    }


    public function doublechance(Request $request){
            $if = 3;
    }

}

