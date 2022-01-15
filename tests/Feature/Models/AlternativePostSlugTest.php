<?php

namespace Tests\Feature\Models;

use App\Models\AlternativePostSlug;
use App\Models\Post;
use Tests\Feature\TestCase;

class AlternativePostSlugTest extends TestCase
{
    public function testModelFactory(): void
    {
        /** @var AlternativePostSlug $model */
        $model = AlternativePostSlug::factory()->create();
        $this->assertDatabaseHas('alternative_post_slugs', ['id' => $model->getKey()]);

        $post = Post::factory()->create();
        $model = AlternativePostSlug::factory()->for($post)->create();
        $this->assertDatabaseHas('alternative_post_slugs', [
            'id' => $model->getKey(),
            'post_id' => $post->getKey(),
        ]);
    }

    public function testPostRelationship(): void
    {
        $post = Post::factory()->create();
        /** @var AlternativePostSlug $model */
        $model = AlternativePostSlug::factory()->for($post)->create();

        $this->assertSame($post->getKey(), $model->post->getKey());
    }
}
