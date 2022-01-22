<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class gradeSeeder extends Seeder
{
    /**
     * @var string[]
     */
    private $grades = [
        [
            'name'=>'default',
            'power'=>0,
            'service'=>'all',
            'admin'=>false,
            'default'=>false,
        ],[
            'name'=>'MedicUser',
            'power'=>1,
            'service'=>'OMC',
            'admin'=>false,
            'default'=>true,
        ],[
            'name'=>'FireUser',
            'power'=>1,
            'service'=>'LSCoFD',
            'admin'=>false,
            'default'=>true,
        ],[
            'name'=>'FireChief',
            'power'=>20,
            'service'=>'LSCoFD',
            'admin'=>true,
            'default'=>false,
        ],[
            'name'=>'MedicChief',
            'power'=>20,
            'service'=>'OMC',
            'admin'=>true,
            'default'=>false,
        ],[
            'name'=>'staff',
            'power'=>21,
            'service'=>'staff',
            'admin'=>false,
            'default'=>true,
        ],[
            'name'=>'dev',
            'power'=>30,
            'service'=>'dev',
            'admin'=>true,
            'default'=>false,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        foreach ($this->grades as $grade){
            $newgrade = new Grade();
            $newgrade->name = $grade['name'];
            $newgrade->power = $grade['power'];
            $newgrade->service = $grade['service'];
            $newgrade->default = $grade['default'];
            $newgrade->admin = $grade['admin'];
            $newgrade->save();
        }

    }
}
