var FacebookApp = function(scopes, baseUrl) {
	this.baseUrl = baseUrl;
	this.scopes = scopes;
	this.init();
};

FacebookApp.prototype.init = function(callback) {

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

};

FacebookApp.prototype.share = function(callback) {

	FB.ui({
		method : 'share',
		href : this.baseUrl,
	}, callback);

};


FacebookApp.prototype.login = function(callback) {

	if (!callback) {
		callback = function() {
			window.location.href = '/login-callback';
		};
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


FacebookApp.prototype.logout = function(callback) {
	FB.logout(callback);
};


FacebookApp.prototype.voteBlocking = function(callback) {

	var me = this;
	this.login(function() {
		console.debug('login callback');
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
	});
};

FacebookApp.prototype.vote = function(callback) {
	
	var me = this;
	this.login(function() {
		console.debug('login callback');
		callback(true);
		return true;
	});
};

$('.login-btn').on("click", function(e) {
	App.fb.login();
});


$('.logout-btn').on("click", function(e) {
	App.fb.logout(function(response) {
		console.debug(response);
		window.wlocation.href = '/';
	});
});

$('.share-btn').on("click", function(e) {
	App.fb.share(function(response) {
		console.debug('share callback', arguments);
		if (response && !response.error_message) {
			console.debug('Posting completed.');
		} else {
			console.error('Posting failed or cancelled');
		}
		return true;
	});
});


$('.selection').on("click", function(e) {
	var elm = $(e.currentTarget);
	var selection = $('#selection').val(elm.data('val'));
	App.fb.vote(function(success) {
		console.debug('on shared the url', success);
		if (success) {
			$('#quizform').submit();
		} else {
			$('#selection').val('');
		}
	});

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
