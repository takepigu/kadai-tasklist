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
        //タスク一覧を取得
        $Task = Task::all();
        
        //タスク一覧ビューでそれを表示
        return view('tasks.index',[
            'tasks' => $Task,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Task = new Task;
        
        //タスク作成ビューを表示
        return view('tasks.create',[
            'task' => $Task,
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
            
        //タスクを作成
        $Task = new Task;
        $Task->status = $request->status;
        $Task->content = $request->content;
        $Task->save();
        
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
        $Task = Task::findOrFail($id);
        
        //タスク詳細ビューでそれを表示
        return view('tasks.show',[
            'task' => $Task,
            ]);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //idの値でタスクを検索して取得
        $Task = Task::findOrFail($id);
        
        //タスク編集ビューでそれを表示
        return view('tasks.edit',[
            'task' => $Task,
            ]);
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
        $Task = Task::findOrFail($id);
        //タスクを更新
        $Task->status = $request->status;
        $Task->content = $request->content;
        $Task->save();
        
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
        //idの値でタスクを検索して取得
        $Task = Task::findOrFail($id);
        //タスクを削除
        $Task->delete();
        
        //トップページへリダイレクトさせる
        return redirect('/');
    }
}
