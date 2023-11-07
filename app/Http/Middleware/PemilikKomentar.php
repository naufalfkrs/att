<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemilikKomentar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $users = Auth::user();
        $comment = Comment::findOrFail($request->id);

        if ($comment->user_id != $users->id) {
            return response()->json(["message" => "anda bukan pemilik komentar ini"], 404);
        }
        return $next($request);
    }
}
