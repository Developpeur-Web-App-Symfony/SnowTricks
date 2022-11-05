(function () {
    $("#showMoreOffset").val(8); //Init

    $("#load-more-tricks").click(function () {
        let offset = $("#showMoreOffset").val();
         let connected = $("#moreContent").data('attribute');

        $.ajax({
            type: 'POST',
            url: '/trick/loadmore',
            data: {
                offset: offset
            },
            success: function (data) {
                //Display card
                for (let i = 0; i < data.length; i++) {
                    let urlViewTrick = "/trick/" + data[i]['slug'];
                    let urlEditTrick = "/trick/edit/" + data[i]['slug'];
                    let urlDeleteTrick = "/trick/delete/" + data[i]['slug'];

                    let html = "";

                    html += '<div class="col-12 col-lg-2 tricks-home p-1 text-center m-2">';
                    html += '<img class="img-fluid" src="media/' + data[i]['additionalImage'] + '" alt="' + data[i]['altImage'] + '">';
                    html += '<div class="infos-tricks-home d-flex justify-content-around align-items-center">';
                    html += '<div>';
                    html += '<a href="' + urlViewTrick + '" class="title_trick"><p>' + data[i]['name'] + '</p></a>';
                    html += '</div>'
                    if (connected === true) {
                        html += '<div class="d-flex align-items-center">';
                        html += '<a class="btn-form" href="' + urlEditTrick + '" data-toggle="tooltip" data-placement="right" title="Modifier la figure"><i class="fas fa-edit"></i></a>';
                        html += '<form method="post" action="' + urlDeleteTrick + '" onsubmit = "return confirm(\'Êtes-vous sûr de bien vouloir supprimer cet élément ?\');" >';
                        html += '<input type="hidden" name="deleteTrickId" value="#">';
                        html += '<input type="hidden" name="trickToken" value="#">';
                        html += '<button class="btn" data-toggle="tooltip" data-placement="right" title="Supprimer la figure"><i class="fa fa-trash"></i></i></button>';
                        html += '</form>';
                        html += '</div>'
                    }
                    html += '</div>';
                    html += '</div>';

                    $("#moreContent").append(html);
                }

                $("#load-more-tricks").hide();
            },
            error: function (data) {
                alert("error " + data);
            }
        });
    });
})();