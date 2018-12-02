$(document).ready(function () {
    $('.btn').click(function(e){
        e.preventDefault();
        $('button').removeClass('btn-info').addClass('btn-default');
        $(this).removeClass('btn-default').addClass('btn-info');
        handleMenuAction(this.innerHTML);
    });

    setDateInfo();
    $('#menu-ul li button')[0].click();
});

function handleMenuAction(new_item){
    // Hide and show the correct content containers (div & canvas)
    if(new_item === 'Meterstanden'){
        $('#overview').css('display', 'inherit');
        $('#chart').css('display', 'none');
    }
    else{
        $('#overview').css('display', 'none');
        $('#chart').css('display', 'inherit');

        generateGraph(new_item);
    }
}