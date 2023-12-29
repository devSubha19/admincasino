<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash; 
use App\Models\admin; 
use App\Models\superstockez; 
use App\Models\stockez; 
use App\Models\agent; 
use App\Models\trans_master; 

class FinanceController extends Controller
{
    public function transfersuperstockez(Request $request){
        $sup = DB::select("SELECT * FROM superstockez where id = ?", [$request->id]);
        
        foreach($sup as $sp){
            $sup_name = $sp->name; 
            $id = $sp->id; 
        } 

        return view('transfersuperstockez',compact('sup_name','id','sup'));
    }

    public function savetransfersuperstockez(Request $request){
        $sendcredit = $request->credit; 
        $sup = superstockez::find($request->id);

        if(session('user_type') == 'admin') {
            $user = admin::find(session('user_id'));
        }
        if(session('user_type') == 'superstockez') {
            $user = superstockez::find(session('user_id'));
        }
        if(session('user_type') == 'stockez') {
            $user = stockez::find(session('user_id'));
        }

        if ($user->credit < $sendcredit) {
            return redirect()->back()->with('error','sorry you don\'t have enough credit');
        }
        else if($sendcredit < 0 ){
            return redirect()->back()->with('error','Invalid Input');
        }
        else{
            
            $user->credit -= $sendcredit;  
            $sup->credit += $sendcredit;  
            
            $tm = new trans_master;
            $tm->sender_uid = $user->username;
            $tm->receiver_uid = $sup->username;

            $tm->sender_endpoint = $user->credit;
            $tm->sender_id = $user->id;
            $tm->receiver_endpoint = $sup->credit;
            $tm->receiver_id = $sup->id;

            $tm->amount = $sendcredit;
            $tm->sender_type = $user->type;
            $tm->receiver_type = $sup->type;
            $tm->sender_active_status = $user->status;
            $tm->receiver_active_status = $sup->status;
            $tm->sender_ip = $user->machine_ip;
            $tm->receiver_ip = $sup->machine_ip;
            $tm->purpose = 'transfer credit';
            
            $user->save(); 
            $sup->save(); 
            $tm->save();

        return redirect('superstockez')->with('success','Credit Transferred Successfully');
    }
}
    public function adjustsuperstockez(Request $request){
        $sup = DB::select("SELECT * FROM superstockez where id = ?", [$request->id]);
        foreach($sup as $sp){
            $sup_name = $sp->name; 
            $id = $sp->id; 
        }

        return view('adjustsuperstockez',compact('sup_name','id','sup'));
    }

    
    public function saveadjustsuperstockez(Request $request){
        
        $rqadjust = $request->adjust; 
        $sup = superstockez::find($request->id); 
        if(session('user_type') == 'admin') {
            $user = admin::find(session('user_id'));
        }
        if(session('user_type') == 'superstockez') {
            $user = superstockez::find(session('user_id'));
        }
        if(session('user_type') == 'stockez') {
            $user = stockez::find(session('user_id'));
        }

        if ($sup->credit < $rqadjust) {
            return redirect()->back()->with('error','Agent Dont have Enough Credits');
        }else if($rqadjust < 0 ) {
            return redirect()->back()->with('error','invalid input');
        }
        else{
            $sup->credit -= $rqadjust;  
            $user->credit += $rqadjust;  
            
            
            $tm = new trans_master;
            $tm->sender_uid = $sup->username;
            $tm->receiver_uid = $user->username;

            $tm->sender_endpoint = $sup->credit;
            $tm->sender_id = $sup->id;
            $tm->receiver_endpoint = $user->credit;
            $tm->receiver_id = $user->id;

            $tm->amount = $rqadjust;
            $tm->sender_type = $sup->type;
            $tm->receiver_type = $user->type;
            $tm->sender_active_status = $sup->status;
            $tm->receiver_active_status = $user->status;
            $tm->sender_ip = $sup->machine_ip;
            $tm->receiver_ip = $user->machine_ip;
            $tm->purpose = 'adjust credit';
            $sup->save(); 
            $user->save(); 
            $tm->save();
            
        return redirect('superstockez')->with('success','Credit Adjusted Successfully');
    }
    }

    
    public function transferstockez(Request $request){
        $sup = DB::select("SELECT * FROM stockez where id = ?", [$request->id]);
        
        foreach($sup as $sp){
            $sup_name = $sp->name; 
            $id = $sp->id; 
        } 

        return view('transferstockez',compact('sup_name','id','sup'));
    }

