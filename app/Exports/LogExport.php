<?php
namespace App\Exports;


use App\Models\ClickLogs;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LogExport implements FromCollection, WithHeadings
{
    
    protected $start_date;
    protected $end_date;

    function __construct($start_date,$end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    public function collection()
    {
        if($this->start_date != ""){
            return ClickLogs::whereDate('created_at', '>=', $this->start_date)->whereDate('created_at','<=',$this->end_date)->get([
                'id', 'click_log_id','offer_id','user_id','google_id','type','pay_out','payload_data','status','created_at','updated_at'
            ]);
        }else{
            return ClickLogs::get([
                'id', 'click_log_id','offer_id','user_id','google_id','type','pay_out','payload_data','status','created_at','updated_at'
            ]);
        }
        
    }
    public function headings(): array
    {
        return [
            'ID',
            'Click Log Id',
            'Offer Id / Banner Id',
            'User Id',
            'Google Id',
            'Type',
            'Pay Out',
            'Payload Data',
            'Status',
            'Created At',
            'Updated At',
        ];
    }
}
  