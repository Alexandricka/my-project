<?php

namespace App\Controller\Exercises;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AmerickeMieryController extends AbstractController
{
    const CM_TO_M = 0.01;
    const CM_TO_MM = 10;
    const CM_TO_INCH = 0.393701;
    const CM_TO_FOOT = 0.0328084;

    #[Route('exercises/americke_miery', name: 'exercises-americke-miery')] //atribut route urcuje url cestu, kt spusta tuto metodu
    public function index(Request $request): Response  //metoda index() je zodpovedna za vykreslenie formulara, spracovanie udajov a vysledkov
    {
        $measures = [
            'CM' => 'centimeter',
            'M' => 'meter',
            'MM' => 'milimeter',
            'PAL' => 'palec',
            'ST' => 'stopa',
        ];


        $content = '';
        $centimeters = $request->request->get('centimeters'); //tento riadok ziskava hodnotu zadanu pouzivatelom do formulara

        $formHtml = '
            <div class="container mt-4">
                <h1>Prevod amerických mier</h1>
                <form method="post" class="mb-4">
                    <div class="form-group">
                        <label for="centimeters">Zadajte hodnotu v centimetroch:</label>
                        <input type="number" 
                               step="0.01" 
                               class="form-control" 
                               id="centimeters" 
                               name="centimeters" 
                               required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Previesť</button>
                </form>
        ';

        $content .= $formHtml;

        if ($centimeters !== null) {
            $result = $this->convertMeasures((float) $centimeters); //ak pouzivatel zada hodnotu v cm zavola sa metoda convertMeasures
            
            $tableHtml = '
                <div class="results">
                    <h2>Výsledky prevodu:</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Jednotka</th>
                                <th>Hodnota</th>
                            </tr>
                        </thead>
                        <tbody>
            ';

            foreach ($measures as $code => $name) {  //foreach prechadza cez vsetky jednotky a pre kazdu jednotku pridava riadok do tabulky
                $tableHtml .= sprintf('   
                    <tr>
                        <td>%s</td>
                        <td>%.4f</td>
                    </tr>',
                    ucfirst($name),
                    $result[$code]
                ); //na dynamicke vytvorenie html riadkov pre kazdu jednotku sa pouziva sprintf()
            }

            $tableHtml .= '
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
                    <title>Prevod amerických mier</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                </head>
                <body>
                    %s
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
                </body>
            </html>
        ', $content); //do content sa pridavaju vysledky a formular

        return new Response($html); //vysledny html kod sa vracia pouzivatelovi ako odpoved cez Response triedu
    }

    private function convertMeasures(float $centimeters): array //tato metoda vykona konverziu zadaneho poctu cm do inych jednotiek, vrati pole
    {
        return [
            'CM' => $centimeters,
            'M' => $centimeters * self::CM_TO_M,
            'MM' => $centimeters * self::CM_TO_MM,
            'PAL' => $centimeters * self::CM_TO_INCH,
            'ST' => $centimeters * self::CM_TO_FOOT,
        ]; //vrati pole kde kazda jednotka ma svoju hodnotu, kt je vynasobena svojim koeficientom
    }
}