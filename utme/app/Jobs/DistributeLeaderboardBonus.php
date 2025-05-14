<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\MonthlyRewardMail;
use App\Models\Leaderboard;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\Bank;
use Carbon\Carbon;


class DistributeLeaderboardBonus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $now = Carbon::now();
        $start = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $end = $now->copy()->subMonthNoOverflow()->endOfMonth();

        $totalCredits = Wallet::where('type', Wallet::TYPE_CREDIT)
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $tenPercent = $totalCredits * 0.10;

        $latestSub = Leaderboard::select('user_id', DB::raw('MAX(created_at) as latest'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('user_id');

        $topUsers = Leaderboard::from('leaderboards as l1')
            ->joinSub($latestSub, 'l2', function ($join) {
                $join->on('l1.user_id', '=', 'l2.user_id')
                    ->on('l1.created_at', '=', 'l2.latest');
            })
            ->orderByDesc('l1.score')
            ->select('l1.user_id', 'l1.score', 'l1.created_at')
            ->limit(10)
            ->with('user') 
            ->get();

        if ($topUsers->isEmpty()) return;
            
            $share = $tenPercent / $topUsers->count();

            foreach ($topUsers as $user) {
                $bankDetails = Bank::where('user_id', $user->user_id)->first();
                if (!$bankDetails) continue;
                $payment = Payment::payUser($bankDetails,$share);
                if ($payment === true) {
                    Mail::to($user->user->email)->queue(new MonthlyRewardMail($share,$user->name));
                }
            }
    }
}
