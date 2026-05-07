<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Document HealthSys')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
            background: white;
        }
        .print-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1a5f7a;
        }
        .header h1 { color: #1a5f7a; font-size: 24px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 12px; }
        .info-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .info-box h3 {
            color: #1a5f7a;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th { background: #f5f5f5; }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 30px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
    @stack('print-styles')
</head>
<body>
    <div class="print-container">
        @yield('print-content')
        <div class="footer">
            <p>HealthSys - Cabinet médical</p>
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>