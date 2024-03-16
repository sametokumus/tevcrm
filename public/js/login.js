'use strict'
$(window).on('load', function () {

    /* get date and time */
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    function startTime() {
        var date = new Date;
        var day = date.getDate();
        var month = monthNames[date.getMonth()];
        var year = date.getFullYear();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes;
        $('#time').text(strTime);
        $('#ampm').text(" " + ampm);
        $('#date').text(day + ' ' + month + ' ' + year);
    }
    setInterval(function () { startTime() }, 500);

    /* change images based on time zones */
    var date = new Date;
    if (date.getHours() < 12 && date.getHours() >= 7) {
        $('#image-daytime').parent().css('background-image', 'url("img/bg-13.jpg")');
    } else if (date.getHours() >= 12 && date.getHours() <= 19) {
        $('#image-daytime').parent().css('background-image', 'url("img/bg-3.jpg")');
    } else {
        $('#image-daytime').parent().css('background-image', 'url("img/bg-12.jpg")');
    }

    /* temperature data */
    var cityname = 'London';
    $('#citychange li').on('click', function () {
        if ($(this).text() != '') {
            $('#citychange li').removeClass('active');
            $(this).addClass('active')
            cityname = $(this).text();
            dataload();
        }
    })
    dataload();
    function dataload() {
        fetch('https://api.openweathermap.org/data/2.5/weather?q=' + cityname + '&APPID=ce2008ef871845f77c7f03aafe2d54eb&units=metric')
            /* change app id= "ce2008ef871845f77c7f03aafe2d54eb" with your id create from https://openweathermap.org/api current weather data */
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                appendData(data);
            })
            .catch(function (err) {
                console.log(err);
            });
    }
    function appendData(data) {
        $('#temperature').text(data.main.temp);
        $('#city').text(data.name);
        $('#tempimage').attr('src', 'img/openweather-icon/light/' + data.weather[0].icon + '@2x.png');
    }

    /* swiper sliders */
    var swiperNews = new Swiper(".news-swiper", {
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
        },
    });
    var swiperImage = new Swiper(".image-swiper", {
        effect: "fade",
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
    });

    /* input validation check*/
    $('#email').on('focusout keyup', function () {
        var field = $(this);
        var $email = this.value;
        validateEmail($email, field);
    });

    function validateEmail(email, field) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (!emailReg.test(email)) {
            field.closest('.check-valid').removeClass('is-valid').next().show()
        } else {
            field.closest('.check-valid').addClass('is-valid').next().hide()
        }
    }

    $('#submitbtn').on('click', function () {
        let valid_email = true;
        let valid_pass = true;
        if ($(this).closest('form').find('.validate-email').not('.is-valid').length > 0) {
            valid_email = false;
        }
        if ($(this).closest('form').find('.validate-pass').not('.is-valid').length > 0) {
            valid_pass = false;
        }

        if (valid_email && valid_pass){
            let userEmail = document.getElementById('login_email').value;
            let userPass = document.getElementById('login_password').value;

            let formData = JSON.stringify({
                "email": userEmail,
                "password": userPass
            });

            fetchDataPost('/admin/login', formData, 'application/json').then(data=>{
                if(data.status == "success"){
                    let __userInfo = data.object.admin;

                    localStorage.setItem('userRole',__userInfo.admin_role_id);
                    localStorage.setItem('userId',__userInfo.id);
                    localStorage.setItem('userEmail',__userInfo.email);
                    localStorage.setItem('userName',__userInfo.name + ' ' + __userInfo.surname);
                    localStorage.setItem('userPhoto',__userInfo.profile_photo);
                    localStorage.setItem('appToken',__userInfo.token);

                    try{
                        var hash = __userInfo.admin_role_id.toString()+(__userInfo.id).toString()+__userInfo.email;
                        var salt = gensalt(5);
                        function result(newhash){
                            localStorage.setItem('userLogin',newhash);

                            var rel = getURLParam('rel');
                            if(rel != null && rel=="xxx"){
                                window.location.href = "xxx?id=";
                            }else{
                                window.location.href = "my-dashboard";
                            }
                        }
                        hashpw(hash, salt, result, function() {});


                    }catch(err){
                        showAlert('err');
                        return;
                    }

                }else{
                    showAlert('data.message');
                }
            });



            $(this).closest('form').find('.global-success').removeClass('d-none');
            $(this).closest('form').find('.global-alert').addClass('d-none');
            setTimeout(function () {
                window.location.replace("password.html");
            }, 2000);
        }else{
            $(this).closest('form').find('.global-alert').removeClass('d-none');
            setTimeout(function () {
                $('.global-alert').addClass('d-none');
            }, 3000);
        }
    })

    $('#password').each(function () {
        $(this).on('focusout keyup', function () {
            if (this.value != '') {
                $(this).closest('.check-valid').addClass('is-valid')
                $(this).closest('form').addClass('was-validated');
            } else {
                $(this).closest('.check-valid').removeClass('is-valid');
                $(this).closest('form').addClass('was-validated');
            }
        });
    });

    $('#viewpassword').on('click', function () {
        var passInput = $(this).closest('.form-group').find('.form-control');
        if (passInput.attr('type') === 'password') {
            $(this).find('i').attr('class', 'bi-eye-slash');
            passInput.attr('type', 'text');
        } else {
            $(this).find('i').attr('class', 'bi bi-eye');
            passInput.attr('type', 'password');
        }
    });

})
