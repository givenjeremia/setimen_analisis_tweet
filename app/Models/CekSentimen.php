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

class CekSentimen extends Model
{
    use HasFactory;
    public function sentimen($kalimat,$dataset_x,$dataset_y)
    {
        $t = new Twitter();
        $data_with_label =[];
            $ts = $t->prerosesing($kalimat);
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
                    'text'=> $kalimat,
                    'text_prepro'=> $ts,
                        ];
                    }
                     
           

                }
                array_push($data_with_label,$hasil_kernel);
            

        
        return $data_with_label;
    }
}
