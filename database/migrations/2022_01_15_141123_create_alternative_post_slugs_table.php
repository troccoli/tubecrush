<?php

use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function down(): void
    {
        Schema::dropIfExists('alternative_post_slugs');
    }

    public function up(): void
    {
        Schema::create('alternative_post_slugs', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->foreignIdFor(Post::class)->references('id')->on('posts');
            $table->timestamps();
        });
    }
};
