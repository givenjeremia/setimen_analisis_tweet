<?php

namespace App\Http\Controllers;

use App\Models\CekSentimen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CekSentimenController extends Controller
{
    //attributes
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
      
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $kalimat = $request->get('kalimat');
        $cek_sentimen = new CekSentimen();
        $dataset_prepro = DB::table('dataset_prepro')->get();
        $dataset_x = [];
        $dataset_y= [];
        foreach ($dataset_prepro as $key => $value) {
            array_push($dataset_x,$value->text);
            array_push($dataset_y, intval($value->label));

        }
        $hasil = $cek_sentimen->sentimen($kalimat,$dataset_x,$dataset_y);
        return response()->json(array(
            'status' => 'oke',
            'msg' => view('hasil_cek_sentimen',compact('hasil'))->render()
        ), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CekSentimen  $cekSentimen
     * @return \Illuminate\Http\Response
     */
    public function show(CekSentimen $cekSentimen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CekSentimen  $cekSentimen
     * @return \Illuminate\Http\Response
     */
    public function edit(CekSentimen $cekSentimen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CekSentimen  $cekSentimen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CekSentimen $cekSentimen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CekSentimen  $cekSentimen
     * @return \Illuminate\Http\Response
     */
    public function destroy(CekSentimen $cekSentimen)
    {
        //
    }
}
