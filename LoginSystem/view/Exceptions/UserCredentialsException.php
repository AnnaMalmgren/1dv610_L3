<?php
namespace View;

class UserCredentialException extends \Exception{}

class UsernameMissingException extends UserCredentialException  {}

class PasswordMissingException extends UserCredentialException  {}

class PasswordsDontMatchException extends UserCredentialException {}

class FieldsMissingException extends UserCredentialException {}
