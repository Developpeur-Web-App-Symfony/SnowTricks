let mybutton = document.getElementById("btn-back-to-top");

mybutton.addEventListener("click", backToTop);

window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {
    if (
        document.body.scrollTop > 100 ||
        document.documentElement.scrollTop > 100
    ) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}

function backToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}