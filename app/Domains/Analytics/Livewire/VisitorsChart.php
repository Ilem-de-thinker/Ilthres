<?php

namespace App\Domains\Analytics\Livewire;

use App\Domains\Analytics\Actions\GenerateReport;
use App\Models\Business;
use Livewire\Component;

class VisitorsChart extends Component
{
    public Business $business;

    public int $days = 14;

    public function render(GenerateReport $report)
    {
        $viewsByDay = $report->viewsByDay($this->business, (int) $this->days);

        $chartConfig = [
            'type' => 'line',
            'labels' => array_keys($viewsByDay),
            'datasets' => [
                [
                    'label' => 'Page Views',
                    'data' => array_values($viewsByDay),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.08)',
                    'pointBackgroundColor' => '#3b82f6',
                ],
            ],
        ];

        return view('livewire.analytics.visitors-chart', [
            'chartConfig' => $chartConfig,
            'days' => $this->days,
        ]);
    }
}
