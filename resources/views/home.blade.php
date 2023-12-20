@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="/assets/css/custom.css">
@endsection

@section('content') 

    <section class="product-preview-carousel"> 

        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="assets/img/banner-1.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-md-block">
                        <h3>Özəl anlarınızın başlanğıcı</h3>
                        <p>Hər anınıza dəyər qatan cehiz məhsulları</p> 
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="assets/img/banner-2.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-md-block">
                        <h3>Səadətinizin memarı</h3>
                        <p>Sizin üçün seçilmiş cehiz məhsulları</p>
                    </div>
                </div> 
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                <i class="fa-solid fa-light fa-arrow-left"></i>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                <i class='fa-solid fa-light fa-arrow-right'></i>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
 
    </section>
 
    <!-- SECTION-PRODUCTS --> 
    <section class="section-product">
        <div class="custom-container">
            <div class="text">
                <h6 class="new-collection">— Yeni Kolleksiya</h6>
                <h2 class="trending-prod">Ən çox satılan məhsullar</h2>
            </div>


            <div class="product-box">
                @foreach($bestSelling as $key => $product)
                    @if(count($product->images)>0)
                    <a href="/product/{{ $product->uuid }}">
                        <div class="product-item">
                            <img src="uploads/products/{{ $product->images[0]->name }}" alt="">

                            <div class="card-body">
                                <div class="product-info">
                                    <a href="/product/{{ $product->uuid }}"> {{ $product->name }}</a>
                                </div>
                                <div class="intro">
                                    <span class="last-price"> 
                                        {{ $product->price }} AZN
                                    </span>
                                    <div class="reyting">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>

                                    </div>

                                </div>
                            </div> 
                        </div> 
                    </a>
                    @endif
                @endforeach
            </div> 
        </div> 
    </section> 
  
    <!-- SECTION OUR PRODUCTS SLIDE -->
    <section class="our-prod-slide section-product">
        <div class="custom-container">
            <div class="row">
                <div>
                    <h6 class="our-prod-new-collection">— Yeni Kalleksiya</h6>
                    <h2 class="our-prod-our-product">Populyar axtarışlar</h2>
                </div>
  
                    <!-- <i class="fa-solid fa-chevron-left"></i> -->
                    <div class="product-box"> 
                        @foreach($populars as $key => $product)
                            @if(count($product->images)>0)
                            <a href="/product/{{ $product->uuid }}">
                                <div class="product-item">
                                    <img src="uploads/products/{{ $product->images[0]->name }}" alt=""> 
                                    <div class="card-body">
                                        <div class="product-info">
                                            <a href="/product/{{ $product->uuid }}">{{ $product->name }}</a>
                                        </div>
                                        <div class="intro">
                                            <span class="last-price"> 
                                                {{ $product->price }} AZN
                                            </span>
                                            <div class="reyting">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i> 
                                            </div> 
                                        </div>
                                    </div> 
                                </div> 
                            </a>
                            @endif
                        @endforeach 
                    </div> 
 
            </div>
        </div>
    </section>

     <!-- SECTION newsletter -->
     <section class="subscribe-section">
        <div class="custom-container">
            <div class="subscription-title">
                <h6 class="newsletter-h6">— Yeniliklər</h6>
                <h2 class="newsletter-h2">Yeniliklərdən xəbərdar olmaq üçün </h2>
                <p></p>
            </div> 
            <form class="email">
                <input type="email" placeholder="Email adress" aria-label="Email address">
                <button class="btn" type="button">Abunə ol</button>
            </form> 
            <div class="newsletter-footer">
                Abunəçilərə birinci ay üçün xidmət ödənişsizdir 
            </div> 
        </div> 
    </section>

    <!-- SECTION-TWO -->
    <section class="section-page-two">
        <div class="custom-container">
            <div class="row">
                <div class="col-md-3">
                    <div class="d-flex">
                        <div class="icon-page-two">
                            <i class="fa-solid fa-truck"></i>
                        </div>

                        <div class="section-div-h5">
                            <h5>Çatdırılma xidməti</h5>
                            <p> 300 AZN + alış verişdə çatdırılma ödənişsizdir </p>
                        </div>
                    </div>

                </div>

                <div class="col-md-3">
                    <div class="d-flex">
                        <div class="icon-page-two">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>

                        <div class="section-div-h5">
                            <h5>Məhsulun qaytarılması</h5>
                            <p>Alınan məhsullar 14gün ərzində qaytarıla bilər</p>
                        </div>
                    </div>

                </div>

                <div class="col-md-3">
                    <div class="d-flex">
                        <div class="icon-page-two">
                            <i class="fa-solid fa-lock"></i>
                        </div>

                        <div class="section-div-h5">
                            <h5>Təhlükəsiz ödəniş</h5>
                            <p>Alınan məhsulu online ödəmə imkanı</p>
                        </div>
                    </div>

                </div>


                <div class="col-md-3">
                    <div class="d-flex">
                        <div class="icon-page-two">
                            <i class="fa-sharp fa-solid fa-headset"></i>
                        </div>

                        <div class="section-div-h5">
                            <h5>24/7 Dəstək</h5>
                            <p> Müştərilərimiz üçün 24/7 dəstək xidməti </p>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection

@section('js') 

<script>

    $(document).ready(function () {

        $(".subscribe-section .email button").click(function() {
            const val = $(".subscribe-section .email input").val();
            if(val.length>0 && val.includes("@")) {
                const data = {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    "mail": val,
                };

                $.ajax({
                    url: '/api/add-subscriber',
                    type: 'post',
                    data,
                    success: function (response) {
                        $(".subscribe-section .email input").val("");
                        $(".subscribe-section .subscription-title p").css("display", "block").text(response.data.message)
                  
                    }
                });
            }
        })

        

    });

</script>

@endsection