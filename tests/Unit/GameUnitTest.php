<?php

namespace Tests\Unit;

use App\Factory\GameMethodFactory;
use App\Services\Games\WordGame\Service;
use Illuminate\Support\Str;
use App\Providers\StrMacroServiceProvider;
use PHPUnit\Framework\TestCase;

class GameUnitTest extends TestCase
{
    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        //Hack for testing my own defined Macros
        $boot = new StrMacroServiceProvider(null);
        $boot->boot();
    }

    public function test_word_is_palindrome()
    {
        $this->assertTrue(Str::isPalindrome('radar'));
    }

    public function test_word_is_not_palindrome()
    {
        $this->assertTrue(!Str::isPalindrome('fuel'));
    }

    public function test_number_of_uniqueChars_in_string()
    {
        $chars = Str::uniqueChars('array');
        $this->assertTrue($chars == 3);
    }

    public function test_word_is_almost_palindrome()
    {
        $service = GameMethodFactory::createService('Word game');

        $this->assertTrue($service->almostPalindrome('abdcba'));
    }

    public function test_word_is_not_almost_palindrome()
    {
        $service = GameMethodFactory::createService('Word game');
        $this->assertTrue(!$service->almostPalindrome('abdecba'));
    }
}
