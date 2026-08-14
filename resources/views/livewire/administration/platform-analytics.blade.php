{{-- Admin platform analytics — top products, services, businesses, sales --}}
<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Platform Analytics</h1>
            <p class="mt-1 text-sm text-gray-500">Top-performing products, services, and businesses.</p>
        </div>
        <select wire:model.live="period"
            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
            <option value="7d">Last 7 days</option>
            <option value="30d">Last 30 days</option>
            <option value="90d">Last 90 days</option>
            <option value="365d">Last year</option>
        </select>
    </div>

    {{-- Summary cards --}}
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">₦{{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-500">Total Orders</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-500">Completed</p>
            <p class="mt-1 text-2xl font-bold text-green-600">{{ number_format($completedOrders?->count ?? 0) }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-500">Contact Clicks</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalContactClicks) }}</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Top Products --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Top Products by Views</h2>
            @if ($topProducts->isEmpty())
                <p class="text-sm text-gray-500">No product views yet.</p>
            @else
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-xs text-gray-500">
                            <th class="py-2">#</th>
                            <th class="py-2">Product</th>
                            <th class="py-2">Business</th>
                            <th class="py-2 text-right">Price</th>
                            <th class="py-2 text-right">Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topProducts as $i => $product)
                            <tr class="border-b">
                                <td class="py-2 text-gray-400">{{ $i + 1 }}</td>
                                <td class="py-2 font-medium">{{ $product['name'] }}</td>
                                <td class="py-2 text-gray-600">{{ $product['business'] }}</td>
                                <td class="py-2 text-right">₦{{ number_format($product['price']) }}</td>
                                <td class="py-2 text-right font-medium">{{ number_format($product['views']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Top Services --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Top Services by Views</h2>
            @if ($topServices->isEmpty())
                <p class="text-sm text-gray-500">No service views yet.</p>
            @else
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-xs text-gray-500">
                            <th class="py-2">#</th>
                            <th class="py-2">Service</th>
                            <th class="py-2">Business</th>
                            <th class="py-2 text-right">Price</th>
                            <th class="py-2 text-right">Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topServices as $i => $service)
                            <tr class="border-b">
                                <td class="py-2 text-gray-400">{{ $i + 1 }}</td>
                                <td class="py-2 font-medium">{{ $service['name'] }}</td>
                                <td class="py-2 text-gray-600">{{ $service['business'] }}</td>
                                <td class="py-2 text-right">₦{{ number_format($service['price']) }}</td>
                                <td class="py-2 text-right font-medium">{{ number_format($service['views']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Top Businesses --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Top Businesses by Views</h2>
            @if ($topBusinesses->isEmpty())
                <p class="text-sm text-gray-500">No page views yet.</p>
            @else
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-xs text-gray-500">
                            <th class="py-2">#</th>
                            <th class="py-2">Business</th>
                            <th class="py-2">Type</th>
                            <th class="py-2 text-right">Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topBusinesses as $i => $business)
                            <tr class="border-b">
                                <td class="py-2 text-gray-400">{{ $i + 1 }}</td>
                                <td class="py-2 font-medium">{{ $business['name'] }}</td>
                                <td class="py-2 text-gray-600">{{ $business['type'] }}</td>
                                <td class="py-2 text-right font-medium">{{ number_format($business['views']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Views by Category --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Views by Category</h2>
            @if ($viewsByCategory->isEmpty())
                <p class="text-sm text-gray-500">No category data yet.</p>
            @else
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-xs text-gray-500">
                            <th class="py-2">Category</th>
                            <th class="py-2 text-right">Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($viewsByCategory as $cat)
                            <tr class="border-b">
                                <td class="py-2 font-medium">{{ $cat->category_name ?? 'Uncategorized' }}</td>
                                <td class="py-2 text-right font-medium">{{ number_format($cat->views) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Top Marketing Links --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 lg:col-span-2">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Top Marketing Links</h2>
            @if ($topMarketingLinks->isEmpty())
                <p class="text-sm text-gray-500">No marketing link clicks yet.</p>
            @else
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-xs text-gray-500">
                            <th class="py-2">#</th>
                            <th class="py-2">Link Name</th>
                            <th class="py-2">Business</th>
                            <th class="py-2 text-right">Clicks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topMarketingLinks as $i => $link)
                            <tr class="border-b">
                                <td class="py-2 text-gray-400">{{ $i + 1 }}</td>
                                <td class="py-2 font-medium">{{ $link['name'] }}</td>
                                <td class="py-2 text-gray-600">{{ $link['business'] }}</td>
                                <td class="py-2 text-right font-medium">{{ number_format($link['clicks']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