    public function savetransferstockez(Request $request){
        $sendcredit = $request->credit; 
        $stk = stockez::find($request->id);

        if(session('user_type') == 'admin') {
            $user = admin::find(session('user_id'));
        }
        if(session('user_type') == 'superstockez') {
            $user = superstockez::find(session('user_id'));
        }
        if(session('user_type') == 'stockez') {
            $user = stockez::find(session('user_id'));
        }

        if ($user->credit < $sendcredit) {
            return redirect()->back()->with('error','Sorry, You Dont have Enough Credits');
        }else if($sendcredit < 0){
            return redirect()->back()->with('error','Invalid Input');
        }
        else{
            $user->credit -= $sendcredit;  
            $stk->credit += $sendcredit;  

            $tm = new trans_master;
            $tm->sender_uid = $user->username;
            $tm->receiver_uid = $stk->username;

            $tm->sender_endpoint = $user->credit;
            $tm->sender_id = $user->id;
            $tm->receiver_endpoint = $stk->credit;
            $tm->receiver_id = $stk->id;

            $tm->amount = $sendcredit;
            $tm->sender_type = $user->type;
            $tm->receiver_type = $stk->type;
            $tm->sender_active_status = $user->status;
            $tm->receiver_active_status = $stk->status;
            $tm->sender_ip = $user->machine_ip;
            $tm->receiver_ip = $stk->machine_ip;
            $tm->purpose = 'transfer credit';
            $user->save(); 
            $stk->save(); 
            $tm->save(); 

        return redirect('stockez')->with('success','Credit Transferred Successfully');
    }
    }

    public function adjuststockez(Request $request){
        $sup = DB::select("SELECT * FROM stockez where id = ?", [$request->id]);
        foreach($sup as $sp){
            $sup_name = $sp->name; 
            $id = $sp->id; 
        }

        return view('adjuststockez',compact('sup_name','id','sup'));
    }

    
    public function saveadjuststockez(Request $request){
        
        $rqadjust = $request->adjust; 
        $stk = stockez::find($request->id); 
        if(session('user_type') == 'admin') {
            $user = admin::find(session('user_id'));
        }
        if(session('user_type') == 'superstockez') {
            $user = superstockez::find(session('user_id'));
        }
        if(session('user_type') == 'stockez') {
            $user = stockez::find(session('user_id'));
        }

        if ($stk->credit < $rqadjust) {
            return redirect()->back()->with('error','Agent Dont have Enough Credits');
        }else if($rqadjust < 0){
            return redirect()->back()->with('error','Invalid Input');

        }
        else{
            $stk->credit -= $rqadjust;  
            $user->credit += $rqadjust;  

            $tm = new trans_master;
            $tm->sender_uid = $stk->username;
            $tm->receiver_uid = $user->username;

            $tm->sender_endpoint = $stk->credit;
            $tm->sender_id = $stk->id;
            $tm->receiver_endpoint = $user->credit;
            $tm->receiver_id = $user->id;

            $tm->amount = $rqadjust;
            $tm->sender_type = $stk->type;
            $tm->receiver_type = $user->type;
            $tm->sender_active_status = $stk->status;
            $tm->receiver_active_status = $user->status;
            $tm->sender_ip = $stk->machine_ip;
            $tm->receiver_ip = $user->machine_ip;
            $tm->purpose = 'adjust credit';
            $stk->save(); 
            $user->save(); 
            $tm->save();
            
        return redirect('stockez')->with('success','Credit Adjusted Successfully');
    }
    }

