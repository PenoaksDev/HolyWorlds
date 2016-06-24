DB::listen(function($query) { var_dump( $query->sql ); } );
