<?php

namespace App\Billing;

use App\Spark;
use Laravel\Cashier\Invoice;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Contracts\Billing\InvoiceNotifier;

class EmailInvoiceNotifier implements InvoiceNotifier
{
    /**
     * Notify the given user about a new invoice.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Laravel\Cashier\Invoice  $invoice
     * @return void
     */
    public function notify(Authenticatable $user, Invoice $invoice)
    {
        $invoiceData = array_merge([
            'vendor' => 'Vendor',
            'product' => 'Product',
            'vat' => new HtmlString(nl2br(e($user->extra_billing_info))),
        ], Spark::generateInvoicesWith());

        $data = compact('user', 'invoice', 'invoiceData');

        Mail::send('emails.billing.invoice', $data, function ($message) use ($user, $invoice, $invoiceData) {
            $message->to($user->email, $user->name)
                    ->subject('Your '.$invoiceData['product'].' Invoice')
                    ->attachData($invoice->pdf($invoiceData), 'invoice.pdf');
        });
    }
}
