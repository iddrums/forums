<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Models\Reply;
use App\Models\Thread;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public  function index($channelId, Thread $thread)
    {
      return $thread->replies()->paginate(10);
    }

    public function store($channelId, Thread $thread)
    {
       $this->validateReply();

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
            ]);

            if (request()->expectsJson()) {
               return $reply->load('owner');
            }

            return back()->with('flash', 'Your reply has been left.');
    }


    public function update(Reply $reply)
    {
       $this->authorize('update', $reply);

       $this->validateReply();

       $reply->update(request(['body']));
    }



    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }
        return back();
    }

    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);

        resolve(Spam::class)->detect(request('body'));
    }
}
