<table class="table">
    <thead>
    <tr>
        <th scope="col" style="font-weight: bold;">Id</th>
        <th scope="col" style="font-weight: bold;">Leasing subject</th>
        <th scope="col" style="font-weight: bold;">Contract cost</th>
        <th scope="col" style="font-weight: bold;">Payment amount</th>
        <th scope="col" style="font-weight: bold;">Total amount</th>
        <th scope="col" style="font-weight: bold;">Validity start</th>
        <th scope="col" style="font-weight: bold;">Validity end</th>
    </tr>
    </thead>
    <tbody>
    @foreach($agreements as $agreement)
        <tr>
            <th
                scope="row"
                style="font-weight: bold;"
            >
                {{ $agreement->id }}
            </th>
            <th scope="row">{{ $agreement->leasing_subject }}</th>
            <th scope="row">{{ $agreement->contract_cost }}</th>
            <th
                scope="row"
                style="
                background-color: #66FF66;
                border: 1px solid #000000;"
            >
                {{ $agreement->payment_amount }}
            </th>
            <th scope="row">{{ $agreement->total_amount }}</th>
            <th scope="row">{{ $agreement->validity_start }}</th>
            <th
                scope="row"
                style="
                background-color: red;
                border: 1px solid #000000;"
            >
                {{ $agreement->validity_end }}
            </th>

            @if (count($agreement->payments) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <td style="font-weight: bold;">Id</td>
                            <td style="font-weight: bold;">Settlement month</td>
                            <td style="font-weight: bold;">Redemption payment</td>
                            <td style="font-weight: bold;">Advance payment amount</td>
                            <td style="font-weight: bold;">Total amount</td>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($agreement->payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->settlement_month }}</td>
                            <td>{{ $payment->redemption_payment }}</td>
                            <td>{{ $payment->advance_payment_amount }}</td>
                            <td
                                style="
                                background-color: #FFFF00;
                                border: 1px solid #000000;"
                            >
                                {{ $payment->total_amount }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
