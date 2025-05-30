<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeethSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teeth = [
            // Adult Teeth (Permanent) - Arabic names with English type
            ['number' => 1, 'name' => 'ضرس العقل', 'type' => 'adult'],
            ['number' => 2, 'name' => 'الضرس الثاني', 'type' => 'adult'],
            ['number' => 3, 'name' => 'الضرس الأول', 'type' => 'adult'],
            ['number' => 4, 'name' => 'الضاحك الثاني', 'type' => 'adult'],
            ['number' => 5, 'name' => 'الضاحك الأول', 'type' => 'adult'],
            ['number' => 6, 'name' => 'الناب', 'type' => 'adult'],
            ['number' => 7, 'name' => 'الرباعية', 'type' => 'adult'],
            ['number' => 8, 'name' => 'الثنية', 'type' => 'adult'],
            ['number' => 9, 'name' => 'الثنية', 'type' => 'adult'],
            ['number' => 10, 'name' => 'الرباعية', 'type' => 'adult'],
            ['number' => 11, 'name' => 'الناب', 'type' => 'adult'],
            ['number' => 12, 'name' => 'الضاحك الأول', 'type' => 'adult'],
            ['number' => 13, 'name' => 'الضاحك الثاني', 'type' => 'adult'],
            ['number' => 14, 'name' => 'الضرس الأول', 'type' => 'adult'],
            ['number' => 15, 'name' => 'الضرس الثاني', 'type' => 'adult'],
            ['number' => 16, 'name' => 'ضرس العقل', 'type' => 'adult'],
            ['number' => 17, 'name' => 'ضرس العقل', 'type' => 'adult'],
            ['number' => 18, 'name' => 'الضرس الثاني', 'type' => 'adult'],
            ['number' => 19, 'name' => 'الضرس الأول', 'type' => 'adult'],
            ['number' => 20, 'name' => 'الضاحك الثاني', 'type' => 'adult'],
            ['number' => 21, 'name' => 'الضاحك الأول', 'type' => 'adult'],
            ['number' => 22, 'name' => 'الناب', 'type' => 'adult'],
            ['number' => 23, 'name' => 'الرباعية', 'type' => 'adult'],
            ['number' => 24, 'name' => 'الثنية', 'type' => 'adult'],
            ['number' => 25, 'name' => 'الثنية', 'type' => 'adult'],
            ['number' => 26, 'name' => 'الرباعية', 'type' => 'adult'],
            ['number' => 27, 'name' => 'الناب', 'type' => 'adult'],
            ['number' => 28, 'name' => 'الضاحك الأول', 'type' => 'adult'],
            ['number' => 29, 'name' => 'الضاحك الثاني', 'type' => 'adult'],
            ['number' => 30, 'name' => 'الضرس الأول', 'type' => 'adult'],
            ['number' => 31, 'name' => 'الضرس الثاني', 'type' => 'adult'],
            ['number' => 32, 'name' => 'ضرس العقل', 'type' => 'adult'],

            // Pediatric Teeth (Primary) - Arabic names with English type
            ['number' => 51, 'name' => 'الضرس الثاني اللبني', 'type' => 'child'],
            ['number' => 52, 'name' => 'الضرس الأول اللبني', 'type' => 'child'],
            ['number' => 53, 'name' => 'الناب اللبني', 'type' => 'child'],
            ['number' => 54, 'name' => 'الرباعية اللبنية', 'type' => 'child'],
            ['number' => 55, 'name' => 'الثنية اللبنية', 'type' => 'child'],
            ['number' => 61, 'name' => 'الثنية اللبنية', 'type' => 'child'],
            ['number' => 62, 'name' => 'الرباعية اللبنية', 'type' => 'child'],
            ['number' => 63, 'name' => 'الناب اللبني', 'type' => 'child'],
            ['number' => 64, 'name' => 'الضرس الأول اللبني', 'type' => 'child'],
            ['number' => 65, 'name' => 'الضرس الثاني اللبني', 'type' => 'child'],
            ['number' => 71, 'name' => 'الضرس الثاني اللبني', 'type' => 'child'],
            ['number' => 72, 'name' => 'الضرس الأول اللبني', 'type' => 'child'],
            ['number' => 73, 'name' => 'الناب اللبني', 'type' => 'child'],
            ['number' => 74, 'name' => 'الرباعية اللبنية', 'type' => 'child'],
            ['number' => 75, 'name' => 'الثنية اللبنية', 'type' => 'child'],
            ['number' => 81, 'name' => 'الثنية اللبنية', 'type' => 'child'],
            ['number' => 82, 'name' => 'الرباعية اللبنية', 'type' => 'child'],
            ['number' => 83, 'name' => 'الناب اللبني', 'type' => 'child'],
            ['number' => 84, 'name' => 'الضرس الأول اللبني', 'type' => 'child'],
            ['number' => 85, 'name' => 'الضرس الثاني اللبني', 'type' => 'child'],
        ];

        foreach ($teeth as $tooth) {
            DB::table('teeth')->insert([
                'id' => Str::uuid(),
                'number' => $tooth['number'],
                'name' => $tooth['name'],
                'type' => $tooth['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}