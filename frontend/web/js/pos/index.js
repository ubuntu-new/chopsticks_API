var $quantity_iden = false;
var $timeout = 1;
var $order_num = null;
var $pizza_quantity = 0;

$(document).ready(function () {

    $pizza_quantity = $("#pizza_quantity");
    $order_num = $(".activeOrder").html();


    localStorage.removeItem('2205')
parseForHtml(JSON.parse(localStorage.getItem('2205')));

    $(document).on("click",".quantity", function () {
        clearTimeout($timeout);

        if (!$quantity_iden) {
            $("#pizza_quantity").html($(this).find("span").html());
            $quantity_iden = true;
        } else {
            $("#pizza_quantity").html($("#pizza_quantity").html()+$(this).find("span").html());
        }


       $timeout = setTimeout(function () {
            $quantity_iden = false;
        },500)


        $(".quantity").removeClass("quantityActive");
        $(this).addClass("quantityActive")

    });

    $(document).on("click",".updateCart", function () {

        var key = $(this).data("key");
        var met = $(this).data("method");
       var obj = JSON.parse(localStorage.getItem('2205'));

        if (met == 'plus')
            obj[key].qn =  (parseInt(obj[key].qn)+1);

        else if (met == 'minus') {
            obj[key].qn = parseInt(obj[key].qn)-1;
            if (obj[key].qn == 0)
                delete  obj[key];
        }


        else if (met == 'remove')
            delete  obj[key];



        localStorage.setItem('2205', JSON.stringify(obj));
        parseForHtml(obj)

    });

    $(document).on("click",".addPizza", function () {

        var el = $(this);
     //   alert($pizza_quantity.html());
     //
        var key = 0;
        var obj = {};

        if (localStorage.getItem('2205')) {
          obj = JSON.parse(localStorage.getItem('2205'));
          key = Object.keys(obj).length;
          obj[key] = {name:el.data("name"), id:el.data("id"),qn:$pizza_quantity.html(), price:el.data("price")};

            parseForHtml(obj)

     } else {
         obj[key] = {name:el.data("name"), id:el.data("id"),qn:$pizza_quantity.html(),  price:el.data("price")};
            parseForHtml(obj)
     }


        localStorage.setItem('2205', JSON.stringify(obj));

        $pizza_quantity.html(1);

        $(".quantityActive").removeClass("quantityActive");
        $(".quantity").eq(1).addClass("quantityActive");
    });

    $(document).on("click",".showCat", function () {

        if (!$(this).hasClass("active")){
            $(".showCat").removeClass("active");
            $(this).addClass("active");
            $(".p_binder").hide();
            $("."+$(this).data("target")).show();
        }
    })
});

function  parseForHtml(obj) {
    var text = '';
    var price = 0;
    $.each(obj, function (key, val) {
        text += ' <div class="row mt-1"> ' +
            '<div class="col-2"><span class="updateCart" data-method="minus" data-key="'+key+'">-</span> '+val["qn"]+
            ' <span class="updateCart" data-method="plus" data-key="'+key+'">+</span> </div> ' +
           '<div class="col-md-1"><span class="updateCart" data-method="remove" data-key="'+key+'">x</span></div>'+
            '<div class="col-5 text-right"> ' +
            '<strong> '+val["name"]+'</strong> ' +
            '</div> ' +
            '<div class="col-2">'+val["price"].toFixed(2)+'</div> ' +
            '<div class="col-2">'+(val["price"]*val["qn"]).toFixed(2)+'</div> ' +
            '</div>';
        price += parseFloat(val["price"]*val["qn"]);
    });
    $("#total_price").html(price.toFixed(2));
    $("#order_view").html(text)
}