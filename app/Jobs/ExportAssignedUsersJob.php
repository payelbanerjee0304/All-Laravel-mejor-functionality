<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AssignedUser;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ExportAssignedUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // protected $filename;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // $this->filename = $filename;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $user = AssignedUser::orderBy('created_at', 'desc')->get();

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header row
        $sheet->setCellValue('A1', 'SL.No.');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Phone');
        $sheet->setCellValue('E1', 'Password');
        $sheet->setCellValue('F1', 'Gender');
        $sheet->setCellValue('G1', 'State');
        $sheet->setCellValue('H1', 'District');
        $sheet->setCellValue('I1', 'City');

        // Populate the spreadsheet with data
        $row = 2;
        $i = 1;

        AssignedUser::orderBy('created_at', 'desc')
        ->chunk(1000, function ($user) use ($sheet, &$row, &$i) {
            foreach ($user as $all) {
                $sheet->setCellValue('A' . $row, $i++);
                $sheet->setCellValue('B' . $row, $all->name);
                $sheet->setCellValue('C' . $row, $all->email);
                $sheet->setCellValue('D' . $row, $all->phone);
                $sheet->setCellValue('E' . $row, $all->password);
                $sheet->setCellValue('F' . $row, $all->gender);
                $sheet->setCellValue('G' . $row, $all->state);
                $sheet->setCellValue('H' . $row, $all->district);
                $sheet->setCellValue('I' . $row, $all->city);
                $row++;
            }
        });

        $filename = 'assigneduser-' . now()->format('Y-m-d') . '.xlsx';
        $filePath = 'exports/' . $filename;
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/' . $filePath));
    }
}
