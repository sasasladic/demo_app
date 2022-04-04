<?php

namespace App\Http\Requests\Game;

use App\Rules\SingleWord;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $game_id
 * @property mixed $input
 */
class GamePlayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'game_id' => 'required|integer',
            'input'   => 'required|array',
            //SingleWord validation rule is defined by me
            'input.*.value'   => ['string', new SingleWord, 'min:3', 'max:50']
        ];
    }
}
