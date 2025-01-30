<!DOCTYPE html>
<html>
<head>
    <title>Commande Validée</title>
</head>
<body>
<h1>Votre commande a été validée !</h1>
<p>Bonjour {{ $commande->nom_client }},</p>
<p>Merci d'avoir commandé chez CinayBurger. Votre commande a été validée.</p>
<p><strong>Nom du Burger :</strong> {{ $commande->burger->nom }}</p>
<p><strong>Description :</strong> {{ $commande->burger->description }}</p>
<p><strong>Montant Total :</strong> {{ $commande->montant_total }} FCFA</p>
<p>Veuillez trouver en pièce jointe un récapitulatif de votre commande.</p>
</body>
</html>
