<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\boards;
use App\board_lists;
use App\login_tokens;

class ListController extends Controller
{

    public function index($board_id, $token){
        $list = board_lists::where('board_id', $board_id)->orderBy('order','asc')->get();

        return response()->json([
            'list'=>$list,
        ],200);
    }

    public function store(Request $request, $board_id, $token){
        $this->validate($request, [
            'name' => 'required',
        ]);
        
        // $order = \DB::table('board_lists')
        //     ->where('board_id', $board_id)
        //     ->max('order')+1;
        $order = board_lists::where('board_id', $board_id)->count()+1;

        board_lists::create([
            'board_id' => $board_id,
            'order' => $order,
            'name' => $request->name,
        ]);

        return response()->json([
            'message'=>'add list success',
        ],200);

    }

    public function update(Request $request, $board_id, $list_id, $token){
        $this->validate($request, [
            'name' => 'required',
        ]);
            
        $list = board_lists::find($list_id);

        $list->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message'=>'update list success',
        ],200);
    }

    public function delete($board_id, $list_id, $token){
        // $card = cards::where('list_id', $list_id);
        // $card->delete();

        $board = board_lists::find($list_id);
        $orders = board_lists::where('order','>',$board->order)->get();
        for($i=0; $i<$orders->count(); $i++){
            $orders[$i]->update([
                'order' => $orders[$i]->order-1,
            ]);
        }
        $board->delete();

        return response()->json([
            'message'=>'delete list success',
        ],200);
    }

    public function moveLeft($board_id, $list_id, $token){
        $list = board_lists::find($list_id);
        $listB = board_lists::where('order',$list->order-1)->first();

        if(is_null($listB)){
            return response()->json([
                'message'=>'ini list pertama tidak bisa dipindah karena yang pertama',
            ],422);
        }else{
            $temp = $list->order;
            $list->order = $listB->order;
            $listB->order = $temp;

            $list->save();
            board_lists::where('id',$listB->id)
                ->update([
                    'order' => $temp,
                ]);

            return response()->json([
                'message'=>'hellow',
                'list'=>$listB,
            ],200);
        }  
    }

    public function moveRight($board_id, $list_id, $token){
        $list = board_lists::find($list_id);  //list kedua
        $listB = board_lists::where('order',$list->order+1)->first();

        if(is_null($listB)){
            return response()->json([
                'message'=>'ini list pertama tidak bisa dipindah karena yang pertama',
            ],422);
        }else{
            $temp = $list->order;
            $list->order = $listB->order;
            $listB->order = $temp;

            $list->save();
            board_lists::where('id',$listB->id)
                ->update([
                    'order' => $temp,
                ]);

            return response()->json([
                'message'=>'hellow',
                'list'=>$listB,
            ],200);
        }  
    }
}
