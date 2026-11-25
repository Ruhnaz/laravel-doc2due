<footer class="expiry-footer" aria-labelledby="expiry-footer-title">
    <p class="expiry-footer__wordmark" aria-hidden="true">Doc2Due</p>
    <div class="expiry-footer__content">
    <div class="expiry-footer__inner">
        <section class="expiry-footer__cta" aria-labelledby="expiry-footer-title">
            <div class="expiry-footer__cta-copy">
                <h2 id="expiry-footer-title">Stop chasing expiry dates</h2>
                <p>Keep policies, certificates and contracts in one list, with reminders before renewal.</p>
            </div>
            <div class="expiry-footer__actions">
                <a href="{{ route('register') }}" class="expiry-footer__button expiry-footer__button--primary">Start free</a>
                <a href="#pricing" class="expiry-footer__button expiry-footer__button--secondary">See pricing</a>
            </div>
        </section>
    </div>

    <div class="expiry-footer__inner expiry-footer__main">
        <div class="expiry-footer__grid">
            <section class="expiry-footer__brand" aria-label="Doc2Due">
                <a href="{{ url('/') }}" class="inline-flex [&_span]:text-white">
                    <x-brand-logo icon="brand" :stroke="2" />
                </a>
                <p class="expiry-footer__tagline">Never Miss an Expiry Date Again.</p>
                <address class="expiry-footer__contact">
                    <a href="mailto:support@doc2due.example">support@doc2due.example</a>
                    <a href="tel:+910000000000">+91 00000 00000</a>
                </address>
                <nav class="expiry-footer__socials" aria-label="Social media">
                    <a href="#" aria-label="LinkedIn">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M5.2 3.5a2.1 2.1 0 1 1 0 4.2 2.1 2.1 0 0 1 0-4.2ZM3.4 9.2H7v11.3H3.4V9.2Zm5.8 0h3.4v1.6h.1c.5-.9 1.6-2 3.4-2 3.6 0 4.3 2.3 4.3 5.2v6.5h-3.6v-5.8c0-1.4 0-3.2-2-3.2s-2.3 1.5-2.3 3.1v5.9H9.2V9.2Z"/></svg>
                    </a>
                    <a href="#" aria-label="X">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 2H22l-6.8 7.8L23.2 22h-6.3L12 14.7 5.6 22H2.4l7.3-8.5L1.8 2h6.5l4.5 6.7L18.9 2Zm-1.1 18h1.7L7.3 3.9H5.5L17.8 20Z"/></svg>
                    </a>
                    <a href="#" aria-label="YouTube">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2C0 8.1 0 12 0 12s0 3.9.5 5.8a3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1c.5-1.9.5-5.8.5-5.8s0-3.9-.5-5.8ZM9.6 15.6V8.4l6.3 3.6-6.3 3.6Z"/></svg>
                    </a>
                </nav>
            </section>

            <nav class="expiry-footer__links" aria-label="Footer navigation">
                <section class="expiry-footer__link-group">
                    <h3>Product</h3>
                    <a class="expiry-footer__link" href="#problem">Why Doc2Due</a>
                    <a class="expiry-footer__link" href="#features">Features</a>
                    <a class="expiry-footer__link" href="#pricing">Pricing</a>
                    <a class="expiry-footer__link" href="#faq">FAQs</a>
                </section>
                <section class="expiry-footer__link-group">
                    <h3>Who it's for</h3>
                    <a class="expiry-footer__link" href="#who">Property</a>
                    <a class="expiry-footer__link" href="#who">Construction</a>
                    <a class="expiry-footer__link" href="#who">Cleaning</a>
                </section>
                <section class="expiry-footer__link-group">
                    <h3>Company</h3>
                    <a class="expiry-footer__link" href="#">About us</a>
                    <a class="expiry-footer__link" href="#">Contact us</a>
                    <a class="expiry-footer__link" href="#">Support</a>
                </section>
                <section class="expiry-footer__link-group">
                    <h3>Account</h3>
                    <a class="expiry-footer__link" href="{{ route('login') }}">Log in</a>
                    <a class="expiry-footer__link" href="{{ route('register') }}">Sign up free</a>
                </section>
            </nav>
        </div>

        <ul class="expiry-footer__trust" aria-label="Doc2Due assurances">
            <li>Email reminders</li>
            <li>Secure payments by Razorpay</li>
        </ul>

        <div class="expiry-footer__legal">
            <p>© 2026 Doc2Due. All rights reserved.</p>
            <nav aria-label="Legal">
                <a class="expiry-footer__link" href="#">Terms &amp; conditions</a>
                <a class="expiry-footer__link" href="#">Privacy policy</a>
                <a class="expiry-footer__link" href="#">Refund &amp; cancellation</a>
            </nav>
        </div>
    </div>
    </div>
</footer>
