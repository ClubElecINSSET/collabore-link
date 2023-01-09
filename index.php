<?php
session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/config.php";
$id = "";
$argument = preg_split("/\//", $_SERVER["REQUEST_URI"]);
if ($argument[1] != "d" && $argument[1] != null) {
  $dsn =
    "mysql:host=" .
    $mysql_address .
    ";dbname=" .
    $mysql_db .
    ";port=" .
    $mysql_port .
    ";charset=utf8mb4";
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ];
  try {
    $pdo = new PDO($dsn, $mysql_username, $mysql_password, $options);
    $pdo->exec("use " . $mysql_db);
  } catch (PDOException $e) {
    die($e->getMessage() . " " . (int) $e->getCode());
  }
  $req = $pdo->prepare("select * from " . $mysql_table . " where id = ?");
  $req->execute([$argument[1]]);
  $row = $req->fetch();
  if (isset($row["original"])) {
    http_response_code(301);
    header("Location: " . $row["original"]);
  } else {

    http_response_code(404);
    $pagename =
      "The shortened link you wish to access does not exist or no longer exists";
    include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/header.php";
?>
    <main role="main" class="cover fadeIn">
      <h1 class="cover-heading">

        <?php $translate->__("Oh no..."); ?>
      </h1>
      <p class="lead">
        <?php $translate->__(
          "The shortened link you wish to access does not exist or no longer exists."
        ); ?>
      </p>
      <a class="btn btn-outline-primary btn-block" type="button" href="/">
        <span class="bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z" />
          </svg> <?php $translate->__("Return to the homepage"); ?></span>

      </a>
    </main>
  <?php include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/footer.php";
  }
} else if ($argument[1] == "d") {
  if ($argument[2] == null) {
    header("Location: /");
    exit();
  }
  $dsn =
    "mysql:host=" .
    $mysql_address .
    ";dbname=" .
    $mysql_db .
    ";port=" .
    $mysql_port .
    ";charset=utf8mb4";
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ];
  try {
    $pdo = new PDO($dsn, $mysql_username, $mysql_password, $options);
    $pdo->exec("use " . $mysql_db);
  } catch (PDOException $e) {
    die($e->getMessage() . " " . (int) $e->getCode());
  }
  $req = $pdo->prepare("select * from " . $mysql_table . " where deleteId = ?");
  $req->execute([$argument[2]]);
  $row = $req->fetch();
  if (isset($row["deleteId"])) {
    $req = $pdo->prepare(
      "delete from " . $mysql_table . " where deleteId = ?"
    );
    $req->execute([$argument[2]]);
    $pagename = "Shortened link deleted";
    include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/header.php";
  ?>
    <main role="main" class="cover fadeIn">
      <h1 class="cover-heading">
        <i class="fa fa-thumbs-up fa-fw" aria-hidden="true">
        </i>
        <?php $translate->__("Your shortened link has been deleted"); ?>
      </h1>
      <p class="lead">
        <?php $translate->__("Thank you for your trust."); ?>
      </p>
      <a class="btn btn-outline-primary btn-block" type="button" href="/">
        <span class="bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z" />
          </svg> <?php $translate->__("Return to the homepage"); ?></span>
      </a>
    </main>
  <?php
    include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/footer.php";
  } else {

    $pagename = "The shortened link could not be removed";
    include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/header.php";
  ?>
    <main role="main" class="cover fadeIn">
      <h1 class="cover-heading">
        <?php $translate->__("Oh no..."); ?>
      </h1>
      <p class="lead">
        <?php $translate->__(
          "This deletion link does not exist."
        ); ?><br><?php $translate->__(
                            "Therefore, the deletion of the shortened link cannot be processed."
                          ); ?>
      </p>
      <a class="btn btn-outline-primary btn-block" type="button" href="/">
        <span class="bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z" />
          </svg> <?php $translate->__("Return to the homepage"); ?></span>
      </a>
    </main>
  <?php
    include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/footer.php";
  }
} else {

  $token = bin2hex(random_bytes(32));
  $_SESSION["token"] = $token;
  include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/header.php";
  ?>
  <main role="main" class="cover fadeIn">
    <h1 class="cover-heading">
      <?php $translate->__(
        "Welcome to collabore link"
      ); ?>
    </h1>
    <p class="lead">
      <?php $translate->__("Shorten all the links you want, efficiently, without any fuss."); ?>
    </p>
    <form method="post" action="shorten">
      <div class="input-group mb-3">
        <input type="text" name="link" class="form-control" placeholder="<?php $translate->__(
                                                                            "Original link"
                                                                          ); ?>" aria-label="<?php $translate->__(
                                                                                                "Original link"
                                                                                              ); ?>" aria-describedby="button-addon2" required autofocus>
        <input type="hidden" value="<?= $token; ?>" name="token" />
        <div class="input-group-append">
          <button class="btn btn-outline-primary" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows-angle-contract" viewBox="0 0 16 16" aria-hidden="true">
              <path fill-rule="evenodd" d="M.172 15.828a.5.5 0 0 0 .707 0l4.096-4.096V14.5a.5.5 0 1 0 1 0v-3.975a.5.5 0 0 0-.5-.5H1.5a.5.5 0 0 0 0 1h2.768L.172 15.121a.5.5 0 0 0 0 .707zM15.828.172a.5.5 0 0 0-.707 0l-4.096 4.096V1.5a.5.5 0 1 0-1 0v3.975a.5.5 0 0 0 .5.5H14.5a.5.5 0 0 0 0-1h-2.768L15.828.879a.5.5 0 0 0 0-.707z" />
            </svg>
          </button>
        </div>
      </div>
    </form>
  </main>
<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/footer.php";
}
?>