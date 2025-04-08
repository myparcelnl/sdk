<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\Helper;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class PdfMerger
{
    /**
     * Merge multiple PDF contents into a single PDF.
     *
     * @param array $pdfContents Array of PDF contents as strings.
     * @return string Merged PDF content.
     */
    public static function merge(array $pdfContents): string
    {
        $pdf = new Fpdi();

        foreach ($pdfContents as $content) {
            $pageCount = $pdf->setSourceFile(StreamReader::createByString($content));
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }
        }

        return $pdf->Output('S');
    }
}