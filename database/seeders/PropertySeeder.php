<?php

namespace Database\Seeders;

use App\Models\Description;
use App\Models\Property;
use App\Models\PropertyDetail;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Property::truncate();
        Property::create([
            'color' => "#c49d64",
            'status' => 1,
            'row_number' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $descriptions = [
            [
                'language_id' => 1,
                'property_id' => 1,
                'title' => 'Çalışma Saatleri',
                'description' => '<p>This is Photoshop\'s version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis  bibendum auctor.Aenean sollicitudin.</p>',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'language_id' => 2,
                'property_id' => 1,
                'title' => 'Working Hours',
                'description' => '<p>This is Photoshop\'s version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis  bibendum auctor.Aenean sollicitudin.</p>',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        $files = [
            [
                'file_id' => 4,
                'fileable_id' => 1,
                'fileable_type' => 'App\Models\Property',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($descriptions as $data) {
            PropertyDetail::insert($data);
        }
        foreach ($files as $data) {
            DB::table('fileables')->insert($data);
        }
    }
}
