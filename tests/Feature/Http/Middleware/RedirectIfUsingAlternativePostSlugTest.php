<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\Post;
use Illuminate\Http\Response;
use Tests\Feature\TestCase;

class RedirectIfUsingAlternativePostSlugTest extends TestCase
{
    public function testRedirectsForOldPostSlugs(): void
    {
        /** @var Post $post */
        $post = Post::factory()->create();
        $this->get(route('single-post', compact('post')))
            ->assertOk();

        $post->alternativeSlugs()->create(['slug' => 'old-slug-to-same-post']);
        $this->get(route('single-post', ['post' => 'old-slug-to-same-post']))
            ->assertStatus(Response::HTTP_MOVED_PERMANENTLY)
            ->assertRedirect(route('single-post', compact('post')));
    }
}
