<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
<style>
    .smartfba-video {
        position: relative;
        overflow: hidden;
        width: 100%;
        padding-top: 56.25%;
    }

    .responsive-iframe {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        width: 100%;
        height: 100%;
    }

    .effect {
        width: 100%;
        padding: 50px 0px 70px 0px;
    }

    .effect h2 {
        font-size: 25px;
        letter-spacing: 3px;
    }

    .effect:nth-child(2) {
        margin-top: 50px;
    }

    .effect:nth-child(2n+1) {
        background-color: #fff;
    }

    .effect:nth-child(2n+1) h2 {
        color: #212121;
    }

    .effect .buttons {
        margin-top: 50px;
        display: flex;
        justify-content: center;
    }

    .effect a:last-child {
        margin-right: 0px;
    }

    /*common link styles !!!YOU NEED THEM*/
    .effect {
        /*display: flex; !!!uncomment this line !!!*/
    }

    .effect a {
        text-decoration: none !important;
        color: #fff;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        margin-right: 20px;
        font-size: 25px;
        overflow: hidden;
        position: relative;
    }

    .effect a i {
        position: relative;
        z-index: 3;
    }

    .effect a.fb {
        background-color: #3b5998;
    }

    .effect a.tw {
        background-color: #00aced;
    }

    .effect a.g-plus {
        background-color: #dd4b39;
    }

    .effect a.dribbble {
        background-color: #ea4c89;
    }

    .effect a.pinterest {
        background-color: #cb2027;
    }

    .effect a.insta {
        background-color: #ea4c89;
    }

    .effect a.in {
        background-color: #007bb6;
    }

    .effect a.vimeo {
        background-color: #1ab7ea;
    }

    /* aeneas effect */
    .effect.aeneas a {
        transition: transform 0.4s linear 0s, border-top-left-radius 0.1s linear 0s, border-top-right-radius 0.1s linear 0.1s, border-bottom-right-radius 0.1s linear 0.2s, border-bottom-left-radius 0.1s linear 0.3s;
    }

    .effect.aeneas a i {
        transition: transform 0.4s linear 0s;
    }

    .effect.aeneas a:hover {
        transform: rotate(360deg);
        border-radius: 50%;
    }

    .effect.aeneas a:hover i {
        transform: rotate(-360deg);
    }
</style>
<div class="content pt-0">
    <div class="card">
        <div class="card-header">
            <h2 class="text-center font-weight-bold text-uppercase">#1 in Amazon Automation</h2>
        </div>
        <div class="card-body">
            <div class="smartfba-video">
                <iframe class="responsive-iframe" src="https://www.youtube.com/embed/SbyUfvZDqoU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <div class="effect aeneas">
                <h2 class="text-center font-weight-bold text-uppercase">Find Us</h2>
                <div class="buttons">
                    <a href="https://www.facebook.com/SmartFBA" target="_blank" class="fb" title="Find us on Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <a href="https://www.youtube.com/c/smartfba" target="_blank" class="pinterest" title="Find us on Youtube"><i class="fa fa-youtube" aria-hidden="true"></i></a>
                    <a href="https://www.instagram.com/smartfba/" target="_blank" class="insta" title="Find us on Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                    <a href="https://www.linkedin.com/company/smartfba/" target="_blank" class="in" title="Find us on Linked In"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>

    </div>
</div>