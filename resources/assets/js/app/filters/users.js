(function(){
	
	var app 		= angular.module('System.Filters');
	



	/**
	*
	*	FILTER: users
	*		- filtering all users with already selected staff
	*
	* 	USAGE:
	* 		{{ users:logout.staff:(index of select) }}
	*
	**/
	app.filter('users', [function(){

		return function( users, staff, index) {
			var filter_users = [];
			angular.forEach(users, function (user) {
				if(staff.length > 1) {
					// check existing users in staff array
					var added_users_array = [];
					angular.forEach(staff, function (st, i) {
						if(i!==index && st.id===user.id) {
							added_users_array.push(user);
						}
					});
					if(added_users_array.length === 0) {
						filter_users.push(user);
					}
				} else {
					filter_users.push(user);
				}
			});
			return filter_users;
		};

	}]);

})();