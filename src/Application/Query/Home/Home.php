<?php

namespace App\Application\Query\Home;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class Home
{

    public function __invoke(HomeCommand $command)
    {
        return [
            "data" => [
                "data1" => "value1",
                "data2" => "value2",
                "data3" => "value3",
            ]
        ];
    }
}
