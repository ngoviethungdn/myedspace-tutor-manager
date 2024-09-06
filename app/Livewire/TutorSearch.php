<?php

namespace App\Livewire;

use App\Models\Tutor;
use Livewire\Component;
use Livewire\WithPagination;

class TutorSearch extends Component
{
    use WithPagination;

    public $searchSubjects = [];

    public $minHourlyRate = 0;

    public $maxHourlyRate = 100;

    public $searchTerm = '';

    protected $rules = [
        'minHourlyRate' => 'numeric|min:0',
        'maxHourlyRate' => 'numeric|min:0',
        'searchSubjects' => 'array',
        'searchTerm' => 'string|nullable',
    ];

    // Reset pagination when filters change
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->resetPage();
    }

    public function render()
    {
        // \DB::enableQueryLog();

        $tutors = Tutor::querySearch([
            'search' => $this->searchTerm,
            'subjects' => $this->searchSubjects,
            'min_hourly_rate' => $this->minHourlyRate,
            'max_hourly_rate' => $this->maxHourlyRate,
        ])->paginate(10);

        // dd($tutors);
        // dd(\DB::getQueryLog());

        return view('livewire.tutor-search', [
            'tutors' => $tutors,
            'subjects' => Tutor::SUBJECTS,
        ]);
    }
}
