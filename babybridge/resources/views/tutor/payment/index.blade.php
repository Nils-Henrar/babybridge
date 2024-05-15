<!-- resources/views/tutor/payments/index.blade.php -->
@extends ('layouts.app')

@section('subtitle', 'Payments')

@section('content_header_title', 'Pending Payments')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @foreach ($payments as $payment)
        @if ($payment)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Child</th>
                        <th>Amount</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $payment->event->title }}</td>
                        <td>{{ $payment->childTutor->child->firstname }}</td>
                        <td>{{ $payment->amount }}</td>
                        <td>{{ $payment->currency }}</td>
                        <td>{{ $payment->status }}</td>
                        <td>
                            <form action="{{ route('tutor.payment.pay', $payment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Pay</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
        @else
        <div class="alert alert-info">No pending payments</div>
        @endif
        @endforeach
    </div>
</div>
@endsection