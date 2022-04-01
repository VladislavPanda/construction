<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid;

class BidController extends Controller
{
    public function store(Request $request){
        $requestData = json_decode(file_get_contents("php://input"));
        $date = \date('Y-m-d');
        $bid['name'] = $requestData[0];
        $bid['phone'] = $requestData[1];
        $bid['category'] = $requestData[2];
        $bid['date'] = $date;
        $bid['message'] = $requestData[3];

        Bid::create($bid);

        return true;
    }
}
