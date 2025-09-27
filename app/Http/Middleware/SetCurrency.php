<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\Currency;

class SetCurrency
{
    public function handle($request, Closure $next)
    {
        if ($code = $request->get('currency')) {
            $c = Currency::where('code', $code)->first();
            if ($c) session(['currency_code' => $code]);
        }
        return $next($request);
    }
}
