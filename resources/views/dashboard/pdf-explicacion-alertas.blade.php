<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explicación de Alertas CRED</title>
    <style>
        @media print {
            @page {
                margin: 1cm;
                size: A4;
            }
            .no-print {
                display: none !important;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            body {
                margin: 0;
                padding: 20px;
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .container {
                background: white !important;
                padding: 40px !important;
                box-shadow: none;
                max-width: 100%;
                margin: 0 auto;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .header {
                page-break-after: avoid;
                border-bottom: 3px solid #667eea !important;
                padding-bottom: 20px !important;
                margin-bottom: 30px !important;
            }
            .header h1 {
                color: #667eea !important;
                font-size: 28px !important;
            }
            .header p {
                color: #666 !important;
            }
            .section {
                page-break-inside: avoid;
                margin-bottom: 35px !important;
            }
            .section-title {
                page-break-after: avoid;
                background: linear-gradient(to right, #667eea, #764ba2) !important;
                color: white !important;
                padding: 12px 20px !important;
                border-radius: 6px !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .section-content {
                padding: 0 10px !important;
            }
            .como h3 {
                color: #667eea !important;
            }
            .campos-list, .rangos-list {
                background: #f8f9fa !important;
                padding: 15px !important;
                border-radius: 6px !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .footer {
                border-top: 2px solid #e0e0e0 !important;
                color: #666 !important;
            }
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #667eea;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 10px 0 0 0;
        }
        .section {
            margin-bottom: 35px;
            page-break-inside: avoid;
        }
        .section-title {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .section-content {
            padding: 0 10px;
        }
        .por-que, .como {
            margin-bottom: 15px;
        }
        .por-que h3, .como h3 {
            color: #667eea;
            font-size: 16px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        .por-que h3::before {
            content: "❓";
            margin-right: 8px;
        }
        .como h3::before {
            content: "⚙️";
            margin-right: 8px;
        }
        .por-que p, .como p {
            margin: 0;
            text-align: justify;
        }
        .campos-list, .rangos-list {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
        }
        .campos-list ul, .rangos-list ul {
            margin: 0;
            padding-left: 25px;
        }
        .campos-list li, .rangos-list li {
            margin-bottom: 5px;
        }
        .rangos-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .rangos-table th {
            background: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .rangos-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        .rangos-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        @media print {
            .rangos-table th {
                background: #667eea !important;
                color: white !important;
            }
            .rangos-table tr:nth-child(even) {
                background: #f8f9fa !important;
            }
            .rangos-table td {
                border-bottom: 1px solid #e0e0e0 !important;
            }
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            color: #666;
            font-size: 12px;
        }
        .btn-print {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin: 20px auto;
            display: block;
            transition: all 0.3s;
        }
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .intro {
            background: #e8f4f8;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #667eea;
            margin-bottom: 30px;
        }
        .intro h2 {
            color: #667eea;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Guía de Alertas CRED</h1>
            <p>Sistema de Seguimiento de Controles de Crecimiento y Desarrollo</p>
            <p style="font-size: 14px; margin-top: 5px;">Generado el: {{ $fechaGeneracion }}</p>
        </div>

        @foreach($informacionAlertas as $index => $alerta)
        <div class="section">
            <div class="section-title">
                {{ $index + 1 }}. {{ $alerta['nombre'] }}
            </div>
            <div class="section-content">
                <div class="como">
                    <h3>¿Cómo se genera la alerta?</h3>
                    <p>{{ $alerta['como'] }}</p>
                </div>
                @if(isset($alerta['campos']))
                <div class="campos-list">
                    <strong>Campos verificados:</strong>
                    <ul>
                        @foreach($alerta['campos'] as $campo)
                        <li>{{ $campo }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(isset($alerta['rangos']))
                <div class="rangos-list">
                    <strong>Rangos establecidos:</strong>
                    <table class="rangos-table">
                        <thead>
                            <tr>
                                <th>Control/Item</th>
                                <th>Rango de Días</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alerta['rangos'] as $nombre => $rango)
                            <tr>
                                <td><strong>{{ $nombre }}</strong></td>
                                <td>{{ $rango }} días</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
        @endforeach

        <div class="footer">
            <p>Este documento explica el funcionamiento del sistema de alertas CRED.</p>
            <p>Para más información, consulte con el administrador del sistema.</p>
        </div>
    </div>

    <button class="btn-print no-print" onclick="window.print()">🖨️ Imprimir / Guardar como PDF</button>

    <script>
        // Auto-imprimir si se accede desde el botón de descarga
        if (window.location.search.includes('print=true')) {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        }
    </script>
</body>
</html>

