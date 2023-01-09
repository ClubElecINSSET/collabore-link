<?php
//Si ouvert depuis le navigateur, redirection vers la page d"accueil
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
	http_response_code(301);
	header("Location: /");
} else {
?>

	<div class="toast" data-delay="3000" id="okayLinkToast">
		<div class="toast-header">
			<strong class="mr-auto bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-fill" viewBox="0 0 16 16" aria-hidden="true">
					<path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1Zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5v-1Zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2Z" />
				</svg> <?php $translate->__("Link copied!"); ?></strong> <button aria-label="Close" class="ml-2 mb-1 close" data-dismiss="toast" type="button"><span aria-hidden="true">&times;</span></button>
		</div>
		<div class="toast-body">
			<div>
				<?php $translate->__("Your shortened link has been successfully copied to your clipboard."); ?>
			</div>
		</div>
	</div>
	<div class="toast" data-delay="3000" id="problemLinkToast">
		<div class="toast-header">
			<strong class="mr-auto bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16" aria-hidden="true">
					<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
				</svg> <?php $translate->__("Unable to copy your link..."); ?></strong> <button aria-label="Close" class="ml-2 mb-1 close" data-dismiss="toast" type="button"><span aria-hidden="true">&times;</span></button>
		</div>
		<div class="toast-body">
			<div>
				<?php $translate->__("An error occurred on copying the shortened link to your clipboard."); ?>
			</div>
		</div>
	</div>
	<div class="toast" data-delay="3000" id="okayDeletionlinkToast">
		<div class="toast-header">
			<strong class="mr-auto bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-fill" viewBox="0 0 16 16" aria-hidden="true">
					<path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1Zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5v-1Zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2Z" />
				</svg> <?php $translate->__("Deletion link copied!"); ?></strong> <button aria-label="Close" class="ml-2 mb-1 close" data-dismiss="toast" type="button"><span aria-hidden="true">&times;</span></button>
		</div>
		<div class="toast-body">
			<div>
				<?php $translate->__("Your deletion link has been successfully copied to your clipboard."); ?>
			</div>
		</div>
	</div>
	<div class="toast" data-delay="3000" id="problemDeletionlinkToast">
		<div class="toast-header">
			<strong class="mr-auto bi-fix"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16" aria-hidden="true">
					<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
				</svg> <?php $translate->__("Unable to copy your deletion link..."); ?></strong> <button aria-label="Close" class="ml-2 mb-1 close" data-dismiss="toast" type="button"><span aria-hidden="true">&times;</span></button>
		</div>
		<div class="toast-body">
			<div>
				<?php $translate->__("An error occurred on copying the deletion link to your clipboard."); ?>
			</div>
		</div>
	</div>
	<div aria-hidden="true" aria-labelledby="showQRCodeTitle" class="modal fade text-center" id="showQRCode" role="dialog" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="showQRCodeTitle"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16" aria-hidden="true">
							<path d="M2 2h2v2H2V2Z" />
							<path d="M6 0v6H0V0h6ZM5 1H1v4h4V1ZM4 12H2v2h2v-2Z" />
							<path d="M6 10v6H0v-6h6Zm-5 1v4h4v-4H1Zm11-9h2v2h-2V2Z" />
							<path d="M10 0v6h6V0h-6Zm5 1v4h-4V1h4ZM8 1V0h1v2H8v2H7V1h1Zm0 5V4h1v2H8ZM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8H6Zm0 0v1H2V8H1v1H0V7h3v1h3Zm10 1h-1V7h1v2Zm-1 0h-1v2h2v-1h-1V9Zm-4 0h2v1h-1v1h-1V9Zm2 3v-1h-1v1h-1v1H9v1h3v-2h1Zm0 0h3v1h-2v1h-1v-2Zm-4-1v1h1v-2H7v1h2Z" />
							<path d="M7 12h1v3h4v1H7v-4Zm9 2v2h-3v-1h2v-1h1Z" />
						</svg> <?php $translate->__("Your QR Code"); ?></h5><button aria-label="Fermer" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div id="QRCode"></div><br>
					<a class="btn btn-outline-primary" id="QRCodeDownload" onclick="this.href = $('#QRCode img:first').attr('src');" type="button" download="<?= $url . "-" . $id . ".png" ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16" aria-hidden="true">
							<path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
							<path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
						</svg> <?php $translate->__("Download your QR Code"); ?></a>
				</div>
			</div>
		</div>
	</div>

<?php
}
?>