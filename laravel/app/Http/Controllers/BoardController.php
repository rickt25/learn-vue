<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\users;
use App\boards;
use App\board_members;
use App\login_tokens;

class BoardController extends Controller
{
    public function index($token){
        $user = login_tokens::where('token', $token)->first();
        $board = board_members::where('user_id', $user->user_id)
            ->join('boards','boards.id','board_members.board_id')->get();

        return response()->json([
            'board'=>$board,
        ],200);
    }

    public function show($board_id, $token){
        $user = login_tokens::where('token', $token)->first();
        $board = boards::find($board_id);
        $members = board_members::where('board_id', $board_id)
            ->join('users','users.id','board_members.user_id')
            ->get();
    
        return response()->json([
            'board'=>$board,
            'members'=> $members,
        ],200);
    }
    
    public function store(Request $request , $token){
        $user = login_tokens::where('token', $token)->first();

        $this->validate($request, [
            'name' => 'required|unique:boards,name',
        ]);

        $board = boards::create([
            'creator_id' => $user->user_id,
            'name' => $request->name,
        ]);

        board_members::create([
            'board_id' => $board->id,
            'user_id' => $board->creator_id,
        ]);

        return response()->json([
            'message'=>'create board success',
        ],200);
    }

    public function update(Request $request, $board_id ,$token){
        $user = login_tokens::where('token', $token)->first();

        $this->validate($request, [
            'name' => 'required',
            'board_id' => 'required|exists:boards,id',
        ]);

        $board = boards::find($board_id);

        $board->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message'=>'update board success',
        ],200);
    }

    public function delete($board_id, $token){
        $board = boards::find($board_id);
        $board_members = board_members::where('board_id',$board_id)->delete();
        $board->delete();

        return response()->json([
            'message'=>'delete board success',
        ],200);
    }

    public function addMember(Request $request, $id, $token){
        $this->validate($request, [
            'username' => 'required',
        ]);

        $member = users::where('username', $request->username)->first();
        if(is_null($member)){
            return response()->json(['message'=>'user did not exist',],422);
        }
            
        board_members::create([
            'board_id' => $id,
            'user_id' => $member->id,
        ]);

        return response()->json([
            'message'=>'add member success',
        ],200);
    }

    public function deleteMember($board_id, $user_id, $token){
        board_members::where('board_id', $board_id)
                    ->where('user_id',$user_id)->delete();

        return response()->json([
            'message'=>'delete member success',
        ],200);
    }


}
