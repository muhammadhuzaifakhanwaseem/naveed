<?php

namespace App\Models;
use App\Models\WithdrawGateway;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBind extends Model
{
    protected $fillable = ['user_id', 'withdraw_method_id', 'email', 'account_information', 'note'];
    public function withdrawMethod()
    {
        return $this->belongsTo(WithdrawGateway::class, 'withdraw_method_id');
    }
}





