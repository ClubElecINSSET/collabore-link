$("button.nojs").css("display", "inline-flex");

$(function() {
    if (window.innerWidth > 500) {
        $('[data-toggle="popover"]').popover()
        $("#popover").popover({
            trigger: "hover"
        });
    }
})

function fallbackCopyTextToClipboard(text, type) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
        var successful = document.execCommand("copy");
        var msg = successful ? "successful" : "unsuccessful";
        if (type == "shortUrl") {
            $("#okayLinkToast").toast("show");
        } else if (type == "deletionLink") {
            $("#okayDeletionlinkToast").toast("show");
        }
    } catch (err) {
        if (type == "shortUrl") {
            $("#problemLinkToast").toast("show");
        } else if (type == "deletionLink") {
            $("#problemDeletionlinkToast").toast("show");
        }
    }
    document.body.removeChild(textArea);
}

function copyTextToClipboard(text, type) {
    if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(text, type);
        return;
    }
    navigator.clipboard.writeText(text).then(function() {
        if (type == "shortUrl") {
            $("#okayLinkToast").toast("show");
        } else if (type == "deletionLink") {
            $("#okayDeletionlinkToast").toast("show");
        }
    }, function(err) {
        if (type == "shortUrl") {
            $("#problemLinkToast").toast("show");
        } else if (type == "deletionLink") {
            $("#problemDeletionlinkToast").toast("show");
        }
    });
}