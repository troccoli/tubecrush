<?php

namespace App\Http\Middleware;

use App\Models\AlternativePostSlug;
use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RedirectIfUsingAlternativePostSlug
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
        if ($request->route()->getName() === 'single-post') {
            $slug = $request->route()->parameter('post');
            $alternativePostSlug = AlternativePostSlug::query()->where('slug', $slug)->first();
            if (!is_null($alternativePostSlug)) {
                $post = Post::find($alternativePostSlug->getPostId());
                return redirect()
                    ->away(
                        route('single-post', compact('post')),
                        Response::HTTP_MOVED_PERMANENTLY
                    );
            }
        }

        return $next($request);
    }
}
