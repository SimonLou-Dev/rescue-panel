<?php


namespace App\Facade;


class TimeInteractor
{

    public function add(string|int $base, string|int $operator):string
    {
        if(is_string($base)){
            $base = $this->dateToSec($base);
        }
        if(is_string($operator)){
            $operator = $this->dateToSec($operator);
        }

        return  '';
    }

    public function remove(string|int $base, string|int $operator):string
    {
        if(is_string($base)){
            $base = $this->dateToSec($base);
        }
        if(is_string($operator)){
            $operator = $this->dateToSec($operator);
        }

        return  '';
    }

    public function dateToSec(string $base):int
    {
        return  0;
    }

    public function secToDate(int $base):string
    {

        return  '';
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


