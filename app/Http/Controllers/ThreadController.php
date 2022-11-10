<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ThreadRequest;
use App\Services\ThreadService;
use App\Repositories\ThreadRepository;
use Illuminate\Support\Facades\Auth;
use Exception;

class ThreadController extends Controller
{
    protected $thread_service;
    /**
     * Create a new controller instance.
     *
     * @param  ThreadService  $thread_service
     * @return void
     *
    **/
    /**
        * The ThreadRepository implementation.
        *
        * @var ThreadRepository
    */
   protected $thread_repository;
   /**
    * Create a new controller instance.
    *
    * @param  ThreadService  $thread_service
    * @return void
    */
   public function __construct(
       ThreadService $thread_service,
       ThreadRepository $thread_repository
   ) {
       $this->middleware('auth')->except('index');
       $this->thread_service = $thread_service;
       $this->thread_repository = $thread_repository;
   }

    public function index()
    {
        $threads = $this->thread_service->getThreads(3);
        return view('threads.index', compact('threads'));
    }

    // 略
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\ThreadRequest $request
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

    public function show($id)
    {
        $thread = $this->thread_repository->findById($id);
        $thread->load('messages.user');
        return view('threads.show', compact('thread'));
    }
}
