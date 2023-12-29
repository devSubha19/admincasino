<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\admin;
use App\Models\superstockez;
use App\Models\stockez;
use App\Models\agent; 
use App\Models\generate_user;
use App\Models\agent_temp_bal;

class UserController extends Controller
{
    public function addadmin(){   
        return view('addadmin');
    }

    private function generateSupUsername()
    {
        $latestUser = generate_user::orderBy('id', 'desc')->first();
    
        if ($latestUser) {
            $id = $latestUser->latest_super + 1;
        } else {
            $id = 1;
        }
    
        $numberOfZeros = 5 - strlen((string)$id);  
        $formattedId = str_repeat('0', $numberOfZeros) . $id; 
        $newUsername = 'SUP' . $formattedId;
        
        return $newUsername;
    }
    

    private function generateStkUsername()
    {
        $latestUser = generate_user::orderBy('id', 'desc')->first();
    
        if ($latestUser) {
            $id = $latestUser->latest_stockez + 1;
        } else {
            $id = 1;
        }
    
        $numberOfZeros = 5 - strlen((string)$id); 
        $formattedId = str_repeat('0', $numberOfZeros) . $id; 
        $newUsername = 'STK' . $formattedId;
        
        return $newUsername;
    }
    
    private function generateAgtUsername()
    {
        $latestUser = generate_user::orderBy('id', 'desc')->first();
    
        if ($latestUser) {
            $id = $latestUser->latest_agent + 1;
        } else {
            $id = 1;
        }
    
        $numberOfZeros = 5 - strlen((string)$id);  
        $formattedId = str_repeat('0', $numberOfZeros) . $id;  
        $newUsername = 'AGT' . $formattedId;
        
        return $newUsername;
    }
    

    public function saveadmin(Request $request){
        $uname = $request->username; 
        $psd = $request->password; 
        $name = $request->name; 
        $email = $request->email; 
        $credit = $request->credit; 
        $ip = $request->ip();
        
        $admin = new admin(); 
        $admin->username  =$uname;
        $admin->password = $psd;
        $admin->name = $name; 
        $admin->email = $email;
        $admin->credit = $credit;
        $admin->machine_ip = $ip; 
        $admin->save(); 
        return redirect('admin');
    }

    public function editadmin(Request $request){  
         
        $adm = admin::where('id',$request->id)->first();
        return view('editadmin',compact('adm'));
    }

    public function saveeditadmin(Request $request){
        $uname = $request->username;    
        $psd = $request->password; 
        $name = $request->name; 
        $email = $request->email; 
        $credit = $request->credit; 
        $ip = $request->ip();
        
        $admin = admin::find($request->id); 
        $admin->username  =$uname;
        $admin->password = $psd;
        $admin->name = $name; 
        $admin->email = $email;
        $admin->machine_ip = $ip; 
        $admin->update(); 
        return redirect('admin')->with('success', 'edited successfully');
    }

    public function admin(){
        $admin = admin::all();
        return view('admin',compact('admin'));
    }
    public function stockez(Request $request){
        $stockezDetails = stockez::with('superStockez')->get();
        $stockez = json_decode($stockezDetails, true);
         
        return view('stockez',compact('stockez'));
    }

    public function addstockez(){
        $superstockez = superstockez::all();
        $username = $this->generateStkUsername();
        return view('addstockez', compact('superstockez','username'));
    }
    public function savestockez(Request $request){
            $uname = $request->username; 
            $psd = $request->password; 
            $tsn_psd = $request->trans_password;
            $name = $request->name; 
            $email = $request->email; 
            $revenue = $request->revenue; 
            $type = $request->type; 
            $ip = $request->ip(); 
            $superstockezId = $request->superstockez;
            
            $stockez = new stockez(); 

            $stockez->username = $uname;
            $stockez->password = $psd;
            $stockez->tsn_psd = $tsn_psd;
            $stockez->name = $name;
            $stockez->email = $email;
            $stockez->revenue = $revenue;
            $stockez->type = $type;
            $stockez->machine_ip = $ip;
            $stockez->superstockez= $superstockezId;
            $stockez->save(); 

            $latest_stockez = generate_user::orderBy('id', 'desc')->first();
            $latest_stockez->latest_stockez += 1; 
            $latest_stockez->save();

            return redirect('stockez')->with('success', 'Stockez added SuccessFully');
    }

    public function viewstockez(Request $request){

        $sup = stockez::find($request->id)->first();
        $agent = agent::where('stockez',$request->id)->get();
        return view('viewstockez',compact('sup','agent'));
    }

    public function viewagent(Request $request){

        $agent = agent::find($request->id);
        return view('viewagent',compact('agent'));
    }


