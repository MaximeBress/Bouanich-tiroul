
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Wordpress/wp-content/themes/jardiwinery_child/style.css" rel="stylesheet">

  </head>
  <body>
    <div class="container-fluid p0">
        <div class="posrel">
            <img src="http://localhost:8888/Wordpress/wp-content/uploads/2016/12/bg-02.jpg" class="superbg" />
        </div>
        <div class="text-center posabs" style="top:25%; width: 100%;" id="title-homepage">
            <h1 class="title-homepage">Feel the harmony within a single glass</h1>
        </div>
        <div id="pointer1" class="pointer posabs" style="top: 40%;right: 10%;"></div>
        <div id="content-bottle1" class="content-bottle posabs" style="top: 43%;right: 3%; display:none;">
            <div class="top">
                <img src="http://localhost:8888/Wordpress/wp-content/uploads/2016/12/wine-02-210x230.jpg" class="superbg" />
            </div>
            <div class="bottom bg-darkgrey text-center">
                <span class="txt-white">Johann Shiraz Cabernet</span><br />
                <span class="txt-gold">2013</span><br />
                <a href="#" class="txt-white">En savoir plus</a><br />
            </div>
        </div>
        <div id="pointer2" class="pointer posabs" style="top: 30%;left: 10%;"></div>
        <div id="content-bottle2" class="content-bottle posabs" style="top: 33%;left: 3%; display:none;">
            <div class="top">
                <img src="http://localhost:8888/Wordpress/wp-content/uploads/2016/12/wine-02-210x230.jpg" class="superbg" />
            </div>
            <div class="bottom bg-darkgrey text-center">
                <span class="txt-white">Johann Shiraz Cabernet</span><br />
                <span class="txt-gold">2013</span><br />
                <a href="#" class="txt-white">En savoir plus</a><br />
            </div>
        </div>
        <div id="pointer3" class="pointer posabs" style="top: 60%;right: 20%;"></div>
        <div id="content-bottle3" class="content-bottle posabs" style="top: 63%;right: 13%; display:none;">
            <div class="top">
                <img src="http://localhost:8888/Wordpress/wp-content/uploads/2016/12/wine-02-210x230.jpg" class="superbg" />
            </div>
            <div class="bottom bg-darkgrey text-center">
                <span class="txt-white">Johann Shiraz Cabernet</span><br />
                <span class="txt-gold">2013</span><br />
                <a href="#" class="txt-white">En savoir plus</a><br />
            </div>
        </div>
        <div id="pointer4" class="pointer posabs" style="top: 67%;left: 20%;"></div>
        <div id="content-bottle4" class="content-bottle posabs" style="top: 23%;left: 13%; display:none;">
            <div class="top">
                <img src="http://localhost:8888/Wordpress/wp-content/uploads/2016/12/wine-02-210x230.jpg" class="superbg" />
            </div>
            <div class="bottom bg-darkgrey text-center">
                <span class="txt-white">Johann Shiraz Cabernet</span><br />
                <span class="txt-gold">2013</span><br />
                <a href="#" class="txt-white">En savoir plus</a><br />
            </div>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function (){
        checkWindowSize()
        showBottle(1);
        showBottle(2);
        showBottle(3);
        showBottle(4);
        if ($(window).width() < 1080) {
           for(var i = 1; i < 5; i++) {
               $('#pointer' + i).hide();
           }
        }
        else {
            for(var i = 1; i < 5; i++) {
                $('#pointer' + i).show();
            }
        }
        $('#title-homepage').center();
        $('.sc_section_title sc_item_title').addClass('txt-white');
    });
    jQuery.fn.center = function () {
        this.css("position","absolute");
        this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                                                    $(window).scrollTop()) + "px");
        this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                                                    $(window).scrollLeft()) + "px");
        return this;
    }

    function showBottle(id) {
        $('#pointer' + id).hover(
            function() {
                $('#content-bottle' + id).show();
            }, function() {
                $('#content-bottle' + id).hover(
                    function() {}, function () {
                        $('#content-bottle' + id).hide();
                    });
                });
    }
    function checkWindowSize() {
        if ($(window).width() < 680) {
           for(var i = 1; i < 5; i++) {
               $('#pointer' + i).hide();
           }
           $('#title-homepage').css({
               'top': '10%'
           })
           $('.title-homepage').css({
               'font-size': '20px',
               'padding': '10px',
               'width': '300px'
           })
        }
        else if ($(window).width() < 1080) {
            for(var i = 1; i < 5; i++) {
                $('#pointer' + i).hide();
            }
            $('#title-homepage').css({
                'top': '15%'
            })
            $('.title-homepage').css({
                'font-size': '35px',
                'padding': '20px',
                'width': '500px'
            })
            $('.container-fluid').removeClass('pt90');
        }
        else {
            for(var i = 1; i < 5; i++) {
                $('#pointer' + i).show();
            }
            $('#title-homepage').css({
                'top': '25%'
            })
            $('.title-homepage').css({
                'font-size': '52px',
                'padding': '30px',
                'width': '620px'
            })
            $('.container-fluid').addClass('pt90');
        }
    }
    $(window).resize( function() {
        checkWindowSize();
    });

    </script>
  </body>
</html>
