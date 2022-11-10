<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ThreadRequest;
use App\Models\Message;
use App\Models\Thread;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('threads.index');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ThreadRequest $request)
    {
        \DB::beginTransaction();
        try {
            // save Thread
            $thread = new Thread();
            $thread->name = $request->name;
            $thread->user_id = Auth::id();
            $thread->latest_comment_time = Carbon::now();
            $thread->save();

            // save Message
            $message = new Message();
            $message->body = $request->content;
            $message->user_id = Auth::id();
            $message->thread_id = $thread->id;
            $message->save();
        } catch (\Exception $error) {
            \DB::rollBack();
            \Log::error($error->getMessage());
            return redirect()->route('threads.index')->with('error', 'スレッドの新規作成に失敗しました。');
        }
        \DB::commit();
        // redirect to index method
        return redirect()->route('threads.index')->with('success', 'スレッドの新規作成が完了しました。');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
