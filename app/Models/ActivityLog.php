<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'type', 'description', 'reference_id', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper untuk mencatat aktivitas
     */
    public static function record($description, $type = 'system', $reference_id = null)
    {
        return self::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'type' => $type,
            'description' => $description,
            'reference_id' => $reference_id,
            'ip_address' => request()->ip(),
        ]);
    }
}
