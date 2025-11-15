<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de Profil Administrateur</title>
    <style>
        /* Styles modernes et softs pour PDF/Impression */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 15mm; /* Marge accrue pour une meilleure présentation */
            font-size: 10pt; /* Légèrement plus grand pour la lisibilité */
            color: #4a4a4a; /* Gris foncé, plus doux que le noir pur */
        }
        h1, h2, h3 {
            color: #2c3e50; /* Gris-bleu foncé sophistiqué */
            margin-bottom: 8px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 25px; /* Augmentation du padding */
            border: 1px solid #e0e0e0; /* Bordure très légère */
            border-radius: 12px; /* Plus de coins arrondis */
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); /* Ombre douce (si supporté par le moteur de rendu PDF) */
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #ecf0f1; /* Bordure très claire et subtile */
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 2.0em;
            margin-bottom: 4px;
        }
        .header p {
            color: #95a5a6; /* Gris muted pour les métadonnées */
        }
        .header p:nth-child(2) {
            color: #3498db; /* Bleu soft pour le nom */
            font-size: 1.2em;
            font-weight: bold;
        }
        .section-title {
            font-size: 1.3em;
            font-weight: bold;
            margin-top: 30px; /* Espace accru */
            margin-bottom: 15px;
            border-bottom: 2px solid #ecf0f1; /* Ligne de séparation très fine */
            padding-bottom: 8px;
            color: #34495e; /* Couleur primaire muted */
        }
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-grid-row {
            display: table-row;
        }
        .info-item {
            display: table-cell;
            padding: 8px 0; /* Plus d'espace vertical */
            vertical-align: top;
            width: 50%;
            border-bottom: 1px dashed #f5f5f5; /* Séparation soft et moderne */
        }
        .info-grid-row:last-child .info-item {
            border-bottom: none;
        }
        .info-item label {
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
            color: #7f8c8d; /* Couleur de label muted */
            font-size: 0.85em;
        }
        .info-item span {
            display: block;
            color: #2c3e50;
            font-size: 1em;
        }
        .roles-list, .permissions-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .roles-list li {
            font-weight: bold;
            margin-bottom: 5px;
            color: #2980b9; /* Bleu légèrement adouci */
        }
        .permissions-list {
            margin-left: 20px;
            font-size: 0.9em;
        }
        .permissions-list li {
            margin-bottom: 2px;
            color: #5d6d7e; /* Gris-bleu pour les permissions */
        }
        .image-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .image-container img {
            max-width: 120px;
            max-height: 120px;
            border-radius: 50%;
            border: 3px solid #bdc3c7; /* Bordure plus douce */
            padding: 4px;
            object-fit: cover;
        }
        .id-images-wrapper {
            display: block;
            text-align: center;
            margin-bottom: 20px;
            margin-top: 25px;
        }
        .id-image-container {
            display: inline-block;
            vertical-align: top;
            margin: 0 10px;
            text-align: center;
            width: 45%;
            box-sizing: border-box;
        }
        .id-image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            border: 1px solid #dcdcdc;
            padding: 3px;
        }
        .qr-code-container {
            text-align: center;
            margin-top: 30px;
            border: 1px solid #e0e0e0;
            padding: 15px;
            background-color: #f7f9fb; /* Fond très clair */
            border-radius: 10px;
        }
        .qr-code-container img {
            width: 100px; /* Légèrement plus petit */
            height: 100px;
            margin-bottom: 8px;
        }
        .page-break {
            page-break-after: always;
        }
        /* Styles pour les badges de statut (modernisés) */
        .status-badge {
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-block;
            font-size: 0.9em;
            line-height: 1;
        }
        .status-active {
            color: #27ae60; /* Vert soft */
            background-color: #e6f7ee; /* Fond très très clair */
        }
        .status-deleted {
            color: #e74c3c; /* Rouge soft */
            background-color: #fae7e6; /* Fond très très clair */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Fiche de Profil Administrateur</h1>
        <p>{{ $admin->prenom }} {{ $admin->nom }}</p>
        <p style="font-size: 0.85em;">Matricule: {{ $admin->matricule }}</p>
        <p style="font-size: 0.75em;">Généré le: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    @if ($admin)
        <div class="image-container">
            @if ($photoProfilPath)
                <img src="data:image/jpeg;base64,{{ base64_encode(@file_get_contents($photoProfilPath)) }}" alt="Photo de Profil">
            @else
                <div style="width: 120px; height: 120px; background-color: #ecf0f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5em; color: #bdc3c7; font-weight: bold; margin: 0 auto;">
                    {{ strtoupper(substr($admin->prenom, 0, 1)) }}{{ strtoupper(substr($admin->nom, 0, 1)) }}
                </div>
            @endif
        </div>

        <div class="section-title">Informations Générales</div>
        <div class="info-grid">
            <div class="info-grid-row">
                <div class="info-item">
                    <label>Email:</label>
                    <span>{{ $admin->email ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Téléphone:</label>
                    <span>{{ $admin->telephone ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="info-grid-row">
                <div class="info-item">
                    <label>Pays:</label>
                    <span>{{ $admin->pays ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Ville:</label>
                    <span>{{ $admin->ville ?? 'N/A' }}</span>
                </div>
            </div>

            {{-- LIGNE POUR LE STATUT DU COMPTE --}}
            <div class="info-grid-row">
                <div class="info-item" style="width: 100%; border-bottom: none;">
                    <label>Statut du Compte:</label>
                    @if ($admin->trashed())
                        <span class="status-badge status-deleted">
                            SUPPRIMÉ (Archivé)
                        </span>
                    @else
                        <span class="status-badge status-active">
                            ACTIF
                        </span>
                    @endif
                </div>
            </div>
            {{-- FIN STATUT DU COMPTE --}}

        </div>

        <div class="section-title">Sécurité du Compte</div>
        <div class="info-grid">
            <div class="info-grid-row">
                <div class="info-item">
                    <label>Mot de Passe:</label>
                    <span>{{ $admin->password ? '********' : 'Non défini' }}</span>
                </div>
                <div class="info-item">
                    <label>Passcode:</label>
                    <span>{{ $admin->passcode ? '********' : 'Non défini' }}</span>
                </div>
            </div>
            <div class="info-grid-row">
                <div class="info-item">
                    <label>Dernier changement mot de passe:</label>
                    <span>{{ $admin->password_changed_at ? $admin->password_changed_at->format('d/m/Y H:i') : 'Jamais' }}</span>
                </div>
                <div class="info-item">
                    <label>Dernier changement passcode:</label>
                    <span>{{ $admin->passcode_reset_date ? $admin->passcode_reset_date->format('d/m/Y H:i') : 'Jamais' }}</span>
                </div>
            </div>
        </div>

        <div class="section-title">Rôles et Permissions Attribués</div>
        @if ($admin->roles->isNotEmpty())
            <ul class="roles-list">
                @foreach ($admin->roles as $role)
                    <li>
                        <span>{{ $role->name }}</span>
                        @if ($role->permissions->isNotEmpty())
                            <ul class="permissions-list">
                                @foreach ($role->permissions->sortBy('name') as $permission)
                                    <li>- {{ $permission->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p style="margin-left: 20px; font-size: 0.85em; color: #95a5a6;">Aucune permission associée à ce rôle.</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p style="color: #95a5a6;">Aucun rôle attribué.</p>
        @endif

        <div class="page-break"></div>

        {{-- Contenu pour la deuxième page --}}
        <div class="section-title" style="margin-top: 0;">Pièces d'Identité</div>
        <div class="id-images-wrapper">
            <div class="id-image-container">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #34495e;">Recto:</label>
                @if ($pieceIdentiteRectoPath)
                    <img src="data:image/jpeg;base64,{{ base64_encode(@file_get_contents($pieceIdentiteRectoPath)) }}" alt="Pièce d'Identité Recto">
                @else
                    <p style="font-size: 0.85em; color: #7f8c8d;">Aucun Recto disponible.</p>
                @endif
            </div>
            <div class="id-image-container">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; color: #34495e;">Verso:</label>
                @if ($pieceIdentiteVersoPath)
                    <img src="data:image/jpeg;base64,{{ base64_encode(@file_get_contents($pieceIdentiteVersoPath)) }}" alt="Pièce d'Identité Verso">
                @else
                    <p style="font-size: 0.85em; color: #7f8c8d;">Aucun Verso disponible.</p>
                @endif
            </div>
        </div>

        <div class="qr-code-container">
            <label style="font-weight: bold; display: block; margin-bottom: 12px; font-size: 1em; color: #34495e;">Code QR du Matricule:</label>
            <img src="{{ $qrCodeBase64 }}" alt="Code QR du Matricule">
            <p style="margin-top: 10px; font-size: 0.9em; color: #7f8c8d;">Matricule: {{ $admin->matricule }}</p>
        </div>

    @else
        <p style="text-align: center; color: #95a5a6;">Les informations de l'administrateur ne sont pas disponibles.</p>
    @endif
</div>
</body>
</html>
