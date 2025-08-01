<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">{{ translate('Login') }}</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                 <div class="p-3">
                    <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                        @csrf

                        @if (addon_is_activated('otp_system'))
                            <!-- Phone -->
                            <div class="form-group phone-form-group mb-1">
                                <input type="tel" id="phone-code"
                                    class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                    value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                            </div>
                            <!-- Country Code -->
                            <input type="hidden" name="country_code" value="">
                            <!-- Email -->
                            <div class="form-group email-form-group mb-1 d-none">
                                <input type="email"
                                    class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email"
                                    id="email" autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- Use Email Instead -->
                            <div class="form-group text-right">
                                <button class="btn btn-link p-0 text-danger" type="button"
                                    onclick="toggleEmailPhone(this)"><i>*{{ translate('Use Email Instead') }}</i></button>
                            </div>
                        @else
                            <!-- Email -->
                            <div class="form-group">
                                <input type="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email"
                                    id="email" autocomplete="off">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif

                        <!-- Password -->
                        <div class="form-group">
                            <input type="password" name="password" class="form-control h-auto rounded-0 form-control-lg"
                                placeholder="{{ translate('Password') }}">
                        </div>

                        <!-- Remember Me & Forgot password -->
                        <div class="row mb-2">
                            <div class="col-6">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <span class=opacity-60>{{ translate('Remember Me') }}</span>
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('password.request') }}"
                                    class="text-reset opacity-60 hov-opacity-100 fs-14">{{ translate('Forgot password?') }}</a>
                            </div>
                        </div>

                        <!-- Login Button -->
                        <div class="mb-5">
                            <button type="submit"
                                class="btn btn-primary btn-block fw-600 rounded-0">{{ translate('Login') }}</button>
                        </div>
                    </form>

                    <!-- Register Now -->
                    <div class="text-center mb-3">
                        <p class="text-muted mb-0">{{ translate('Dont have an account?') }}</p>
                        <a href="{{ route('user.registration') }}">{{ translate('Register Now') }}</a>
                    </div>
                    
                    <!-- Social Login -->
                    @if (get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1 || get_setting('apple_login') == 1)
                        <div class="separator mb-3">
                            <span class="bg-white px-3 opacity-60">{{ translate('Or Login With') }}</span>
                        </div>
                        <ul class="list-inline social colored text-center mb-5">
                            <!-- Facebook -->
                            @if (get_setting('facebook_login') == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'facebook']) }}"
                                        class="facebook">
                                        <i class="lab la-facebook-f"></i>
                                    </a>
                                </li>
                            @endif
                            <!-- Google -->
                            @if (get_setting('google_login') == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'google']) }}"
                                        class="google">
                                        <i class="lab la-google"></i>
                                    </a>
                                </li>
                            @endif
                            <!-- Twitter -->
                            @if (get_setting('twitter_login') == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'twitter']) }}"
                                        class="twitter">
                                        <i class="lab la-twitter"></i>
                                    </a>
                                </li>
                            @endif
                            <!-- Apple -->
                            @if (get_setting('apple_login') == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'apple']) }}"
                                        class="apple">
                                        <i class="lab la-apple"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
   
<script>
    /* ---------- toggle between phone & email ---------- */
   

    /* ---------- send OTP ---------- */
    document.getElementById('send-otp-btn').addEventListener('click', () => {
        const phoneVisible = !document.querySelector('.phone-form-group').classList.contains('d-none');
        const phone = phoneVisible ? document.getElementById('phone-code').value.trim() : '';
        const email = phoneVisible ? '' : document.getElementById('email').value.trim();

        if (!phone && !email) {
            AIZ.plugins.notify('warning', '{{ translate('Please enter your phone number or email') }}');
            return;
        }

        const payload = phoneVisible ? { phone } : { email };

        AIZ.plugins.notify('info', '{{ translate('Sending OTP...') }}', 'info');

        fetch("{{ route('otp.login.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                /* move to OTP step */
                document.getElementById('input-section').classList.add('d-none');
                document.getElementById('otp-section').classList.remove('d-none');

                /* remember what we sent */
                document.getElementById('login-type').value = phoneVisible ? 'phone' : 'email';
                document.getElementById('login-value').value = phoneVisible ? phone : email;

                AIZ.plugins.notify('success', res.message || '{{ translate('OTP sent successfully') }}');
            } else {
                AIZ.plugins.notify('danger', res.message || '{{ translate('Failed to send OTP') }}');
            }
        })
        .catch(() => {
            AIZ.plugins.notify('danger', '{{ translate('Server error - please try again') }}');
        });
    });
</script>

<style>
    .divider-text {
        position: relative;
        text-align: center;
        margin: 15px 0;
        color: #6c757d;
        font-size: 0.8rem;
    }
    .divider-text::before,
    .divider-text::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #dee2e6;
        margin: auto 10px;
    }
    .divider-text::before {
        margin-right: 10px;
    }
    .divider-text::after {
        margin-left: 10px;
    }
    .social-btn {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .form-control {
        padding: 10px 15px;
    }
    .card {
        border-radius: 12px;
    }
</style>

</div>
