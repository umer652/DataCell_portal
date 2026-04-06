<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsExportTemplate implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'John Doe',
                'john.doe@example.com',
                'Male',
                'Mr. John Sr.',
                'CS-2024-001',
                'APP-2024-001',
                '1',
                'Computer Science',
                'Fall 2024',
                'A',
                '2024-09-01',
                '1',
                'student',
                'Computer Science Department',
                'password123',
                'New student comment'
            ],
        ];
    }
    
    public function headings(): array
    {
        return [
            'name',
            'email',
            'gender',
            'father_name',
            'roll_no',
            'app_no',
            'semester',
            'program',
            'session',
            'section',
            'enrollment_date',
            'new_student',
            'designation',
            'department',
            'password',
            'comment'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}