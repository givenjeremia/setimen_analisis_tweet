<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\Twitter;
use Phpml\Metric\Accuracy;
use Illuminate\Http\Request;
use Phpml\Classification\SVC;
use Phpml\Dataset\ArrayDataset;
use Phpml\Metric\ConfusionMatrix;
use Illuminate\Support\Facades\DB;
use Sastrawi\Stemmer\StemmerFactory;
use Abraham\TwitterOAuth\TwitterOAuth;
use Phpml\Metric\ClassificationReport;
use Phpml\SupportVectorMachine\Kernel;
use Symfony\Component\Process\Process;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;


class TwitterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $responseBody = [];
        return view('craw', compact('responseBody'));
        
// $client = new Client();
// $url = "http://localhost:5000/bales%20chat/2023-01-01/2023-01-10/15";

// $response = $client->request('GET', $url);

// $responseBody = json_decode($response->getBody());
// // dd($responseBody);
// dd($responseBody);
// // return response()->json(array(
// //     'status'=>'oke',
// //     'msg'=>view('frontend.getDetailMovie',compact('responseBody'))->render()
// // ),200);
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
        ini_set('max_execution_time', 0);
        $client = new Client();
        $query = $request->get('query');
        $sebelum = $request->get('sebelum');
        $sesudah = $request->get('sesudah');
        $count = $request->get('count');
        // Get Data Crawling
        $url = 'http://localhost:5000/'.$query.'/'.$sebelum.'/'.$sesudah.'/'.$count;
        $response = $client->request('GET', $url);
        $responseBody = json_decode($response->getBody());

        // Get Dataset Prepro
        $t = new Twitter();
        $dataset_prepro = DB::table('dataset_prepro')->get();
        // dd($dataset_prepro);
        $dataset_x = [];
        $dataset_y= [];
        foreach ($dataset_prepro as $key => $value) {
            array_push($dataset_x,$value->text);
            array_push($dataset_y, intval($value->label));

        }
        
        $craw_prepro =[];
        $data_with_label =$t->sentimen($responseBody, $dataset_x, $dataset_y);
        // dd($data_with_label );
        // dd($responseBody);
        // dd($data_with_label );
        
        // Generate Total Sentimen
        $negatif = 0;
        $netral = 0;
        $positif = 0;
        foreach ($data_with_label as $item) {
            if($item['high_acc']['label'] == '0'){
                $negatif++;
            }
            else if($item['high_acc']['label'] == '1'){
                $netral++;
            }
            else {
                $positif++;
            }
        }
        $keterangan = [
            'total_data'=> count($data_with_label),
            'total_positif' => $positif,
            'total_netral' => $netral,
            'total_negatif'=>$negatif,
        ];
        return response()->json(array(
            'status' => 'oke',
            'msg' => view('hasil',compact('data_with_label','keterangan','negatif','netral','positif'))->render()
        ), 200);

  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Twitter  $twitter
     * @return \Illuminate\Http\Response
     */
    public function show(Twitter $twitter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Twitter  $twitter
     * @return \Illuminate\Http\Response
     */
    public function edit(Twitter $twitter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Twitter  $twitter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Twitter $twitter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Twitter  $twitter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Twitter $twitter)
    {
        //
    }

    




}
