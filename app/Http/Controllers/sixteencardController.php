<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\sixteen_card; 
use Illuminate\Http\Request;

class sixteencardController extends Controller
{

    public function generateTicketId(){
        $latestTicket = sixteen_card::orderBy('id', 'desc')->first();
    
        if ($latestTicket) {
            $lastTicketId = $latestTicket->ticket_id;
            $prefix = substr($lastTicketId, 0, 5);  
            $number = intval(substr($lastTicketId, 5));
    
            if ($prefix === 'TKSCD' && $number < 99999) {
                $number++;
            } else {
                $prefix = 'TKSCD';
                $number = 100000;
            }
        } else {
            $prefix = 'TKSCD';
            $number = 1;
        }
    
        $formattedNumber = str_pad($number, 5, '0', STR_PAD_LEFT);
        $newTicketId = $prefix . $formattedNumber;
        return $newTicketId;
    }

    public function sixteenapi(Request $request){
        $uname = $request->input('agent_user_name');
        $bet_amount = $request->input('bet_amount');
        $bet_number =json_encode( $request->input('bet_number'));
        $start_point = $request->input('start_point');
        $end_point = $request->input('end_point');

try{
             $games_data = new sixteen_card;
            $games_data->agent_user_name = $uname;
            $games_data->game_id = 1;
            $games_data->bet_amount = $bet_amount;
            $games_data->bet_number = $bet_number;
            $games_data->start_point = $start_point;
            $games_data->end_point = $end_point;
            $games_data->ticket_id = sixteencardController::generateTicketId();
            $games_data->save();
            $ticketId = $games_data->ticket_id;
            return response()->json(['status' => true, 'Ticket_id' => $ticketId, 'type' => $bet_number]);
        
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sixteenbet(Request $request){
        $characters = ['J', 'Q', 'K', 'A'];
        $suits = ['heart', 'spades', 'diamond', 'clubs'];
        
        $randomCharacter = $characters[array_rand($characters)];
        $randomSuit = $suits[array_rand($suits)];
        
        $randomCard = $randomCharacter . '_' . $randomSuit;
        
    
        return response()->json(['status' => true, 'WinNumber' => $randomCard]);
    }

}
