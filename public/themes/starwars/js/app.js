var App = function(scopes) {
	this.scopes = scopes;
	this.init();
};

App.prototype.init = function(callback) {

	console.debug('init');
	var self = this;

	FB.getLoginStatus(function(response) {
		if (response.status === 'connected') {

			console.debug('connected', response);


			// the user is logged in and has authenticated your
			// app, and response.authResponse supplies
			// the user's ID, a valid access token, a signed
			// request, and the time the access token 
			// and signed request each expire
			var uid = response.authResponse.userID;
			var accessToken = response.authResponse.accessToken;
		} else if (response.status === 'not_authorized') {

			console.error('not_authorized', response);
			// the user is logged in to Facebook, 
			// but has not authenticated your app
		} else {
			console.error('no feedback', response);
			// the user isn't logged in to Facebook.
		}
	});

}

App.prototype.share = function(callback) {

	console.debug('share')

	FB.ui({
		method : 'share',
		href : 'http://www.mattimatti.com',
	}, callback);

}


App.prototype.login = function(callback) {

	if (!callback) {
		callback = function() {
			window.location.href = '/login-callback';
		}
	}

	FB.login(function(response) {
		if (response.authResponse) {
			callback();
		} else {
			console.error('User cancelled login or did not fully authorize.');
		}
	}, {
		scope : this.scopes
	});

	return false;
};


App.prototype.logout = function(callback) {
	FB.logout(callback);
};


App.prototype.onClick = function(callback) {

	var me = this;
	this.login(function() {
		console.debug('login callback')
		me.share(function(response) {
			console.debug('share callback', arguments);
			if (response && !response.error_message) {
				console.debug('Posting completed.');
				callback(true);
			} else {
				console.error('Posting failed or cancelled');
				callback(false);
			}

		});
		return true;
	})


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
		windo.wlocation.href = '/';
	});
});


$('.selection').on("click", function(e) {
	var elm = $(e.currentTarget);
	var selection = $('#selection').val(elm.data('val'));
	fbapp.onClick(function(success) {
		console.debug('on shared the url', success);
		if (success) {
			$('#quizform').submit();
		} else {
			$('#selection').val('');
		}
	})

});


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
