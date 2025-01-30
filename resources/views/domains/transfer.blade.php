<x-app-layout>
    <!-- HERO BANNER ONE -->
    <section class="rts-hero-three rts-hosting-banner rts-hero__one domain-checker-padding banner-default-height">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="rts-hero__content domain">
                        <h1 data-sal="slide-down" data-sal-delay="100" data-sal-duration="800">We Made Domain Transfer Easy
                        </h1>
                        <p class="description" data-sal="slide-down" data-sal-delay="200" data-sal-duration="800">Enter
                            the domain that you would like to transfer to Hostie</p>

                        <form action="{{ route('domains.transfer') }}" method="POST" class="domain-form d-flex gap-3">
                            @csrf
                            <input type="hidden" name="domain" value="register">
                            <input type="hidden" name="a" value="add">
                            <input type="text" placeholder="Enter the domain you want to transfer" name="query"
                                required>
                            <button class="submit-btn" type="submit" name="domain_type">Transfer</button>
                        </form>
                        <div class="banner-content-tag">
                            <p class="desc" data-sal-delay="400" data-sal-duration="800">Looking for a new domain
                                name? <a href="domain-checker.html">Try domain checker</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-shape-area">
            <img class="three" src="assets/images/banner/banner-bg-element.svg" alt="">
        </div>
    </section>
    <!-- HERO BANNER ONE END -->
    <!-- DOMAIN TRANSFER FORM AREA -->
    <!-- TRANSFER PRICE -->
    <div class="rts-transfer-price-table alice__blue section__padding">
        <div class="container">
            <div class="row">
                <div class="row justify-content-center">
                    <div class="rts-section w-450 text-center">
                        <h2 class="rts-section__title " data-sal="slide-down" data-sal-delay="100"
                            data-sal-duration="800">
                            Check Our Domain
                            Transfer Prices
                        </h2>
                    </div>
                </div>
            </div>
            <!-- transfer rate table -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="transfer-domain-table">
                        <table class="table table-responsive transfer-domain">
                            <thead class="heading__bg">
                                <tr>
                                    <th class="cell">Domains</th>
                                    <th class="cell text-center">Transfer / Renewal Price</th>

                                </tr>
                            </thead>
                            <tbody class="table__content">
                                @foreach ($extensions as $extension)
                                    <tr>
                                        <td class="tld">{{ $extension->tld }}</td>
                                        <td class="price text-center">
                                            <h5 class="transfer">{{ $extension->formattedRegistrationPrice() }} /year
                                            </h5>
                                            <p class="renew">Renewal Price {{ $extension->formattedRenewalPrice() }}
                                                /year</p>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- TRANSFER PRICE END -->
</x-app-layout>
