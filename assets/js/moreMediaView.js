(function () {
    let btnMoreMedia = $("#more-media");
    let media = $(".media-trick");
    btnMoreMedia.click(function () {
        media.css("display", "flex");
        media.css("flex-direction", "row");
        media.css("justify-content", "center");
        btnMoreMedia.css("display", "none");
    });
})();