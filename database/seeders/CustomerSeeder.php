<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerDetail;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::truncate();
        Customer::create([
            'location' => 'Newyork, USA',
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        Customer::create([
            'location' => 'Newyork, USA',
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $descriptions = [
            [
                'language_id' => 1,
                'customer_id' => 1,
                'title' => 'David Gover',
                'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in velit dolor.Vivamus gravida, neque nec interdum cursus, erat ligula porta nibh.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'language_id' => 2,
                'customer_id' => 1,
                'title' => 'David Gover',
                'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in velit dolor.Vivamus gravida, neque nec interdum cursus, erat ligula porta nibh.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'language_id' => 1,
                'customer_id' => 2,
                'title' => 'John Stone',
                'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in velit dolor.Vivamus gravida, neque nec interdum cursus, erat ligula porta nibh.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'language_id' => 2,
                'customer_id' => 2,
                'title' => 'John Stone',
                'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in velit dolor.Vivamus gravida, neque nec interdum cursus, erat ligula porta nibh.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        $files = [
            [
                'file_id' => 19,
                'fileable_id' => 1,
                'fileable_type' => 'App\Models\Customer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'file_id' => 19,
                'fileable_id' => 2,
                'fileable_type' => 'App\Models\Customer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];



        foreach ($descriptions as $data) {
            CustomerDetail::insert($data);
        }
        foreach ($files as $data) {
            DB::table('fileables')->insert($data);
        }
    }
}