    public function editstockez(Request $request){
       
        $id = $request->id; 
        $stockez = stockez::find($id);
        return view('editstockez',compact('stockez','id'));
    }
    public function deletestockez(Request $request){
        $stockez = stockez::find($request->id); 
        $stockez->delete();

        return redirect()->back()->with('success', 'deleted successfully');
    }
    public function transferstockez(Request $request){
        return view('transferstockez');
    }
    public function adjuststockez(Request $request){
        return view('adjuststockez');
    }
    public function banstockez(Request $request){
        $sup = stockez::find($request->id);
        $sup->status = ($request->status == 0) ? 1 : 0;
        $sup->save();
        $status  = ($sup->status == 0 ) ?  "banned" : "unbanned"; 
        return redirect('stockez')->with("success","$sup->name $status");
    }

    public function superstockez(){
        $superstockez = superstockez::all();
        return view('superstockez',compact('superstockez'));
    }
 
    
    public function addsuperstockez(Request $request){
        $username = $this->generateSupUsername();
        return view('addsuperstockez',compact('username')); 
    }

    public function editsuperstockez(Request $request){
        $id = $request->id; 
        $sup = Superstockez::where('id', $id)->first();
        return view('editsuperstockez',compact('sup', 'id'));
    }


    public function savesuperstockez(Request $request){
            $uname = $request->username; 
            $psd = $request->password; 
            $tsn_psd =  $request->trans_password;
            $name = $request->name; 
            $email = $request->email; 
            $revenue = $request->revenue; 
            $type = $request->type; 
            $ip = $request->ip(); 
            
            $superstockez = new superstockez(); 
            
            $superstockez->username = $uname;
            $superstockez->password = $psd;
            $superstockez->tsn_psd = $tsn_psd;
            $superstockez->name = $name;
            $superstockez->email = $email;
            $superstockez->revenue = $revenue;
            $superstockez->type = $type;
            $superstockez->machine_ip = $ip;
            $superstockez->save(); 

            $latest_super = generate_user::orderBy('id', 'desc')->first();
            $latest_super->latest_super += 1; 
            $latest_super->save();

            return redirect('superstockez')->with('success', 'SuperStockez added SuccessFully');
    }
    public function saveeditsuperstockez(Request $request){
            
            $uname = $request->username; 
            $psd = $request->password; 
            $tsn_psd = $request->trans_password;
            $name = $request->name;         
            $email = $request->email; 
            $revenue = $request->revenue; 
            $type = $request->type; 
            $ip = $request->ip(); 
            
            $superstockez = superstockez::find($request->id); 

            $superstockez->username = $uname;
            $superstockez->password = $psd;
            $superstockez->tsn_psd = $tsn_psd;
            $superstockez->name = $name;
            $superstockez->email = $email;
            $superstockez->revenue = $revenue;
            $superstockez->type = $type;
            $superstockez->machine_ip = $ip;
            $superstockez->update(); 
            return redirect('superstockez')->with('success', 'SuperStockez edited SuccessFully');
    }

    function bansuperstockez(Request $request){

        $sup = superstockez::find($request->id);
        $sup->status = ($request->status == 0) ? 1 : 0;
        $sup->save();
        $stat = $sup->status; 
        $status  = ($sup->status == 0 ) ?  "banned" : "unbanned"; 
        return redirect('superstockez')->with("success","$sup->name $status");
    }


    public function deletesuperstockez(Request $request){
            $superstockez = superstockez::find($request->id); 
            $superstockez->delete();

            
            return redirect()->back()->with('success', 'superStockez deleted successfully');
    }


    public function viewsuperstockez(Request $request){
            $sup = superstockez::find($request->id)->first();
            $stockez = stockez::where('superstockez',$request->id)->get();
            return view('viewsuperstockez',compact('sup','stockez'));
    }


    public function saveeditstockez(Request $request){
        $uname = $request->username; 
        $psd = $request->password;
        $tsn_psd = $request->trans_password;
        $name = $request->name; 
        $email = $request->email; 
        $revenue = $request->revenue; 
        $type = $request->type; 
        $ip = $request->ip(); 
        $superstockezId = $request->superstockez;
        
        $stockez = stockez::find($request->id); 

        $stockez->username = $uname;
        $stockez->password = $psd;
        $stockez->tsn_psd = $tsn_psd;
        $stockez->name = $name;
        $stockez->email = $email;
        $stockez->revenue = $revenue;
        $stockez->type = $type;
        $stockez->machine_ip = $ip;
        $stockez->superstockez = $superstockezId;
        $stockez->update(); 
        return redirect('stockez')->with('success', 'Stockez updated SuccessFully');

       
    }

    public function agent(Request $request){
        $agent = agent::with('stoCkez')->get();
        
        $agent = json_decode($agent, true);
        
        return view('agent',compact('agent'));
    }

