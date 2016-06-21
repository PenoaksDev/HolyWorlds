<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	protected $key = "permission";
	protected $fillable = ["permission", "value_default", "value_assigned"];
	public $timestamps = false;
	public $incrementing = false;

	public static find( $permission )
	{
		foreach ( self::get() as $perm )
		{
			self::prepareExpression( $perm );
		}
	}

	public static prepareExpression( $perm )
	{
		if ( startswith $perm )
			return $perm;

		$perm = str_replace( '.', '\.', $perm );
		$perm = str_replace( '*', '(.*)', $perm );

		if ( preg_match( '/(\d+)-(\d+)/', $perm, $matches, PREG_OFFSET_CAPTURE ) )
			foreach ( $matches as $match )
			{
				$from = $match[1];
				$to = $match[2];

				if ( $from > $to )
				{
					$tmp = $from;
					$from = $to;
					$to = $tmp;
				}

				$perm = str_replace( $match[0], '(' . implode( '|', range( $from, $to ) ) . ')' );
			}

		return $perm;
	}
}
