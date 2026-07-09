@extends('layouts.app')

@section('title', 'Terms & Conditions')

@section('content')
<section class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Terms & Conditions</h1>
        <p class="mt-2 text-sm text-gray-500">Last updated: {{ date('F d, Y') }}</p>

        <div class="mt-8 space-y-6 text-sm text-gray-600 leading-relaxed">
            <p>Welcome to Ilthres Market. By accessing or using our platform, you agree to be bound by these Terms & Conditions. If you do not agree, please do not use our services.</p>

            <h2 class="text-lg font-semibold text-gray-900">1. Account Registration</h2>
            <p>You must provide accurate and complete information when creating an account. You are responsible for maintaining the confidentiality of your login credentials and for all activities under your account.</p>

            <h2 class="text-lg font-semibold text-gray-900">2. Business Listings</h2>
            <p>Merchants are solely responsible for the accuracy, legality, and appropriateness of their business profiles, products, services, and media content. Ilthres Market reserves the right to remove any listing that violates these terms.</p>

            <h2 class="text-lg font-semibold text-gray-900">3. Prohibited Activities</h2>
            <p>You may not use the platform for any unlawful purpose, to impersonate others, to distribute malware, to engage in fraud, or to infringe on the intellectual property rights of others.</p>

            <h2 class="text-lg font-semibold text-gray-900">4. Payments & Subscriptions</h2>
            <p>Paid plans are billed monthly via our payment partners (Paystack, Flutterwave). Cancellations take effect at the end of the current billing period. Refunds are handled on a case-by-case basis.</p>

            <h2 class="text-lg font-semibold text-gray-900">5. Limitation of Liability</h2>
            <p>Ilthres Market is provided "as is" without warranties of any kind. We are not liable for any damages arising from your use of the platform, to the fullest extent permitted by law.</p>

            <h2 class="text-lg font-semibold text-gray-900">6. Changes to Terms</h2>
            <p>We may update these terms at any time. Continued use of the platform after changes constitutes acceptance of the new terms. We will notify users of material changes via email.</p>

            <h2 class="text-lg font-semibold text-gray-900">7. Contact</h2>
            <p>For questions about these terms, please reach out through our support channels listed on the platform.</p>
        </div>
    </div>
</section>
@endsection
