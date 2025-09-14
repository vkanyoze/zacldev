<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-custom-gray">
                    Payment Details
                </h2>
                <div class="text-lg mt-2 text-custom-gray">View payment transaction details</div>
            </div>
            <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors duration-200 flex items-center no-print">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Payments
            </a>
        </div>
    </x-slot>

    <div class="py-4 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Payment Overview Card -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Payment Overview</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $payment->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Reference</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $payment->invoice_reference ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Amount</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-semibold">
                                        {{ number_format($payment->amount_spend, 2) }} {{ $payment->currency ?? 'USD' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        @if($payment->status === 'completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @elseif($payment->status === 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $payment->created_at->format('M d, Y H:i:s') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $payment->description ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Information Card -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">User Information</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">User ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $payment->user_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $payment->user->email ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">User Status</dt>
                            <dd class="mt-1">
                                @if($payment->user && $payment->user->email_verified_at)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Verified
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Unverified
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $payment->user->created_at->format('M d, Y') ?? 'N/A' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Card Information Card -->
            @if($payment->card)
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Card Information</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Card Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">
                                **** **** **** {{ substr($payment->card->card_number, -4) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Card Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $payment->card->type_of_card ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Cardholder Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $payment->card->name . ' ' . $payment->card->surname ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Card Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            @endif

            <!-- Payment Actions -->
            <div class="bg-white rounded-lg shadow overflow-hidden no-print">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flex flex-wrap gap-3">
                        @if($payment->status === 'completed')
                        <form action="{{ route('admin.payments.refund', $payment) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors duration-200 flex items-center"
                                    onclick="return confirm('Are you sure you want to refund this payment?')">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                                Refund Payment
                            </button>
                        </form>
                        @endif
                        
                        <button onclick="printInvoice()" 
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Invoice
                        </button>
                        
                        <a href="{{ route('admin.payments.export') }}?payment_id={{ $payment->id }}" 
                           class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .printable-invoice, .printable-invoice * {
                visibility: visible;
            }
            .printable-invoice {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white;
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
            .invoice-header {
                border-bottom: 2px solid #333;
                padding-bottom: 20px;
                margin-bottom: 30px;
            }
            .invoice-details {
                margin-bottom: 30px;
            }
            .invoice-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 30px;
            }
            .invoice-table th,
            .invoice-table td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }
            .invoice-table th {
                background-color: #f5f5f5;
                font-weight: bold;
            }
            .invoice-total {
                text-align: right;
                font-size: 18px;
                font-weight: bold;
                margin-top: 20px;
            }
            .invoice-footer {
                margin-top: 50px;
                border-top: 1px solid #ddd;
                padding-top: 20px;
                text-align: center;
                color: #666;
            }
        }
    </style>

    <!-- Printable Invoice Content -->
    <div class="printable-invoice" style="display: none;">
        <div class="invoice-header">
            <h1 style="color: #333; margin: 0; font-size: 28px;">PAYMENT INVOICE</h1>
            <p style="color: #666; margin: 5px 0 0 0; font-size: 14px;">Payment Reference: {{ $payment->invoice_reference ?? 'N/A' }}</p>
        </div>

        <div class="invoice-details">
            <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div>
                    <h3 style="color: #333; margin: 0 0 10px 0;">Payment Information</h3>
                    <p style="margin: 5px 0;"><strong>Payment ID:</strong> {{ $payment->id }}</p>
                    <p style="margin: 5px 0;"><strong>Date:</strong> {{ $payment->created_at->format('F d, Y') }}</p>
                    <p style="margin: 5px 0;"><strong>Time:</strong> {{ $payment->created_at->format('H:i:s') }}</p>
                    <p style="margin: 5px 0;"><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
                </div>
                <div>
                    <h3 style="color: #333; margin: 0 0 10px 0;">Customer Information</h3>
                    <p style="margin: 5px 0;"><strong>User ID:</strong> {{ $payment->user_id }}</p>
                    <p style="margin: 5px 0;"><strong>Email:</strong> {{ $payment->user->email ?? 'N/A' }}</p>
                    @if($payment->user)
                    <p style="margin: 5px 0;"><strong>Member Since:</strong> {{ $payment->user->created_at->format('F d, Y') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->description ?? 'Payment Transaction' }}</td>
                    <td>{{ number_format($payment->amount_spend, 2) }}</td>
                    <td>{{ $payment->currency ?? 'USD' }}</td>
                    <td>{{ ucfirst($payment->status) }}</td>
                </tr>
            </tbody>
        </table>

        @if($payment->card)
        <div style="margin-bottom: 30px;">
            <h3 style="color: #333; margin: 0 0 15px 0;">Payment Method</h3>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Card Type</th>
                        <th>Card Number</th>
                        <th>Cardholder Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $payment->card->type_of_card ?? 'N/A' }}</td>
                        <td>**** **** **** {{ substr($payment->card->card_number, -4) }}</td>
                        <td>{{ $payment->card->name . ' ' . $payment->card->surname ?? 'N/A' }}</td>
                        <td>Active</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        <div class="invoice-total">
            <p style="margin: 0;"><strong>Total Amount: {{ number_format($payment->amount_spend, 2) }} {{ $payment->currency ?? 'USD' }}</strong></p>
        </div>

        <div class="invoice-footer">
            <p style="margin: 0; font-size: 12px;">
                This is a computer-generated invoice. No signature required.<br>
                Generated on {{ now()->format('F d, Y \a\t H:i:s') }}
            </p>
        </div>
    </div>

    <!-- Print JavaScript -->
    <script>
        function printInvoice() {
            // Hide all non-printable elements
            const noPrintElements = document.querySelectorAll('.no-print');
            noPrintElements.forEach(el => el.style.display = 'none');
            
            // Show the printable invoice
            const printableInvoice = document.querySelector('.printable-invoice');
            printableInvoice.style.display = 'block';
            
            // Print the page
            window.print();
            
            // Restore the original display after printing
            setTimeout(() => {
                printableInvoice.style.display = 'none';
                noPrintElements.forEach(el => el.style.display = '');
            }, 1000);
        }
    </script>
</x-admin-layout>
