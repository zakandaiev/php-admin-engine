<?php

namespace Engine;

class User
{
	protected static $current;
	protected static $stored = [];

	public function __construct()
	{
		self::$current = new \stdClass();
		self::$current->id = null;
		self::$current->authorized = false;

		if (Session::hasCookie(COOKIE_KEY['auth'])) {
			$auth_key = Session::getCookie(COOKIE_KEY['auth']);
			$bind_ip = AUTH['bind_session_to_ip'] ? 'AND auth_ip = :auth_ip' : '';

			$sql = "
				SELECT
					*
				FROM
					{user}
				WHERE
					auth_token = :auth_token
					AND is_enabled IS true
					$bind_ip
				ORDER BY
					date_created DESC
				LIMIT 1
			";

			$user = new Statement($sql);

			$binding = ['auth_token' => $auth_key];

			if (AUTH['bind_session_to_ip']) {
				$binding['auth_ip'] = Request::ip();
			}

			$user = $user->execute($binding)->fetch();

			if ($user) {
				self::$current = self::format($user, true);
				self::$stored[$user->id] = self::$current;
			}
		}

		return $this;
	}

	public static function get($id = null)
	{
		if (isset($id) && isset(self::$stored[$id])) {
			return self::$stored[$id];
		} else if (isset($id)) {
			$sql = 'SELECT * FROM {user} WHERE id = :id ORDER BY date_created DESC LIMIT 1';

			$user = new Statement($sql);

			$user = $user->execute(['id' => $id])->fetch();

			if (!$user) {
				return $user;
			}

			$user = self::format($user);

			self::$stored[$user->id] = $user;

			return $user;
		}

		return self::$current;
	}

	public static function set($key, $data = null)
	{
		self::$current->{$key} = $data;

		return true;
	}

	public static function format($user, $authorized = false)
	{
		if (!$user) {
			return $user;
		}

		$user->authorized = $authorized;
		$user->fullname = !empty($user->name) ? "{$user->name} ($user->email)" : "$user->email";
		$user->setting = is_json($user->setting) ? json_decode($user->setting) : new \stdClass();
		$user->setting->notifications = isset($user->setting->notifications) && is_json($user->setting->notifications) ? json_decode($user->setting->notifications) : new \stdClass();

		return $user;
	}

	// TODO
	public static function authorize($user, $lifetime = null)
	{
		// $auth_token = Hash::token();

		// $user->ip = Request::ip();

		// $authorize = '
		// 	UPDATE {user} SET
		// 		auth_token = :auth_token,
		// 		auth_ip = :auth_ip,
		// 		auth_date = CURRENT_TIMESTAMP
		// 	WHERE id = :user_id
		// ';

		// $authorize = new Statement($authorize);

		// $authorize->execute(['user_id' => $user->id, 'auth_ip' => $user->ip, 'auth_token' => $auth_token]);

		// Session::setCookie(COOKIE_KEY['auth'], $auth_token, $lifetime ?? LIFETIME['auth']);

		// self::$current = $user;
		// self::$current->authorized = true;

		// Notification::create('user_authorize', $user->id, ['ip' => $user->ip]);

		Log::write("User ID: {$user->id} logged in from IP: {$user->ip}", 'user');

		Hook::run('user.authorize', $user);

		return true;
	}

	public static function unauthorize()
	{
		// Session::unsetCookie(COOKIE_KEY['auth']);

		$user_id = self::$current->id;
		$user_ip = Request::ip();
		Log::write("User ID: $user_id logged out from IP: $user_ip", 'user');

		Hook::run('user.unauthorize', self::$current);

		// self::$current = new \stdClass();
		// self::$current->authorized = false;

		return true;
	}

	public static function register($user)
	{
		// if(is_array($user)) {
		// 	$user = json_decode(json_encode($user, JSON_UNESCAPED_UNICODE));
		// }

		// $user_password = $user->password;
		// $user->password = Hash::password($user->password);

		// $register = '
		// 	INSERT INTO {user}
		// 		(name, email, password)
		// 	VALUES
		// 		(:name, :email, :password)
		// ';

		// $register = new Statement($register);

		// $user->id = $register->execute(json_decode(json_encode($user, JSON_UNESCAPED_UNICODE), true))->insertId();

		// self::authorize($user);

		// Notification::create('user_register', $user->id, ['ip' => $user->ip]);

		// $user->password = $user_password;

		// Mail::send('Register', $user->email, $user);

		Log::write("User ID: {$user->id} registered from IP: {$user->ip}", 'user');

		Hook::run('user.register', $user);

		return true;
	}

	public static function restore($email)
	{
		// $statement = new Statement('SELECT * FROM {user} WHERE email = :email LIMIT 1');

		// $user = $statement->execute(['email' => $email])->fetch();

		// if(empty($user)) {
		// 	return false;
		// }

		// $new_password = Hash::token();

		// $update_password = '
		// 	UPDATE {user} SET
		// 		password = :new_password
		// 	WHERE id = :id
		// ';

		// $update_password = new Statement($update_password);

		// $update_password->execute(['id' => $user->id, 'new_password' => Hash::password($new_password)]);

		// $user->password = $new_password;

		// Notification::create('user_restore', $user->id, ['ip' => Request::ip()]);

		// Mail::send('Restore', $email, $user);

		$user_ip = Request::ip();
		Log::write("User ID: {$user->id} restored password from IP: $user_ip", 'user');

		Hook::run('user.restore', $user);

		return true;
	}

	public static function update($key, $value = null, $id = null)
	{
		// $id = $id ?? self::get()->id;

		// $binding = [$key => $value, 'id' => $id];

		// $sql = "UPDATE {user} SET $key = :$key WHERE id = :id";

		// $statement = new Statement($sql);

		// $statement->execute($binding);

		$user_ip = Request::ip();

		Log::write("User ID: $id updated $key from IP: $user_ip", 'user');

		Hook::run('user.update', $id);
		Hook::run('user.update.' . $key, $id);

		return true;
	}

	public static function delete($id)
	{
		// $sql = "DELETE FROM {user} WHERE id = :id";

		// $statement = new Statement($sql);

		// $statement->execute(['id' => $id]);

		$user_ip = Request::ip();

		Log::write("User ID: $id deleted from DB from IP: $user_ip", 'user');

		Hook::run('user.delete', $id);

		return true;
	}
}
