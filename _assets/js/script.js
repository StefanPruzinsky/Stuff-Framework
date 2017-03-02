var StyleHelper = function(){};
StyleHelper.prototype = 
    {
        ApplyErrorStyle: function()
        {
            var bodyElement = document.getElementsByTagName("BODY")[0];
            bodyElement.style.background = "#3f6eb3";
            bodyElement.style.color = "#1f3759";
        }
    };

var SigningHelper = function(){};
SigningHelper.prototype =
    {
        RunSigningChanger: function ()
        {
            var signingChangers = document.getElementsByClassName('message');

            Array.prototype.forEach.call(
                signingChangers,
                function (item, index)
                {
                    item.getElementsByTagName("a")[0].addEventListener(
                        "click",
                        function ()
                        {
                            $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
                        }
                    )
                }
            );
        },
        ShowAppropriateForm: function ()
        {
            var currentURL = document.URL;

            var registerForm = document.getElementsByClassName("register-form")[0];
            var loginForm = document.getElementsByClassName("login-form")[0];

            if (currentURL.indexOf("register") != -1)
            {
                registerForm.style.display = "block";
                loginForm.style.display = "none";
            }
        },
        RecognizeFailEvent: function ()
        {
            var lastPieceOfCurrentRoute = document.URL.substr(document.URL.lastIndexOf("/") + 1);

            if (lastPieceOfCurrentRoute != "login" && lastPieceOfCurrentRoute != "register")
            {
                if(! $('.alert-box').length)
                {
                    $('<div class="alert-box fail" >' + lastPieceOfCurrentRoute.replace(/\+/g, ' ') + '</div>').prependTo('body').delay(5000).fadeOut(1000, function() {
                        $('.alert-box').remove();
                    });
                }
            }
        }
    };