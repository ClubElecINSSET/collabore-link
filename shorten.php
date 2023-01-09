<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
function random_str(
    int $length,
    string $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, "8bit") - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces[] = $keyspace[random_int(0, $max)];
    }
    return implode("", $pieces);
}
if (
    isset($_POST["link"]) &&
    isset($_POST["token"]) &&
    isset($_SESSION["token"]) &&
    $_POST["token"] == $_SESSION["token"]
) {
    $link = $_POST["link"];
    $link = ltrim($link);
    $_POST["link"] = "";
    if (
        preg_match(
            "/^(http(s?):\/\/)?(\[(([0-9a-f]{1,4}:){7}[0-9a-f]{1,4}|([0-9a-f]{1,4}:){1,7}:|([0-9a-f]{1,4}:){1,6}:[0-9a-f]{1,4}|([0-9a-f]{1,4}:){1,5}(:[0-9a-f]{1,4}){1,2}|([0-9a-f]{1,4}:){1,4}(:[0-9a-f]{1,4}){1,3}|([0-9a-f]{1,4}:){1,3}(:[0-9a-f]{1,4}){1,4}|([0-9a-f]{1,4}:){1,2}(:[0-9a-f]{1,4}){1,5}|[0-9a-f]{1,4}:((:[0-9a-f]{1,4}){1,6})|:((:[0-9a-f]{1,4}){1,7}|:)|fe80:(:[0-9a-f]{0,4}){0,4}%[0-9a-z]+|::(ffff(:0{1,4})?:)?((25[0-5]|(2[0-4]|1?[0-9])?[0-9])\.){3}(25[0-5]|(2[0-4]|1?[0-9])?[0-9])|([0-9a-f]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1?[0-9])?[0-9])\.){3}(25[0-5]|(2[0-4]|1?[0-9])?[0-9]))\])|(http(s?):\/\/)?(((([a-zA-Z]+)|([0-9]{1,3}))\.)+(([a-zA-Z]+)|([0-9]{1,3})))/i",
            $link
        )
    ) {
        $valid_url = true;
    } else {
        $valid_url = false;
    }
    if (!stristr($link, "http")) {
        $link = "https://" . $link;
    }
    if ($valid_url) {
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
        while (true) {
            $id = random_str($char_per_id);
            $deleteId = random_str($char_per_deleteId);
            while (
                $id == "index" ||
                $id == "shorten" ||
                $id == "delete" ||
                $id == "report" ||
                $id == "reported"
            ) {
                $id = random_str($char_per_id);
                $deleteId = random_str($char_per_deleteId);
            }
            $req = $pdo->prepare(
                "select * from " . $mysql_table . " where id = ?"
            );
            $req->execute([$id]);
            $getId = $req->fetch();
            $req = $pdo->prepare(
                "select * from " . $mysql_table . " where deleteId = ?"
            );
            $req->execute([$deleteId]);
            $getdeleteId = $req->fetch();
            if (
                !isset($getId["original"]) ||
                !isset($getdeleteId["original"])
            ) {
                break;
            }
        }
        $req = $pdo->prepare(
            "insert into " .
                $mysql_table .
                " (id, original, deleteId, time) values (?, ?, ?, ?)"
        );
        $req->execute([$id, $link, $deleteId, time()]);
        $req = $pdo->prepare("select * from " . $mysql_table . " where id = ?");
        $req->execute([$id]);
        $row = $req->fetch();
        if (!isset($row["original"])) {

            $pagename = "An unknown error has occurred";
            include_once $_SERVER["DOCUMENT_ROOT"] .
                "/assets/includes/header.php";
?>
            <main role="main" class="cover fadeIn">
                <h1 class="cover-heading">

                    <?php $translate->__("Oh no..."); ?>
                </h1>
                <p class="lead">
                    <?php $translate->__("An unknown error has occurred."); ?>
                </p>
                <a class="btn btn-outline-primary btn-block" type="button" href="/">
      <span class="bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16" aria-hidden="true">
  <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
</svg> <?php $translate->__("Return to the homepage"); ?></span>
      
    </a>
            </main>
        <?php
        } elseif (isset($row["original"])) {

            $pagename = "Your link has been successfully shortened";
            include_once $_SERVER["DOCUMENT_ROOT"] .
                "/assets/includes/header.php";
        ?>
            <main role="main" class="cover fadeIn">
                <h1 class="cover-heading">

                    <?php $translate->__("Your link has been successfully shortened"); ?>
                </h1>
                <p class="lead">
                    <?php $translate->__("Thank you for your trust."); ?>
                </p>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
                                <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
                            </svg> <?php $translate->__("Shortened link"); ?></span>
                    </div>


                    <input type="text" name="link" class="form-control form-control-shortenedlink" value="<?= "https://" .
                                                                                                                $url .
                                                                                                                "/" .
                                                                                                                $id ?>" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary copyshorturl nojs">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-fill" viewBox="0 0 16 16" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1Zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5v-1Zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2Z" />
                            </svg>
                        </button>
                        <button class="btn btn-outline-primary nojs" onclick="showQRCode();">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M2 2h2v2H2V2Z" />
                                <path d="M6 0v6H0V0h6ZM5 1H1v4h4V1ZM4 12H2v2h2v-2Z" />
                                <path d="M6 10v6H0v-6h6Zm-5 1v4h4v-4H1Zm11-9h2v2h-2V2Z" />
                                <path d="M10 0v6h6V0h-6Zm5 1v4h-4V1h4ZM8 1V0h1v2H8v2H7V1h1Zm0 5V4h1v2H8ZM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8H6Zm0 0v1H2V8H1v1H0V7h3v1h3Zm10 1h-1V7h1v2Zm-1 0h-1v2h2v-1h-1V9Zm-4 0h2v1h-1v1h-1V9Zm2 3v-1h-1v1h-1v1H9v1h3v-2h1Zm0 0h3v1h-2v1h-1v-2Zm-4-1v1h1v-2H7v1h2Z" />
                                <path d="M7 12h1v3h4v1H7v-4Zm9 2v2h-3v-1h2v-1h1Z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                            </svg> <?php $translate->__("Deletion link"); ?></span>
                    </div>
                    <input type="text" name="deletionlink" class="form-control form-control-deletionlink" value="<?= "https://" . $url . "/d/" . $deleteId ?>" data-toggle="popover" data-placement="bottom" data-content="<?php $translate->__("Keep this deletion link in case you want to delete your shortened link in the future.") ?>" data-trigger="hover" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary copydeletionlink nojs">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-fill" viewBox="0 0 16 16" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1Zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5v-1Zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2Z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <a class="btn btn-outline-primary btn-block" type="button" href="/">
      <span class="bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16" aria-hidden="true">
  <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
</svg> <?php $translate->__("Return to the homepage"); ?></span>
      
    </a>
            </main>
        <?php
        }
    } elseif (!$valid_url) {

        $pagename = "The link you want to shorten is invalid";
        include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/header.php";
        ?>
        <main role="main" class="cover fadeIn">
            <h1 class="cover-heading">

                <?php $translate->__("Oh no..."); ?>
            </h1>
            <p class="lead"><?php $translate->__(
                                "The link you want to shorten is invalid."
                            ); ?>
            </p>
            <a class="btn btn-outline-primary btn-block" type="button" href="/">
      <span class="bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
  <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
</svg> <?php $translate->__("Return to the homepage"); ?></span>
      
    </a>
        </main>
<?php
    }
} else {
    http_response_code(301);
    header("Location: /");
}
include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/footer.php";
session_destroy();
