<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\twelve_card; 
use Illuminate\Http\Request;

class twelvecardController extends Controller
{

    public function generateTicketId(){
        $latestTicket = twelve_card::orderBy('id', 'desc')->first();
    
        if ($latestTicket) {
            $lastTicketId = $latestTicket->id + 1;
        } else {
            $lastTicketId = 1;
        }
    
        $prefix = 'TKTWV';
        $zerosCount = max(0, 4 - strlen((string)$lastTicketId));
        $formattedNumber = str_repeat('0', $zerosCount) . $lastTicketId;
        $newTicketId = $prefix . $formattedNumber;
        
        return $newTicketId;
    }
    
    public function twelveapi(Request $request){
        $uname = $request->input('agent_user_name');
        $bet_amount = $request->input('bet_amount');
        $bet_number =json_encode( $request->input('bet_number'));
        $start_point = $request->input('start_point');
        $end_point = $request->input('end_point');

try{
             $games_data = new twelve_card;
            $games_data->agent_user_name = $uname;
            $games_data->game_id = 1;
            $games_data->bet_amount = $bet_amount;
            $games_data->bet_number = $bet_number;
            $games_data->start_point = $start_point;
            $games_data->end_point = $end_point;
            $games_data->ticket_id = twelvecardController::generateTicketId();
            $games_data->save();
            $ticketId = $games_data->ticket_id;
            return response()->json(['status' => true, 'Ticket_id' => $ticketId, 'type' => $bet_number]);
        
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function twelvebet(Request $request){
        $characters = ['J', 'Q', 'K'];
        $suits = ['heart', 'spades', 'diamond', 'clubs'];
        
        $randomCharacter = $characters[array_rand($characters)];
        $randomSuit = $suits[array_rand($suits)];
        
        $randomCard = $randomCharacter . '_' . $randomSuit;
        
    
        return response()->json(['status' => true, 'WinNumber' => $randomCard]);
    }

}
