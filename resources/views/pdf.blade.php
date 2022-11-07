<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<style>
    #watermark {
        position: fixed;
        top:   1100px;
        left:     30px;
        /** The width and height may change
            according to the dimensions of your letterhead
        **/
        width:    21.8cm;
        height:   28cm;

        /** Your watermark should be behind every content**/
        z-index:  -1000;
    }

    #header {
        position: fixed;
        left: 0px;
        top: 0px;
        right: 0px;
        width: 100%;
        background-color: blue;
    }

    @page {
        margin: 0px;
        padding: 0px;
    }

</style>

<div id="header">
    <h1 class="ms-3">Agreements</h1>
</div>
<body>
    <div id="watermark">
        <img src="{{ storage_path('app/public/images/Logo_S_stroked.png') }}" height="20%" width="20%" />
    </div>
    <table class="table mt-5 ms-3 w-75">
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
                <td>
                    {{ $agreement->id }}
                </td>
                <td>{{ $agreement->leasing_subject }}</td>
                <td>{{ $agreement->contract_cost }}</td>
                <td>
                    {{ $agreement->payment_amount }}
                </td>
                <td>{{ $agreement->total_amount }}</td>
                <td>{{ $agreement->validity_start }}</td>
                <td>
                    {{ $agreement->validity_end }}
                </td>
            @if (count($agreement->payments) > 0)

                    <td>
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
                                    <td>
                                        {{ $payment->total_amount }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </td>
            @endif
            </tr>

        @endforeach
        </tbody>
    </table>
</body>