    public function transferagent(Request $request){
        $sup = DB::select("SELECT * FROM agent where id = ?", [$request->id]);
        
        foreach($sup as $sp){
            $sup_name = $sp->name; 
            $id = $sp->id; 
        } 

        return view('transferagent',compact('sup_name','id','sup'));
    }

    public function savetransferagent(Request $request){
        $sendcredit = $request->credit; 
        $agt = agent::find($request->id);

        if(session('user_type') == 'admin') {
            $user = admin::find(session('user_id'));
        }
        if(session('user_type') == 'superstockez') {
            $user = superstockez::find(session('user_id'));
        }
        if(session('user_type') == 'stockez') {
            $user = stockez::find(session('user_id'));
        }
        

        if ($user->credit < $sendcredit) {
            return redirect()->back()->with('error','Sorry, You Dont have Enough Credits');
        }
        else if($sendcredit < 0){
            return redirect()->back()->with('error', 'Invalid Input');
        }
        else{
            $user->credit -= $sendcredit;  
            $agt->credit += $sendcredit;  

            $tm = new trans_master;
            $tm->sender_uid = $user->username;
            $tm->receiver_uid = $agt->username;

            $tm->sender_endpoint = $user->credit;
            $tm->sender_id = $user->id;
            $tm->receiver_endpoint = $agt->credit;
            $tm->receiver_id = $agt->id;

            $tm->amount = $sendcredit;
            $tm->sender_type = $user->type;
            $tm->receiver_type = $agt->type;
            $tm->sender_active_status = $user->status;
            $tm->receiver_active_status = $agt->status;
            $tm->sender_ip = $user->machine_ip;
            $tm->receiver_ip = $agt->machine_ip;
            $tm->purpose = 'transfer credit';
            $user->save(); 
            $agt->save(); 
            $tm->save(); 

        return redirect('agent')->with('success','Credit Transferred Successfully');
    }
    }

    public function adjustagent(Request $request){
        $sup = DB::select("SELECT * FROM agent where id = ?", [$request->id]);
        foreach($sup as $sp){
            $sup_name = $sp->name; 
            $id = $sp->id; 
        }

        return view('adjustagent',compact('sup_name','id','sup'));
    }

    
    public function saveadjustagent(Request $request){
        
        $rqadjust = $request->adjust; 
        $agt = agent::find($request->id); 
        if(session('user_type') == 'admin') {
            $user = admin::find(session('user_id'));
        }
        if(session('user_type') == 'superstockez') {
            $user = superstockez::find(session('user_id'));
        }
        if(session('user_type') == 'stockez') {
            $user = stockez::find(session('user_id'));
        }

        if ($agt->credit < $rqadjust) {
            return redirect()->back()->with('error','Agent Dont have Enough Credits');
        }else if($rqadjust < 0){
            return redirect()->back()->with('error','invalid input');

        }
        else{
            $agt->credit -= $rqadjust;  
            $user->credit += $rqadjust;  

            $tm = new trans_master;
            $tm->sender_uid = $agt->username;
            $tm->receiver_uid = $user->username;

            $tm->sender_endpoint = $agt->credit;
            $tm->sender_id = $agt->id;
            $tm->receiver_endpoint = $user->credit;
            $tm->receiver_id = $user->id;

            $tm->amount = $rqadjust;
            $tm->sender_type = $agt->type;
            $tm->receiver_type = $user->type;
            $tm->sender_active_status = $agt->status;
            $tm->receiver_active_status = $user->status;
            $tm->sender_ip = $agt->machine_ip;
            $tm->receiver_ip = $user->machine_ip;
            $tm->purpose = 'adjust credit';
            $agt->save(); 
            $user->save(); 
            $tm->save();

        return redirect('agent')->with('success','Credit Adjusted Successfully');
    }
        
    }


    public function transferpoint(Request $request){
        $stockez = stockez::all();
        $superstockez = superstockez::all();
        $agent = agent::all();
        return view('transferpoint',compact('stockez', 'superstockez','agent'));
    }


}
