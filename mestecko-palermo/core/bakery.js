if (typeof IGS == "undefined") {
    var IGS = {};
}

if (typeof trim != "function") {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g,'');
    }
}

IGS.version = "0.0.0.1";

IGS.Bakery = {

    /**
     *  Sets a cookie based on name and value
     *  The third optional argument is an anonymous object that
     *  lets you set either of the following: an expiry date (in days),
     *  a path, a domain and a security parameter for the cookie. They
     *  should be written as properties of an anonymous object
     *
     *  Ex: IGS.bakery.setCookie("foo", "bar", { // <-- note the bracket
     *          expires: 5, // The number of DAYS until the cookie expires
     *          path: "/dir", // What directories from your root can access the cookie, "/" means all
     *          domain: "domain.com", //
     *          security: true
     *          } // <-- note the bracket
     *      );
     *
     *  @static
     *  @access  public
     *  @param   string
     *  @param   string
     *  @param   object
     *  @return  void
     *  @TODO escape()
     */
    setCookie: function(n, v, options) {
        var c = encodeURIComponent(n) + "=" + encodeURIComponent(v);
        if (typeof options == "object") {
            for (var o in options) {
                if (o.toString() == "expires") {
                    options[o] = this.getGMTString(options[o]);
                }
                c+= ";" + o + "=" + options[o];
            }
            c = c.substring(0,c.length-1);
        }
        document.cookie = c;
    },

    /**
     *  Retrieves a cookie based by name(@param)
     *
     *  @static
     *  @access  public
     *  @param   string
     *  @return  array
     *  @TODO unescape()
     */
    getCookie: function(n) {
        if (document.cookie != "") {
            return this.getAllCookies()[n];
        }
    },

    /**
     *  Deletes a cookie (sets a cookie that expired 365 days ago)
     *
     *  @static
     *  @access  public
     *  @param   string
     *  @return  bool
     */
    deleteCookie: function(n) {
        n = encodeURIComponent(n);
        document.cookie = n + "=none;expires=" + this.getGMTString(-365);
        //this.setCookie(n, "", {"expires": -365});
    },

    /**
     *  Retrieves all the cookies, if any
     *  Returns an associative array, which can be read like this:
     *
     *  var cookies = IGS.Bakery.getAllCookies()[i]
     *  for (var i in cookies) {
     *      alert(cookies[i]);
     *  }
     *
     *  ...or you could access them in the following 2 ways:
     *
     *  IGS.Bakery.getAllCookies()["name_of_the_cookie"], or
     *  IGS.Bakery.getAllCookies().name_of_the_cookie;
     *
     *  for the second method, you should take care that your cookies
     *  respect the JavaScript notation rules for variables
     *
     *  @static
     *  @access  public
     *  @return  mixed - array on succes, false on failure
     */
    getAllCookies: function() {
        var cs = document.cookie;
        if (cs != "") {
            var name, value;
            var ca = []; // cookies in an associative array with key being the name of the cookie
                cs = cs.split(";");
            for (var i=0; i<cs.length; i++) {
                cs[i] = cs[i].trim().split("=");
                name  = decodeURIComponent(cs[i][0]);
                value = decodeURIComponent(cs[i][1]);
                ca[name] = value;
            }
            return ca;
        }
        return false;
    },

    /**
     *
     */
    deleteAllCookies: function() {
        this.getAllCookies();
        for (var c in this.cookies) {
            this.deleteCookie(c);
        }
    },

    /**
     *  Gets a GMT compliant string needed to set a cookie
     *
     *  @static
     *  @access  public
     *  @param   int - number of daysss
     *  @return  string
     */
    getGMTString: function(d) {
        var t = new Date();
        var e = new Date(t.getTime() + d*24*60*60*1000);
        return e.toGMTString();
    }

}