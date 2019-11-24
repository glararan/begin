$(document).ready(function()
{
    $("html").niceScroll();
    
    $(window).load(function()
    {
        var hash = window.location.hash;
        
        if(hash.indexOf("registrace") != -1)
        {
            $('html, body').animate(
            {
                scrollTop: $("#registrace").offset().top
            }, 2000);
        }
        
        if(hash.indexOf("zmenitheslo") != -1)
        {
            $('html, body').animate(
            {
                scrollTop: $("#zmenitheslo").offset().top
            }, 2000);
        }
        
        $.parallaxify(
        {
            positionProperty: 'transform',
            motionType: 'performance',
            mouseMotionType: 'performance'
        });
        $('body').parallax("50%", -0.7);
        //$("#eagle").parallaxify();
    });
    
    $("#loginForm").validator().on("submit", function(e)
    {
        if(e.isDefaultPrevented())
        {
            // error
        }
        else // good
        {
            e.preventDefault();
            
            $.post("php/controllers/login.php", {account: $("#loginForm input[name='account']").val(), password: $("#loginForm input[name='password']").val()}, function(data)
            {            
                if(data.success == 0)
                {
                    if(data.error != 0)
                    {
                        alert(data.error);

                        return;
                    }
                }
                else if(data.success == 1)
                {
                    $("#loginForm input[type='submit']").addClass("has-success");

                    setTimeout(function()
                    {
                        location.reload();
                    }, 500);
                }
            }, "json");
        }
    });
    
    $("#registerForm").validator().on("submit", function(e)
    {
        if(e.isDefaultPrevented())
        {
            // error
        }
        else // good
        {
            e.preventDefault();
            
            $.post("php/controllers/register.php", {account: $("#registerForm input[name='accountName']").val(), email: $("#registerForm input[name='emailAddress']").val(), password: $("#registerForm input[name='password']").val(), password2: $("#registerForm input[name='password2']").val()}, function(data)
            {
                if(data.success == 0)
                {
                    if(data.error != 0)
                    {
                        alert(data.error);

                        return;
                    }
                }
                else if(data.success == 1)
                {
                    $("#registerForm input[type='submit']").addClass("has-success");

                    setTimeout(function()
                    {
                        window.location.href = "http://itheria.cz";
                    }, 500);
                }
            }, "json");
        }
    });
});