<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderInvoices extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $table = 'order_invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'id', 'order_id', 'invoice_number', 'invoice_date', 'invoice_amount', 'bank_details', 'payment_attachment','payment_details','created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [   
                            // 'created_at', 'updated_at', 
                            'deleted_at','payment_attachment'
                        ];

  
    protected $appends = ['payment_attachments'];

    /**
     * Get order
     *
     */
    public function order()
    {
        return $this->hasOne( 'App\Models\Orders', 'id','order_id' );
    }

    /**
     * Set payment attachment attribute
     *
     */
    public function getPaymentAttachmentsAttribute()
    {   
        if( $this->payment_attachment )
            return explode(",",$this->payment_attachment);
        else
            return $this->payment_attachment;
    }    
}
