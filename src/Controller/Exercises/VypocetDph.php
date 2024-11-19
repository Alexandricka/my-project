<?php

namespace App\Controller\Exercises;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; 

class VypocetDph extends AbstractController
{
    const DPH = 0.2; // 20% DPH

    #[Route('exercises/dph', name: 'exercises-dph')]
    public function index(Request $request): Response
    {
        $content = '';
        $bezdph = $request->request->get('bezdph');

        $formHtml = '
            <div class="container mt-4">
                <h1>Výpočet DPH</h1>
                <form method="post" class="mb-4">
                    <div class="form-group">
                        <label for="bezdph">Zadajte čiastku bez DPH:</label>
                        <input type="number" 
                            step="0.01" 
                            class="form-control" 
                            id="bezdph" 
                            name="bezdph" 
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Vypočítať</button>
                </form>
        ';

        $content .= $formHtml;

        if ($bezdph !== null) {
            $result = $this->calculateDph((float) $bezdph);
            
            // Vytvoríme tabuľku s výsledkami
            $tableHtml = '
                <div class="results">
                    <h2>Výsledky:</h2>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Suma bez DPH</td>
                                <td>' . number_format($result['bez_dph'], 2, '.', '') . ' €</td>
                            </tr>
                            <tr>
                                <td>DPH (' . (self::DPH * 100) . '%)</td>
                                <td>' . number_format($result['dph'], 2, '.', '') . ' €</td>
                            </tr>
                            <tr>
                                <td><strong>Celková suma s DPH</strong></td>
                                <td><strong>' . number_format($result['s_dph'], 2, '.', '') . ' €</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            ';

            $content .= $tableHtml;
        }

        $content .= '</div>';

        $html = sprintf('
            <!DOCTYPE html>
            <html>
                <head>
                    <title>Výpočet DPH</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                </head>
                <body>
                    %s
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
                </body>
            </html>
        ', $content);

        return new Response($html);
    }

    private function calculateDph(float $bezDph): array
    {
        $dph = $bezDph * self::DPH;
        $sDph = $bezDph + $dph;

        return [
            'bez_dph' => $bezDph,
            'dph' => $dph,
            's_dph' => $sDph
        ];
    }
}