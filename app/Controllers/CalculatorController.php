<?php
// app/Controllers/CalculatorController.php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/View.php';
require_once __DIR__ . '/../Models/Calculator.php';



class CalculatorController extends Controller
{
    
    public function show()
    {
        // Render the calculator view
        View::render('calculator');
    }

    public function calculate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Method not allowed'], 405);
            return;
        }

      $request = new Request();
$expression = $request->post('expression');
$angleMode = $request->post('angleMode', 'DEG');


        if (trim($expression) === '') {
            $this->json(['success' => false, 'error' => 'Expression is empty'], 400);
            return;
        }

        try {
            $calculator = new Calculator();
            $result = $calculator->evaluate($expression, $angleMode);

            $this->json([
                'success' => true,
                'result'  => $result
            ]);

        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 400);
        }
    }
}
