@extends(EcommerceHelper::viewPath('customers.layouts.account-settings'))

@section('title', __('Go Tracking'))

@section('account-content')
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Orden</th>
                <th>Carrier Name</th>
                <th>Tracking Status</th>
                <th>Status</th>
                <th>Link</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $tracking)
                <tr>
                    <td>{{ $tracking['order_code'] }}</td>
                    <td>{{ $tracking['carrier'] }}</td>
                    <td>{{ $tracking['tracking_status'] ?? 'N/A' }}</td>
                    <td>{{ $tracking['workflow_status'] ?? 'N/A' }}</td>
                    <td>
                        @if(!empty($tracking['url_tracking']))
                            <a href="{{ $tracking['url_tracking'] }}" class="btn btn-primary btn-sm" target="_blank">
                                Open Link
                            </a>
                        @else
                            N/A
                        @endif
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No tracking data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@stop
