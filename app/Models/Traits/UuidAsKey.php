<?php
namespace App\Models\Traits;

use Ramsey\Uuid\Uuid;

trait UuidAsKey
{
	public static function bootUuidForKey()
	{
		static::creating(function ($model) {
			$model->incrementing = false;
			$model->{$model->getKeyName()} = (string)Uuid::uuid4();
		});
	}

	public function getCasts()
	{
		return $this->casts;
	}
}
