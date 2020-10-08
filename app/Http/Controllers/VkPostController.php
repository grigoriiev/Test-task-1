<?php

namespace App\Http\Controllers;

use App\Models\VkPost;
use ATehnix\VkClient\Client;
use Illuminate\Http\Request;
use ATehnix\VkClient\Requests\Request as VkRequest;



class VkPostController extends Controller
{

    public function index(){

        return view('vk.vk-statistics');
    }

  public function vkPosts(){

    if(VkPost::count()<1) {

        $api = new Client();

        $request = new VkRequest('wall.get', ['owner_id' => config('services.vkontakte.owner_id')], config('services.vkontakte.token'));

        $response = $api->send($request);

        foreach ($response['response']['items'] as $key => $value) {
            VkPost::create ([
                "date" =>date('Y-m-d',$value['date']),
                "text"=>$value['text'],
                "likesCount"=> $value['likes']['count']
            ]);
        }
    }

    $post=VkPost::take(10)->get();

    return response()->json(['posts'=>$post]);
}



public function vkPostsDateRange(Request $request)
{
    $request->validate([
        'daterange'=>'required'
    ]);

    $request = explode(' - ', $request->input('daterange'));

    $post=VkPost::whereBetween('date', array($request [0],$request [1]))->take(10)->get();

    return response()->json(['posts'=>$post]);
}
}
