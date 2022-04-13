<?php


namespace App\Facade;


class TimeInteractor
{

    public function HoursAdd(string|int $base, string|int $operator):string
    {

        if(is_string($base)){
            $base = $this->hoursToSec($base);
        }
        if(is_string($operator)){
            $operator = $this->hoursToSec($operator);
        }


        return  $this->secToHours($base + $operator);
    }

    public function HoursRemove(string|int $base, string|int $operator):string
    {
        if(is_string($base)){
            $base = $this->hoursToSec($base);
        }
        if(is_string($operator)){
            $operator = $this->hoursToSec($operator);
        }


        return  $this->secToHours($base - $operator);
    }

    public function dateToSec(string|null $base):int
    {
        if(is_null($base)) return 0;

        return  0;
    }

    public function secToDate(int $base):string
    {

        return  '';
    }

    public function hoursToSec(string|null $base, bool $displaySec = true):int
    {
        if(is_null($base)) return 0;
        $symbole = substr($base, 0, 1-(strlen($base)));
        $base = str_replace(['+','-'], '', $base);
        $exploded = explode(':',$base);
        $final = 0;
        $final += (int) $exploded[0]*3600;
        $final += (int) $exploded[1]*60;
        if($displaySec){
            $final +=  (int) $exploded[2];
        }

        if($symbole === '-'){
            $final = -1*$final;
        }

        return $final;

    }

    public function secToHours(string|null $base, bool $displaySec = true):string
    {

        if(is_null($base)) return 0;
        $symbole = substr($base, 0, 1-(strlen($base)));
        $base = str_replace(['+','-'], '', $base);
        $hours = (string) floor($base / 3600);
        $hours = ($hours < 10 ? '0' . $hours : $hours);
        $hoursR = $base % 3600;
        $min = (string) floor($hoursR / 60);
        $min = ($min < 10 ? '0' . $min : $min);
        $sec = $hoursR % 60;
        $sec = ($sec < 10 ? '0' . $sec : $sec);

        if($displaySec) {
            return ($symbole === '-' ? '-' :'') . $hours. ':' . $min . ':' . $sec;
        }else{
            return ($symbole === '-' ? '-' :'') . $hours. ':' . $min;
        }

    }


    public function stringToSec(string|null $enter):int
    {
        if(is_null($enter)){
            return 0;
        }
        $enter = (string) $enter;

        $explode = explode(' ', $enter);
        $final = 0;
        foreach ($explode as $part){
            $multiplicator = strtolower(substr($part,-1));
            $time = substr($part,0,-1);
            if($multiplicator == 'j'){
                $final += $time * 3600*24;
            }
            if($multiplicator == 'h'){
                $final += $time * 3600;
            }
            if($multiplicator == 'm' || $multiplicator == 'min'){
                $final += $time * 60;
            }
            if($multiplicator == 's'){
                $final += $time;
            }
        }
        return $final;
    }

    public function secToString(int|null $enter): string
    {
        if(is_null($enter)){
            return '';
        }
        $enter = (int) $enter;

        $calculate['jR'] = $enter % (3600*24);
        $calculate['j'] = (int) floor($enter / (3600*24));
        $calculate['hR'] = $calculate['jR'] % (3600);
        $calculate['h'] = (int) floor($calculate['jR']/ (3600));
        $calculate['mR'] = $calculate['hR'] % (60);
        $calculate['m'] = (int) floor($calculate['hR']/ (60));
        $calculate['s'] = $calculate['mR'];

        return ($calculate['j'] != 0 ? $calculate['j'] . 'j ' : '') .  ($calculate['h'] != 0 ? $calculate['h'] . 'h ': '') . ($calculate['m'] != 0 ? $calculate['m'] . 'm ':'') . ($calculate['s'] != 0 ? $calculate['s'] . 's': '');
    }

}


