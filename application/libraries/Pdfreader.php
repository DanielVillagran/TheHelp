<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'libraries/PdfParserLoader.php');

use Smalot\PdfParser\Parser;

class Pdfreader
{
    public function extractMovimientos($filePath)
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();

            // Limpieza de texto
            $text = preg_replace("/[ \t]+/", " ", trim($text));

            // Procesa y separa los movimientos
            $resultado = $this->procesarAcuseIMSS($text);

            return [
                'operados' => $resultado['altas'],
                'rechazados' => $resultado['rechazados']
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    private function procesarAcuseIMSS($texto)
    {
        $lineas = preg_split('/\r\n|\r|\n/', $texto);
        $altas = [];
        $rechazados = [];
        $esRechazado = false;

        foreach ($lineas as $linea) {
            $linea = trim($linea);
            if ($linea === '') continue;

            // Si llegamos a la parte de rechazos, activamos la bandera
            if (stripos($linea, 'Relación de movimientos rechazados') !== false) {
                $esRechazado = true;
                continue;
            }

            // Ignorar encabezados y texto institucional
            if (preg_match('/^(Tipo|Contacto|Reforma|INSTITUTO|Sello|Página|Para efectos)/i', $linea)) {
                continue;
            }

            // Detectar líneas de empleados (operados)
            if (preg_match('/[A-ZÁÉÍÓÚÑ]{3,} [A-ZÁÉÍÓÚÑ]{3,}/u', $linea) && preg_match('/\d{2}\/\d{2}\/\d{4}/', $linea)) {
                if (!$esRechazado) {
                    if (preg_match('/(\d{2}\/\d{2}\/\d{4})(\d+)/', $linea, $matches)) {
                        $fecha = $matches[1];
                        $numero = $matches[2];
                        $antesFecha = substr($linea, 0, strpos($linea, $fecha));
                        preg_match_all('/[A-ZÁÉÍÓÚÑ]+/', $antesFecha, $matchesNombre);
                        $nombre = implode(' ', $matchesNombre[0]);
                        $altas[] = $nombre . ',' . $numero;
                    }
                }
            }


            if ($esRechazado && preg_match('/[A-ZÁÉÍÓÚÑ]{2,}.*\d{11,}/u', $linea)) {
                $linea = preg_replace('/^\d+\s+/', '', $linea);
                if (preg_match('/^([A-ZÁÉÍÓÚÑ\s]+)\.(\d+)/', trim($linea), $matches)) {
                    $nombre = $matches[1];
                    $numero = $matches[2];
                    $rechazados[] = $nombre . ',' . $numero;
                }
            }
        }

        return [
            'altas' => $altas,
            'rechazados' => $rechazados
        ];
    }
}
