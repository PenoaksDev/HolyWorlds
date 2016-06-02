<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupInheritance extends Model
{
    protected $table = "group_inheritance";
    protected $fillable = ["child", "parent", "type"];
    public $timestamps = false;

    public function group()
    {
        return $this->hasOne(Group::class, "groupId", "parent");
    }

    public function assignedTo()
    {
        if ( $this->type == 0 )
            return $this->belongsTo( Group::class, "child", "groupId" );
        if ( $this->type == 1 )
            return $this->belongsTo( User::class, "child", "userId" );
    }
}
