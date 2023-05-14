<div>
    @foreach($getRecord()->studentSchedule->unique('teacher_id') ?? [] as $schedule)
            {{$schedule->teacher->full_name . ($loop->last ? '' : ', ')}}
    @endforeach
</div>
