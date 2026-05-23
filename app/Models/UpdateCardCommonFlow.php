<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateCardCommonFlow extends Model
{
    protected $table = 'update_card_common_flow';

    protected $fillable = [
        'upload_card_id',
        'status',
        'verified_by',
        'verified_on',
        'close_by',
        'closed_on',
    ];

    public function uploadCard()
    {
        return $this->belongsTo('App\Models\UploadCard', 'upload_card_id', 'id');
    }

    public function verifier()
    {
        return $this->belongsTo('App\Models\Admin', 'verified_by', 'id');
    }

    public function closer()
    {
        return $this->belongsTo('App\Models\Admin', 'close_by', 'id');
    }
}
