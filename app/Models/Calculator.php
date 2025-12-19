<?php

class Calculator
{
    public function evaluate(string $expression, string $angleMode = 'DEG'): float
    {
        // Constants
        $expression = str_replace(['π', 'e'], [M_PI, M_E], $expression);

        // Power operator
        $expression = str_replace('^', '**', $expression);

        // DEG → RAD for trig
        if ($angleMode === 'DEG') {
            $expression = preg_replace_callback(
                '/\b(sin|cos|tan)\(([^()]*)\)/i',
                fn($m) => "{$m[1]}(({$m[2]}) * pi() / 180)",
                $expression
            );
        }

        // Character whitelist
        if (!preg_match('/^[0-9+\-*/().,\s%^]*$/', $expression)) {
            throw new Exception('Invalid characters');
        }

        try {
            $result = eval("return $expression;");
        } catch (Throwable) {
            throw new Exception('Calculation error');
        }

        if (!is_numeric($result) || is_nan($result) || is_infinite($result)) {
            throw new Exception('Invalid result');
        }

        return (float) $result;
    }
}
