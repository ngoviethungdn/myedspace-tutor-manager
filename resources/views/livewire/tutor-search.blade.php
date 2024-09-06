<div class="p-6">
    <!-- Search Input with Debounce -->
    <div class="mb-4">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="searchTerm" 
            placeholder="Search by name" 
            class="form-input rounded-md shadow-sm mt-1 block w-full"
        >
    </div>

    <!-- Subjects Filter -->
    <div class="mb-4">
        <label for="subjects" class="block text-sm font-medium text-gray-700">Subjects</label>
        <select 
            multiple 
            wire:model.live="searchSubjects" 
            id="subjects" 
            class="form-select rounded-md shadow-sm mt-1 block w-full"
        >
            @foreach ($subjects as $subject)
                <option value="{{ $subject }}">{{ $subject }}</option>
            @endforeach
        </select>
    </div>

    <!-- Hourly Rate Filter with Dual Handle Slider -->
    <!-- Hourly Rate Filter with Sliders -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Hourly Rate</label>
        <div class="flex flex-col">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm">Min: ${{ $minHourlyRate }}</span>
                <span class="text-sm">Max: ${{ $maxHourlyRate }}</span>
            </div>
            <input 
                type="range" 
                wire:model.live="maxHourlyRate" 
                min="0" 
                max="100" 
                step="0.01" 
                class="form-range"
            >
        </div>
    </div>

    <!-- Display Tutors -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
        @forelse ($tutors as $tutor)
            <div class="border p-4 rounded-lg shadow-md">
                @if ($tutor->avatar)
                    <img 
                        src="{{ asset($tutor->avatar) }}" 
                        alt="Avatar" 
                        class="w-16 h-16 rounded-full mb-2"
                    >
                @endif
                <h3 class="text-lg font-semibold">{{ $tutor->name }}</h3>
                <p class="text-sm text-gray-600">Hourly Rate: ${{ $tutor->hourly_rate }}</p>
                <p class="text-sm text-gray-600">Subjects: {{ implode(', ', $tutor->subjects) }}</p>
            </div>
        @empty
            <p>No tutors found.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    {{ $tutors->links() }}
</div>