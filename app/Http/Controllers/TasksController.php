<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use  App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data =[];
        if (\Auth::check()) { //認証済みの場合

            $user = \Auth::user();
            
            $tasks = $user->tasks()->orderBy('created_at','desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            //タスク一覧ビューでそれを表示
            return view('tasks.index',$data);
        }
        else {
            return view('welcome');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        //タスク作成ビューを表示
        return view('tasks.create',[
            'task' => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required',
            ]);
            
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);
        
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //idの値でタスクを検索して取得
        $task = \App\Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
           //タスク詳細ビューでそれを表示
        return view('tasks.show',[
            'task' => $task,
        ]);
        }
        
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }
        
        
        
        
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //タスク詳細ビューでそれを表示
        $task = \App\Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
          //タスク編集ビューでそれを表示
        return view('tasks.edit',[
            'task' => $task,
        ]);
        }
        
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }
        
        
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        //バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required',
            ]);
            
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            //タスクを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        }
        
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // idの値で投稿を検索して取得
        $task = \App\Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }
}
