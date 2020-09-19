<link href="https://cdn.jsdelivr.net/npm/glider-js@1/glider.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css" integrity="sha512-EZLkOqwILORob+p0BXZc+Vm3RgJBOe1Iq/0fiI7r/wJgzOFZMlsqTa29UEl6v6U6gsV4uIpsNZoV32YZqrCRCQ==" crossorigin="anonymous" />
<script src="https://cdn.jsdelivr.net/npm/glider-js@1/glider.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        new Glider(document.querySelector('.js-carousel--simple'), {
            slidesToShow: 1,
            slidesToScroll: 1,
            scrollLock: true,
            dots: '.js-carousel--simple-dots',
            rewind: true,
            responsive: [
                {
                    // screens greater than >= 775px
                    breakpoint: 775,
                    settings: {
                        // Set to `auto` and provide item width to adjust to viewport
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        itemWidth: 150,
                        duration: 0.25
                    }
                },{
                    // screens greater than >= 1024px
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        itemWidth: 150,
                        duration: 0.25
                    }
                }
            ]
        });
    });

</script>



<article class="c-carousel c-carousel--simple">
    <div class="c-carousel__slides js-carousel--simple">
        <?php foreach ($banners as $banner) : ?>
            <article class="c-carousel__slide">
                <a <?=(!empty($banner['url'])) ? 'href="'.$banner['url'].'"' : ''; ?> target="_blank" >
                    <div class="row">
                        <div class="four columns left-block">
                            <h1>
                                <?=$banner['title']?>
                            </h1>
                            <h3>
                                <?=$banner['description']?>
                            </h3>
                            <hr>
                            <br>
                            <div class="js-carousel--simple-dots"></div>
                        </div>
                        <div class="eight columns right-block">
                            <img src="<?php echo wp_get_attachment_url($banner['image_attachment_id']); ?>" alt="<?php echo wp_get_attachment_url($banner['image_attachment_id']); ?>">
                        </div>
                    </div>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
</article>

<style>
    .c-carousel__slide div.columns {
        margin-right: 0%;
        margin-left: 0%;
        height: 350px;
    }
    .left-block{
        background-color: #1e1e1e;
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
        float: left;
        margin-bottom: 5%;
    }
    .right-block img{
        min-height: 350px;
        width: 100%;
    }
    .glider-dot.active {
        background:#ffffff;
    }

</style>