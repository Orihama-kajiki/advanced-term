<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConvertEmptyStringsToNull
{
    public function handle($request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$item, $key) {
            $item = is_string($item) && $item === '' ? null : $item;
        });

        $request->merge($input);

        return $next($request);
    }
}
