<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\boards;
use App\board_lists;
use App\cards;
use App\login_tokens;

class CardController extends Controller
{
    public function index($list_id, $token){
        $card = cards::where('list_id', $list_id)->orderBy('order','asc')->get();

        return response()->json([
            'card'=>$card,
        ],200);
    }

    public function store(Request $request, $board_id, $list_id, $token){
        $this->validate($request, [
            'task' => 'required',
        ]);
        
        $order = \DB::table('cards')
            ->where('list_id', $list_id)
            ->count()+1;

        cards::create([
            'list_id' => $list_id,
            'order' => $order,
            'task' => $request->task,
        ]);

        return response()->json([
            'message'=>'add card success',
        ],200);
    }

    public function update(Request $request, $board_id, $card_id, $token){
        $this->validate($request, [
            'task' => 'required',
        ]);

        $card = cards::find($card_id);

        $card->task=$request->task;
        $card->save();

        return response()->json([
            'message'=>'update card success',
        ],200);
    }

    public function delete($board_id, $card_id, $token){
        $card = cards::find($card_id);
        $orders = cards::where('order','>',$card->order)->get();
        for($i=0; $i<$orders->count(); $i++){
            $orders[$i]->update([
                'order' => $orders[$i]->order-1,
            ]);
        }
        $card->delete();

        return response()->json([
            'message'=>'delete card success',
        ],200);
    }

    public function moveUp($board_id, $card_id, $token){
        $card = cards::find($card_id);  //list kedua
        $cardB = cards::where('list_id',$card->list_id)
            ->where('order', $card->order-1)
            ->first();   

        if(is_null($cardB)){
            return response()->json([
                'message'=>'ini card pertama tidak bisa dipindah karena yang pertama',
            ],422);
        }else{

            $card->update([
                'order' => $card->order-1,
            ]);
            $cardB->update([
                'order' => $card->order+1,
            ]);

            return response()->json([
                'message'=>'hellow',
                'card'=>$cardB,
            ],200);
        }  
    }

    public function moveDown($board_id, $card_id, $token){
        $card = cards::find($card_id);  //list kedua
        $cardB = cards::where('list_id',$card->list_id)
            ->where('order', $card->order+1)
            ->first();   

        if(is_null($cardB)){
            return response()->json([
                'message'=>'ini card pertama tidak bisa dipindah karena yang pertama',
            ],422);
        }else{
            $card->update([
                'order' => $card->order+1,
            ]);
            $cardB->update([
                'order' => $cardB->order-1,
            ]);

            return response()->json([
                'message'=>'hellow',
                'card'=>$cardB,
            ],200);
        }  
    }

    public function moveCard($card_id, $list_id, $token){
        $card = cards::find($card_id);
        $card->update([
            'list_id' => $list_id,          
        ]);

        return response()->json([
            'message'=>'move card success',
        ],200);

    }
}
