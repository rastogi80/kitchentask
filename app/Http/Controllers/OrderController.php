<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CsvImportRequest;


class OrderController extends Controller
{
    public function createOrder()
    {
       //echo "Hello How are you";
         return view("order");

    }

    public function orderTaken(Request $request){
        $validated = $request->validate([
            'csvfile' => 'required|file',
            'foodorder' => 'required|file',
        ]);

        $csvfile = $request->file('csvfile');
        $foodorder =  $request->file('foodorder');

        $error = "NotTrue";

            if($csvfile->getClientOriginalExtension() != 'csv'){
                $error = "Please enter Kitchen Product's CSV";

            }
            
            if($foodorder->getClientOriginalExtension() != 'json'){
                $error = "Please enter food order in JSON";
            }
            
            if($error == "NotTrue"){
                $json = file_get_contents($request->file('foodorder'), true); 
                $foodOrder =  json_decode($json);

                if(isset($foodOrder->ingredients)){
                    $flag = $this->checkOrder($csvfile, $foodOrder->ingredients);
                    if($flag == "Success"){
                        echo "Order Available";
                    }else{
                        echo $flag; 
                    }
                }

            }else{
                echo $error;
            }
           
  
    }

    private function checkOrder($csvFile, $itemIngrediant){

        $csvdata = $this->readCSV($csvFile);
        if(isset($csvdata)){
            foreach($itemIngrediant as $key){
           
               $stockDate = $csvdata[$key->item]['date']; 
               
                $stockFlag = $this->checkstock($key->item, $key->amount, $csvdata);
     
                if($stockFlag == "stock available"){
                     $dateFlag = $this->checkdate($stockDate);
                 
                     if($dateFlag == "success"){
                         return "Success";
                     }else{
                         $error = 'Item expiry date is over or not formated';
                         return $error;
                     }
                 }else{
                    $error = "Stock not avilable";
                    return $error;
                } 
             }
        }else{
            $error = "CSV not formatted";
            return $error;
        }  
    }

    private function readCSV($file){
        $csvFile = file($file);
        $stockData = [];
        foreach ($csvFile as $line) {
            $dataRow = explode(",", $line);
            if(!empty($dataRow['0']) && !empty($dataRow['1']) && !empty($dataRow['2']) && !empty($dataRow['3'])){
                $stockData[$dataRow['0']]= array('stock'=>$dataRow['1'],'unit'=> $dataRow['2'], 'date'=>$dataRow['3']);
            }else{
                return false;
            }
            
        }
        return $stockData;
    }

    private function checkdate($stockDate){
        
        $stockDate = str_replace('/', '-', $stockDate);
        $stockDate =  strtotime($stockDate);
        $currentDate = strtotime("now");

        if ($stockDate){  
            if($stockDate > $currentDate){
                return "success";
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    private function checkstock($item, $amount, $csvdata){
        if(!empty($item) && !empty($amount) && !empty($csvdata)){
            if($csvdata[$item]){
                if($csvdata[$item]['stock'] >= $amount){
                    return "stock available";
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }

    }
}
