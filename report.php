<?php
$pagename = "Report an illicit link";
session_start();
$token = bin2hex(random_bytes(32));
$_SESSION["token"] = $token;
$id = "";
include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/header.php";
?>
<main role="main" class="cover fadeIn">
  <h1 class="cover-heading">
    <?php $translate->__(
      "Report an illicit link"
    ); ?>
  </h1>
  <p class="lead">
    <?php $translate->__(
      "A shortened link from collabore link leads to illegal, disturbing or pornographic content?"
    ); ?><br><?php $translate->__(
                  "Report it below and we will take care of it as soon as possible."
                ); ?>
  </p>
  <form method="post" action="reported">

    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">https://<?= $url; ?>/</span>
      </div>
      <input type="text" name="linkId" pattern="[A-Za-z0-9-]+" minlength="8" maxlength="8" class="form-control form-control-report" placeholder="<?php $translate->__(
                                                                                                                                                    "Link identifier"
                                                                                                                                                  ); ?>" aria-label="<?php $translate->__(
                              "Link identifier"
                            ); ?>" aria-describedby="button-addon2" required autofocus>
      <input type="hidden" value="<?= $token; ?>" name="token" />
      <div class="input-group-append">

        <button class="btn btn-outline-primary" type="submit">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z" />
          </svg>
        </button>
      </div>
    </div>
    <a class="btn btn-outline-primary btn-block" type="button" href="/">
      <span class="bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16" aria-hidden="true">
  <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
</svg> <?php $translate->__("Return to the homepage"); ?></span>
      
    </a>
  </form>
</main>
<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/assets/includes/footer.php";
?>