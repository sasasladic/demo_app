<?php

namespace App\Services\Games\WordGame;

use App\Helper\Dictionary;
use App\Http\Resources\FormField\Model\FormField;
use App\Services\Games\GameResponse;
use App\Services\Games\GameServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Service implements GameServiceInterface
{
    private string $word;

    private int $points = 0;

    public function getFields(): Collection
    {
        $collection = collect();
        $collection->add(new FormField('text', 'word'));

        return $collection;
    }

    /**
     * Api call is a little slower, so I played around with file Cache.
     *
     * Can be used pspell_new + pspell_check, but there is requirement to install php extension.
     * In this way you can just run the app.
     *
     */
    public function validate(array $data): GameResponse
    {
        if (!isset($data[0]) && !isset($data[0]['value']) || Str::length($data[0]['value']) === 0) {
            return new GameResponse('Please make sure you have entered a word!', false, 422);
        }

        try {
            $this->word = strtolower($data[0]['value']);

            if (Cache::has($this->word)) {
                $exists = Cache::get($this->word);
            } else {
                $exists = Dictionary::wordExists($this->word);
                Cache::put($this->word, $exists);
            }

            if (!$exists) {
                return new GameResponse('Word does not exist in English dictionary!', false, 422);
            }

            return new GameResponse();
        } catch (\Exception $exception) {
            return new GameResponse($exception->getMessage(), false, $exception->getMessage());
        }
    }

    /**
     * Game steps:
     *
     * a) Give 1 point for each unique letter.
     * b) Give 3 extra points if the word is a palindrome.
     * c) Give 2 extra points if the word is “almost palindrome”.
     * Definition of “almost palindrome”: if by removing at most one letter from the word, the word will be a true palindrome.
     *
     * @return void
     */
    public function play(): void
    {
        //Defined my own Str Macro - stored in Providers->StrMacroServiceProvider->boot()
        $this->points += Str::uniqueChars($this->word);

        //Defined my own Str Macro - stored in Providers->StrMacroServiceProvider->boot()
        $palindrome = Str::isPalindrome($this->word);
        if ($palindrome) {
            $this->points += 3;
        }

        if (!$palindrome && $this->almostPalindrome($this->word)) {
            $this->points += 2;
        }
    }

    /**
     * I assumed that there is no need to be defined like macro.
     *
     * PS. In case that it needs to be reused, make it.
     */
    public function almostPalindrome(string $word): bool
    {
        $left = 0;
        $right = strlen($word) - 1;

        while ($left <= $right) {
            if ($word[$left] != $word[$right]) {
                return Str::isPalindrome($word, $left + 1, $right) || Str::isPalindrome($word, $left, $right - 1);
                /**
                 * In case we want to use alternative way in Str::isPalindrome (comment)
                 *
                 * return Str::isPalindrome(substr($word, $left + 1, -(strlen($word)-$right)))
                 *        || Str::isPalindrome(substr($word, $left, -(strlen($word) - ($right - 1))));
                 */
            }

            $left++;
            $right--;
        }

        return true;
    }

    public function getResult(): GameResponse
    {
        return new GameResponse(data: ['points' => $this->points, 'input' => $this->word]);
    }
}
