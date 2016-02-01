var App = function() {
};

App.prototype.share = function(callback) {

	callback(true);
	return;
	
	FB.ui({
		method : 'share',
		href : 'http://www.mattimatti.com',
	}, callback);

}


App.prototype.login = function() {

	FB.login(function(response) {
		if (response.authResponse) {
			window.location.href = '/login-callback';
		} else {
			alert('User cancelled login or did not fully authorize.');
		}
	});

	return false;
};


App.prototype.logout = function(callback) {
	FB.logout(callback);
};


App.prototype.onClick = function(callback) {

	this.share(function(response) {
		if (response && !response.error_message) {
			console.debug('Posting completed.');
			callback(true);
		} else {
			console.debug('Error while posting.');
			callback(false);
		}

	});
	return true;
};

//FB.getLoginStatus(function(response) {
//	  if (response.status === 'connected') {
//	    // the user is logged in and has authenticated your
//	    // app, and response.authResponse supplies
//	    // the user's ID, a valid access token, a signed
//	    // request, and the time the access token 
//	    // and signed request each expire
//	    var uid = response.authResponse.userID;
//	    var accessToken = response.authResponse.accessToken;
//	  } else if (response.status === 'not_authorized') {
//	    // the user is logged in to Facebook, 
//	    // but has not authenticated your app
//	  } else {
//	    // the user isn't logged in to Facebook.
//	  }
//	 });


$('.login-btn').on("click", function(e) {
	fbapp.login();
});


$('.logout-btn').on("click", function(e) {
	fbapp.logout(function(response) {
		console.debug(response);
	});
});


$('.selection').on("click", function(e) {
	var elm = $(e.currentTarget);
	var selection = $('#selection').val(elm.data('val'));
	fbapp.onClick(function(success) {
		if (success) {
			$('#quizform').submit();
		} else {
			alert('too bad');
		}


	})

});


var fbapp = new App();


(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {
		return;
	}
	js = d.createElement(s);
	js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


window.fbAsyncInit = function() {
	FB.init({
		appId : '634642500001239',
		cookie : true,
		version : 'v2.5'
	});
}
