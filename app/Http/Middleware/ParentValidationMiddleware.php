<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class ParentValidationMiddleware
{
    public function handle($request, Closure $next)
    {
        //obtencion de token
        $token = $request->header('Authorization');

        //compruebo si es valido
        if ($this->isValidToken($token)) {
            return $next($request);
        }

        return response()->json(['error' => 'Invalid token.'], Response::HTTP_UNAUTHORIZED);
    }

    private function isValidToken($token)
    {
        //divido el token
        $parentheses = str_split($token);

        $stack = [];
        //parentesis y llaves permitidas
        $openingParentheses = ['{', '[', '('];
        $closingParentheses = ['}', ']', ')'];

        foreach ($parentheses as $char) {
            if (in_array($char, $openingParentheses)) {
                array_push($stack, $char);
            } elseif (in_array($char, $closingParentheses)) {
                $lastChar = array_pop($stack);

                if ($lastChar === null || !$this->areParenthesesMatching($lastChar, $char)) {
                    return false;
                }
            }
        }

        return empty($stack);
    }

    private function areParenthesesMatching($opening, $closing)
    {
        //Verifico si el paréntesis de apertura y el de cierre son correspondientes
        return ($opening === '{' && $closing === '}') ||
            ($opening === '[' && $closing === ']') ||
            ($opening === '(' && $closing === ')');
    }
}
