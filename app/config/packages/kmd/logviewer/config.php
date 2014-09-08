<?php

return array(

    'base_url'   => 'logviewer',
    'filters'    => array(
        'global' => array('role:admin'),
        'view'   => array(),
        'delete' => array()
    ),
    'log_dirs'   => array('app' => storage_path().'/logs'),
    'log_order'  => 'desc', // Change to 'desc' for the latest entries first
    'per_page'   => 20,
    'view'       => 'logviewer::viewer',
    'p_view'     => 'pagination::slider'

);
