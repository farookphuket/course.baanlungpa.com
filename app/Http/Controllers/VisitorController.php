<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getVisitor(){
        $this->isNewVisitor();
        $visitor = Visitor::all();

        $year = $this->visitedYear();
        $month = $this->visitedMonth();
        $today = Visitor::whereDate("visited_at","=",date("Y-m-d"))
                        ->get();
        
        return response()->json([
            "visitor_all" => count($visitor),
            "visited_day" => count($today),
            "visited_month" => $month,
            "visited_year" => $year,
            "your_ip" => getUserIp(),
            "your_os" => getUserOs(),
            "your_browser" => getUserBrowser(),
            "your_device" => getUserDevice()
        ]);
    }

    public function visitedYear(){
        $visit = Visitor::whereYear("created_at","=",date("Y"))
                        ->get();
        return count($visit);
    }

    public function visitedMonth(){
        $visit = Visitor::whereYear("created_at","=",date("Y-m"))
                        ->get();
        return count($visit);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }


    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $visit_data = [
            "os" => getUserOs(),
            "ip" => getUserIp(),
            "browser" => getUserBrowser(),
            "device" => getUserDevice(),
            "visited_at" => now()
        ]; 


        Visitor::create($visit_data);

        // get the last row 
        $visit = Visitor::latest()->first();
        Visitor::backupVisitor($visit->id,"insert");

    }


    public function isNewVisitor(){
        $ip = getUserIp();
        $visit_date = date('Y-m-d',time());

        $visit_obj = Visitor::where('ip',$ip)
                            ->whereDate('visited_at','=',$visit_date)
                            ->get();
        if(count($visit_obj) == 0):
            $this->store();
        endif;
        return $visit_obj;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function show(Visitor $visitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function edit(Visitor $visitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visitor $visitor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visitor $visitor)
    {
        //
    }
}
