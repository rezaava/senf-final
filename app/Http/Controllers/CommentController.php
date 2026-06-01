<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Organ;
use App\Models\Service;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'score' => 'required|integer|min:1|max:5',
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
        ]);

        Comment::create([
            'name' => auth()->user()->name,
            'text' => $request->text,
            'score' => $request->score,
            'user_id' => auth()->id(),
            'commentable_type' => $request->commentable_type,
            'commentable_id' => $request->commentable_id,
            'is_approved' => false,
        ]);

        return response()->json(['status' => 'ok']);
    }

    public function index()
    {
        $organ_comments = Comment::whereHasMorph('commentable',Organ::class)->count();
        $service_comments = Comment::whereHasMorph('commentable',Service::class)->count();

        return view('dashboard.comment.index', compact('organ_comments','service_comments'));
    }
    public function organs()
    {
        $comments = Comment::whereHasMorph('commentable',Organ::class)->paginate(20);
        return view('dashboard.comment.organs', compact('comments'));
    }
    public function services()
    {
        $comments = Comment::whereHasMorph('commentable',Service::class)->paginate(20);

        return view('dashboard.comment.service', compact('comments'));
    }
    public function index2()
    {
        $comments = Comment::orderBy('created_at', 'desc')->paginate(10);

        return view('dashboard.comments.index', compact('comments'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['is_approved' => true]);

        return back()->with('success', 'نظر با موفقیت تایید شد.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'نظر با موفقیت حذف شد.');
    }
}
