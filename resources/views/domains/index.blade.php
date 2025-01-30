<x-app-layout>
    <!-- HERO BANNER ONE -->
    <section class="rts-hero-three rts-hero__one rts-hosting-banner domain-checker-padding banner-default-height">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="rts-hero__content domain">
                        <h1 data-sal="slide-down" data-sal-delay="100" data-sal-duration="800">Find Best Unique Domains
                            Checker!
                        </h1>
                        <p class="description" data-sal="slide-down" data-sal-delay="200" data-sal-duration="800">Web
                            Hosting, Domain Name and Hosting Center Solutions</p>
                        <form id="domainSearchForm" action="{{ route('domains.search') }}" method="POST"
                              data-sal-delay="300" data-sal-duration="800">
                            @csrf
                            <div class="rts-hero__form-area">
                                <input type="text" autocomplete="off" placeholder="Enter domain name (without TLD)" name="domain"
                                       id="domainInput" value="{{ old('domain', $searchedDomain ?? '') }}">
                                <div class="select-button-area">
                                    <select name="extension" id="tldSelect" class="price__select">
                                        @foreach ($domains as $domain)
                                            <option value="{{ ltrim($domain->tld, '.') }}"
                                                {{ old('extension', $searchedExtension ?? '') == ltrim($domain->tld, '.') ? 'selected' : '' }}>
                                                {{ $domain->tld }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" id="searchButton" class="rts-btn">Search Domain</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-shape-area">
            <img class="three" src="{{asset('assets/images/banner/shape-03.png')}}" alt="banner">
        </div>
    </section>

    @if (isset($domains))
        <!-- DOMAIN PRICING -->
        <section class="rts-domain-pricing-area area-2 pt--120 pb--120">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="section-title-area w-550">
                        <h2 class="section-title">Domain Price List</h2>
                        <p class="desc">Keep in mind that TLD prices can change over time, and different
                            registrars may offer different deals and packages
                        </p>
                    </div>
                </div>
                <div class="section-inner">
                    <div class="pricing-table-area">
                        @if (session('error'))
                            <div class="alert alert-danger mt-4">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (isset($searchResults))
                            <div class="tab__content open" id="all">
                                <table class="table table-hover table-responsive">
                                    <thead class="heading__bg">
                                    <tr>
                                        <th>Domain Name</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table__content">
                                    @foreach ($searchResults as $result)
                                        <tr>
                                            <td class="cell">{{ $result['domain_name'] }}</td>
                                            <td class="cell">
                                                @if ($result['is_available'])
                                                    <span class="badge bg-success">Available</span>
                                                @else
                                                    <span class="badge bg-danger">Not Available</span>
                                                @endif
                                            </td>
                                            <td class="cell"> @php
                                                    $tld = '.' . explode('.', $result['domain_name'], 2)[1];
                                                    $pricing = $domains->firstWhere('tld', $tld);
                                                @endphp
                                                {{ $pricing ? $pricing->formattedRegistrationPrice() : 'N/A' }}
                                            </td>
                                            <td class="cell">
                                                @if ($result['is_available'])
                                                    <a href="/cart/add?domain={{ urlencode($result['domain_name']) }}"
                                                       class="rts-btn btn-small">Register Now</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </section>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('domainSearchForm');
                const searchResult = document.getElementById('searchResult');
                searchResult.style = undefined;
                const alertDiv = searchResult.querySelector('.alert');
                const searchButton = document.getElementById('searchButton');
                const domainInput = document.getElementById('domainInput');
                const tldSelect = document.getElementById('tldSelect');
                tldSelect.value = undefined;

                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const domainName = domainInput.value.trim().toLowerCase();
                    if (!domainName) {
                        searchResult.style.display = 'block';
                        alertDiv.className = 'alert alert-danger';
                        alertDiv.textContent = 'Please enter a domain name';
                        return;
                    }

                    // Combine domain name and TLD
                    const fullDomain = domainName + '.' + tldSelect.value;

                    searchButton.disabled = true;
                    searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';

                    fetch('{{ route('domains.search') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            domain: fullDomain
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            searchResult.style.display = 'block';
                            if (data.error) {
                                alertDiv.className = 'alert alert-danger';
                                alertDiv.textContent = data.error;
                            } else {
                                const price = data.price ?
                                    ` - Price: ${new Intl.NumberFormat('rw-RW', { style: 'currency', currency: 'RWF' }).format(data.price)}` :
                                    '';
                                alertDiv.className = data.available ? 'alert alert-success' :
                                    'alert alert-warning';
                                alertDiv.textContent = data.available ?
                                    `Domain ${data.domain} is available!${price}` :
                                    `Domain ${data.domain} is not available. ${data.reason}`;
                            }
                        })
                        .catch(error => {
                            searchResult.style.display = 'block';
                            alertDiv.className = 'alert alert-danger';
                            alertDiv.textContent = 'An error occurred while checking the domain.';
                            console.error('Error:', error);
                        })
                        .finally(() => {
                            searchButton.disabled = false;
                            searchButton.innerHTML = 'Search Domain';
                        });
                });

                // Add input validation
                domainInput.addEventListener('input', function(e) {
                    const value = e.target.value.trim();
                    const isValid = /^[a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]$/.test(value);
                    if (!isValid && value) {
                        e.target.setCustomValidity(
                            'Domain name can only contain letters, numbers, and hyphens, and cannot start or end with a hyphen.'
                        );
                    } else {
                        e.target.setCustomValidity('');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
