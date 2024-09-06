<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Tutor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TutorStatisticsWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Tutors', Tutor::whereJsonLength('subjects', '>', 0)->count())
                ->description('Tutors with at least one subject')
                ->color('success'),

            Stat::make('Total Students', Student::count())
                ->description('Total registered students')
                ->color('primary'),

            Stat::make('Average Hourly Rate', '$'.number_format(Tutor::avg('hourly_rate'), 2))
                ->description('Average hourly rate of tutors')
                ->color('warning'),

            Stat::make('Highest Paid Subject', Tutor::getHighestPaidSubject() ?? 'N/A')
                ->description('Based on average hourly rate of tutors teaching that subject')
                ->color('danger'),
        ];
    }
}
