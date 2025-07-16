
<div class="aiz-main-wrapper d-flex flex-column justify-content-md-center bg-white">
    <section class="bg-white overflow-hidden">
        <div class="row">
            <div class="col-xxl-6 col-xl-9 col-lg-10 col-md-7 mx-auto py-lg-4">
                <div class="card shadow-none rounded-0 border-0">
                    <div class="row no-gutters ">
                        <!-- Left Side Image-->
                        <div class="col-lg-4">
                           
                        </div>

                        <div class=" mt-1 col-lg-4 p-4 p-lg-5 d-flex flex-column justify-content-center border right-content" style="height: auto;">
                            

                            <!-- Titles -->
                            <div class="text-center text-lg-left">
                                <h1 class="fs-20 fs-md-20 fw-700 text-primary" style="text-transform: uppercase;">{{ translate('Login To ') }} {{ env('APP_NAME') }}</h1>
                               
                            </div>

                            <!-- Login form -->
                            <div class="pt-3">
                                <div class="">
                                    <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        
                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="email" class="fs-12 fw-700 text-soft-dark">{{  translate('Email') }}</label>
                                            <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} rounded-0" value="{{ old('email') }}" placeholder="{{  translate('johndoe@example.com') }}" name="email" id="email" autocomplete="off">
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                            
                                        <!-- password -->
                                        <div class="form-group">
                                            <label for="password" class="fs-12 fw-700 text-soft-dark">{{  translate('Password') }}</label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control rounded-0 {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ translate('Password')}}" name="password" id="password">
                                                <i class="password-toggle las la-2x la-eye"></i>
                                            </div>
                                        </div>
                                            @if (session('error_message'))
                                                <div class="alert alert-danger">
                                                    <strong> {{ session('error_message') }} </strong>
                                                </div>
                                            @endif

                                       
                                        <!-- Submit Button -->
                                        <div class="mb-4 mt-4">
                                            <button type="submit" class="btn btn-primary btn-block fw-700 fs-14 rounded">{{  translate('Login') }}</button>
                                        </div>
                                    </form>
                                     <div class="mt-3 mr-4 mr-md-0">
                        <a href="{{ route('home') }}" class="ml-auto fs-14 fw-700 d-flex align-items-center text-primary" style="max-width: fit-content;">
                            <i class="las la-arrow-left fs-20 mr-1"></i>
                            {{ translate('Home')}}
                        </a>
                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                   
                </div>
            </div>
        </div>
    </section>
</div>

