<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css" integrity="sha512-EZLkOqwILORob+p0BXZc+Vm3RgJBOe1Iq/0fiI7r/wJgzOFZMlsqTa29UEl6v6U6gsV4uIpsNZoV32YZqrCRCQ==" crossorigin="anonymous" />
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<style>
    .swiper-slide div.columns {
        margin-right: 0%;
        margin-left: 0%;
        height: 350px;
    }
    .left-block{
        background-color: #434343;
        color: white;
        padding: 3%;
        min-height: 350px;
    }
    .left-block h1{
        font-weight: bold;
        font-size: 24px;
    }
    .left-block h3{
        font-size: 18px;
    }
    .left-block hr{
        width: 10%;
        margin-bottom: 5%;
        float: left;
        color: white;
    }
    .right-block img{
        min-height: 350px;
        width: 100%;
    }

    .swiper-pagination {
        bottom: 10%;
        left: 0%;
        width: 30%;
    }
    .swiper-pagination-bullet-active {
        background-color: white;
    }
    .swiper-pagination-bullet {
        background-color: gray;
    }

    .swiper-pagination-bullet {
        margin-right: 25%;
    }
    @media (max-width: 750px) {
        .swiper-pagination {
            top:40%;
            left:40%;
            width: 30%;
        }
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var mySwiper = new Swiper('.swiper-container', {
            // Optional parameters
            direction: 'horizontal',
            loop: true,
            autoplay: true,
            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
            },
        })
    });
</script>
<!-- Slider main container -->
<div class="swiper-container">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
        <!-- Slides -->
        <?php foreach ($banners as $banner) : ?>
            <div class="swiper-slide">
                <a <?=(!empty($banner['url'])) ? 'href="'.$banner['url'].'"' : ''; ?> target="_blank" >
                    <div class="row">
                        <div class="four columns left-block">
                            <h1><?=wp_trim_words($banner['title'],10)?></h1>
                            <h3><?=wp_trim_words($banner['description'],15)?></h3>
                            <hr>
                            <div class="swiper-pagination"></div>
                        </div>
                        <div class="eight columns right-block">
                            <img src="<?php echo wp_get_attachment_url($banner['image_attachment_id']); ?>" alt="<?php echo wp_get_attachment_url($banner['image_attachment_id']); ?>">
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>