<?php

namespace App\Contracts\Billing;

use Laravel\Cashier\Invoice;
use Illuminate\Contracts\Auth\Authenticatable;

interface InvoiceNotifier
{
    /**
     * Notify the given user about a new invoice.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Laravel\Cashier\Invoice  $invoice
     * @return void
     */
    public function notify(Authenticatable $user, Invoice $invoice);
}
