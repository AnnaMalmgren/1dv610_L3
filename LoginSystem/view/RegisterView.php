<?php
namespace View;

require_once(__DIR__ . '/../model/User.php');
require_once('Exceptions/UserCredentialsException.php');

class RegisterView extends LoginView {
    private static $name = 'RegisterView::UserName';
	private static $password = 'RegisterView::Password';
	private static $passwordRepeat = 'RegisterView::PasswordRepeat';
	private static $messageId = 'RegisterView::Message';
	private static $register = 'RegisterView::Register';
	private $message = "";
	private $messageStyle = "";

	public function getRequestName() : string {
		return trim($_POST[self::$name]);
	}

	public function getRequestPwd() : string {
		return trim($_POST[self::$password]);
	}
	
	public function getRequestPwdRepeat() : string {
		return trim($_POST[self::$passwordRepeat]);
	}

	public function userWantsToRegister() : bool {
		return isset($_POST[self::$register]);
	}

	private function getFilteredName() : string {
		return $this->userWantsToRegister() ? strip_tags($this->getRequestName()) : "";
	}

	public function getRegUserCredentials() : \Model\UserCredentials {
		if (!$this->passwordsMatch()) {
			throw new PasswordsDontMatchException(); 
		}
		if ($this->isFieldMissing()) {
			throw new FieldsMissingException();
		}
		return new \Model\UserCredentials($this->getRequestName(), $this->getRequestPwd());
	}

	public function passwordsMatch() : bool {
		return $this->getRequestPwd() == $this->getRequestPwdRepeat();
	}

	public function isFieldMissing() : bool {
		return empty($this->getRequestName()) && empty($this->getRequestPwd());
	}

	private function setAlertDangerStyle() {
		$this->messageStyle = 'class="alert alert-danger"';
	}

	public function setCredentialsMissingMsg() {
		if ($this->isFieldMissing()) {
			$this->setAlertDangerStyle();
			$userNameMsg = 'Username has too few characters, at least 3 characters.';
			$PwdMsg = 'Password has too few characters, at least 6 characters.';
			$this->message = "$userNameMsg<br>$PwdMsg";
		}
	}

	public function setToShortUsernameMessage() {
		$this->setAlertDangerStyle();
		$this->message = 'Username has too few characters, at least 3 characters.';
	}

	public function setToShortPwdMessage() {
		$this->setAlertDangerStyle();
		$this->message = 'Password has too few characters, at least 6 characters.';
	}

	public function setInvalidCharactersMessage() {
		$this->setAlertDangerStyle();
		$this->message = 'Username contains invalid characters.';
	}

	public function setUserExistsMessage() {
		$this->setAlertDangerStyle();
		$this->message = 'User exists, pick another username.';
	}

	public function setPwdsDontMatchMessage() {
		$this->setAlertDangerStyle();
		$this->message = 'Passwords do not match.';
	}

	public function response() {
		return $this->generateRegisterFormHTML($this->message);
	}

	private function generateRegisterFormHTML($message) {
		return '
			<div class="container m-3">
				<a href="?" class="text-decoration-none mt-3 mb-2 text-primary">Back to login</a>
				<form action="?register" method="post" enctype="multipart/form-data" class="w-50">
					<legend>Register a new user - Write username and password</legend>
					<p id="' . self::$messageId . '" ' . $this->messageStyle . '>' . $message . '</p>
					<div class="form-group">	
						<label for="' . self::$name . '">Username :</label>
						<input type="text" id="' . self::$name . '" name="' . self::$name . '" 
						value="' . $this->getFilteredName() . '" class="form-control"/>
					</div>
					<div class="form-group">
					    <label for="' . self::$password . '">Password :</label>
					    <input type="password" id="' . self::$password . '" name="' . self::$password . '" 
						class="form-control"/>
					</div>
					<div class="form-group">
					    <label for="' . self::$passwordRepeat . '">Repeat password :</label>
					    <input type="password" id="' . self::$passwordRepeat . '" 
						name="' . self::$passwordRepeat . '" class="form-control" />
					</div>
					<input id ="submit" type="submit" name=' . self::$register . ' value="Register" 
					class="btn btn-primary mt-2 mb-2"/>
				</form>
			</div>';
	}
}