<?php
namespace App\Http\Controllers;
use App\Models\agent_temp_bal;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Crypt;
use App\Models\admin; 
use App\Models\superstockez; 
use App\Models\stockez; 
use App\Models\agent; 

class ViewController extends Controller
{
    public function index(){
        $agt = agent::where('onstatus','1')->get();
        $onplayer = $agt->count();
        $playPoints = agent_temp_bal::where('created', Carbon::today())->sum('bet_amount');  
        
        $winPoints = agent_temp_bal::where('created', Carbon::today())->sum('agent_win');  
        $endPoints =  admin::sum('credit');
        return view("index", compact('onplayer', 'playPoints', 'winPoints', 'endPoints'));
    }

    
    public function dashboard(){
        
        $agent = agent::with('stoCkez')->get();
 
        $agent = json_decode($agent,true);
        $qry = DB::SELECT(" SELECT * FROM agent_temp_bal WHERE created >= DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00') AND created <= NOW(); ");
        $totalPlayPoints = 0; 
        $totalWinPoints = 0;
        foreach($qry as $gdata){
            $totalPlayPoints += $gdata->bet_amount ; 
            $totalWinPoints += $gdata->agent_win; 
        }   
        
        $todayDate = now()->toDateString();  
 
        $averageBet = DB::table('agent_temp_bal')
            ->whereDate('created', $todayDate)
            ->avg('bet_amount'); 

        $EndPoints = DB::table('admin')->sum('credit');
        $SuperStockez = DB::table('superstockez')->sum('credit');

        $totalProfit = DB::table('commission')
            ->whereDate('created_at', $todayDate)
            ->sum('admin_commission'); 

            

        return view("dashboard",compact('agent','totalPlayPoints','totalPlayPoints','totalWinPoints','averageBet','EndPoints','SuperStockez','totalProfit'));
    }

    }
 

?>