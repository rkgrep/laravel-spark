<div class="panel panel-default">
    <div class="panel-heading">
        Invoice History
    </div>

    <div class="panel-body">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th class="text-right">Receipt</th>
            </tr>
            </thead>

            <tbody class="no-border-y">
            @foreach ($invoices as $invoice)
                <tr>
                    <td>
                        <strong>{{ $invoice->date()->format('Y-m-d') }}</strong>
                    </td>
                    <td>
                        {{ $invoice->total() }}
                    </td>
                    <td class="text-right">
                        <a href="{{ url('settings/user/plan/invoice/'.$invoice->id) }}">
                            Download
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
