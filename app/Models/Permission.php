<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ["name", "type", "permission", "value"];
    public $timestamps = false;

    public function assignedTo()
    {
        if ( $this->type == 0 )
            return $this->belongsTo( Group::class, "name", "groupId" );
        if ( $this->type == 1 )
            return $this->belongsTo( User::class, "name", "userId" );
    }
}
