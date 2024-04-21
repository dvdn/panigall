document.addEventListener("DOMContentLoaded", function (event) {

    // Get the modal
    var modal = document.getElementById("js-modal");

    var itemImg = document.getElementsByClassName("item js-modal-item");
    var modalImg = document.getElementById("js-modal-content");
    var captionText = document.getElementById("caption");
    var position = document.getElementById("img_pos");
    var next = document.getElementById("next");
    var prev = document.getElementById("prev");

    for (var i = 0; i < itemImg.length; i++) {

        itemImg[i].addEventListener("click", (event) => {
            var currentEl = event.currentTarget;
            if (!currentEl.getAttribute('href')) return; // Not found, exit here

            modal.style.display = "block";
            modalImg.src = currentEl.getAttribute('href');
            var itemName = currentEl.childNodes[1].innerHTML; // Span contains item name
            captionText.innerHTML = itemName;
            position.innerHTML = [...itemImg].indexOf(currentEl);

            event.preventDefault();
            return false;
        });
    }

    // Closes the modal
    modalImg.onclick = function () {
        modal.style.display = "none";
    }

    document.getElementById("close").onclick = function () {
        modal.style.display = "none";
    }

    // Next Prev
    next.addEventListener("click", (e) => {
        var currentPos = Number(position.innerHTML)
        if (currentPos < itemImg.length - 1) {
            itemImg[currentPos + 1].click();
        } else {
            modal.style.display = "none";
        }
    });

    prev.addEventListener("click", (e) => {
        var currentPos = Number(position.innerHTML)
        if (currentPos > 0) {
            itemImg[currentPos - 1].click();
        } else {
            modal.style.display = "none";
        }
    });
});
