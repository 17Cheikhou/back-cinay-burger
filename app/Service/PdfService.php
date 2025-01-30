<?php

namespace App\Service;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Commande;

class PdfService{
    public function generatePdf(Commande $commande)
    {
        return Pdf::loadView('pdf.commande', ['commande' => $commande])->output();
    }
}

