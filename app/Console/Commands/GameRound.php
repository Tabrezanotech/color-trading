<?php

// app/Console/Commands/CreateGameRound.php
use Illuminate\Console\Command;
use App\Models\GameRound;
use Carbon\Carbon;

class CreateGameRound extends Command
{
    protected $signature = 'game:create-round';
    protected $description = 'Create a new game round every minute';

   public function handle()
{
    $now = Carbon::now();

    // For 60 sec game
    $last60 = GameRound::where('duration', 60)->latest('counter_id')->first();
    $nextId60 = $last60 ? $last60->counter_id + 1 : 11230001;

    GameRound::create([
        'counter_id' => $nextId60,
        'start_time' => $now,
        'end_time' => $now->copy()->addSeconds(60),
        'duration' => 60
    ]);

    // For 180 sec game
    $last180 = GameRound::where('duration', 180)->latest('counter_id')->first();
    $nextId180 = $last180 ? $last180->counter_id + 1 : 13230001;

    GameRound::create([
        'counter_id' => $nextId180,
        'start_time' => $now,
        'end_time' => $now->copy()->addSeconds(180),
        // 'duration' => 180
    ]);

    // For 300 sec game
    $last300 = GameRound::where('duration', 300)->latest('counter_id')->first();
    $nextId300 = $last300 ? $last300->counter_id + 1 : 15230001;

    GameRound::create([
        'counter_id' => $nextId300,
        'start_time' => $now,
        'end_time' => $now->copy()->addSeconds(300),
        // 'duration' => 300
    ]);

    $this->info("3 Game rounds created successfully.");
}

}
