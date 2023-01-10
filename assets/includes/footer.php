<?php
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(301);
    header("Location: /");
} else {
     ?>
<footer class="mastfoot mt-auto">
    <p>
      <a href="/report"><?php $translate->__("Report an illicit link"); ?>
      </a>
      <br>
      <a href="https://forge.collabore.fr/ClubElecINSSET/collabore-link"><?php $translate->__("Source code"); ?>
      </a>
      <br> 
      <a href="https://collabore.fr/legals"><?php $translate->__("Legal notice"); ?>
      </a> 
      • 
      <a href="https://collabore.fr/terms"><?php $translate->__("Terms of service"); ?>
      </a>
    </p>
</footer>
</div>
<?php include_once $_SERVER["DOCUMENT_ROOT"] .
    "/assets/includes/modalotoast.php"; ?>
<script src="/assets/js/jquery.min.js?v=<?= $version; ?>"></script>
<script src="/assets/js/bootstrap.bundle.min.js?v=<?= $version; ?>"></script>
<script src="/assets/js/script.js?v=<?= $version; ?>"></script>
<script>
  if ($(".copyshorturl")[0]) {
    var copyBtn = document.querySelector(".copyshorturl");
    copyBtn.addEventListener("click", function(event) {
      copyTextToClipboard("<?php if (isset($id) && $id != NULL) { echo 'https://' . $url . '/' . $id; } ?>", "shortUrl");
    }
                            );
  }
  if ($(".copydeletionlink")[0]) {
    var copyBtn = document.querySelector(".copydeletionlink");
    copyBtn.addEventListener("click", function(event) {
      copyTextToClipboard("<?php if (isset($deleteId)) { echo 'https://' . $url . '/d/' . $deleteId; } ?>", "deletionLink");
    }
                            );
  }
  function showQRCode() {
    document.getElementById("QRCode").innerHTML = '<img src="/assets/tools/qrcode-url/index.php?q=<?= "https://$url/$id" ?>&l=L&b=1&s=6" />';
    $("#showQRCode").modal("show");
  }
</script>
</body>
</html>
<?php
}
?>
