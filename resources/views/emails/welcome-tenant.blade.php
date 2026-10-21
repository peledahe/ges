<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a GES Taller</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.08); border: 1px solid #e5e7eb;">
                    <!-- Cabecera con degradado -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 36px 30px; text-align: center; color: #ffffff;">
                            <div style="display: inline-block; width: 54px; height: 54px; line-height: 54px; border-radius: 16px; background-color: rgba(255,255,255,0.18); font-size: 26px; margin-bottom: 12px; border: 1px solid rgba(255,255,255,0.25);">
                                ⚡
                            </div>
                            <h1 style="margin: 0; font-size: 26px; font-weight: 900; letter-spacing: -0.5px; color: #ffffff;">
                                GES Taller
                            </h1>
                            <p style="margin: 6px 0 0; font-size: 13px; text-transform: uppercase; letter-spacing: 2px; color: #c7d2fe; font-weight: 700;">
                                Gestión Automotriz Profesional
                            </p>
                        </td>
                    </tr>

                    <!-- Insignia de Prueba Gratuita -->
                    <tr>
                        <td style="padding: 0 30px;">
                            <div style="margin-top: -18px; background: linear-gradient(90deg, #10b981 0%, #059669 100%); color: #ffffff; padding: 10px 18px; border-radius: 30px; text-align: center; font-size: 13px; font-weight: 800; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);">
                                🎉 ¡Tu prueba gratuita de 15 días ha comenzado!
                            </div>
                        </td>
                    </tr>

                    <!-- Contenido principal -->
                    <tr>
                        <td style="padding: 30px 32px 20px;">
                            <h2 style="margin: 0 0 12px; font-size: 20px; font-weight: 800; color: #111827;">
                                ¡Hola, {{ $user->name }}! 👋
                            </h2>
                            <p style="margin: 0 0 18px; font-size: 15px; line-height: 1.6; color: #4b5563;">
                                Te damos la más cordial bienvenida a <strong>GES Taller</strong>. Tu nueva cuenta y el espacio de trabajo para tu taller <strong>{{ $tenant->name }}</strong> están completamente listos para que comiences a optimizar tu operación hoy mismo.
                            </p>

                            <!-- Tarjeta de Credenciales de Acceso -->
                            <div style="background-color: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 14px; padding: 20px; margin-bottom: 24px;">
                                <h3 style="margin: 0 0 12px; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #166534; font-weight: 800;">
                                    🔑 Credenciales Registradas de Inicio de Sesión
                                </h3>
                                <table width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
                                    <tr>
                                        <td style="padding: 6px 0; color: #475569; width: 140px;">Usuario / Correo:</td>
                                        <td style="padding: 6px 0; font-weight: 700; color: #0f172a; font-family: monospace; font-size: 15px;">{{ $user->email }}</td>
                                    </tr>
                                    @if (!empty($password))
                                    <tr>
                                        <td style="padding: 6px 0; color: #475569;">Contraseña:</td>
                                        <td style="padding: 6px 0; font-weight: 700; color: #0f172a; font-family: monospace; font-size: 15px;">
                                            <span style="background-color: #ffffff; padding: 4px 10px; border-radius: 6px; border: 1px solid #cbd5e1; display: inline-block;">{{ $password }}</span>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="padding: 6px 0; color: #475569;">Página de ingreso:</td>
                                        <td style="padding: 6px 0; font-weight: 600; color: #4f46e5;">
                                            <a href="{{ $loginUrl }}" style="color: #4f46e5; text-decoration: underline;">{{ $loginUrl }}</a>
                                        </td>
                                    </tr>
                                </table>
                                <p style="margin: 12px 0 0; font-size: 12px; color: #15803d; line-height: 1.4;">
                                    🛡️ <em>Conserva este correo en un lugar seguro. Podrás modificar tu contraseña cuando desees desde tu perfil.</em>
                                </p>
                            </div>

                            <!-- Tarjeta de Datos de la Cuenta -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 24px;">
                                <h3 style="margin: 0 0 14px; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 800;">
                                    📌 Resumen de tu nuevo espacio:
                                </h3>
                                <table width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b; width: 140px;">Empresa / Taller:</td>
                                        <td style="padding: 6px 0; font-weight: 700; color: #0f172a;">{{ $tenant->name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b;">Administrador:</td>
                                        <td style="padding: 6px 0; font-weight: 700; color: #0f172a;">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b;">Rol en sistema:</td>
                                        <td style="padding: 6px 0; font-weight: 700; color: #059669;">Administrador General</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b;">Vigencia prueba:</td>
                                        <td style="padding: 6px 0; font-weight: 700; color: #d97706;">
                                            15 días (hasta el {{ $tenant->trial_ends_at ? $tenant->trial_ends_at->format('d/m/Y') : now()->addDays(15)->format('d/m/Y') }})
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Botón Principal -->
                            <div style="text-align: center; margin: 30px 0 24px;">
                                <a href="{{ $loginUrl }}" style="display: inline-block; background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color: #ffffff; text-decoration: none; padding: 14px 34px; border-radius: 12px; font-size: 15px; font-weight: 800; box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4); text-transform: uppercase; letter-spacing: 0.5px;">
                                    🚀 Ingresar a Mi Taller
                                </a>
                            </div>

                            <!-- Características pre-configuradas -->
                            <div style="border-top: 1px dashed #e2e8f0; padding-top: 20px; margin-top: 20px;">
                                <h4 style="margin: 0 0 10px; font-size: 13px; font-weight: 800; color: #334155;">
                                    Tu taller ya incluye configurado:
                                </h4>
                                <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #475569; line-height: 1.8;">
                                    <li><strong>Tablero Kanban por Áreas:</strong> Recepción, Presupuestos, Mecánica, Pintura, Calidad y Entrega.</li>
                                    <li><strong>Recepción Digital:</strong> Inventario, checklist interactivo y diagramas de carrocería para marcar daños.</li>
                                    <li><strong>Cotizaciones y Pagos:</strong> Cotizador ágil, cobros parciales y liquidación.</li>
                                    <li><strong>Página Pública de Rastreo para tus Clientes:</strong>
                                        <a href="{{ $trackingUrl }}" style="color: #4f46e5; text-decoration: underline;">
                                            {{ $trackingUrl }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Pie de página -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 22px 30px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8;">
                            <p style="margin: 0 0 6px;">
                                Este correo fue enviado automáticamente al registrar tu taller en <strong>GES Taller</strong>.
                            </p>
                            <p style="margin: 0;">
                                Si tienes alguna duda o necesitas asistencia durante tu prueba, contáctanos respondiendo a este mensaje.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
