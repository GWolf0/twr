@props([
    'address' => '123 Business Street, City, Country',
    'phone' => '+1 (000) 000-0000',
    'email' => 'contact@example.com',
])

<footer class="mt-16 border-t border-border bg-secondary text-secondary-foreground">
    <div class="max-w-7xl mx-auto px-6 py-12">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            {{-- Brand / About --}}
            <div>
                <h3 class="text-lg font-semibold text-foreground">
                    {{ config('app.name') }}
                </h3>

                <p class="mt-3 text-sm text-muted-foreground">
                    Building reliable digital solutions with simplicity and clarity.
                </p>

                <div class="mt-4 text-sm text-muted-foreground space-y-1">
                    <p>{{ $address }}</p>
                    <p>
                        <a href="tel:{{ $phone }}" class="hover:text-primary transition">
                            {{ $phone }}
                        </a>
                    </p>
                    <p>
                        <a href="mailto:{{ $email }}" class="hover:text-primary transition">
                            {{ $email }}
                        </a>
                    </p>
                </div>
            </div>

            {{-- Company Links --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wide text-foreground">
                    Company
                </h4>

                <ul class="mt-4 space-y-2 text-sm">
                    <li>
                        <a href="/about" class="hover:text-primary transition">
                            About Us
                        </a>
                    </li>
                    <li>
                        <a href="/contact" class="hover:text-primary transition">
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="/careers" class="hover:text-primary transition">
                            Careers
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Legal Links --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wide text-foreground">
                    Legal
                </h4>

                <ul class="mt-4 space-y-2 text-sm">
                    <li>
                        <a href="/privacy-policy" class="hover:text-primary transition">
                            Privacy Policy
                        </a>
                    </li>
                    <li>
                        <a href="/terms-of-service" class="hover:text-primary transition">
                            Terms of Service
                        </a>
                    </li>
                    <li>
                        <a href="/cookie-policy" class="hover:text-primary transition">
                            Cookie Policy
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div class="mt-12 pt-6 border-t border-border text-center text-xs text-muted-foreground">
            © {{ now()->year }} {{ config('app.name') }}. All rights reserved.
        </div>

    </div>
</footer>
