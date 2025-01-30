<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Log;

class CommandeValider extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;
    protected $pdfContent;
    /**
     * Create a new message instance.
     */
    public function __construct(Commande $commande)
    {
        $this->commande = $commande;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $email = $this->commande->email;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::error('Adresse e-mail non valide pour la commande ID: ' . $this->commande->id . ' : ' . $email);
            throw new \Exception('Adresse e-mail non valide : ' . $email);
        }

        return new Envelope(
            from: new Address('CinayBurger@gmail.com', 'CinayBurger'),
            to: new Address($email, $this->commande->email),
            subject: 'Confirmation de votre commande'
        );
    }

    /**
     * Configure le contenu de l'email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.commandeValider',
            with: [
                'commande' => $this->commande,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        try {
            $pdf = Pdf::loadView('pdf.commande', ['commande' => $this->commande]);
            Log::info('PDF généré avec succès pour la commande ID: ' . $this->commande->id);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du PDF : ' . $e->getMessage());
            throw $e; // Relancer l'exception pour gérer l'erreur en amont si nécessaire
        }

        return [
            Attachment::fromData(fn() => $pdf->output(), 'commande.pdf')
                ->withMime('application/pdf'),
        ];
    }

}
