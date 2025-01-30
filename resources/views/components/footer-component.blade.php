<div>
    <!-- FOOTER AREA -->
    <footer class="rts-footer site-footer-one section__padding">
        <div class="container">
            <div class="row">
                <!-- widget -->
                <div class="col-lg-3 col-md-5 col-sm-6 rts-footer__widget--column">
                    <div class="rts-footer__widget footer__widget w-280">
                        <a href="#" aria-label="main page link" class="footer__logo">
                            <img src="{{ asset('assets/logo.webp') }}" alt="">
                        </a>
                        <p class="brand-desc">We’re on a mission make life easier for web developers & small businesses.
                        </p>
                        <div class="separator site-default-border"></div>
                        <div class="payment__method">
                            <h6>Payment Method</h6>
                            <ul>
                                <li><img src="assets/images/payment/visa.svg" alt=""></li>
                                <li><img src="assets/images/payment/master-card.svg" alt=""></li>
                                <li><img src="assets/images/payment/paypal.svg" alt=""></li>
                                <li><img src="assets/images/payment/american-express.svg" alt=""></li>
                                <li><img src="assets/images/payment/wise.svg" alt=""></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- widget end -->
                <!-- widget -->
                <div class="col-lg-2 col-md-3 col-sm-6 rts-footer__widget--column">
                    <div class="rts-footer__widget footer__widget extra-padding">
                        <h5 class="widget-title">Company</h5>
                        <div class="rts-footer__widget--menu ">
                            <ul>
                                <li><a href="about.html">About Us</a></li>
                                <li><a href="blog.html">News Feed</a></li>
                                <li><a href="contact.html">Contact</a></li>
                                <li><a href="affiliate.html">Affiliate Program</a></li>
                                <li><a href="technology.html">Our Technology</a></li>
                                <li><a href="knowledgebase.html">Knowledgebase</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- widget end -->
                <!-- widget -->
                <div class="col-lg-2 col-md-4 col-sm-6 rts-footer__widget--column">
                    <div class="rts-footer__widget footer__widget extra-padding">
                        <h5 class="widget-title">Feature</h5>
                        <div class="rts-footer__widget--menu ">
                            <ul>
                                <li><a href="{{route('domains.index')}}">Domain Checker</a></li>
                                <li><a href="{{route('domains.transfer')}}">Domain Transfer</a></li>
                                <li><a href="{{route('domains.index')}}">Domain Registration</a></li>
                                <li><a href="{{route('domains.index')}}">Whois</a></li>
                                <li><a href="support.html">Support</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- widget end -->
                <!-- widget -->
                <div class="col-lg-2 col-md-6 col-sm-6 rts-footer__widget--column">
                    <div class="rts-footer__widget footer__widget">
                        <h5 class="widget-title">Hosting</h5>
                        <div class="rts-footer__widget--menu">
                            <ul>
                                <li><a href="shared-hosting.html">Shared Hosting</a></li>
                                <li><a href="wordpress-hosting.html">Wordpress Hosting</a></li>
                                <li><a href="vps-hosting.html">VPS Hosting</a></li>
                                <li><a href="reseller-hosting.html">Reseller Hosting</a></li>
                                <li><a href="dedicated-hosting.html">Dedicated Hosting</a></li>
                                <li><a href="cloud-hosting.html">Cloud Hosting</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- widget end -->
                <!-- widget -->
                <div class="col-lg-3 col-md-6 rts-footer__widget--column">
                    <div class="rts-footer__widget footer__widget">
                        <h5 class="widget-title">Join Our Newsletter</h5>
                        <p>We'll send you news and offers.</p>
                        <form action="#" class="newsletter mx-40">
                            <input type="email" class="home-one" name="email" placeholder="Enter mail" required>
                            <span class="icon"><i class="fa-regular fa-envelope-open"></i></span>
                            <button type="submit" aria-label="Submit"><i
                                    class="fa-regular fa-arrow-right"></i></button>
                        </form>
                        <div class="social__media">
                            <h5>social media</h5>
                            <div class="social__media--list">
                                <a href="https://www.facebook.com" aria-label="social-link" target="_blank"
                                    class="media"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="https://www.instagram.com" aria-label="social-link" target="_blank"
                                    class="media"><i class="fa-brands fa-instagram"></i></a>
                                <a href="https://www.linkedin.com" aria-label="social-link" target="_blank"
                                    class="media"><i class="fa-brands fa-linkedin"></i></a>
                                <a href="https://www.x.com" aria-label="social-link" target="_blank" class="media"><i
                                        class="fa-brands fa-x-twitter"></i></a>
                                <a href="https://www.behance.com" aria-label="social-link" target="_blank"
                                    class="media"><i class="fa-brands fa-behance"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- widget end -->
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="rts-footer__copyright text-center">
                    <p>&copy; Copyright {{date('Y')}} {{config('app.name')}}. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER AREA END -->
    <!-- THEME PRELOADER START -->
    <div class="loader-wrapper">
        <div class="loader">
        </div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>
    <!-- THEME PRELOADER END -->
    <!-- BACK TO TOP AREA START -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
                style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>
    <!-- BACK TO TOP AREA EDN -->

    <!-- All Plugin -->
    <script defer src="{{ asset('assets/js/plugins.min.js') }}"></script>
    <!-- main js -->
    <script defer src="{{ asset('assets/js/main.js') }}"></script>
</div>
