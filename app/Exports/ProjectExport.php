<?php

namespace App\Exports;

use DB;
use App\Models\ProjectDetail;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\WithHeadings; // to add headings
use Maatwebsite\Excel\Concerns\FromCollection; // to use collections
use Illuminate\Contracts\Queue\ShouldQueue; // to be able to queue

class ProjectExport implements FromCollection, WithHeadings, ShouldQueue
{
    // set the parameters here first
    private $project_id;

    // construct passed parameters here
    function __construct($project_id) {
        $this->project_id = $project_id;
    }

    public function headings():array{
        return[
            'Id',
            'Category',
            'Particulars',
            'Quantity',
            'Description',
            'Unit Price',
            'Total Price',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = ProjectDetail::leftJoin('project_categories', 'project_details.category_id', '=', 'project_categories.id')
                ->where('project_details.project_id', $this->project_id)
                ->select('project_details.id', 'project_categories.name as category_name', 'project_details.name', 'project_details.qty', 'project_details.description', 'project_details.usd_total', 'project_details.total');
        return $query->get();
    }
}
