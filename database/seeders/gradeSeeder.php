<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class gradeSeeder extends Seeder
{
    /**
     * @var string[]
     */
    private $grades = ['user','admin'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->grades as $grade){
            $newgrade = new Grade();
            $a = 0;
            $perms = $grade == 'user' ? 1 : 0;
            $newgrade['name'] = $grade;
            while ($a <= 26) {
                $newgrade['perm_' . $a] = $perms;
                $a++;
            }
            echo $newgrade->save();
        }

    }
}
