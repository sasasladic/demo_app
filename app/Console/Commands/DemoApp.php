<?php

namespace App\Console\Commands;

use App\Console\Commands\Services\HelperModels\Auth;
use App\Console\Commands\Services\HelperModels\Game;
use App\Console\Commands\Services\HelperModels\User;
use Illuminate\Console\Command;

class DemoApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:demo_app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run demo game';

    /**
     * @var string
     */
    private string $token='';

    /**
     * @var Game
     */
    private Game $gameHelper;

    /**
     * @var null
     */
    private $selectedGame = null;


    public function __construct()
    {
        parent::__construct();
        $this->gameHelper = new Game();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        /*
         * Didn't know if I need to make it by calling api(s) or services, so I made it by calling api(s)
         */
        $this->outputMessage("Welcome to the Demo App!", 'comment');

        $this->makeAuth();

        $exit = false;
        while (!$exit) {
            $menuChoice = $this->choice(
                'Choose action',
                ['List of games', 'Show history', 'Exit'],
                0
            );
            $option = match ($menuChoice) {
                'Show history' => 2,
                'Exit' => 3,
                default => 1
            };

            switch ($option) {
                case 1:
                    $this->gameHelper = new Game();
                    $this->gameHelper->setToken($this->token);

                    $gamesResponse = $this->getGames();
                    if (!$gamesResponse['continue']) {
                       break;
                    }

                    $this->chooseGame($gamesResponse['games']);

                    $enteredFields = $this->getAndFillGameFields();
                    if (!$enteredFields['continue']) {
                        break;
                    }
                    $this->launchGame($enteredFields['input']);
                    break;
                case 2:
                    $this->showHistory();
                    break;
                case 3:
                    $exit = true;
                    break;
            }
        }

        $this->outputMessage('See you next time!');

        return 1;
    }

    private function outputMessage(string $message, string $type = 'info'): void
    {
        $output = "<$type>";
        if ($type == 'error') {
            $output .= 'Error: ';
        }
        $output .= $message;
        $output .= "</$type>";

        $this->output->writeln($output . PHP_EOL);
    }

    private function showHistory(): void
    {
        $this->outputMessage("History of your results", 'comment');
        $user = new User();
        $user->setToken($this->token);
        // Missing some logic to get last played game (or all games)
        $historyResult = $user->getGameHistory(['gameId' => $this->selectedGame?->id ?? 1]);

        $th = ['Game', 'Input', 'Points', 'Created At'];
        $tb = [];
        foreach ( $historyResult->data as $row) {
            $tb[] = ['game' => $row->game, 'input' => $row->input, 'points' => $row->points, 'created_at' => $row->created_at];
        }
        $this->table($th, $tb);
    }

    private function launchGame(array $input): void
    {
        $body = [
            'input' => $input,
            'game' => $this->selectedGame->name,
            'game_id' => $this->selectedGame->id
        ];

        $response = $this->gameHelper->launchGame($body);
        if ($response->success) {
            $this->outputMessage("Congrats, you won {$response->data->points} points!");
        }else{
            $this->outputMessage($response->message, 'error');
        }
    }

    private function makeAuth(): void
    {
        $authHelper = new Auth();
        $continue = false;
        while(!$continue) {
            $menuChoice = $this->choice(
                'Choose action',
                ['Login', 'Registration'],
                0
            );

            $option = match ($menuChoice) {
                'Registration' => 2,
                default => 1,
            };

            switch ($option) {
                case 1:
                    $this->outputMessage("Please, enter your credentials: ");
                    $email = $this->ask('Email');
                    $pass = $this->secret('Password');

                    //Execute login
                    $authenticated = $authHelper->login(['email' => $email, 'password' => $pass]);
                    if (!$authenticated->success) {
                        $this->outputMessage($authenticated->message, 'error');
                        break;
                    }
                    $this->outputMessage("Welcome, {$authenticated->data->name}", 'comment');
                    $this->token = $authenticated->data->token;
                    $continue = true;
                    break;
                case 2:
                    $this->outputMessage("Please, Fill next fields: ");
                    $name = $this->ask('Name');
                    $email = $this->ask('Email');
                    $pass = $this->secret('Password');
                    $passConfirmation = $this->secret('Password Confirmation');
                    $body = [
                        'name' => $name,
                        'email' => $email,
                        'password' => $pass,
                        'password_confirmation' => $passConfirmation
                    ];

                    $registered = $authHelper->register($body);
                    if (!$registered->success) {
                        $this->outputMessage($registered->message, 'error');
                        break;
                    }
                    $this->outputMessage("Welcome, {$registered->data->name}", 'comment');
                    $this->token = $registered->data->token;
                    $continue = true;
                    break;
            }
        }
    }

    private function getGames(): array
    {
        $response = $this->gameHelper->getGames();

        if (!$response->success) {
            $this->outputMessage($response->message, 'error');
        }

        return [
            'continue' => $response->success,
            'games' => $response->data
        ];
    }

    private function chooseGame(array $games): void
    {
        $gameChoices = [];
        foreach ($games as $game) {
            $gameChoices[$game->id] = $game->name;
        }
        //Choose a game
        $choice = $this->choice(
            'Choose a game that you want to play?',
            $gameChoices,
            1
        );
        foreach ($games as $game) {
            if ($game->name === $choice) {
                $this->selectedGame = $game;
                break;
            }
        }
    }

    private function getAndFillGameFields(): array
    {
        $continue = true;
        //Get Game Fields
        $response = $this->gameHelper->getGameFields(['game_id' => $this->selectedGame->id, 'game' => $this->selectedGame->name]);
        if (!$response->success) {
            $this->outputMessage($response->message, 'error');
            $continue = false;
        }

        $input = [];
        if ($continue) {
            $this->outputMessage('Please enter next values: ', 'comment');
            foreach ($response->data as $field) {
                $inputValue = $this->ask(ucfirst($field->name));
                $input[] = [
                    'name' => $field->name,
                    'value' => $inputValue
                ];
            }
        }

        return [
            'input' => $input,
            'continue' => $continue
        ];
    }

}