    public function addagent(Request $request){
        $stockez = stockez::all();
        $username = $this->generateAgtUsername();
        return view('addagent',compact('stockez','username'));
    }
    
    public function editagent(Request $request){
        $agent = agent::find($request->id);
        $id=$request->id;
        return view('editagent',compact('agent','id'));
    }


    public function saveagent(Request $request){
            $uname = $request->username; 
            $psd = $request->password; 
            $tsn_psd = $request->trans_password;
            $name = $request->name; 
            $email = $request->email; 
            $revenue = $request->revenue; 
            $type = $request->type; 
            $ip = $request->ip(); 
            $stockez = $request->stockez;
            
            $agent = new agent(); 

            $agent->username = $uname;
            $agent->password = $psd;
            $agent->tsn_psd = $tsn_psd;
            $agent->name = $name;
            $agent->email = $email;
            $agent->revenue = $revenue;
            $agent->type = $type;
            $agent->machine_ip = $ip;
            $agent->stockez = $stockez;
            $agent->save(); 

            $latest_agent = generate_user::orderBy('id', 'desc')->first();
            $latest_agent->latest_agent += 1; 
            $latest_agent->save();

        return redirect('agent')->with('success', "Agent Added Successfully");
    }


    public function saveeditagent(Request $request){
        $uname = $request->username; 
        $psd = $request->password; 
        $tsn_psd = $request->trans_password;
        $name = $request->name; 
        $email = $request->email; 
        $revenue = $request->revenue; 
        $type = $request->type; 
        $ip = $request->ip(); 
        $stockez = $request->stockez;
        
        $agent = agent::find($request->id);

        $agent->username = $uname;
        $agent->password = $psd;
        $agent->tsn_psd = $tsn_psd;
        $agent->name = $name;
        $agent->email = $email;
        $agent->revenue = $revenue;
        $agent->type = $type;
        $agent->machine_ip = $ip;
        $agent->stockez = $stockez;
        $agent->update(); 
        return redirect('agent')->with("success",'agent updated successfully');
    }



    public function banagent(Request $request){
        $sup = agent::find($request->id);
        $sup->status = ($request->status == 0) ? 1 : 0;
        $sup->save();
        $status  = ($sup->status == 0 ) ?  "banned" : "unbanned"; 
        return redirect('agent')->with("success","$sup->name $status");
    }   

    public function deleteagent(Request $request){
        $agent = agent::find($request->id); 
            $agent->delete();
 

            return redirect()->back()->with('success','agent deleted successfully');
    }
    public function agentloginreq(Request $request){
        $agent = agent::where('loginstatus',0)->with('stoCkez')->get();
        $agent = json_decode($agent,true);
        return view('loginreq',compact('agent'));
    }
    public function allowagentlogin(Request $request){
        $agent = agent::find($request->id);
        $agent->loginstatus = ($agent->loginstatus == 0) ? 1 : 0; 
        $agent->save();
        return redirect()->back();
    }
    public function blockagentlogin(Request $request){
        $agent = agent::find($request->id);
        $agent->loginstatus = ($agent->loginstatus == 0) ? 2 : 0; 
        $agent->save();
        return redirect()->back();
    }
    


    public function getUserData(Request $request){
        $selectedOption = $request ->input('selectedOption');
        $val = explode(',',$selectedOption);
        $category = $val[1];
        $id = $val[0];

        
        if($category == 'sup1'){

            $superstockez = superstockez::find($id);
            $usertype = 'superstockez';
            $transferlink = "transfersuperstockez?id=$id";
            $adjustlink = "adjustsuperstockez?id=$id";
            return response()->json(['user' => $superstockez, 'usertype' => $usertype, 'transferlink' => $transferlink, 'adjustlink' => $adjustlink]);
            
        }else if($category == 'agt3'){

            $agent = agent::find($id);
            $usertype = 'agent';
            $transferlink = "transferagent?id=$id";
            $adjustlink = "adjustagent?id=$id";
            return response()->json(['user' => $agent, 'usertype' => $usertype, 'transferlink' => $transferlink, 'adjustlink' => $adjustlink]);

        }else{
            $stockez = stockez::find($id);
            $usertype = 'stockez';
            $transferlink = "transferstockez?id=$id";
            $adjustlink = "adjuststockez?id=$id";
            return response()->json(['user' => $stockez, 'usertype' => $usertype, 'transferlink' => $transferlink, 'adjustlink' => $adjustlink]);
        }
        
    }

    public function assignrole(Request $request){
        return view('assignrole');
    }



    public function agentpaymesetper(Request $request){
        return view('agentpaymesetper');
    }

    public function agentpaymsetamt(Request $request){
        return view('agentpaymsetamt');
    }

    public function api(Request $request){
        return response()->json(['message' => 'sanity check']);
    }

}
