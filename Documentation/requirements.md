# Requerimientos del Sistema CRM para Talleres Mecánicos

## Visión General

Desarrollar un sistema tipo CRM para la gestión de múltiples talleres mecánicos (Multi-tenancy). El sistema permitirá gestionar la recepción de vehículos, seguimiento de reparaciones a través de distintas áreas (mecánica, pintura, etc.), autorizaciones de aseguradoras, repuestos y comunicación con el cliente.

## Funcionalidades Principales

### 1. Multi-tenancy (Gestión de Múltiples Empresas)

-   **Tabla de Talleres**: Debe existir un registro de empresas/talleres.
-   **Datos del Taller**: Nombre, Administrador, Correo, Usuario, Teléfono, Dirección, Cantidad de Usuarios permitidos.
-   **Aislamiento de Datos**: Toda la información debe estar vinculada a un taller específico. Las empresas comparten la aplicación pero no ven los datos de las otras.

### 2. Catálogos y Áreas

-   **Áreas de Trabajo**: Catálogo de áreas (Administración, Recepción, Enderezado y Pintura, Mecánica Gasolina, Diesel, Electromecánica, etc.).
-   **Personalización**: Posibilidad de agregar nuevas áreas.
-   **Encargados**: Cada área tiene un responsable que recibe y da seguimiento.

### 3. Flujo de Trabajo y Recepción

-   **Recepción de Vehículo**:
    -   Formulario con características del vehículo.
    -   Checklist del estado físico y documentación (Tarjeta de circulación, calcomanía).
    -   **Fotografías**: Captura de fotos de daños.
    -   **Seguros**: Indicar si es trabajo de aseguradora o particular.
-   **Diagnóstico y Presupuesto**:
    -   Listado de reparaciones necesarias.
    -   Listado de repuestos requeridos.
-   **Autorizaciones (Aseguradoras/Clientes)**:
    -   Área administrativa tramita autorizaciones.
    -   **Alertas**: Sistema de alarmas cuando se autoriza un trabajo para contactar al cliente.

### 4. Gestión de Compras y Repuestos

-   Módulo para cotizar y adquirir repuestos basado en el diagnóstico.
-   Entrega de repuestos al área correspondiente para continuar la reparación.

### 5. Seguimiento y Control

-   **Estatus del Trabajo**: Gerencia debe poder ver el estatus global.
-   **Tracking para Clientes**: Los clientes pueden consultar el estado de su vehículo mediante el número de placa (sin necesidad de login complejo).
-   **Notificaciones**: Envío de correos electrónicos en cambios de estado clave y finalización.

## Requerimientos Técnicos

-   **Framework Backend**: Laravel 12 (PHP).
-   **Base de Datos**: MySQL.
-   **Servidor**: Linux con Nginx.
-   **Frontend**: Tailwind CSS.
-   **Entorno JS**: Node.js.
-   **Seguridad**: Autenticación de Doble Factor (2FA).
-   **Infraestructura**: Todo instalado excepto la aplicación Laravel.
-   **Credenciales Desarrollo**: User: `perry`, Pass: `password`.

## Entregables

1.  Análisis y Diseño Conceptual.
2.  Planificación del Desarrollo.
3.  Código Fuente (Laravel App).
4.  SSH Key para despliegue (`taller_crm_deploy_key`).
5.  Documentación en carpeta `Documentation`.
