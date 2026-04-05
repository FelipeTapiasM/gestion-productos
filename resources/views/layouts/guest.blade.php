<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GestionTienda') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }
        .card-header {
            background: #1e3a5f;
            padding: 32px 36px 28px;
            text-align: center;
        }
        .logo-icon {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }
        .card-header h1 { color: #fff; font-size: 20px; font-weight: 600; }
        .card-header p  { color: rgba(255,255,255,0.6); font-size: 13px; margin-top: 4px; }
        .card-body { padding: 32px 36px 36px; }
        .field { margin-bottom: 20px; }
        .field label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .field input {
            width: 100%; height: 42px;
            border: 1.5px solid #d1d5db; border-radius: 8px;
            padding: 0 14px; font-size: 14px; color: #111827;
            background: #f9fafb; outline: none;
            transition: border-color 0.15s;
        }
        .field input:focus { border-color: #1e3a5f; background: #fff; }
        .field-row {
            display: flex; align-items: center;
            justify-content: space-between; margin-bottom: 24px;
        }
        .check-label {
            display: flex; align-items: center; gap: 7px;
            font-size: 13px; color: #6b7280; cursor: pointer;
        }
        .check-label input { accent-color: #1e3a5f; }
        .forgot { font-size: 13px; color: #1e3a5f; font-weight: 500; text-decoration: none; }
        .forgot:hover { text-decoration: underline; }
        .btn-login {
            width: 100%; height: 44px;
            background: #1e3a5f; color: #fff;
            border: none; border-radius: 8px;
            font-size: 15px; font-weight: 600; cursor: pointer;
            transition: background 0.15s;
        }
        .btn-login:hover { background: #16304f; }
        .error-msg {
            background: #fef2f2; border: 1px solid #fecaca;
            color: #b91c1c; border-radius: 8px;
            padding: 10px 14px; font-size: 13px;
            margin-bottom: 18px;
        }
        .status-msg {
            background: #f0fdf4; border: 1px solid #86efac;
            color: #15803d; border-radius: 8px;
            padding: 10px 14px; font-size: 13px;
            margin-bottom: 18px;
        }
        .footer-text {
            text-align: center; font-size: 12px; color: #9ca3af;
            margin-top: 20px; padding-top: 20px;
            border-top: 1px solid #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="logo-icon">
                <svg width="28" height="28" fill="none" viewBox="0 0 24 24">
                    <rect x="2" y="2" width="9" height="9" rx="2" fill="white" opacity="0.9"/>
                    <rect x="13" y="2" width="9" height="9" rx="2" fill="white" opacity="0.5"/>
                    <rect x="2" y="13" width="9" height="9" rx="2" fill="white" opacity="0.5"/>
                    <rect x="13" y="13" width="9" height="9" rx="2" fill="white" opacity="0.9"/>
                </svg>
            </div>
            <h1>GestionTienda</h1>
            <p>Ingresa tus credenciales para continuar</p>
        </div>
        <div class="card-body">
            {{ $slot }}
            <div class="footer-text">
                GestionTienda v1.0 &nbsp;·&nbsp; Solo acceso autorizado
            </div>
        </div>
    </div>
</body>
</html>