<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class gradeSeeder extends Seeder
{
    /**
     * @var string[]
     */
    private $grades = ['user','Resident','Caregiver','Nurse','Doctor','Senior Doctor','Team Manager','Assistant - Chief','Paramedical - Chief','Inspecteur', 'Développeur'];

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
            while ($a <= 26) {
                if($a == 0){
                    $newgrade->perm_0 = 1;
                }else{
                    $newgrade['perm_' . $a] = 0;
                }
                $a++;
            }
            echo $newgrade->save();
        }

    }
}
