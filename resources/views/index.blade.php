<!DOCTYPE html>
<html lang="en">

<head>

    <title>Məhsul haqqında</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- linkler -->
    <link rel="stylesheet" href="/assets/css/ekocart.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/assets/css/all.min.css">

</head>

<!-- BODY -->
<body>
    <header>
        <div class="header-top">
            <div class="custom-container">

                <div class="header-top-item">

                    <i class="fa-solid fa-store"></i>
                    <span>Welcome to Our store Ekocart</span>
                </div>

                <div class="header-top-item">

                    <i class="fa-solid fa-truck"></i>
                    <span>Free shipping worldwide</span>

                </div>

                <div class="sosial-icons">
                    <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://twitter.com/?lang=ru"><i class="fa-brands fa-twitter"></i></a>
                    <a href="https://www.linkedin.com/"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram"></i></a>
                </div>

            </div>

        </div>


        <!-- header 2 -->
        <div class="header-brand">
            <div class="custom-container">

                <div class="brand">
                    <a href="#">
                        <img src="/assets/img/brand.svg" alt="photo">
                    </a>
                </div>

                <div class="call-us">
                    <div>
                        <i class="fa-solid fa-mobile-screen"></i>
                    </div>
                    <span> Bizimlə əlaqə </span>
                    <a href="#">+994-77-717-77-17</a>
                </div>

                <!-- search bootstrap -->
                <form class="search" role="search">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Bütün kateqoriyalar</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                    <input type="search" placeholder="Axtar..." aria-label="Search">
                    <button class="btn" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

            </div>


            <!-- navbar -->
            <div class="navbar-layer">
                <div class="custom-container">
                    <nav>
                        <ul>

                            <li> <a href="">Əsas səhifə</a> </li>

                            @foreach($menus as $key => $menu)
                            <li>
                                <a href="">{{ $menu->name }}</a> 
                                @if(count($menu->categories)>0)
                                <i class="fa-solid fa-chevron-down icon-down"></i>
                                <div class="under-menu-box">
                                    @foreach($menu->categories as  $category)
                                    <div class="main-category-item">
                                        <a href="#">{{ $category->name }}</a>
                                        @if(count($category->subs)>0)
                                        <div class="sub-category-box">
                                            @foreach($category->subs as  $sub)
                                                <a href="#">{{ $sub->name }}</a>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div> 
                                    
                                    @endforeach
                                </div>
                                @endif
                            </li>
                            @endforeach
                            
                        </ul>
                    </nav>


                    <div class="navbar-icons">
                        <a href="#">
                            <i class="fa-regular fa-user"></i>
                        </a>

                        <a href="#">
                            <i class="fa-regular fa-heart"></i>
                        </a>

                        <a href="#">
                            <span class="notification" notification="0"></span>
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </header>


    <!-- SECTION  -->
    <section class="home-page">
        <div class="custom-container d-flex " style="justify-content: space-between;">
            <div class="align-items-center">
                <h1>{{ $data->name }}</h1>
            </div>

            <div class="home-shop">

                <nav>
                    <ul class="home-shop-icon">
                        <li>
                            <i class="fa-solid fa-house" id="home"></i>
                        </li>

                        <li><a href="/">Əsas səhifə</a></li>
                        <li class="slash"><a href="#">{{ $data->categoryName }}</a></li>
                        <li class="slash"><a href="#" id="product-image-color">{{ $data->name }}</a></li>

                    </ul>
                </nav>

            </div>

        </div>

    </section>


    <!-- SECTION 2 -->
    <section class="section-two">
        <div class="custom-container">
            <div class="detail-custom-container">

                <div class="product-img">
                    <img src="/uploads/products/{{ count($data->images)>0 ? $data->images[0]->name: ''}}" alt="">
                    <div class="image-tab-box">
                        @foreach($data->images as $key => $image)
                        <img src="/uploads/products/{{ $image->name}}" class="{{ $key===0 ? 'active-tab' : '' }}" alt="" >
                        @endforeach
                    </div>
                </div>

                <div>
                    <div class="product-details">

                        <h3>{{ $data->name }}</h3>

                        <div class="rating">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half"></i>
                        </div>



                        <div class="pr">

                            <span class="product-price">

                                <h4>
                                  {{ $data->price }}AZN
                                    <!-- <del class="text-muted-h4" id="sale">2600$</del> -->

                                </h4>

                            </span> 

                            <ul>
                                <!-- <li>Availibility: <span>In Stock</span> </li> -->
                                <li>Kateqoriya: <span>{{ $data->categoryName }}</span> </li>

                            </ul>

                        </div>

                        <p id="prod-det-p">
                          {{ $data->description }}
                        </p>

                    </div>

                    <div class="align-items">
                        <div class="product-num"> 
                            <i class="fa-solid fa-minus iconplus"></i>
                            <input type="text" id="input" name="product" value="1">
                            <i class="fa-solid fa-plus iconminus"></i>  
                        </div>
                        <div class="cart-action-box">
                            <button><i class="fa-solid fa-cart-shopping"></i>Səbətə at</button>    
                        </div>
                        <!-- <div class="product-colors">
                            <div class="red"></div>
                            <div class=""></div>
                        </div> -->

                    </div>

                    
 
                </div>

            </div>

        </div>

    </section> 


    <!-- SECTION TABLE -->
    <section class="section-table">
        <div class="custom-container">

            <ul class="nav nav-tabs" id="product-details-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="false">
                        Ətraflı məlumat
                    </button>
                </li>
                <!-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                        Description
                    </button>
                </li>  -->
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="product-table">
                        <table>
                          @foreach($data->specifications as $sp)
                            <tr>
                              <td>{{ $sp->name }}</td> 
                              <td>{{ $sp->value }}</td> 
                            </tr>
                          @endforeach

                          @foreach($data->values as $value)
                            <tr>
                              <td>{{ $value->type }}</td> 
                              <td>{{ $value->name }}</td> 
                            </tr>
                          @endforeach
                        </table> 
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"></div> 
            </div>
        </div>
    </section>


    <!-- Suggested products -->
    <section class="product-slider">
        <div class="custom-container">

            <div class="less-title">
                <h6>Təklif olunan</h6>
                <h2>Oxşar məhsullar</h2>
            </div>


            <!-- Slider -->  
            <div class="addition-offers owl-carousel owl-theme">
                @foreach($data->others as $other)
                    @if(count($other->images)>0)
                    <div class="item">
                        <a href="">
                            <img src="/uploads/products/{{ $other->images[0]->name }}" alt="" >
                            <h3>{{ $other->name }}</h3>
                            <p>{{ $other->price }} AZN</p>
                        </a>
                    </div>
                    @endif 
                @endforeach 
            </div> 

        </div>
    </section>
 


    <!-- FOOTER -->
    <footer class="footer-menu">
        <div class="custom-container">
            <div class="row">
                <div class="col-md-3 footer-top"> 
                    <img src="/assets/img/brand.svg" alt="photo">
                    <h5>
                        Əlçatan -  In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document
                    </h5>

                    <div class="footer-icons">
                        <ul>
                            
                            <li>
                                <a href="https://www.facebook.com/">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                            </li>
                            
                            <li>
                                <a href="https://www.instagram.com/">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                            </li>

                            <li>
                                <a href="https://twitter.com">
                                    <i class="fa-brands fa-twitter"></i>
                                </a>
                            </li>

                            <li>
                                <a href="https://www.linkedin.com">
                                    <i class="fa-brands fa-linkedin-in"></i>
                                </a>
                            </li> 

                        </ul>

                    </div>

                </div>
                <div class="col-md-3 footer-top">
                    <div class="second-info" id="info">
                        <h2>Keçid linkləri</h2>
                        <ul class="li-style">
                        @foreach($menus as $key => $menu)
                            <li>
                                <a href="">{{ $menu->name }}</a> 
                            </li>
                        @endforeach
                        </ul>

                    </div>
                </div>
                
                <div class="col-md-3 footer-top">
                    <div class="second-info" id="info">
                        <h2>Özəlliklər</h2>
                        <ul class="li-style">
                            <li><a href="#">Xidmətlərimiz</a></li> 
                            <li><a href="#">Üstünlüklərimiz</a> </li>
                            <li><a href="#">Müraciət və Geri dönüş</a> </li>
                            <li><a href="#">Karyeranızı bizimlə qurun</a></li> 
                        </ul>

                    </div>
                </div>

                <div class="col-md-3 footer-top">
                   
                    <div class="contact">
                        <div class="icon"> <i class="fa-solid fa-map"></i> </div>
                        <h6>Bizim ünvanımız</h6>
                        <p>Bakı şəhər</p>
                    </div>

                   
                    <div class="contact">
                        <div class="icon"> <i class="fa-solid fa-envelope"></i> </div>
                        <h6>Email us</h6>
                        <p>elchatan@gmail.com</p>
                    </div>
  
                    <div class="contact">
                        <div class="icon"> <i class="fa-solid fa-mobile-screen-button contact-icon"></i> </div>
                        <h6>Phone number</h6>
                        <p>+9977777117</p>
                    </div>
 
                    <div class="contact">
                        <div class="icon"> <i class="fa-regular fa-clock"></i> </div>
                        <h6>İş saatları</h6>
                        <p>Bazar ertəsi - Cümə: 10:00 - 22:00</p>
                    </div>
                </div>
            </div>


        </div>
        </div>
    </footer>

    

    <!-- <script src="js/"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
        integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS"
        crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.js"></script>
    <script src="../assets/js/owl.carousel.min.js"></script>
    <script>
        $( document ).ready(function() {
            $(".image-tab-box img").click(function(){
                let path = $(this).attr("src");
                $(".product-img>img").attr("src", path);

                $(".image-tab-box img").attr("class", "");
                $(this).attr("class", "active-tab");
            });

            $('.owl-carousel').owlCarousel({
                loop:true,
                margin:10,
                dots: false,
                autoplay:true,
                nav:true,
                navText: ['<i class="fa-solid fa-light fa-arrow-left"></i>',"<i class='fa-solid fa-light fa-arrow-right'></i>"],
                responsive:{
                    0:{
                        items:1,
                        nav: true,
                    },
                    600:{
                        items:3,
                        nav:true,
                    },
                    1000:{
                        items: 3, 
                        nav: true,
                        // navText: [&#x27;next&#x27;,&#x27;prev&#x27;]
                    }
                }
            })
        });

       
    </script>
</body>

</html>