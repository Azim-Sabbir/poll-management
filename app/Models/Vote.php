<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $fillable = ['poll_id', 'option_id', 'user_id', 'ip_address', 'user_agent'];

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }
}
