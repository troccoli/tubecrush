<?php

namespace Database\Seeders;

use App\Models\Line;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LineSeeder extends Seeder
{
    public function run(): void
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
