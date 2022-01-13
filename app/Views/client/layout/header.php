<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="icon" type="image/x-icon" href="/assets/images/favicon.png">
<!-- Global stylesheets -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
<link href="/assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
<link href="/assets/css/all.min.css" rel="stylesheet" type="text/css">
<!-- /global stylesheets -->

<!-- Core JS files -->
<script src="/assets/js/main/jquery.min.js"></script>
<script src="/assets/js/main/bootstrap.bundle.min.js"></script>
<!-- /core JS files -->

<!-- Theme JS files -->
<script src="/assets/js/plugins/visualization/echarts/echarts.min.js"></script>

<script src="/assets/js/app.js"></script>
<script src="/assets/js/demo_charts/pages/dashboard_6/light/progress_sortable.js"></script>
<script src="/assets/js/demo_charts/pages/dashboard_6/light/bars_grouped.js"></script>
<!-- /theme JS files -->
<style>
    .message {
        text-align-last: center;
        font-size: 14px;
        font-family: 'Roboto';
        font-weight: bold;
    }


    .effect {
        width: 100%;
        padding: 50px 0px 70px 0px;

        bottom: 0;
    }

    .effect h2 {
        font-size: 25px;
        letter-spacing: 3px;
    }

    .effect:nth-child(2) {
        margin-top: 0px;

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
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        margin-right: 20px;
        font-size: 22px;
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
        background-color: #464141;
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