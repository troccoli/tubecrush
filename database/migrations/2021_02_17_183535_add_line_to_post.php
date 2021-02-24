<?php

use App\Models\Line;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddLineToPost extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('line_id')->default(0);

            $table->foreign('line_id')->references('id')->on('lines');
        });

        $this->addData();
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('line_id');
        });
    }

    private function addData(): void
    {
        $lines = [
            'Bakerloo line',
            'Central line',
            'Circle line',
            'District line',
            'Elizabeth line',
            'Hammersmith & City line',
            'Jubilee line',
            'Metropolitan line',
            'Northern line',
            'Piccadilly line',
            'Victoria line',
            'Waterloo & City line',
            'London Overground',
            'London Buses',
            'Emirates Air Line',
            'DLR',
            'London Trams',
            'TfL Rail',
            'Commuter Train',
        ];

        foreach ($lines as $line) {
            Line::query()->create([
                'name' => $line,
                'slug' => Str::slug($line),
            ]);
        }
    }
}
