var wrapper = document.getElementById("signature-pad"),
    clearButton = wrapper.querySelector("[data-action=clear]"),
    saveButton = wrapper.querySelector("[data-action=save]"),
    canvas = wrapper.querySelector("canvas"),
    signaturePad;

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}

window.onresize = resizeCanvas;
resizeCanvas();

signaturePad = new SignaturePad(canvas);

clearButton.addEventListener("click", function (event) {
    $("#idb64").val(null);
    signaturePad.clear();
});

saveButton.addEventListener("click", function (event) {
    if (signaturePad.isEmpty()) {
        alert("Please provide signature first.");
    } else {
        
        $("#idb64").val(signaturePad.toDataURL());

        //window.open(signaturePad.toDataURL());

        // //$data_uri = "data:image/png;base64,iVBORw0K...";
        // $data_uri = signaturePad.toDataURL();
        // $data_pieces = explode(",", $data_uri);
        // $encoded_image = $data_pieces[1];
        // $decoded_image = base64_decode($encoded_image);
        // file_put_contents( "signature.png",$decoded_image);
    }
});
