<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activez votre compte ImmoSyn</title>
    <style>
        body {
            font-family: 'Outfit', 'Plus Jakarta Sans', 'Segoe UI', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .wrapper {
            background-color: #f8fafc;
            padding: 40px 20px;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 1px rgba(0, 0, 0, 0.01);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #3b66f5 0%, #7694f8 100%);
            padding: 35px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 16px;
        }
        .text {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
        }
        .cta-container {
            text-align: center;
            margin: 32px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: #3b66f5;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            padding: 14px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(59, 102, 245, 0.2), 0 2px 4px -1px rgba(59, 102, 245, 0.1);
            transition: all 0.2s ease-in-out;
        }
        .footer {
            padding: 24px 40px;
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            line-height: 1.5;
            color: #64748b;
            margin: 0 0 8px 0;
        }
        .footer a {
            color: #3b66f5;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 24px 0;
        }
        .fallback-link {
            font-size: 12px;
            color: #64748b;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header Banner -->
            <div class="header">
                <h1>ImmoSyn</h1>
            </div>
            
            <!-- Main Content -->
            <div class="content">
                <p class="greeting">Bonjour {{ $user->prenom }} {{ $user->nom }},</p>
                <p class="text">
                    Bienvenue sur <strong>ImmoSyn</strong> ! Votre inscription a été enregistrée avec succès. 
                    Pour finaliser la création de votre compte et activer tous vos accès en toute sécurité, veuillez valider votre adresse email en cliquant sur le bouton ci-dessous :
                </p>
                
                <div class="cta-container">
                    <a href="{{ $url }}" class="cta-button">Activer mon compte</a>
                </div>
                
                <p class="text">
                    Ce lien de validation est valable pendant <strong>60 minutes</strong>. Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité.
                </p>
                
                <div class="divider"></div>
                
                <p class="text" style="font-size: 13px; color: #64748b; margin-bottom: 0;">
                    Si le bouton ne fonctionne pas, copiez et collez l'adresse suivante dans votre navigateur internet :<br>
                    <span class="fallback-link"><a href="{{ $url }}">{{ $url }}</a></span>
                </p>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p>Besoin d'aide ? Notre support est à votre disposition.</p>
                <p>&copy; 2026 ImmoSyn. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</body>
</html>
