<?php

namespace App\Domains\Analytics\Livewire;

use App\Domains\Analytics\Actions\GenerateReport;
use App\Models\AnalyticsEvent;
use App\Models\Business;

class VisitorsChart extends \Livewire\Component
{
    public Business $business;
    public int $days = 14;

    public function render(GenerateReport $report)
    {
        $viewsByDay = $report->viewsByDay($this->business, (int) $this->days);
        $max = max(1, max($viewsByDay));

        return view('livewire.analytics.visitors-chart', [
            'viewsByDay' => $viewsByDay,
            'max' => $max,
        ]);
    }
}
