<?php
namespace View;

class LayoutView {
  private static $registerLink = "register";
  private static $title = "CardGame";
  private $charset = "utf-8";
  private $bootstrapCSS = '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
  private $exceptionMsg = "";

  public function userClicksRegisterLink() : bool {
    return isset($_GET[self::$registerLink]);
  }
  
  public function setCharset(string $newCharSet) {
    $this->charset = $newCharSet;
  }

  public function setExceptionMsg() {
    $this->exceptionMsg = "Something went wrong";
  }
  
  
  public function render($isLoggedIn, LoginView $v, DateTimeView $dtv, GameView $gameView) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset=' . $this->charset . '>
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
          ' . $this->bootstrapCSS . '
          <title>' . self::$title .'</title>
        </head>
        <body>
          <div class="mt-3 ml-4">
            <h2>CardGame 21</h2>
              ' . $this->renderIsLoggedIn($isLoggedIn) . '
              ' . $v->response($isLoggedIn) . ' 
          </div>
          <div class="container">
            ' . $gameView->response($isLoggedIn) . '
            ' . $this->exceptionMsg . '
            ' . $dtv->show() . '
          </div>
        </body>
        </html>';
  }

  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2 class="text-muted d-flex justify-content-start">Logged in</h2>';
    } else {
      return '<h2 class="text-muted d-flex justify-content-start">Not logged in</h2>';
    }
  }
}
