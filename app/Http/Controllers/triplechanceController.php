<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\admin;
use App\Models\agent;
use App\Models\commission;
use App\Models\agent_temp_bal as agtmpbl;
use App\Models\game;
use App\Models\remaining_triple_chance as remtriple;
use App\Models\stockez;
use App\Models\triple_chance;
use App\Models\superstockez;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class triplechanceController extends Controller
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
    public function game($bet_type, $gameTab, $bt_amt, $bt_nm, $multiple)
    {
        $game_id = 3;
        $triple_chance = DB::select("SELECT * FROM $gameTab where game_id='$game_id' AND active_status = '1' ");

        $agent = DB::SELECT("SELECT agent_user_name FROM $gameTab WHERE agent_user_name IN(SELECT agent_user_name FROM $gameTab) group by agent_user_name");

        $adminrev = 15;
        $userRev = [];
        $mult9 = [];
        $withbonus = [];
        $win = 0;
        static $comm = 3;
        $comm += 1;
        foreach ($agent as $agt) {
            $agent = agent::where('username', $agt->agent_user_name)->with('stoCkez')->first();
            $agent = json_decode($agent, true);
            $sup = superstockez::where('id', $agent['sto_ckez']['superstockez'])->first();
            $stockez_rev = $agent['sto_ckez']['revenue'];
            $sup_rev = $sup->revenue;
            $totalpercentage = ($stockez_rev + $sup_rev + $adminrev);

            foreach ($triple_chance as $trpl) {
 
                if ($comm == 4) {
                    if ($trpl->agent_user_name == $agent['username']) {

                        $single_betamt = $trpl->single_bet_amount;
                        $double_betamt = $trpl->double_bet_amount;
                        $triple_betamt = $trpl->triple_bet_amount;

                        
                        $betamt = $single_betamt + $double_betamt + $triple_betamt;
                        
                        $send_stockez = ($betamt * $stockez_rev / 100);
                        $send_super = ($betamt * $sup_rev / 100);
                        $send_admin = ($betamt * $adminrev / 100);

                        $admin_comm = admin::latest()->first();
                        $sStockez = stockez::find($agent['sto_ckez']['id']);
                        $sSup = superstockez::find($sup->id);

                        $sStockez->credit += $send_stockez;
                        $sSup->credit += $send_super;
                        $admin_comm->credit += $send_admin;
                        $sStockez->save();
                        $sSup->save();
                        $admin_comm->save();

                        $commission = new commission;

                        $commission->agent_username = $agent['username'];
                        $commission->super_username = $sup->username;
                        $commission->stockez_username = $agent['sto_ckez']['username'];
                        $commission->bet_amount = $betamt;
                        $commission->super_commission = $send_super;
                        $commission->stockez_commission = $send_stockez;
                        $commission->admin_commission = $send_admin;
                        $commission->game_name = 'triple chance';
                        $commission->ticket_id = $trpl->ticket_id;
                        $commission->save();

                    }
                }
            }
            $userRev[$agt->agent_user_name] = $totalpercentage;
        }

        $bet_amt_commission = [];

        $totalBetAmount = 0;

        foreach ($triple_chance as $triple) {
            $totalBetAmount += $triple->$bt_amt;

            $betnum = json_decode($triple->$bt_nm, true);
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

                    $bet_amt_commission[] = $triple->$bt_amt - ($triple->$bt_amt * $value / 100);

                }

            }

        }

        $mnggame = game::find(3);

        if ($mnggame->next_bonus != null) {
            $bonus = $mnggame->next_bonus;
        } else {
            $bonus = 1;
        }

        if ($comm == 6) {
            $mnggame->next_bonus = $mnggame->bonus;
            $mnggame->bonus = null;
            $mnggame->save();
        }

        foreach ($totalBetNumber as $key => $value) {
            $mult9[] = $value * $multiple;
            $withbonus[] = $value * $multiple * $bonus;
        }
