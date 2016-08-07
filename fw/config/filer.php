<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | Determine whether or not to automatically define filer's routes. You can
    | use Filer::routes($router, $namespace) if you wish to define them in your
    | app.
    |
    */

    'routes' => true,

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | With routes enabled, you can specify the prefix to use here.
    |
    */

    'route_prefix' => 'files',

    /*
    |--------------------------------------------------------------------------
    | Storage Path
    |--------------------------------------------------------------------------
    |
    | The relative and absolute paths to the directory where your local
    | attachments are stored.
    |
    */

    'path' => [
        'relative' => 'uploads',
        'absolute' => $fw->buildPath( 'uploads', 'storage' ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Attachment Query String
    |--------------------------------------------------------------------------
    |
    | Enable this to append a query string to attachment URLs generated by this
    | package. This uses the attachment's updated_at timestamp in UNIX
    | timestamp format.
    |
    */

    'append_query_string' => true,

    /*
    |--------------------------------------------------------------------------
    | Deletion Cleanup
    |--------------------------------------------------------------------------
    |
    | Enable this to make Filer attempt deletion of local files when the
    | attachments they're referenced from are deleted from the database.
    |
    */

    'cleanup_on_delete' => true,

];
