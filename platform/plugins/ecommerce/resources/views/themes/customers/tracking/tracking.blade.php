@extends(EcommerceHelper::viewPath('customers.layouts.account-settings'))

@section('title', __('Go Tracking'))

@section('account-content')
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tracking Number</th>
                <th>Tracking Status</th>
                <th>Workflow Status</th>
                <th>Carrier Name</th>
                <th>Order ID</th>
                <th>Quotation ID</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $tracking)
                <tr>
                    <td>{{ $tracking['tracking_number'] }}</td>
                    <td>{{ $tracking['tracking_status'] ?? 'N/A' }}</td>
                    <td>{{ $tracking['workflow_status'] ?? 'N/A' }}</td>
                    <td>{{ $tracking['carrier_name'] ?? 'N/A' }}</td>
                    <td>{{ $tracking['order_id'] ?? 'N/A' }}</td>
                    <td>{{ $tracking['quotation_id'] ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No tracking data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@stop
