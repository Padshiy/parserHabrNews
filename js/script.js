function pagination(elem){
    let page = $(elem).html();
    updateNews(page);
    let link = $('.pagination a');
    link.removeClass('btn-primary');
    $(elem).addClass('btn-primary');
}

function updateNews(page) {
    $.ajax({
        type: "POST",
        url: "/ajax/",
        data: {page: page, action: 'update'},
        dataType: 'json'
    })
        .done(function(obj){
            if(obj.success === false) {
                console.log(obj.msg);
                return;
            }
            $('.news').html(obj.msg);
        });
}

function downloadNews()
{

    $.ajax({
        type: "POST",
        url: "/ajax/",
        data: {action: 'download'},
        dataType: 'json'
    })
        .done(function(obj){
            if(obj.success === false) {
                $('.news__download_message').html(obj.msg);
            }
            let page = $('.pagination a.btn-primary').html();
            updateNews(page);
        });
}

function fullNews(elem) {
    let news = $(elem).parent().children('.news__value_full');
    $.fancybox.open(news);
}