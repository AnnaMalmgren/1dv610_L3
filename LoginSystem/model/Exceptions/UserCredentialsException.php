<?php
namespace Model;
class UserCredentialException extends \Exception{}

class UsernameMissingException extends UserCredentialException  {}

class PasswordMissingException extends UserCredentialException  {}