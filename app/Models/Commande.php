<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'nom_client',
        'prenom_client',
        'telephone_client',
        'statut',
        'email',
        'montant_total',
        'date_paiement',
        'montant_paiement',
        'burger_id',
        "quantite"
    ];

    public function articles()
    {
        return $this->hasMany(ArticleCommande::class);
    }

    public function burger()
    {
        return $this->belongsTo(Burger::class);
    }
}
