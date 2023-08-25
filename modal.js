document.addEventListener("DOMContentLoaded", function(event) {

    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var item_img = document.getElementsByClassName("item js_modal");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    //setTimeout(function(){

    for (var i = 0; i < item_img.length; i++) {
        console.log(item_img[i]);
        item_img[i].addEventListener("click", (event) => {
            console.log(event.target.src);

            const anchor = event.target.closest("a");   // Find closest Anchor (or self)
            if (!anchor) return;                        // Not found. Exit here.
            console.log( anchor.getAttribute('href'));

            modal.style.display = "block";
            modalImg.src = anchor.getAttribute('href');
            //captionText.innerHTML = this.alt;

            event.preventDefault();
            return false;

        });

    }

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
    modal.style.display = "none";
    }

});