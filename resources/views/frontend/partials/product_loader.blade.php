<style>
    #globalProductLoader .skeleton-card {
        height: 270px;
        padding: 16px;
        background-color: #fff;
        border: 1px solid #e6e6e6;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        animation: fadeIn 0.3s ease-in;
    }

    .skeleton-loader {
        background: linear-gradient(-90deg, #f0f0f0 0%, #e0e0e0 50%, #f0f0f0 100%);
        background-size: 400% 400%;
        animation: skeleton-blink 1.2s ease-in-out infinite;
        border-radius: 6px;
    }

    .skeleton-img {
        height: 140px;
        margin-bottom: 12px;
    }

    .skeleton-title {
        height: 16px;
        width: 80%;
        margin-bottom: 8px;
    }

    .skeleton-price {
        height: 14px;
        width: 40%;
    }

    @keyframes skeleton-blink {
        0% { background-position: 100% 0; }
        100% { background-position: -100% 0; }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div id="globalProductLoader" class="d-none">
    <section class="mb-4 pt-4">
        <div class="container sm-px-0 pt-2 px-1">
            <div class="row gutters-5 flex-wrap align-items-center px-2 mb-3">
                <div class="col">
                    <div class="d-flex align-items-center">
                        <h1 class="fs-18 fs-md-24 fw-700 text-dark">
                            {{ translate('Searching for results...') }}
                        </h1>
                    </div>
                </div>
            </div>

            <div class="row mx-2">
                @for ($i = 0; $i < 10; $i++)
                    <div class="col-5-custom mb-3 px-2">
                        <div class="skeleton-card">
                            <div class="skeleton-loader skeleton-img"></div>
                            <div class="skeleton-loader skeleton-title"></div>
                            <div class="skeleton-loader skeleton-price"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>
</div>