//₹
        if ($bet_type == "single") {
            $lastRow = remtriple::latest()->first();
            
            if ($lastRow == null) {
                $remaining = 0;
            } else {
                $col_type = $bet_type . "_remaining";
              
                $remaining = $lastRow->$col_type;

            }
        } else if ($bet_type == "double") {
            $lastRow = remtriple::latest()->first();
            $col_type = $bet_type . "_remaining";
            $remaining = $lastRow->$col_type;
        } else {
            $lastRow = remtriple::latest()->first();
            $col_type = $bet_type . "_remaining";
          
            $remaining = $lastRow->$col_type;
        } 

        $calculatedPoint = array_sum($bet_amt_commission);

        $verylow_calculatedPoint = $calculatedPoint;
        $calculatedPoint += $remaining;

        $minMult9 = min($mult9);
        $maxMult9 = max($mult9);
        $keysOfMinValue = (array_keys($mult9, $minMult9))[0];
        $keysOfMaxValue = (array_keys($mult9, $maxMult9))[0];

        $minwithbonus = min($withbonus);
        $maxwithbonus = max($withbonus);
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

                    return $winNumber;
                } else {
                    if ($minwithbonus < $verylow_calculatedPoint) {
                        $winNumber = $keysOfMinwithbonus;
                        $verylow_calculatedPoint -= $minwithbonus;
                        $admin = admin::latest()->first();
                        $admin->credit += $verylow_calculatedPoint;
                        $admin->save();

                        return $winNumber;

                    } else {
                        $extra = $verylow_calculatedPoint * 150 / 100;

                        $admin = admin::latest()->first();
                        $admin->credit -= $extra;

                        $verylow_calculatedPoint += $extra;

                        $winNumber = $keysOfMinValue;
                        $verylow_calculatedPoint -= $minMult9;
                        $admin->credit += $verylow_calculatedPoint;
                        $admin->save();

                        return $winNumber;
                    }
                }

            } else {
                if ($bonus == 1) {

                    $winNumber = $keysOfMinValue;
                    $verylow_calculatedPoint -= $minMult9;
                    $admin = admin::latest()->first();
                    $admin->credit += $verylow_calculatedPoint;
                    $admin->save();

                    return $winNumber;
                } else //if bonus then
                {
                    if ($minwithbonus >= $verylow_calculatedPoint) {
                        $winNumber = $keysOfMinValue;
                        $verylow_calculatedPoint -= $minMult9;
                        $admin = admin::latest()->first();
                        $admin->credit += $verylow_calculatedPoint;
                        $admin->save();

                        return $winNumber;
                    } else {
                        $winNumber = $keysOfMinwithbonus;
                        $verylow_calculatedPoint -= $minwithbonus;
                        $admin = admin::latest()->first();
                        $admin->credit += $verylow_calculatedPoint;
                        $admin->save();

                        return $winNumber;
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

                    if ($comm == 4) {
                        $remm = new remtriple;
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    } else {
                        $remm = remtriple::latest()->first();
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    }

                    return $winNumber;
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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
                    }
                }

            } else {
                if ($bonus == 1) {
//whoareyou
                    foreach ($mult9 as $key => $value) {
                        if ($value < $calculatedPoint) {
                            $valLessCalc[] = $value;
                        }
                    }
                    $maxval = max($valLessCalc);
                    echo($maxval);echo "<br>";
                    $keysofmaxval = (array_keys($mult9, $maxval))[0];

                    $winNumber = $keysofmaxval;
                    $calculatedPoint -= $maxval;

                    if ($comm == 4) {

                        $remm = new remtriple;
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    } else {

                        $remm = remtriple::latest()->first();
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    }
                    return $winNumber;

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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                    $winval = doublechance::findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                    $keysofwinval = (array_keys($mult9, $winval))[0];

                    $winNumber = $keysofwinval;
                    $calculatedPoint -= $winval;

                    if ($comm == 4) {
                        $remm = new remtriple;
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    } else {
                        $remm = remtriple::latest()->first();
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    }

                    return $winNumber;
                } else {
                    if ($minwithbonus < $calculatedPoint) {

                        $halfcalc = $calculatedPoint / 2;

                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = doublechance::findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                        $keysofwinval = (array_keys($withbonus, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
                    } else {
                        $extra = $calculatedPoint * 150 / 100;
                        $calculatedPoint += $extra;

                        $halfcalc = $calculatedPoint / 2;

                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = doublechance::findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                        $keysofwinval = (array_keys($mult9, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                    $winval = doublechance::findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                    $keysofwinval = (array_keys($mult9, $winval))[0];

                    $winNumber = $keysofwinval;
                    $calculatedPoint -= $winval;

                    if ($comm == 4) {
                        $remm = new remtriple;
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    } else {
                        $remm = remtriple::latest()->first();
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    }

                    return $winNumber;
                } else //if bonus then
                {
                    if ($minwithbonus >= $calculatedPoint) {
                        $halfcalc = $calculatedPoint / 2;

                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = doublechance::findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                        $keysofwinval = (array_keys($mult9, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
                    } else {
                        $halfcalc = $calculatedPoint / 2;

                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = doublechance::findNumberNear($valLessCalc, $halfcalc, $halfcalc);
                        $keysofwinval = (array_keys($withbonus, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                    $winval = doublechance::findNumberNear($valLessCalc, $percalc, $percalc);
                    $keysofwinval = (array_keys($mult9, $winval))[0];

                    $winNumber = $keysofwinval;
                    $calculatedPoint -= $winval;

                    if ($comm == 4) {
                        $remm = new remtriple;
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    } else {
                        $remm = remtriple::latest()->first();
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    }

                    return $winNumber;
                } else {
                    if ($minwithbonus < $calculatedPoint) {

                        $percalc = $calculatedPoint * $percent / 100;

                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = doublechance::findNumberNear($valLessCalc, $percalc, $percalc);
                        $keysofwinval = (array_keys($withbonus, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;

                    } else {
                        $extra = $calculatedPoint * 150 / 100;
                        $calculatedPoint += $extra;

                        $percalc = $calculatedPoint * $percent / 100;

                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = doublechance::findNumberNear($valLessCalc, $percalc, $percalc);
                        $keysofwinval = (array_keys($mult9, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                    $winval = doublechance::findNumberNear($valLessCalc, $percalc, $percalc);
                    $keysofwinval = (array_keys($mult9, $winval))[0];

                    $winNumber = $keysofwinval;
                    $calculatedPoint -= $winval;

                    if ($comm == 4) {
                        $remm = new remtriple;
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    } else {
                        $remm = remtriple::latest()->first();
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    }

                    return $winNumber;

                } else //if bonus then
                {
                    if ($minwithbonus >= $calculatedPoint) {
                        $percalc = $calculatedPoint * $percent / 100;

                        foreach ($mult9 as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = doublechance::findNumberNear($valLessCalc, $perfcalc, $perfcalc);
                        $keysofwinval = (array_keys($mult9, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
                    } else {
                        $percalc = $calculatedPoint * $percent / 100;

                        foreach ($withbonus as $key => $value) {
                            if ($value < $calculatedPoint) {
                                $valLessCalc[] = $value;
                            }
                        }

                        $winval = doublechance::findNumberNear($valLessCalc, $percalc, $percalc);
                        $keysofwinval = (array_keys($withbonus, $winval))[0];

                        $winNumber = $keysofwinval;
                        $calculatedPoint -= $winval;

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                    if ($comm == 4) {
                        $remm = new remtriple;
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    } else {
                        $remm = remtriple::latest()->first();
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    }

                    return $winNumber;
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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                    if ($comm == 4) {
                        $remm = new remtriple;
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    } else {
                        $remm = remtriple::latest()->first();
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    }

                    return $winNumber;
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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;

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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
                    }
                }
            }
        } else { //"setting = "agent"

            $id = $mnggame->agent;
            $agent = agent::find($id);
            $ag_uname = $agent->username;
            $ag_filter = dbl::where('agent_user_name', $ag_uname)->get();

            if ($ag_filter->isEmpty()) {
                $ran = rmdbl::inRandomOrder()->first();
                $ran = $ran->agent_user_name;
                $ag_filter = rmdbl::where('agent_user_name', $ran)->get();
            }

            $totalagentbet = [];

            foreach ($ag_filter as $agf) {

                $betnum = json_decode($agf->$bt_nm, true);
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

                    if ($comm == 4) {
                        $remm = new remtriple;
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    } else {
                        $remm = remtriple::latest()->first();
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    }

                    return $winNumber;
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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                    if ($comm == 4) {
                        $remm = new remtriple;
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    } else {
                        $remm = remtriple::latest()->first();
                        $remm->game_id = $game_id;
                        $game_type = $bet_type . "_remaining";
                        $remm->$game_type = $calculatedPoint;
                        $remm->save();
                    }

                    return $winNumber;
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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
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

                        if ($comm == 4) {
                            $remm = new remtriple;
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        } else {
                            $remm = remtriple::latest()->first();
                            $remm->game_id = $game_id;
                            $game_type = $bet_type . "_remaining";
                            $remm->$game_type = $calculatedPoint;
                            $remm->save();
                        }

                        return $winNumber;
                    }
                }
            }

        }

    }

    public function triplechance(Request $request)
    {
        $datetime = date("Y-m-d H:i:s", time());
        $isActive = triple_chance::where('active_status', 1)->exists();

        if (!$isActive) {
            return "no records";
        }
        $list = ["single", "double", "triple"];
        $tot_amt = ["single_bet_amount", "double_bet_amount", "triple_bet_amount"];
        $bet_num = ["single_bet_num", "double_bet_num", "triple_bet_num"];
        $multiple = [9,99,999];
        $gamename = "triple_chance";
        $results = [];

        foreach ($list as $key => $bet_type) {
            $result = triplechanceController::game($bet_type, $gamename, $tot_amt[$key], $bet_num[$key], $multiple[$key]);
            $results[$bet_type] = $result;
        }
        return $results; 
        
        $totalBetAmt = 0;
        $tab = DB::SELECT("SELECT * FROM  double_chance where active_status = '1' ");
        foreach ($results as $key => $value) {
        
            if ($key == "andar") {
                $multiple = 9;
                foreach ($tab as $tb) {
                    $betnum = json_decode($tb->andar_bet_number, true);
                    $keys = range(0, count($betnum) - 1);
                    $betNumber = array_combine($keys, $betnum);
                    foreach ($betNumber as $betkey => $betvalue) {
                        if ($betkey == $value) {
                                $ag_tmp_bl = new agtmpbl; 
                                $ag_tmp_bl->agent_user_name = $tb->agent_user_name;
                                $ag_tmp_bl->game = 2;
                                $ag_tmp_bl->ticket_id = $tb->ticket_id;
                                $ag_tmp_bl->start_point = $tb->start_point;
                                $ag_tmp_bl->end_point = $tb->end_point;
                                $ag_tmp_bl->bet_amount = $tb->andar_bet_amount;
                                $ag_tmp_bl->agent_win = $betvalue*9;
                                $ag_tmp_bl->save();
                        }
                    }
                }

            }else if( $key == "bahar"){
                $multiple = 9;
                foreach ($tab as $tb) {
                    $betnum = json_decode($tb->andar_bet_number, true);
                    $keys = range(0, count($betnum) - 1);
                    $betNumber = array_combine($keys, $betnum);
                    foreach ($betNumber as $betkey => $betvalue) {
                        if ($betkey == $value) {
                                $ag_tmp_bl1 = agtmpbl::where('ticket_id',$tb->ticket_id)->first();
                                $ag_tmp_bl1->bet_amount += $tb->andar_bet_amount;
                                $ag_tmp_bl1->agent_win += $betvalue*9;
                                $ag_tmp_bl1->save();
                        }
                    }
                }
            }else{
                $multiple = 99;
                foreach ($tab as $tb) {
                    $betnum = json_decode($tb->andar_bet_number, true);
                    $keys = range(0, count($betnum) - 1);
                    $betNumber = array_combine($keys, $betnum);
                    foreach ($betNumber as $betkey => $betvalue) {
                        if ($betkey == $value) {
                            $ag_tmp_bl1 = agtmpbl::where('ticket_id',$tb->ticket_id)->first();
                            $ag_tmp_bl1->bet_amount += $tb->andar_bet_amount;
                            $ag_tmp_bl1->agent_win += $betvalue*99;
                            $ag_tmp_bl1->save();
                        }
                    }
                }
            }
        }

        dbl::where('active_status',1)
        ->where('created_at', '<', $datetime)
        ->update(['active_status' => 0]);
        
        return $results; 
    }
    
}
