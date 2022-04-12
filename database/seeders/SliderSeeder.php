<?php

namespace Database\Seeders;

use App\Models\Slider;
use App\Models\SliderDetail;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Slider::truncate();
        Slider::create([
            'status'         => 1,
            'row_number'     => 1,
            'release_date'   => Carbon::now(),
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now()
        ]);

        $detail = [
            [
                'language_id'          => 1,
                'slider_id'            => 1,
                'title'                => 'Şark Restoranı',
                'sub_title'            => 'İstanbul\'un En Lezzetli',
                'short_description'    => 'Vezirhan Emirgan\'a Hoşgeldiniz.',
                'created_at'           => Carbon::now(),
                'updated_at'           => Carbon::now()
            ],
            [
                'language_id'          => 2,
                'slider_id'            => 1,
                'title'                => 'Şark Restoranı',
                'sub_title'            => 'İstanbul\'un En Lezzetli',
                'short_description'    => 'Vezirhan Emirgan\'a Hoşgeldiniz.',
                'created_at'           => Carbon::now(),
                'updated_at'           => Carbon::now()
            ]
        ];

        $files = [
            [
                'file_id'       => 3,
                'fileable_id'   => 1,
                'fileable_type' => 'App\Models\Slider',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]
        ];

        foreach ($detail as $data) {
            SliderDetail::insert($data);
        }

        foreach ($files as $data) {
            DB::table('fileables')->insert($data);
        }
    }
}
