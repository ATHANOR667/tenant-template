<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($type) }} Notification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Facultatif : pour assurer que la couleur d'arrière-plan du corps est blanche */
        body {
            background-color: #f7f7f7; /* Léger fond gris pour le contraste */
        }
    </style>
</head>
<body class="bg-gray-100 p-4 sm:p-8">
<div class="max-w-xl mx-auto bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">

    <div style="background-color: {{ $color }};" class="p-6 text-white text-center">
        <h1 class="text-3xl font-extrabold tracking-tight">
            {{ ucfirst($type) }}
        </h1>
        <p class="text-lg mt-1 opacity-90">
            Notification
        </p>
    </div>

    <div class="p-6 sm:p-8 space-y-6">
        <p class="text-gray-700 leading-relaxed text-base">
            {{ $mailMessage }}
        </p>

    </div>

    <div class="border-t border-gray-200"></div>

    <div class="p-4 bg-gray-50 text-center">
        <p class="text-xs text-gray-500">
            © {{ date('Y') }} **The Hope Charity**. Tous droits réservés.
        </p>
        <p class="text-xs text-gray-400 mt-1">
            Ce courriel a été envoyé automatiquement. Veuillez ne pas y répondre.
        </p>
    </div>
</div>
</body>
</html>
