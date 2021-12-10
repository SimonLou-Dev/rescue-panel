<?php

namespace Tests\Unit;

use App\Http\Controllers\Rapports\ServiceController;
use App\Http\Controllers\Service\OperatorController;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class ServiceControllerTest extends TestCase
{


    /**
     * A basic unit test example.
     *
     * @return void
     * @covers \App\Http\Controllers\Rapports\ServiceController::addTime
     */
    public function test_ServiceTimeAdder()
    {
        $added = '32:16:09';
        $base = '46:28:17';
        $final = OperatorController::addTime($base, $added);

        try {
            $this->assertEquals('78:44:26', $final);
        } catch (ExpectationFailedException | InvalidArgumentException $e) {
            echo $e;
        }
    }

}
