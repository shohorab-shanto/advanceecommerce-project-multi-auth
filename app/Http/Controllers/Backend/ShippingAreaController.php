<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ShipDivision;
use App\Models\ShipDistrict;
use App\Models\ShipState;

class ShippingAreaController extends Controller
{
    public function DivisionView(){
        $divisions = ShipDivision::orderBy('id','DESC')->get();
        return view('backend.ship.division.view_division',compact('divisions'));
    }

    public function DivisionStore(Request $request){
        $request->validate([
            'division_name' => 'required',
        ]);

        ShipDivision::insert([
            'division_name' => $request->division_name,
            'created_at'=> Carbon::now(), //for when coupon is created date
        ]);

        $notification = array(
            'message' =>  'Division inserted Sucessfuly',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }//end method

    public function DivisionEdit($id){
        $divisions = ShipDivision::findOrFail($id);
        return view('backend.ship.division.edit_division',compact('divisions'));
    }//end method

    public function DivisionUpdate(Request $request,$id){
        ShipDivision::findOrFail($id)->update([
            'division_name' => $request->division_name,
            'created_at'=> Carbon::now(), //for when coupon is created date
        ]);

        $notification = array(
            'message' =>  'Division updated Sucessfully',
            'alert-type' => 'info'
        );
        return redirect()->route('manage-division')->with($notification);
    }//end method

    public function DivisionDelete($id){
        ShipDivision::findOrFail($id)->delete();
        $notification = array(
            'message' =>  'Division Deletd Sucessfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }//end method

    /// Start Ship District /////

    public function DistrictView(){
        $divisions = ShipDivision::orderBy('division_name','ASC')->get();
        $districts = ShipDistrict::with('division')->orderBy('id','DESC')->get(); //division method in district model which connect district to division
        //$districts = ShipDistrict::orderBy('id','DESC')->get();
        return view('backend.ship.district.view_district',compact('divisions','districts'));
    }

    public function DistrictStore(Request $request){
        $request->validate([
            'division_id' => 'required',
            'district_name' => 'required',
        ]);

        ShipDistrict::insert([
            'division_id' => $request->division_id,
            'district_name' => $request->district_name,
            'created_at'=> Carbon::now(), //for when coupon is created date
        ]);

        $notification = array(
            'message' =>  'District inserted Sucessfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }//end method

    public function DistrictEdit($id){
        $divisions = ShipDivision::orderBy('division_name','ASC')->get();
        $districts = ShipDistrict::findOrFail($id);
        return view('backend.ship.district.edit_district',compact('divisions','districts'));
    }//end method

    public function DistrictUpdate(Request $request,$id){
        ShipDistrict::findOrFail($id)->update([
            'division_id' => $request->division_id,
            'district_name' => $request->district_name,
            'created_at'=> Carbon::now(), //for when coupon is created date
        ]);

        $notification = array(
            'message' =>  'District updated Sucessfully',
            'alert-type' => 'info'
        );
        return redirect()->route('manage-district')->with($notification);
    }//end method

    public function DistrictDelete($id){
        ShipDistrict::findOrFail($id)->delete();
        $notification = array(
            'message' =>  'District Deletd Sucessfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }//end method

    // end District///

    //ship state start///

    public function StateView(){
        $divisions = ShipDivision::orderBy('division_name','ASC')->get();
        $districts = ShipDistrict::orderBy('district_name','ASC')->get();
        $states = ShipState::with('division','district')->orderBy('id','DESC')->get(); //division method in district model which connect district to division
        return view('backend.ship.state.view_state',compact('divisions','districts','states'));
    }

    // //TO GET district
    // public function GetDistrict($division_id){
    //     $dis = ShipDistrict::where('division_id',$division_id)->orderBy('district_name','ASC')->get();
    //     return json_encode($dis);
    // } //end method

    public function StateStore(Request $request){
        $request->validate([
            'division_id' => 'required',
            'district_id' => 'required',
            'state_name' => 'required',
        ]);

        ShipState::insert([
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_name' => $request->state_name,
            'created_at'=> Carbon::now(), //for when coupon is created date
        ]);

        $notification = array(
            'message' =>  'State inserted Sucessfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }//end method

    public function StateEdit($id){
        $divisions = ShipDivision::orderBy('division_name','ASC')->get();
        $districts = ShipDistrict::orderBy('district_name','ASC')->get();
        $states = ShipState::findOrFail($id);
        return view('backend.ship.state.edit_state',compact('divisions','districts','states'));
    }//end method

    public function StateUpdate(Request $request,$id){
        ShipState::findOrFail($id)->update([
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_name' => $request->state_name,
            'created_at'=> Carbon::now(), //for when coupon is created date
        ]);

        $notification = array(
            'message' =>  'State updated Sucessfully',
            'alert-type' => 'info'
        );
        return redirect()->route('manage-state')->with($notification);
    }//end method

    public function StateDelete($id){
        ShipState::findOrFail($id)->delete();
        $notification = array(
            'message' =>  'State Deletd Sucessfully',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);
    }//end method

    //end ship state

}
