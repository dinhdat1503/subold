<?php

namespace App\Console\Commands\Website;

use App;
use Illuminate\Console\Command;

class UserLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:user-level';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User Level Update';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $userLevels = json_decode(siteSetting('user_levels'), true);
            $levelKeys = array_keys($userLevels);
            rsort($levelKeys);
            $usersToUpdate = \App\Models\User::where("status", true)->where('level', '<', max($levelKeys))->where("total_recharge", ">=", $userLevels[2]['money'])->get();
            $updatesCount = 0;
            foreach ($usersToUpdate as $user) {
                $currentLevel = $user->level;
                $newLevel = $currentLevel;
                foreach ($levelKeys as $levelId) {
                    if ($user->total_recharge >= $userLevels[$levelId]['money']) {
                        $newLevel = (int) $levelId;
                        break;
                    }
                }
                if ($newLevel > $currentLevel) {
                    $user->level = $newLevel;
                    $user->save();
                    $updatesCount++;
                }
            }
            $this->info("User Level Update Success! Total users updated: {$updatesCount}");
            return self::SUCCESS;
        } catch (\Exception $e) {
            \Log::warning('User Level Update failed', [
                'error' => $e->getMessage(),
            ]);
            $this->error('User Level Update failed');
            return self::FAILURE;
        }
    }
}
