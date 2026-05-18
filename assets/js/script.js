$(document).ready(function(){

    $("#search").keyup(function(){

        let keyword = $(this).val();

        $.ajax({
            url: 'api/products/search.php',
            method: 'GET',
            data: {
                q: keyword
            },

            success:function(response){
                $("#productArea").html(response);
            }
        });
    });

});
