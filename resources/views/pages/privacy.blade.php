@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<section class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Privacy Policy</h1>
        <p class="mt-2 text-sm text-gray-500">Last updated: {{ date('F d, Y') }}</p>

        <div class="mt-8 space-y-6 text-sm text-gray-600 leading-relaxed">
            <p>Ilthres Market respects your privacy. This policy explains how we collect, use, and protect your personal information.</p>

            <h2 class="text-lg font-semibold text-gray-900">1. Information We Collect</h2>
            <p>We collect information you provide when creating an account — including your name, email address, and business details. We also collect usage data such as page views and interactions to improve our platform.</p>

            <h2 class="text-lg font-semibold text-gray-900">2. How We Use Your Information</h2>
            <p>Your information is used to operate and improve Ilthres Market, process payments, communicate with you about your account, and personalise your experience. We never sell your personal data to third parties.</p>

            <h2 class="text-lg font-semibold text-gray-900">3. Data Storage & Security</h2>
            <p>Your data is stored securely on our servers. We implement industry-standard security measures including encryption, access controls, and regular security audits to protect your information.</p>

            <h2 class="text-lg font-semibold text-gray-900">4. Third-Party Services</h2>
            <p>We use Paystack and Flutterwave for payment processing. These partners have their own privacy policies governing how they handle your payment data. We do not store full payment card details.</p>

            <h2 class="text-lg font-semibold text-gray-900">5. Your Rights</h2>
            <p>You may request access to, correction of, or deletion of your personal data at any time by contacting us through the platform. You may also export your business data from your dashboard.</p>

            <h2 class="text-lg font-semibold text-gray-900">6. Cookies</h2>
            <p>We use essential cookies for authentication and platform functionality. Analytics cookies help us understand usage patterns. You can control cookie preferences through your browser settings.</p>

            <h2 class="text-lg font-semibold text-gray-900">7. Changes to This Policy</h2>
            <p>We may update this policy from time to time. Material changes will be communicated via email or platform notice.</p>

            <h2 class="text-lg font-semibold text-gray-900">8. Contact</h2>
            <p>If you have questions about this Privacy Policy, please reach out through our support channels.</p>
        </div>
    </div>
</section>
@endsection
