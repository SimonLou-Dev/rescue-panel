<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class gradeSeeder extends Seeder
{
    /**
     * @var string[]
     */
    private $grades = ['user','Candidate','Probies','Engineer','Firefighter','Senior Firefighter','Lieutenant','Capitaine','Battalion Chief','Assistant - Chief','Chief','912','Inspecteur', 'Développeur'];

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
            $newgrade['name'] = $grade;
            while ($a <= 39) {
                if ($a == 0) {
                    $newgrade->perm_0 = 1;
                } else if ($newgrade['name'] === 'Développeur' || $newgrade['name'] === 'Chief' || $newgrade['name'] === '912' || $newgrade['name'] === 'Inspecteur') {
                    $newgrade['perm_' . $a] = 1;
                } else {
                    $newgrade['perm_' . $a] = 0;
                }
                $a++;
            }
            $newgrade->save();
        }

    }
}
