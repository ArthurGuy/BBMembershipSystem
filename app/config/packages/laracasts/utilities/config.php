<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View to Bind JavaScript Vars To
    |--------------------------------------------------------------------------
    |
    | Set this value to the name of the view (or partial) that
    | you want to prepend the JavaScript variables to.
    |
    */
    'bind_js_vars_to_this_view' => 'partials/js-data',

    /*
    |--------------------------------------------------------------------------
    | JavaScript Namespace
    |--------------------------------------------------------------------------
    |
    | By default, we'll add variables to the global window object.
    | It's recommended that you change this to some namespace - anything.
    | That way, from your JS, you may do something like `Laracasts.myVar`.
    |
    */
    'js_namespace' => 'BB.chartData'

];