<?php

namespace App\Imports;

use App\Models\Course;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class CoursesImport implements ToCollection
{
    public $errorMessage = null;
    public $inserted = 0;

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        foreach ($rows as $index => $row) {

            $code = strtoupper(trim($row[0] ?? ''));

            // ❌ REGEX VALIDATION
            if (!preg_match('/^[A-Z]{3}-[0-9]{3}$/', $code)) {
                $this->errorMessage = "Invalid course code at row " . ($index + 1) . ": " . $code;
                DB::rollBack();
                return;
            }

            // ❌ DUPLICATE CHECK
            if (Course::where('course_code', $code)->exists()) {
                $this->errorMessage = "Duplicate course code at row " . ($index + 1) . ": " . $code;
                DB::rollBack();
                return;
            }

            Course::create([
                'course_code'  => $code,
                'course_title' => $row[1] ?? null,
                'description'  => $row[2] ?? null,
            ]);

            $this->inserted++;
        }

        DB::commit();
    }
}
