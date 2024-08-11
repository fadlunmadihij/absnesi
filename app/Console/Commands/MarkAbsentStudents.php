<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\data_siswa;
use App\Models\absensi;
use Carbon\Carbon;

class MarkAbsentStudents extends Command
{
    protected $signature = 'students:mark-absent';
    protected $description = 'Mark students as absent if they have not checked in for the day';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $date = Carbon::today();

        $students = data_siswa::all();

        foreach ($students as $student) {
            $attendance = absensi::where('data_siswa_id', $student->id)
                ->whereDate('tanggal', $date)
                ->first();

            if (!$attendance) {
                absensi::create([
                    'data_siswa_id' => $student->id,
                    'tanggal' => $date,
                    'status' => 'A'
                ]);
            }
        }

        $this->info('Absent students have been marked successfully.');
    }
}
