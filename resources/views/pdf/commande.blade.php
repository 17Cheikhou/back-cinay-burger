<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Facture de Commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .footer p {
            margin: 5px 0;
            color: #7f8c8d;
        }
        .details {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #fff;
            margin-top: 20px;
        }
        .details p {
            margin: 10px 0;
            line-height: 1.6;
        }
        .details strong {
            color: #2c3e50;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table, .table th, .table td {
            border: 1px solid #ddd;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .table td {
            background-color: #fff;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }
        .total span {
            color: #2c3e50;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="path/to/your/logo.png" alt="CinayBurger">
        <h1>Facture de Commande</h1>
    </div>

    <div class="details">
        <p><strong>Nom du Client :</strong> {{ $commande->nom_client }}</p>
        <p><strong>Prénom du Client :</strong> {{ $commande->prenom_client }}</p>
        <p><strong>Téléphone :</strong> {{ $commande->telephone_client }}</p>
        <p><strong>Date de Commande :</strong> {{ $commande->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="details">
        <h2>Détails de la Commande</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Nom du Burger</th>
                <th>Description</th>
                <th>Quantité</th>
                <th>Montant Total</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $commande->burger->nom }}</td>
                <td>{{ $commande->burger->description }}</td>
                <td>{{ $commande->quantite }}</td>
                <td>{{ $commande->montant_total }} FCFA</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="total">
        <p><strong>Total :</strong> <span>{{ $commande->montant_total }} FCFA</span></p>
    </div>

    <div class="footer">
        <p>Merci pour votre achat !</p>
        <p>Contact : CinayBurger@gmail.com | Téléphone : +221 77 123 45 67</p>
    </div>
</div>
</body>
</html>
