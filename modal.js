document.addEventListener("DOMContentLoaded", function(event) {

    // Get the modal
    var modal = document.getElementById("js-modal");

    var itemImg = document.getElementsByClassName("item js-modal-item");
    var modalImg = document.getElementById("js-modal-content");
    var captionText = document.getElementById("caption");

    for (var i = 0; i < itemImg.length; i++) {
        console.log(itemImg[i]);
        itemImg[i].addEventListener("click", (event) => {

            if (!this.activeElement.getAttribute('href')) return; // Not found. Exit here.

            modal.style.display = "block";
            modalImg.src = this.activeElement.getAttribute('href');
            var itemName = this.activeElement.childNodes[1].innerHTML; // Span contains item name
            captionText.innerHTML = itemName;

            event.preventDefault();
            return false;

        });

    }

    // Closes the modal
    modal.onclick = function() {
        modal.style.display = "none";
    }
    var span = document.getElementsByClassName("close")[0];
    span.onclick = function() {
        modal.style.display = "none";
    }

});