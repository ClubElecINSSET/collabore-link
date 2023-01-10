<?php
ini_set("session.cache_limiter", "public");
session_cache_limiter(false);
session_start();
if (!empty($_POST)) {
    $_SESSION["save"] = $_POST;

    $file = $_SERVER["PHP_SELF"];
    if (!empty($_SERVER["QUERY_STRING"])) {
        $fichierActuel .= "?" . $_SERVER["QUERY_STRING"];
    }

    header("Location: " . $file);
    exit();
}

if (isset($_SESSION["save"])) {
    $_POST = $_SESSION["save"];

    unset($_SESSION["save"]);
}
include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/config.php";
$id = "";
if (
    isset($_POST["linkId"]) &&
    isset($_POST["token"]) &&
    isset($_SESSION["token"]) &&
    $_POST["token"] == $_SESSION["token"]
) {
    $argument = $_POST["linkId"];
    $argument = ltrim($argument);
    $_POST["linkId"] = "";
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
    $req->execute([$argument]);
    $row = $req->fetch();
    if (isset($row["id"])) {
        $deletionId = $row["deletionId"];
        $origin = $row["original"];
        include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/header.php";
        $to_email = "abuse@$url";
        $subject = "Report of a link from $name ($argument → $origin)";
        $message = "Hello,\n\nThe shortened link https://$url/$argument was reported by a user of the $name link shortener.\nIt targets the link $origin.\nIts deletion link is https://$url/d/$deletionId.\nPlease check whether or not this link complies with $name's terms of use.\n\nCordially,\nThe $name reporting page.";
        $headers = "From: no-reply@$url\n";
        $headers .= "Content-type: text/plain; charset=utf-8\n";
        mail($to_email, $subject, $message, $headers);
        $pagename = "Report sent";
?>
        <main role="main" class="cover fadeIn">
            <h1 class="cover-heading">

                <?php $translate->__("Thank you for your report"); ?>
            </h1>
            <p class="lead">
                <?php $translate->__(
                    "We will take care of your report as soon as possible"
                ); ?>
            </p>
            <a class="btn btn-outline-primary btn-block" type="button" href="/">
      <span class="bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16" aria-hidden="true">
  <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
</svg> <?php $translate->__("Return to the homepage"); ?></span>
      
    </a>
        </main>
    <?php
    } else {

        $pagename = "The report could not be sent";
        include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/header.php";
    ?>
        <main role="main" class="cover fadeIn">
            <h1 class="cover-heading">

                <?php $translate->__("Oh no..."); ?>
            </h1>
            <p class="lead">
                <?php $translate->__(
                    "The identifier of the link you wish to report does not exist."
                ); ?><br><?php $translate->__(
                    "Therefore, the report cannot be processed."
                ); ?>
            </p>
            <a class="btn btn-outline-primary btn-block" type="button" href="/report">
      <span class="bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle-fill" viewBox="0 0 16 16" aria-hidden="true">
  <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
</svg> <?php $translate->__("Return to the reporting page"); ?></span>
      
    </a>
        </main>
<?php
    }
} else {
    http_response_code(301);
    header("Location: /report");
}
include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/footer.php";
session_destroy();
?>