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
                    {{ __('copywrite.footer_copy') }}
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
                    {{ __('common.company') }}
                </h4>

                <ul class="mt-4 space-y-2 text-sm">
                    <li>
                        <a href="/about" class="hover:text-primary transition">
                            {{ __('common.about_us') }}
                        </a>
                    </li>
                    <li>
                        <a href="/contact" class="hover:text-primary transition">
                            {{ __('common.contact') }}
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Legal Links --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wide text-foreground">
                    {{ __('common.legal') }}
                </h4>

                <ul class="mt-4 space-y-2 text-sm">
                    <li>
                        <a href="/privacy-policy" class="hover:text-primary transition">
                            {{ __('common.privacy_policy') }}
                        </a>
                    </li>
                    <li>
                        <a href="/terms-of-service" class="hover:text-primary transition">
                            {{ __('common.terms') }}
                        </a>
                    </li>
                    <li>
                        <a href="/cookie-policy" class="hover:text-primary transition">
                            {{ __('common.cookie_policy') }}
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        {{-- Bottom Bar --}}
        {{-- <div class="mt-12 pt-6 border-t border-border text-center text-xs text-muted-foreground">
            © {{ now()->year }} {{ config('app.name') }}. All rights reserved.
        </div> --}}

        {{-- Bottom Bar --}}
        <div
            class="mt-12 pt-6 border-t border-border flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-muted-foreground">

            {{-- Copyright --}}
            <div>
                © {{ now()->year }} {{ config('app.name') }}. All rights reserved.
            </div>

            {{-- Language Switch --}}
            <div>
                <x-layout.lang-switch class="w-28 text-xs" />
            </div>

        </div>

    </div>
</footer>
