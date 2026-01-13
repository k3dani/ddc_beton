jQuery(document).ready(function ($) {
    window.addEventListener('load', () => {

        const infoBox = document.querySelectorAll('.info-box');
        const content = document.querySelectorAll('.info-box .content')

        infoBox.forEach((val, i) => {
            val.addEventListener('mouseenter', () => {
                if (infoBox[i].classList.contains('active')) {
                    infoBox[i].classList.remove('active')
                } else {
                    infoBox.forEach(val => {
                        infoBox[i].classList.remove('active')
                    })

                    infoBox[i].classList.add('active')
                }
            })
        })

        infoBox.forEach((val, i) => {
            val.addEventListener('mouseleave', () => {
                if (infoBox[i].classList.contains('active')) {
                    infoBox[i].classList.remove('active')
                    content[i].classList.remove('active')
                } else {
                    infoBox.forEach(val => {
                        infoBox[i].classList.remove('active')
                    })
                    content.forEach(val => {
                        content[i].classList.remove('active')
                    })

                    infoBox[i].classList.remove('active')
                    content[i].classList.remove('active')
                }
            })
        })

        content.forEach((val, i) => {
            val.addEventListener('click', () => {
                if (content[i].classList.contains('active')) {
                    content[i].classList.remove('active')
                } else {
                    content.forEach(val => {
                        val.classList.remove('active')
                    })

                    content[i].classList.add('active')
                }

            })
        });

        const menuItem = document.querySelectorAll('.product-menu ul .menu-item-has-children');
        menuItem.forEach((val, i) => {
            val.addEventListener('click', () => {
                if (menuItem[i].classList.contains('active')) {
                    menuItem[i].classList.remove('active');
                } else {
                    menuItem.forEach(val => {
                        val.classList.remove('active');
                    })
                    menuItem[i].classList.add('active');
                }
            });
        })

        if (!document.querySelector('body').classList.contains('logged-in')) {
            var parent = document.querySelectorAll('.product-menu ul .menu-item-has-children .sub-menu');
            var child = document.querySelectorAll('.background');
            parent.forEach((val, i) => {
                parent[i].prepend(child[i]);
            })
        }

    });
    // Smooth scroll

    $('.smooth[href*="#"], .smooth a[href*="#"]').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {

            var target = jQuery(this.hash);
            target = target.length ? target : jQuery('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                jQuery('html,body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });


    // remove last digit fro mquantity
    // Scroll header


    $(function () {
        //caches a jQuery object containing the header element
        var header = $("header");
        $(window).scroll(function () {
            var scroll = $(window).scrollTop();
            if (scroll >= 1) {
                header.addClass("scrolled");
            } else {
                header.removeClass("scrolled");
            }
        });
        if ($(window).scrollTop() >= 1) {
            header.addClass("scrolled");
        }
    });


    // Responsive menu


    $(".resp-btn, .resp-close").click(function () {
        $(this).toggleClass('active-btn');
        $('.menu').toggleClass('active-menu');
    });


    /* pass value fom input to input */

    $('.product.betonas').on('change', 'input', function () {

        var text = $('.product.betonas .qty').val()
        localStorage.setItem('quantity', text);

        var sufix = $('.product.betonas .qty').attr("sufix");
        localStorage.setItem('sufix1', sufix);

        var price = $('#product_total_price .price').html();
        localStorage.setItem('prev-price', price);

        var prevTitle = $('.product.betonas .col-md-5 h1.prev-title').html();
        localStorage.setItem('prev-title', prevTitle);
    });
    if ($(".pristatymas").length > 0) {
        var grossAmount = parseFloat(localStorage.getItem('prev-price'));
        var VATRate = 0.27; // VAT rate of 27%
        var netAmount = grossAmount / (1 + VATRate); // Calculate the net amount (VAT should be subtracted from gross amount)

        var VAT = netAmount; // Set VAT as the net amount
        $('.product.pristatymas input.yith-wcbk-booking-persons').val(Math.ceil((localStorage.getItem('quantity')) / 6));
        $('.product.pristatymas .prev-price p.result').html( '<span class="net"> Gross: ' + localStorage.getItem('prev-price')  +'</span>'  + ' <span> Net: ' + VAT.toFixed()  + 'Ft' + '</span>');
        $('.product.pristatymas .prev-quantity p.result').html(localStorage.getItem('quantity') + localStorage.getItem('sufix1'));
        $('.product.pristatymas .prev-titile p.result').html(localStorage.getItem('prev-title'));

    }


    $('.betono-blokeliai').on('change', 'input', function () {
        var text = $('.betono-blokeliai .qty').val()
        localStorage.setItem('quantity', text);

        var sufix = $('.betono-blokeliai .qty').attr("sufix");
        localStorage.setItem('sufix1', sufix);

        var price = $('#product_total_price .price').html();
        localStorage.setItem('prev-price', price);

        var prevTitle = 'Betoonplokid';
        localStorage.setItem('prev-title', prevTitle);
    });


    // image lightbox

    $('.image-popup-no-margins').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        closeBtnInside: false,
        fixedContentPos: true,
        mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
        image: {
            verticalFit: true
        }
    });


    // map popup

    $(function () {
        $('.popup-modal').magnificPopup({
            type: 'inline',
            preloader: false,
            closeOnBgClick: true,

        });
        $(document).on('click', '.popup-modal-dismiss', function (e) {
            e.preventDefault();
            $.magnificPopup.close();
        });
    });




    $('#roast_custom_options').change(function () {
        var selectedMethod = $(this).children("option:selected").val();
        if (selectedMethod === 'Tulen ise järele') {
            $('.pristatymas .yith-wcbk-form-section-persons-wrapper').css("display", "none");
            $('.pristatymas .yith-wcbk-form-section-services-wrapper').css("display", "none");
            $('.pristatymas .custom-delivery.second').css("display", "block");
        } else {
            $('.pristatymas .yith-wcbk-form-section-persons-wrapper').css("display", "flex");
            $('.pristatymas .yith-wcbk-form-section-services-wrapper').css("display", "block");
            $('.pristatymas .custom-delivery.second').css("display", "none");
        }
    });


    // produkto tkesto susikleidimas ant mobile

    var windowWidth = $(window).width();
    if (windowWidth < 768) {
        $('.description-text').slideUp("slow", function () {
        });
        $(".toggle-text").click(function () {
            $(this).toggleClass('active');
            $('.description-text').slideToggle("slow", function () {
            });
        });
    }


    // skaiciuokle

    $('ul.tabs li').click(function () {
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#" + tab_id).addClass('current');
    });


    //staciakampis
    $('.form-stac .calc').click(function () {
        var ilgis = $('.form-stac input.ilgis').val();
        var plotis = $('.form-stac input.plotis').val();
        var aukstis = $('.form-stac input.aukstis').val();
        var midResult = ilgis * plotis * aukstis;
        var percentages = midResult * 0.05;
        var finalResult = midResult + percentages;
        $('.form-stac input.result').val(finalResult.toFixed(1));
        $('.form-stac input.mid-result').val(midResult.toFixed(1));
    });

    //cilindras

    $('.form-cil .calc').click(function () {
        var diametras = $('.form-cil input.diametras').val();
        var spindulys = Number(diametras) / 2;
        var aukstis = Number($('.form-cil input.aukstis').val());
        var kiekis = Number($('.form-cil input.kiekis').val());
        var midResult = 3.14 * (spindulys * spindulys) * aukstis * kiekis;
        var percentages = midResult * 0.1;
        var finalResult = midResult + percentages;
        $('.form-cil input.result').val(finalResult.toFixed(1));
        $('.form-cil input.mid-result').val(midResult.toFixed(1));
    });


    //rosverkas

    $('.form-ros .calc').click(function () {
        var perimetras = $('.form-ros input.perimetras').val();
        var a = Number($('.form-ros input.a').val());
        var b = Number($('.form-ros input.b').val());
        var midResult = a * b * perimetras;
        var percentages = midResult * 0.02;
        var finalResult = midResult + percentages;
        $('.form-ros input.result').val(finalResult.toFixed(1));
        $('.form-ros input.mid-result').val(midResult.toFixed(1));
    });


    // show hide product select fields
    $('.yith-wcbk-booking-form-totals').after(`
           <div class="price-roast-atsiimti-punkte">
			<div class="wrapper" style="display: flex; width: 100%; justify-content: space-between;">
				<div class="yith-wcbk-booking-form-total__label" style="font-weight: 500; font-size: 2rem;">Kõik kohaletoimetamiseks</div>
				<div class="yith-wcbk-booking-form-total__value"><span class="woocommerce-Price-amount amount" style="font-weight: 500; font-size: 2rem; "><bdi><span class="woocommerce-Price-currencySymbol">€</span>420,00</bdi></span></div>
    		</div>
			</div>
            `);


    $('.price-roast-atsiimti-punkte').hide();
    $('#pickup_locations_custom_options_field').hide();
    $('#roast_custom_options').change(function () {
        if ($(this).val() == 'Toimeta objektile') {
            $('#coordinatesInputs').show();
            $('#pickup_locations_custom_options_field').hide();
            $('.yith-wcbk-booking-form-totals').show();
            $('.price-roast-atsiimti-punkte').hide();
        } else if ($(this).val() == 'Tulen ise järele') {
            $('#pickup_locations_custom_options_field').show();
            $('#coordinatesInputs').hide();
            $('.yith-wcbk-booking-form-totals').hide();
            $('.price-roast-atsiimti-punkte').show();
            $('.yith-wcbk-form-section-dates-date-time label').html('Kedvelt felvételi időpont');
            $('.yith-wcbk-booking-form-total__value bdi').html('€ 0,00');
            $('#yith-wcbk-booking-persons').val('1');

        }
    });


    // igno karusele

    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        navText: ["<img src='/wp-content/themes/wam/assets/images/preview.svg'>", "<img src='/wp-content/themes/wam/assets/images/next.svg'>"],
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })

    $('.owl-carousel-eco').owlCarousel({
        loop: true,
        margin: 16,
        nav: true,
        navText: ["<img src='/wp-content/themes/wam/assets/images/preview.svg'>", "<img src='/wp-content/themes/wam/assets/images/next.svg'>"],
        dots: false,
        autoWidth: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 4
            }
        }
    })

    $('.owl-carousel-ecocrete').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        dots:false,
        navText: ["<div class='arrow'>" +
        "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"14.617\" height=\"23.577\" viewBox=\"0 0 14.617 23.577\">\n" +
        "  <g id=\"Group_1489\" data-name=\"Group 1489\" transform=\"translate(11.788 1.414) rotate(90)\">\n" +
        "    <path id=\"Path_582\" data-name=\"Path 582\" d=\"M0,10.374,10.374,0,20.748,10.374\" transform=\"translate(0 0)\" fill=\"none\" stroke=\"#fff\" stroke-width=\"4\"/>\n" +
        "  </g>\n" +
        "</svg>\n" +
        "</div>",
            "<div class='arrow'>" +
            "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"14.617\" height=\"23.577\" viewBox=\"0 0 14.617 23.577\">\n" +
            "  <g id=\"Group_1489\" data-name=\"Group 1489\" transform=\"translate(11.788 1.414) rotate(90)\">\n" +
            "    <path id=\"Path_582\" data-name=\"Path 582\" d=\"M0,10.374,10.374,0,20.748,10.374\" transform=\"translate(0 0)\" fill=\"none\" stroke=\"#fff\" stroke-width=\"4\"/>\n" +
            "  </g>\n" +
            "</svg>\n" +
            "</div>"],
        // autoWidth: true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    })


        const submenu = document.querySelectorAll('.menu-item-has-children .sub-menu');

        const haschildren = document.querySelectorAll('.menu-item-has-children');

        haschildren.forEach((val,i )=> {
            val.addEventListener('click', () => {
                if (val.classList.contains('active')) {
                    val.classList.remove('active')
                } else {
                    val.classList.add('active')
                }
                if (submenu[i].classList.contains('active')) {
                    submenu[i].classList.remove('active');
                } else {
                    submenu[i].classList.add('active')
                }
            })
        })

    window.addEventListener('resize', (event) => {

        const submenu = document.querySelectorAll('.menu-item-has-children .sub-menu');

        const haschildren = document.querySelectorAll('.menu-item-has-children');

        haschildren.forEach((val,i )=> {
            val.addEventListener('click', () => {
                if (val.classList.contains('active')) {
                    val.classList.remove('active')
                } else {
                    val.classList.add('active')
                }
                if (submenu[i].classList.contains('active')) {
                    submenu[i].classList.remove('active');
                } else {
                    submenu[i].classList.add('active')
                }
            })
        })

    })


    //2022 08 02 max heigh-----------------------------------------------------------------------

    function equalheight() {
        if($(window).width() > 768) {
            $('.boxes .box').each(function (index) {
                var maxHeight = 0;
                $(this).find('.top').height('auto');
                $(this).find('.top').each(function (index) {
                    if ($(this).height() > maxHeight)
                        maxHeight = $(this).height();
                });
                $('.boxes .box .top').height(maxHeight);
            });
        }
    }
    $(document).ready(function () {
        equalheight();
    });
    $(window).bind("resize", equalheight);

// betono blokeliu fixxai

    $('.pristaymas-blokeliams .custom-delivery label').text('Valige, kuidas soovite oma betoonplokke koguda');

    $( document ).ajaxComplete(function() {
        $('.pristaymas-blokeliams .yith-wcbk-booking-form-total').html('<h3>Juht võtab teiega ühendust täpse tarnehinna saamiseks.</h3>');
    });


});



// jei naudojama nuolaida



