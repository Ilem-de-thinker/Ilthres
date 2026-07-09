<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:process-scheduled-plan-changes')]
#[Description('Apply scheduled plan changes when the current billing period has ended.')]
class ProcessScheduledPlanChanges extends Command
{
    public function handle()
    {
        $processed = 0;

        Subscription::whereNotNull('scheduled_plan_id')
            ->whereColumn('scheduled_plan_id', '!=', 'plan_id')
            ->where('ends_at', '<=', now())
            ->chunk(100, function ($subscriptions) use (&$processed) {
                foreach ($subscriptions as $subscription) {
                    $subscription->update([
                        'plan_id' => $subscription->scheduled_plan_id,
                        'scheduled_plan_id' => null,
                    ]);
                    $processed++;
                }
            });

        $this->info("Processed {$processed} scheduled plan change(s).");
    }
}
