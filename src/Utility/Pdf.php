<?php
namespace Solcre\SolcreFramework2\Utility;

use TCPDF;

class Pdf extends TCPDF
{
    public function Header()
    {
        $ormargins = $this->getOriginalMargins();
        $headerfont = $this->getHeaderFont();
        $headerdata = $this->getHeaderData();
        if (($headerdata['logo']) AND ($headerdata['logo'] != K_BLANK_IMAGE)) {
            $this->Image($headerdata['logo'], $this->GetX(), $this->getHeaderMargin(),
                $headerdata['logo_width']);
            $imgy = $this->getImageRBY();
        } else {
            $imgy = $this->GetY();
        }
        $cell_height = round(($this->getCellHeightRatio() * $headerfont[2]) / $this->getScaleFactor(), 2);
        if ($this->getRTL()) {
            $header_x = $ormargins['right'] + ($headerdata['logo_width'] * 1.1);
        } else {
            $header_x = $ormargins['left'] + ($headerdata['logo_width'] * 1.1);
        }
        $header_x = 100;
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 11);
        $this->SetX($header_x);
        $this->Cell(0, $cell_height, $headerdata['title'], 0, 1, '', 0, '', 0);
        $this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
        $this->SetX($header_x);
        $this->MultiCell(0, $cell_height, $headerdata['string'], 0, 'R', 0, 1, '', '', true, 0, false);
        $this->SetLineStyle(array(
            'width' => 0.85 / $this->getScaleFactor(),
            'cap' => 'butt',
            'join' => 'miter',
            'dash' => 0,
            'color' => array(0, 0, 0)
        ));
        $this->SetY((2.835 / $this->getScaleFactor()) + max($imgy, $this->GetY()));
        if ($this->getRTL()) {
            $this->SetX($ormargins['right']);
        } else {
            $this->SetX($ormargins['left']);
        }
        $this->Cell(0, 0, '', 'T', 0, 'C');
    }

    public function Footer()
    {
        $this->SetY(-25);
        $ormargins = $this->getOriginalMargins();
        $this->SetLineStyle(array(
            'width' => 0.85 / $this->getScaleFactor(),
            'cap' => 'butt',
            'join' => 'miter',
            'dash' => 0,
            'color' => array(0, 0, 0)
        ));
        if ($this->getRTL()) {
            $this->SetX($ormargins['right']);
        } else {
            $this->SetX($ormargins['left']);
        }
        $this->Cell(0, 0, '', 'T', 1, 'C');
//        $this->Image(K_PATH_IMAGES . "inmobia_logo.jpg", $this->GetX(), $this->GetY(), 23, 0, '', '', '', false, 72);
        $this->SetFont('helvetica', 'I', 9);
        $this->Cell(0, 10, $this->creator, 0, 0, 'C');
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'R');
    }

    public function Titulo($string, $marginBottom = 5)
    {
        $this->setFillColor(22, 50, 38);
        $this->setTextColor(255, 255, 255);
        $this->MultiCell(0, 7, $string, 1, 'L', 1, 1, '', '', true);
        $this->SetY($this->GetY() + $marginBottom);
    }

    public function Contenedor($string, $height, $marginTop = 5, $ishtml = false)
    {
        $this->setFillColor(242, 242, 242);
        $this->setTextColor(20, 50, 38);
        $this->MultiCell(0, $height, $string, 1, 'L', 1, 1, '', $this->GetY() + $marginTop, true, '', $ishtml);
    }
}

?>