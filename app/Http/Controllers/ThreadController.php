<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Requests\ThreadRequest;
use App\Models\Message;
use App\Models\Thread;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\ThreadService;
use Exception;

class ThreadController extends Controller
{
    protected $thread_service;

    public function __construct(
        ThreadService $thread_service // インジェクション
    ) {
        $this->middleware('auth')->except('index');
        $this->thread_service = $thread_service; // プロパティに代入する。
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $threads = $this->thread_service->getThreads(3);
        return view('threads.index', compact('threads'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ThreadRequest $request)
    {
        try {
            $data = $request->only(
                ['name', 'content']
            );
            $this->thread_service->createNewThread($data, Auth::id()); // new せずとも $this-> の形で呼び出せる（インジェクションした為）。
        } catch (Exception $error) {
            return redirect()->route('threads.index')->with('error', 'スレッドの新規作成に失敗しました。');
        }
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
