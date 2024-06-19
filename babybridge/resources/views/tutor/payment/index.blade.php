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
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th>Événement</th>
                    <th>Enfant</th>
                    <th>Montant</th>
                    <th>Devise</th>
                    <th>Status</th>
                </tr>
                </thead>
                @foreach ($payments as $payment)
                    @if ($payment)
                        <tbody>
                            <tr>
                                <td>{{ $payment->event->title }}</td>
                                <td>{{ $payment->childTutor->child->firstname }}</td>
                                <td>{{ $payment->amount }}</td>
                                <td>{{ $payment->currency }}</td>
                                <td>{{ $payment->status  ? 'En attente' : 'Payé' }}</td>
                                <td style="text-align: center;">
                                    <form action="{{ route('tutor.payment.pay', $payment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-info">Aller vers le payement</button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    @else
                        <div class="alert alert-info">No pending payments</div>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection