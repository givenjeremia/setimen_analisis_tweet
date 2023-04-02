<?php

namespace App\Models;

use GuzzleHttp\Client;
use App\Models\Twitter;
use Phpml\Classification\SVC;
use Phpml\Dataset\ArrayDataset;
use Illuminate\Support\Facades\DB;
use Sastrawi\Stemmer\StemmerFactory;
use Phpml\SupportVectorMachine\Kernel;
use Illuminate\Database\Eloquent\Model;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Twitter extends Model
{
    use HasFactory;

    
    public function prerosesing($text)
    {
        $hasil = "";
        //To Lower
        $hasil = strtolower($text);
        // Preprosesing in API
        $client = new Client();
        $response = $client->request('POST', 'http://localhost:5000/preproPOST', [
            'form_params' => [
                'tweet' =>  $hasil,
               
            ]
        ]);
        $hasil = json_decode($response->getBody());
        // dd($hasil);

        $stemmerFactory = new StemmerFactory();
        $stemmer = $stemmerFactory->createStemmer();

        $hasil = $stemmer->stem($hasil);

        $stopwordFactory = new StopWordRemoverFactory();
        $stopword = $stopwordFactory->createStopWordRemover();

        $hasil = $stopword->remove($hasil);

        $normalizer = DB::table('kamus_normalisasi')->get();
        foreach ($normalizer as $key => $value) {
            # code...
            $hasil = str_replace($value->sebelum,$value->sesudah,$hasil);
        }

        return $hasil;
    }

    public function sentimen($responseBody,$dataset_x,$dataset_y)
    {
        $t = new Twitter();
        $data_with_label =[];
        foreach ($responseBody as $value) {

            $ts = $t->prerosesing($value->content);
            $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
            $tfIdfTransformer = new TfIdfTransformer();
            # code...
            $dataset = $dataset_x;
            $dataset[] = $ts;
            // echo $value['after'].'<br>';
            $vectorizer->fit($dataset);
            $vectorizer->transform($dataset);
            
            $tfIdfTransformer->fit($dataset);
            $tfIdfTransformer->transform($dataset);
            
            $new_data = $dataset[count($dataset)-1];
            unset($dataset[count($dataset)-1]);
                $kernels = ['LINEAR','POLYNOMIAL','SIGMOID','RBF'];

                $hasil_kernel = [];
                $accuracy = 0;
                $dataset_gabung = new ArrayDataset(
                    $samples = $dataset,
                    $targets = $dataset_y
                );
                
                
                $split = new StratifiedRandomSplit($dataset_gabung, 0.2);
                // dd($dataset);
                // train group
                $x_train = $split->getTrainSamples();
                $y_train = $split->getTrainLabels();
                // test group
                $x_test = $split->getTestSamples();
                $y_test = $split->getTestLabels();
                foreach ($kernels as $key => $kernel) {
                   
                    $k = null;
                    if($kernel == 'LINEAR') {
                        $k = Kernel::LINEAR;
                    }
                    elseif($kernel == 'POLYNOMIAL'){
                        $k = Kernel::POLYNOMIAL;
                    }
                    elseif($kernel == 'SIGMOID'){
                        $k = Kernel::SIGMOID;
                    }
                    else{
                        $k = Kernel::RBF;
                    }
                    // dd($k);
                    $classifier = new SVC($k);
            $classifier->train($x_train, $y_train);
            $results = $classifier->predict($x_test);
            $results_single = $classifier->predict($new_data);
              $client = new Client();
            //   dd($results);
            $response = $client->request('POST', 'http://localhost:5000/report', [
                    'json'=>array('y_test'=>$y_test,'y_pred'=>$results)
                ]);
                $report = json_decode($response->getBody());
                    if($accuracy < $report->accuracy ){
                        // Set acc New 
                        $accuracy = $report->accuracy;
                        $hasil_kernel['high_acc'] = [
                            'k'=>$k,
                            'kernel'=>$kernel,
                    'report'=>$report->{'weighted avg'},
                    'acc'=> $report->accuracy,
                    'label'=> $results_single ,
                    'text'=> $value->content,
                    'text_prepro'=> $ts,
                    'tanggal'=>$value->date,
                    'username'=>$value->username,
                        ];
                    }
                     
           

                }
                array_push($data_with_label,$hasil_kernel);
            

        }
        return $data_with_label;
    }

}
