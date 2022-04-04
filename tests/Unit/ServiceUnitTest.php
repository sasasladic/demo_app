<?php

namespace Tests\Unit;

use App\Factory\GameMethodFactory;
use App\Services\Games\WordGame\Service;
use PHPUnit\Framework\TestCase;

class ServiceUnitTest extends TestCase
{
    public function test_creating_a_game_service()
    {
        $service = GameMethodFactory::createService('Word game');

        $this->assertTrue($service instanceof Service);
    }
}
