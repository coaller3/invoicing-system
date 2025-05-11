<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProjectExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithTitle
{
    protected $projects;

    public function __construct($projects)
    {
        $this->projects = $projects;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->projects;
    }

    public function map($project): array
    {
        return [
            $project->name,
            $project->description,
            $project->rate,
            $project->duration,
            $project->client->name,
            date('Y-m-d', strtotime($project->created_at)),
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Rate / Hour',
            'Total Hours',
            'Client',
            'Created At',
        ];
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function(AfterSheet $event) {
    //             $event->sheet->getDelegate()->setCellValue('A1', 'Date: ' . $this->date);
    //         },
    //     ];
    // }

    // public function startCell(): string
    // {
    //     return 'A3';
    // }

    public function title(): string
    {
        return 'Projects';
    }

}
