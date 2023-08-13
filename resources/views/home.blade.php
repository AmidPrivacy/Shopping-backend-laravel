@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="/assets/css/custom.css">
@endsection

@section('content') 

    <section class="product-preview-carousel"> 

        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="assets/img/slide1.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h3>First slide label</h3>
                        <p>Some representative placeholder content for the first slide.</p>
                        <a href="">Ətraflı</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="assets/img/slide2.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Second slide label</h3>
                        <p>Some representative placeholder content for the first slide.</p>
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

    <!-- SECTION-PRODUCTS -->
    <section class="section-product">
        <div class="custom-container">
            <div class="text">
                <h6 class="new-collection">— Yeni Kolleksiya</h6>
                <h2 class="trending-prod">Ən çox satılan məhsullar</h2>
            </div>


            <div class="product-box">
                @foreach($products as $key => $product)
                    @if(count($product->images)>0)
                    <div class="product-item">
                        <img src="uploads/products/{{ $product->images[0]->name }}" alt="">

                        <div class="card-body">
                            <div class="product-info">
                                <a href="#">{{ $product->name }}</a>
                            </div>
                            <div class="intro">
                                <span class="last-price">
                                    <!-- <del class="old-price">
                                        $
                                        1200
                                    </del> -->
                                    
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
                    @endif
                @endforeach
            </div>

        </div>

    </section> 

    <!-- SPECIAL OFFER -->
    <section class="special-offer">
        <div class="bg-image">
            <img src="assets/img/03.jpg" id="background-image" alt="">

            <div class="bg-text">
                <span class="limited-offer">MƏHDUD TƏKLİF</span>
                <h2 class="bg-h2">
                    Aşağıdakı məhsullara
                    <br>
                    <span class="bg-span">60% -dək</span>
                    ENDİRİM
                </h2> 
            </div>

        </div>

    </section>

    <!-- SECTION OUR PRODUCTS SLIDE -->
    <section class="our-prod-slide">
        <div class="container-fluid">
            <div class="row">
                <div>
                    <h6 class="our-prod-new-collection">— Yeni Kalleksiya</h6>
                    <h2 class="our-prod-our-product">Bizim Məhsullar</h2>
                </div>

                <!-- <ul class="ul-nav-tabs">
                    <li class="li-nav-item"><a href="#">Top Rated</a></li>
                    <li class="li-nav-item"><a href="#">New Product</a></li>
                    <li class="li-nav-item"><a href="#">Best Seller</a></li>
                </ul> -->


                <div id="left" class="wrapper">
                    <!-- <i class="fa-solid fa-chevron-left"></i> -->
                    <div class="carousel">
                        <a href="#"><img src="assets/img/carouselimage1.jpeg" alt="" class="carousel-width"></a>
                        <a href="#"><img src="assets/img/carouselimage2.jpeg" alt="" class="carousel-width"></a>
                        <a href="#"><img src="assets/img/carouselimage3.jpeg" alt="" class="carousel-width"></a>
                        <a href="#"><img src="assets/img/im2jpeg.jpeg" alt="" class="carousel-width"></a>
                        <a href="#"><img src="assets/img/img1.peg.jpeg" alt="" class="carousel-width"></a>
                        <a href="#"><img src="assets/img/img3.jpeg" alt="" class="carousel-width"></a>
                    </div>
                    <!-- <i id="right" class="fa-solid fa-chevron-right"></i> -->

                </div>


            </div>
        </div>
    </section>

     <!-- SECTION newsletter -->
     <section class="subscribe-section">
        <div class="custom-container">
            <div>
                <h6 class="newsletter-h6">— Yeniliklər</h6>
                <h2 class="newsletter-h2">Yeniliklərdən xəbərdar olmaq üçün </h2>
            </div>

            <form class="email" role="doc-example">
                <input type="email" placeholder="Email adress" aria-label="Email address">
                <button class="btn" type="submit">Abunə ol</button>
            </form>

            <div class="newsletter-footer">
                Abunəçilərə birinci ay üçün xidmət ödənişsizdir 
            </div>



        </div>

    </section>


    <!-- SECTİN COMPANY LOGO -->
    <section class="section-client-logo">
        <div class="custom-container"> 
            <div class="d-flex flex-wrap">
                @foreach($centers as $key => $company)
                <div class="client-logo"> <img src="uploads/{{ $company->picture }}" alt=""></div>
                @endforeach
            </div> 
        </div>
    </section>

@endsection