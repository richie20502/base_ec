<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Orden</title>
</head>
<body>
    <h1>Orden de Envío Generada</h1>
    <p>Hola,</p>
    <p>Se ha generado una nueva orden de envío con los siguientes detalles:</p>
    <ul>
        <li><strong>ID de la Orden:</strong> {{ $orderDetails['order_id'] }}</li>
        <li><strong>Proveedor:</strong> {{ $orderDetails['provider_name'] }}</li>
        <li><strong>Monto Total:</strong> ${{ $orderDetails['total'] }}</li>
        <li><strong>Días Estimados:</strong> {{ $orderDetails['days'] }}</li>
    </ul>
    <p>Gracias por utilizar nuestro servicio.</p>
</body>
</html>
