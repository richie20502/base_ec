@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <h1>Registros de Tracking Pendientes</h1>

    @if ($dataSky->isEmpty())
        <p>No hay datos que coincidan con la búsqueda.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer ID</th>
                    <th>Tracking Number</th>
                    <th>Carrier Name</th>
                    <th>Order ID</th>
                    <th>Quotation ID</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataSky as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->customer_id }}</td>
                        <td>{{ $item->tracking_number }}</td>
                        <td>{{ $item->carrier_name }}</td>
                        <td>{{ $item->order_id }}</td>
                        <td>{{ $item->quotation_id }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-generar-orden"
                                data-tracking-id="{{ $item->id }}" data-order-id="{{ $item->order_id }}">
                                Generar Orden
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- INICIO Modal -->
    <div class="modal fade" id="modalGenerarOrden" tabindex="-1" aria-labelledby="modalGenerarOrdenLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Cambia modal-lg si necesitas un modal más grande -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGenerarOrdenLabel">Generar Orden de Envío</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Contenido del modal -->
                    <div id="preloader" class="text-center my-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Generando orden, por favor espera...</p>
                    </div>
                    <p id="modalMessage"></p>
                    <input type="hidden" id="trackingIdInput">
                    <input type="hidden" id="orderIdInput">
                    <div id="rateTableContainer" style="display: none;">
                        <h5 class="mt-3">Opciones de tarifas disponibles:</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Proveedor</th>
                                    <th>Proveedor</th>
                                    <th>Servicio</th>
                                    <th>Total</th>
                                    <th>Días</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="rateTableBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="confirmarOrdenBtn" class="btn btn-primary">Generar Orden</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN Modal -->
    <style>
        table.table-striped {
            border-collapse: collapse;
            width: 100%;
        }

        table.table-striped th,
        table.table-striped td {
            text-align: center;
            /* Centra los textos y las imágenes */
            vertical-align: middle;
            /* Alinea verticalmente el contenido */
            padding: 10px;
            /* Agrega espaciado */
        }

        table.table-striped th {
            background-color: #f1f1f1;
            /* Fondo claro para encabezados */
        }

        table.table-striped td img {
            display: block;
            margin: 0 auto;
            /* Centra la imagen horizontalmente */
            max-width: 50px;
            /* Limita el tamaño de las imágenes */
            height: auto;
            /* Mantiene la proporción */
        }

        .modal .table-striped {
            margin-top: 20px;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Abrir el modal al hacer clic en el botón
            $('.btn-generar-orden').on('click', function() {
                const trackingId = $(this).data('tracking-id');
                const orderId = $(this).data('order-id');

                $('#modalMessage').text(
                    `¿Estás seguro de que deseas generar una orden para el Order ID: ${orderId}?`);
                $('#trackingIdInput').val(trackingId);
                $('#orderIdInput').val(orderId);

                const modal = new bootstrap.Modal(document.getElementById('modalGenerarOrden'));
                modal.show();

                $('#preloader').hide();
                $('#rateTableBody').empty();
                $('#rateTableContainer').hide();
                $('#confirmarOrdenBtn').show();
            });

            // Confirmar y enviar la solicitud AJAX desde el modal
            $('#confirmarOrdenBtn').on('click', function() {
                const trackingId = $('#trackingIdInput').val();
                const orderId = $('#orderIdInput').val();

                $('#preloader').show();
                $('#confirmarOrdenBtn').hide();

                $.ajax({
                    url: "{{ route('quotation.admin') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tracking_id: trackingId,
                        order_id: orderId
                    },
                    success: function(response) {
                        $('#preloader').hide();
                        console.log(response.data);

                        if (response.data.rates && Array.isArray(response.data.rates)) {
                            const rates = response.data.rates;
                            let tableRows = '';

                            rates.forEach((rate, index) => {
                                // Determinar la imagen según el nombre del proveedor
                                const providerImage = rate.provider_name
                                .toLowerCase() === 'fedex' ?
                                    `<img src="/themes/farmart/images/rates/fedex.png" alt="FedEx">` :
                                    rate.provider_name.toLowerCase() === 'dhl' ?
                                    `<img src="/themes/farmart/images/rates/dhl.png" alt="DHL">` :
                                    rate.provider_name.toLowerCase() ===
                                    'paquetexpress' ?
                                    `<img src="/themes/farmart/images/rates/paquetexpress.png" alt="Paquetexpress">` :
                                    rate.provider_name.toLowerCase() === 'sendex' ?
                                    `<img src="/themes/farmart/images/rates/sendex.png" alt="Sendex">` :
                                    'N/A';

                                // Generar las filas de la tabla
                                tableRows += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${providerImage}</td>
                                        <td>${rate.provider_name || 'N/A'}</td>
                                        <td>${rate.provider_service_name || 'N/A'}</td>
                                        <td>$${rate.total}</td>
                                        <td>${rate.days || 'N/A'}</td>
                                        <td>
                                            <button class="btn btn-primary generateOrderBtn"
                                                    data-rate-id="${rate.id}"
                                                    data-provider-name="${rate.provider_name}"
                                                    data-total="${rate.total}"
                                                    data-quotation-id="${response.data.id}"
                                                    data-tracking-id="${trackingId}"
                                                    data-order-id="${orderId}" >
                                                Generar Orden
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });



                            $('#rateTableBody').html(tableRows);
                            $('#rateTableContainer').show();
                        } else {
                            alert('No se encontraron tarifas en la respuesta.');
                        }
                    },
                    error: function() {
                        $('#preloader').hide();
                        alert('Hubo un error al generar la orden.');
                        $('#confirmarOrdenBtn').show();
                    }
                });
            });

            $(document).on('click', '.generateOrderBtn', function() {
                const rateId = $(this).data('rate-id');
                const providerName = $(this).data('provider-name');
                const total = $(this).data('total');
                const quotationId = $(this).data('quotation-id');
                const trackingId = $(this).data('tracking-id');
                const orderId = $(this).data('order-id');

                const modal = bootstrap.Modal.getInstance(document.getElementById('modalGenerarOrden'));

                $('#preloader').show();

                $.ajax({
                    url: "{{ route('tracking.admin') }}", // Asegúrate de que esta ruta sea la correcta
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        rate_id: rateId,
                        provider_name: providerName,
                        total: total,
                        quotation_id: quotationId,
                        tracking_id: trackingId,
                        order_id: orderId
                    },
                    success: function(response) {
                        $('#preloader').hide();
                        alert('Orden generada con éxito: ' + response.message);
                        modal.hide();
                    },
                    error: function() {
                        $('#preloader').hide();
                        alert('Hubo un error al generar la orden.');
                    }
                });
            });
            /*fin*/
        });
    </script>
@endsection
