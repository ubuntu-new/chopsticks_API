var $branch = 'saburtalo';
//var $branch = phpData.branch;
var activeSong;


//Plays the song. Just pass the id of the audio element.
function play(id){

    //Sets the active song to the song being played. All other functions depend on this.
    activeSong = document.getElementById(id);
    //Plays the song defined in the audio tag.
    activeSong.muted = !activeSong.muted;
    activeSong.play();


    //Calculates the starting volume percentage of the song.
}
$(document).ready(function () {


    $(document).on("click",".start", function () {
        $.ajax({
            url: phpData.updateOrders,
            type: 'post',
            dataType: 'text',
            data: {
                order_id: $(this).data("orderid"),
                status: 2
            },
            success: function (result) {
                getofinishrderbacker();
                getorderbacker();
                if (result == 0)
                    PNotify.success({
                        delay: 5000,
                        title: 'start prodces',
                        text: 'Successfuly'
                    });
                else
                    PNotify.error({
                        delay: 5000,
                        title: 'Send to backer',
                        text: 'Something went wrong'
                    });
            }
        })
    });


    $(document).on("click",".back", function () {
        $.ajax({
            url: phpData.updateOrders,
            type: 'post',
            dataType: 'text',
            data: {
                order_id: $(this).data("orderid"),
                status: 1
            },
            success: function (result) {
                getofinishrderbacker();
                getorderbacker();
                if (result == 0)
                    PNotify.success({
                        delay: 5000,
                        title: 'Return',
                        text: 'Successfuly'
                    });
                else
                    PNotify.error({
                        delay: 5000,
                        title: 'Send to backer',
                        text: 'Something went wrong'
                    });
            }
        })
    });

    $(document).on("click",".finish", function () {
        $.ajax({
            url: phpData.updateOrders,
            type: 'post',
            dataType: 'text',
            data: {
                order_id: $(this).data("orderid"),
                status: 3
            },
            success: function (result) {
                getofinishrderbacker();
                getorderbacker();
                if (result == 0)
                    PNotify.success({
                        delay: 5000,
                        title: 'start prodces',
                        text: 'Successfuly'
                    });

                else
                    PNotify.error({
                        delay: 5000,
                        title: 'Send to backer',
                        text: 'Something went wrong'
                    });
            }
        })
    });






    getorderbacker();
    getofinishrderbacker();
    setInterval(function(){
        getorderbacker();

    }, 1000*30)

})


var  $interval = null;

function getorderbacker() {
    var text = "";
    $.ajax({
        url: phpData.getOrders,
        type: 'post',
        dataType: 'json',
        data: {
            branch: $branch,
            status: 1
        },
        success: function (result) {



            $.each(result, function(key, val){

                var milliseconds = (new Date() - new Date(val.created_at).addHours(2));

                var minutes =val.duration - Math.round(milliseconds/60000);

                var product = ''; var delivery = '';

                var data = JSON.parse(val["order_data"]);
                if (val["source"] == 'woocommerce') {
                    $.each(data.line_items, function (p_k, p_v) {

                        var size = "";

                        if (p_v.meta_data.length > 0) {

                            switch (p_v.meta_data[0].value) {
                                case "small":
                                    size = "S";
                                    break;
                                case  "medium":
                                    size = "M";
                                    break;
                                case  "xl":
                                    size = "XL";
                                    break;

                            }
                        }
                        product += "<div class='col-12'><span class='f_title'>" + p_v.quantity + " " + size + "  " + p_v.name + "</span></div>";

                        $.each(p_v.meta_data, function (mt_k, mt_v) {
                            if (mt_v.value == 'medium' || mt_v.value == 'small' || mt_v.value == 'xl') {
                            }
                            else
                                product += "<div class='col-2'></div>" +
                                    "<div class='col-10 f_16'>" + mt_v.value + "</div>";
                        });

                    });

                    delivery = "<span class='f_text'>"+ (data.shipping.first_name !=""? data.shipping.first_name : data.billing.first_name)+" "+(data.shipping.last_name != "" ?data.shipping.last_name:data.billing.last_name)+"</span>";


                    delivery += "<span  class='f_text font-weight-bold' style='color: #f5b225'>" + data.shipping_lines[0].method_title + "</span>";


                    if (data.customer_note != "")
                        delivery += "<span  class='f_text font-weight-bold'>" + data.customer_note + "</span>";


                    text += ' <div class=" aab col-sm-4 ">' +
                        '<div class="card ">' +
                        '   <div class="card-body">' +
                        '   <h4 class="mt-0 header-title"> #' + val.order_id.toString().slice(-2) + '<div style="float: right"><span class="badge badge-pill badge-danger">' + minutes + ' Min</span></div> ' + '</h4>' +
                        '<div class="row m-t-10">' +
                        '<div class="col-md-12">' +
                        delivery +
                        '</div></div>' +
                        '<div class="row m-t-10">' +
                        product +
                        '</div>' +

                        '<div class="row m-t-10">' +
                        '<div class="col-12 text-right">' +
                        '<span class="f_text">' + data.total + '</span>' +
                        '</div>' +
                        "<div class='col-6 m-t-10' style='padding-right: 0'></div>" +
                        "<div class='col-6 m-t-10'><button class='start btn btn-success waves-effect waves-light' data-status='4' data-orderid='" + val.id + "'>Prep <i class='ion-arrow-right-a'></i>   </button></div>" +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';


                }
                else if (val["source"] == 'Legacy')   {

                    $.each(data[0]["items"], function(key,prod) {
                        product += "<div class='col-12'><span class='f_title'>"+prod["quantity"]+"X "+prod["name"]+"</span></div>";
                        $.each(prod["options"],function(p_key,p_val) {
                            product += "<div class='col-2'></div>" +
                                "<div class='col-10 f_16'>"+p_val["group_name"]+": "+p_val["name"]+"</div>";
                        })
                    });



                    delivery = "<span class='f_text'>"+data[0]["client_first_name"]+" "+data[0]["client_last_name"]+"</span>";
                    delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'>Delivery in "+val["branch"]+"</span>";
                    delivery +="<span  class='f_text font-weight-bold'>"+data[0]["instructions"]+"</span>";

                    text += ' <div class="aab col-sm-4">' +
                        '<div class="card ">' +
                        '   <div class="card-body">' +
                        '   <h4 class="mt-0 header-title"> #'+data[0]["id"].toString().slice(-2)+'<div style="float: right; text-align: right">' +
                        '<span style="font-size: 12px;">'+data[0]["updated_at"].slice(0,-8)+'</span><br/>'+
                        '<span class="badge badge-pill badge-danger">' +minutes+
                        ' Min</span>'+
                        '</div></h4>' +
                        '<div class="row m-t-10 w-100">' +
                        '<div class="col-md-12">' +
                        delivery+
                        '</div></div>'+
                        '<div class="row m-t-10">' +
                        product+
                        '</div>'+
                        '<div class="row m-t-10">' +
                        '<div class="col-12 text-right">' +
                        '<span class="f_text">'+data[0]["total_price"]+'</span>' +
                        '</div>' +
                        "<div class='col-6 m-t-10' style='padding-right: 0'></div>" +
                        "<div class='col-6 m-t-10'><button class='start btn btn-success waves-effect waves-light' data-status='4' data-orderid='" + val.id + "'>Prep <i class='ion-arrow-right-a'></i>   </button></div>" +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                }
                else if (val["source"] == "pos") {


                    $.each(data["items"], function(key,prod) {
                        product += "<div class='col-12'><span class='f_title'>"+prod["qty"]+" "+" "+(prod["size"]?prod["size"]:"")+( (prod["cuts"])?" /16 ":" ")+prod["name"]+"</span></div>";
                        if (prod["crust"])
                            product += "<div class='col-2'></div>" +
                                "<div class='col-10 f_16'>Crust: "+prod["crust"]+"</div>" +
                                "<div class='col-2'></div>"+
                                "<div class='col-10 f_16'>Sauce: "+prod["sauce"]+"</div>";


                        $.each(prod["defaultToppings"],function(p_key,p_val) {
                            if(typeof(p_val["isDeleted"]) != "undefined" && p_val["isDeleted"]) {
                                product += "<div class='col-2'></div>" +
                                    "<div class='col-10 f_16' style='color:red'>"+p_val["name"]+"</div>";
                            } else
                                product += "<div class='col-2'></div>" +
                                    "<div class='col-10 f_16'>"+p_val["name"]+"</div>";
                        });
                        $.each(prod["toppings"],function(p_key,p_val) {

                            product += "<div class='col-2'></div>" +
                                "<div class='col-10 f_16'>"+p_val["count"]+"X "+p_val["name"]+"</div>";
                        });


//if (typeof(prod["half1"]["toppings"]) != 'undefined')
//    console.log(prod["half1"]["toppings"]);
                        if (prod["custom"] == "yes") {


                            product += "<div class='col-12'><span class='f_title'>A "+prod["half1"]["name"]+"</span></div>";
                            product +=
                                "<div class='col-2'></div>"+
                                "<div class='col-10 f_16'>Sauce: "+prod["half1"]["sauce"]+"</div>";

                            $.each(prod["half1"]["defaultToppings"],function(h1_d_k,h1_d_v) {
                                if(typeof(h1_d_v["isDeleted"]) != "undefined" && h1_d_v["isDeleted"]) {
                                    product += "<div class='col-2'></div>" +
                                        "<div class='col-10 f_16' style='color:red'>"+h1_d_v["name"]+"</div>";
                                } else
                                    product += "<div class='col-2'></div>" +
                                        "<div class='col-10 f_16'>"+h1_d_v["name"]+"</div>";
                            })
                            $.each(prod["half1"]["toppings"],function(p_key,h1_d_v) {

                                product += "<div class='col-2'></div>" +
                                    "<div class='col-10 f_16'>"+h1_d_v["count"]+"X "+h1_d_v["name"]+"</div>";
                            })

                            product += "<div class='col-12'><span class='f_title'>B "+prod["half2"]["name"]+"</span></div>";
                            product +=
                                "<div class='col-2'></div>"+
                                "<div class='col-10 f_16'>Sauce: "+prod["half2"]["sauce"]+"</div>";

                            $.each(prod["half1"]["defaultToppings"],function(h1_d_k,h1_d_v) {
                                if(typeof(h1_d_v["isDeleted"]) != "undefined" && h1_d_v["isDeleted"]) {
                                    product += "<div class='col-2'></div>" +
                                        "<div class='col-10 f_16' style='color:red'>"+h1_d_v["name"]+"</div>";
                                } else
                                    product += "<div class='col-2'></div>" +
                                        "<div class='col-10 f_16'>"+h1_d_v["name"]+"</div>";
                            })
                            $.each(prod["half1"]["toppings"],function(p_key,h1_d_v) {

                                product += "<div class='col-2'></div>" +
                                    "<div class='col-10 f_16'>"+h1_d_v["count"]+"X "+h1_d_v["name"]+"</div>";
                            })

                        } else {
                            if(typeof(prod["half1"]) != 'undefined')
                                if(typeof(prod["half1"]["toppings"]) != 'undefined')
                                {
                                    if (prod["half1"]["toppings"].length > 0)
                                        product += "<div class='col-12'><span class='f_title'>Side A Toppingss</span></div>";

                                    $.each(prod["half1"]["defaultToppings"],function(p_key,p_val) {
                                        if(typeof(p_val["isDeleted"]) != "undefined" && p_val["isDeleted"]) {
                                            product += "<div class='col-2'></div>" +
                                                "<div class='col-10 f_16' style='color:red'>"+p_val["name"]+"</div>";
                                        } else
                                            product += "<div class='col-2'></div>" +
                                                "<div class='col-10 f_16'>"+p_val["name"]+"</div>";
                                    });
                                    $.each(prod["half1"]["toppings"],function(p_key,p_val) {

                                        product += "<div class='col-2'></div>" +
                                            "<div class='col-10 f_16'>"+p_val["count"]+"X "+p_val["name"]+"</div>";
                                    });
                                }
                            if(typeof(prod["half2"]) != 'undefined')
                                if(typeof(prod["half2"]["toppings"]) != 'undefined')
                                {
                                    if (prod["half2"]["toppings"].length > 0)
                                        product += "<div class='col-12'><span class='f_title'>Side B Toppingss</span></div>";

                                    $.each(prod["half1"]["defaultToppings"],function(p_key,p_val) {
                                        if(typeof(p_val["isDeleted"]) != "undefined" && p_val["isDeleted"]) {
                                            product += "<div class='col-2'></div>" +
                                                "<div class='col-10 f_16' style='color:red'>"+p_val["name"]+"</div>";
                                        } else
                                            product += "<div class='col-2'></div>" +
                                                "<div class='col-10 f_16'>"+p_val["name"]+"</div>";
                                    });
                                    $.each(prod["half1"]["toppings"],function(p_key,p_val) {

                                        product += "<div class='col-2'></div>" +
                                            "<div class='col-10 f_16'>"+p_val["count"]+"X "+p_val["name"]+"</div>";
                                    });
                                }
                        }


                    });


                    if(typeof(data.customer) != "undefined" && data.customer.name !== null)
                        delivery += "<span class='f_text'>"+data.customer.name+"</span>";

                    if(typeof(data.customer.comment) != "undefined" && data.customer.comment !== null) {
                        delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'> "+data.customer.comment+"</span>";
                    }
                    if(typeof(data.customer.comment2) != "undefined" && data.customer.comment2 !== null) {
                        delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'> "+data.customer.comment2+"</span>";
                    }

                    delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'>"+data.deliveryMethod+ " "+val["branch"]+"</span>";


                    text += ' <div class="blockDiv aab col-sm-4 col-lg-3">' +
                        '<div class="card ">' +
                        '   <div class="card-body">' +
                        '   <h4 class="mt-0 header-title">Pos #'+data["orderId"]+'<div style="float: right; text-align: right">' +
                        '<span style="font-size: 12px;">'+val["created_at"].slice(0,-3)+'</span><br/>'+
                        '<span class="badge badge-pill badge-danger">' +minutes+
                        ' Min</span>'+
                        '</div></h4>' +
                        '<div class="row m-t-10 w-100">' +
                        '<div class="col-md-12">' +
                        delivery+
                        '</div></div>'+
                        '<div class="row m-t-10">' +
                        product+
                        '</div>'+
                        '<div class="row m-t-10">' +
                        '<div class="col-12 text-right">' +
                        '<span class="f_text">'+data["totalPrice"]+'</span>' +
                        '</div>' +
                        "<div class='col-6 m-t-10' style='padding-right: 0'></div>" +
                        "<div class='col-6 m-t-10'><button class='start btn btn-success waves-effect waves-light' data-status='4' data-orderid='" + val.id + "'>Prep <i class='ion-arrow-right-a'></i>   </button></div>" +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }

            });



            var soundFx = $( '#song' ); // Get our sound FX.
            if ( result.length > 0) {
                $("#pending_count").html(result.length);

                if ($interval != null)
                    clearInterval($interval);

                soundFx[0].play();

                $interval =  setInterval(function(){
                    soundFx[0].play();

                }, 10000);
            } else {
                $("#pending_count").html("");
                if ($interval != null)
                    clearInterval($interval);
            }

            $("#orders").html(text);
            $grid =  $('.aaa').masonry({
                itemSelector: '.aab'
            });

            $grid.masonry('destroy');

            $grid.masonry({
                itemSelector: '.aab'
            });



        }
    })
}
Date.prototype.addHours= function(h){
    this.setHours(this.getHours()+h);
    return this;
}
function getofinishrderbacker() {
    var text = "";
    $.ajax({
        url: phpData.getOrders,
        type: 'post',
        dataType: 'json',
        data: {
            branch: $branch,
            status: 2
        },
        success: function (result) {
            $.each(result, function(key, val){

                var product = ''; var  delivery = '';

                var milliseconds = (new Date() - new Date(val.created_at).addHours(2));

                var minutes =val.duration - Math.round(milliseconds/60000);

                var  data = JSON.parse(val["order_data"]);

                if (val["source"] == 'woocommerce') {

                    $.each(data.line_items, function (p_k, p_v) {

                        var size = "";

                        if (p_v.meta_data.length > 0) {

                            switch (p_v.meta_data[0].value) {
                                case "small":
                                    size = "S";
                                    break;
                                case  "medium":
                                    size = "M";
                                    break;
                                case  "xl":
                                    size = "XL";
                                    break;

                            }
                        }
                        product += "<div class='col-12'><span class='f_title'>" + p_v.quantity + " " + size + "  " + p_v.name + "</span></div>";

                        $.each(p_v.meta_data, function (mt_k, mt_v) {
                            if (mt_v.value == 'medium' || mt_v.value == 'small' || mt_v.value == 'xl') {
                            }
                            else
                                product += "<div class='col-2'></div>" +
                                    "<div class='col-10 f_16'>" + mt_v.value + "</div>";
                        });

                    });
                    delivery = "<span class='f_text'>" + data.billing.first_name + " " + data.billing.last_name + "</span>";

                    delivery += "<span  class='f_text font-weight-bold' style='color: #f5b225'>" + data.shipping_lines[0].method_title + "</span>";

                    if (data.customer_note != "")
                        delivery += "<span  class='f_text font-weight-bold'>" + data.customer_note + "</span>";

                    text += ' <div class=" aab  col-sm-4">' +
                        '<div class="card ">' +
                        '   <div class="card-body">' +
                        '   <h4 class="mt-0 header-title"> #' + val.order_id.toString().slice(-2) + '<div style="float: right"><span class="badge badge-pill badge-danger">' + minutes + ' Min</span></div> ' + '</h4>' +
                        '<div class="row m-t-10">' +
                        '<div class="col-md-12">' +
                        delivery +
                        '</div></div>' +
                        '<div class="row m-t-10">' +
                        product +
                        '</div>' +

                        '<div class="row m-t-10">' +
                        '<div class="col-12 text-right">' +
                        '<span class="f_text">' + data.total + '</span>' +
                        '</div>' +
                        "<div class='col-6 m-t-10' style='padding-right: 0'><button class='back btn btn-warning waves-effect waves-light' data-orderid='" + val.id + "'> <i class='ion-arrow-left-a'></i></div>" +
                        "<div class='col-6 m-t-10'><button class='finish btn btn-success waves-effect waves-light' data-orderid='" + val.id + "'>Bake <i class='ion-arrow-right-a'></i>   </button></div>" +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }
                else if (val["source"] == 'Legacy')   {

                    $.each(data[0]["items"], function(key,prod) {
                        product += "<div class='col-12'><span class='f_title'>"+prod["quantity"]+"X "+prod["name"]+"</span></div>";
                        $.each(prod["options"],function(p_key,p_val) {
                            product += "<div class='col-2'></div>" +
                                "<div class='col-10 f_16'>"+p_val["group_name"]+": "+p_val["name"]+"</div>";
                        })
                    });



                    delivery = "<span class='f_text'>"+data[0]["client_first_name"]+" "+data[0]["client_last_name"]+"</span>";
                    delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'>Delivery in "+val["branch"]+"</span>";
                    delivery +="<span  class='f_text font-weight-bold'>"+data[0]["instructions"]+"</span>";

                    text += ' <div class="aab col-sm-4">' +
                        '<div class="card ">' +
                        '   <div class="card-body">' +
                        '   <h4 class="mt-0 header-title"> #'+data[0]["id"].toString().slice(-2)+'<div style="float: right; text-align: right">' +
                        '<span style="font-size: 12px;">'+data[0]["updated_at"].slice(0,-8)+'</span><br/>'+
                        '<span class="badge badge-pill badge-danger">' +minutes+
                        ' Min</span>'+
                        '</div></h4>' +
                        '<div class="row m-t-10 w-100">' +
                        '<div class="col-md-12">' +
                        delivery+
                        '</div></div>'+
                        '<div class="row m-t-10">' +
                        product+
                        '</div>'+
                        '<div class="row m-t-10">' +
                        '<div class="col-12 text-right">' +
                        '<span class="f_text">'+data[0]["total_price"]+'</span>' +
                        '</div>' +
                        "<div class='col-6 m-t-10' style='padding-right: 0'><button class='back btn btn-warning waves-effect waves-light' data-orderid='" + val.id + "'> <i class='ion-arrow-left-a'></i></div>" +
                        "<div class='col-6 m-t-10'><button class='finish btn btn-success waves-effect waves-light' data-orderid='" + val.id + "'>Bake <i class='ion-arrow-right-a'></i>   </button></div>" +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                }
                else if (val["source"] == "pos") {


                    $.each(data["items"], function(key,prod) {
                        product += "<div class='col-12'><span class='f_title'>"+prod["qty"]+" "+" "+(prod["size"]?prod["size"]:"")+( (prod["cuts"])?" /16 ":" ")+prod["name"]+"</span></div>";
                        if (prod["crust"])
                            product += "<div class='col-2'></div>" +
                                "<div class='col-10 f_16'>Crust: "+prod["crust"]+"</div>" +
                                "<div class='col-2'></div>"+
                                "<div class='col-10 f_16'>Sauce: "+prod["sauce"]+"</div>";


                        $.each(prod["defaultToppings"],function(p_key,p_val) {
                            if(typeof(p_val["isDeleted"]) != "undefined" && p_val["isDeleted"]) {
                                product += "<div class='col-2'></div>" +
                                    "<div class='col-10 f_16' style='color:red'>"+p_val["name"]+"</div>";
                            } else
                                product += "<div class='col-2'></div>" +
                                    "<div class='col-10 f_16'>"+p_val["name"]+"</div>";
                        });
                        $.each(prod["toppings"],function(p_key,p_val) {

                            product += "<div class='col-2'></div>" +
                                "<div class='col-10 f_16'>"+p_val["count"]+"X "+p_val["name"]+"</div>";
                        });


//if (typeof(prod["half1"]["toppings"]) != 'undefined')
//    console.log(prod["half1"]["toppings"]);
                        if (prod["custom"] == "yes") {


                            product += "<div class='col-12'><span class='f_title'>A "+prod["half1"]["name"]+"</span></div>";
                            product +=
                                "<div class='col-2'></div>"+
                                "<div class='col-10 f_16'>Sauce: "+prod["half1"]["sauce"]+"</div>";

                            $.each(prod["half1"]["defaultToppings"],function(h1_d_k,h1_d_v) {
                                if(typeof(h1_d_v["isDeleted"]) != "undefined" && h1_d_v["isDeleted"]) {
                                    product += "<div class='col-2'></div>" +
                                        "<div class='col-10 f_16' style='color:red'>"+h1_d_v["name"]+"</div>";
                                } else
                                    product += "<div class='col-2'></div>" +
                                        "<div class='col-10 f_16'>"+h1_d_v["name"]+"</div>";
                            })
                            $.each(prod["half1"]["toppings"],function(p_key,h1_d_v) {

                                product += "<div class='col-2'></div>" +
                                    "<div class='col-10 f_16'>"+h1_d_v["count"]+"X "+h1_d_v["name"]+"</div>";
                            })

                            product += "<div class='col-12'><span class='f_title'>B "+prod["half2"]["name"]+"</span></div>";
                            product +=
                                "<div class='col-2'></div>"+
                                "<div class='col-10 f_16'>Sauce: "+prod["half2"]["sauce"]+"</div>";

                            $.each(prod["half1"]["defaultToppings"],function(h1_d_k,h1_d_v) {
                                if(typeof(h1_d_v["isDeleted"]) != "undefined" && h1_d_v["isDeleted"]) {
                                    product += "<div class='col-2'></div>" +
                                        "<div class='col-10 f_16' style='color:red'>"+h1_d_v["name"]+"</div>";
                                } else
                                    product += "<div class='col-2'></div>" +
                                        "<div class='col-10 f_16'>"+h1_d_v["name"]+"</div>";
                            })
                            $.each(prod["half1"]["toppings"],function(p_key,h1_d_v) {

                                product += "<div class='col-2'></div>" +
                                    "<div class='col-10 f_16'>"+h1_d_v["count"]+"X "+h1_d_v["name"]+"</div>";
                            })

                        } else {
                            if(typeof(prod["half1"]) != 'undefined')
                                if(typeof(prod["half1"]["toppings"]) != 'undefined')
                                {
                                    if (prod["half1"]["toppings"].length > 0)
                                        product += "<div class='col-12'><span class='f_title'>Side A Toppingss</span></div>";

                                    $.each(prod["half1"]["defaultToppings"],function(p_key,p_val) {
                                        if(typeof(p_val["isDeleted"]) != "undefined" && p_val["isDeleted"]) {
                                            product += "<div class='col-2'></div>" +
                                                "<div class='col-10 f_16' style='color:red'>"+p_val["name"]+"</div>";
                                        } else
                                            product += "<div class='col-2'></div>" +
                                                "<div class='col-10 f_16'>"+p_val["name"]+"</div>";
                                    });
                                    $.each(prod["half1"]["toppings"],function(p_key,p_val) {

                                        product += "<div class='col-2'></div>" +
                                            "<div class='col-10 f_16'>"+p_val["count"]+"X "+p_val["name"]+"</div>";
                                    });
                                }
                            if(typeof(prod["half2"]) != 'undefined')
                                if(typeof(prod["half2"]["toppings"]) != 'undefined')
                                {
                                    if (prod["half2"]["toppings"].length > 0)
                                        product += "<div class='col-12'><span class='f_title'>Side B Toppingss</span></div>";

                                    $.each(prod["half1"]["defaultToppings"],function(p_key,p_val) {
                                        if(typeof(p_val["isDeleted"]) != "undefined" && p_val["isDeleted"]) {
                                            product += "<div class='col-2'></div>" +
                                                "<div class='col-10 f_16' style='color:red'>"+p_val["name"]+"</div>";
                                        } else
                                            product += "<div class='col-2'></div>" +
                                                "<div class='col-10 f_16'>"+p_val["name"]+"</div>";
                                    });
                                    $.each(prod["half1"]["toppings"],function(p_key,p_val) {

                                        product += "<div class='col-2'></div>" +
                                            "<div class='col-10 f_16'>"+p_val["count"]+"X "+p_val["name"]+"</div>";
                                    });
                                }
                        }


                    });


                    if(typeof(data.customer) != "undefined" && data.customer.name !== null)
                        delivery += "<span class='f_text'>"+data.customer.name+"</span>";

                    if(typeof(data.customer.comment) != "undefined" && data.customer.comment !== null) {
                        delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'> "+data.customer.comment+"</span>";
                    }
                    if(typeof(data.customer.comment2) != "undefined" && data.customer.comment2 !== null) {
                        delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'> "+data.customer.comment2+"</span>";
                    }

                    delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'>"+data.deliveryMethod+ " "+val["branch"]+"</span>";


                    text += ' <div class="blockDiv aab col-sm-4 col-lg-3">' +
                        '<div class="card ">' +
                        '   <div class="card-body">' +
                        '   <h4 class="mt-0 header-title">Pos #'+data["orderId"]+'<div style="float: right; text-align: right">' +
                        '<span style="font-size: 12px;">'+val["created_at"].slice(0,-3)+'</span><br/>'+
                        '<span class="badge badge-pill badge-danger">' +minutes+
                        ' Min</span>'+
                        '</div></h4>' +
                        '<div class="row m-t-10 w-100">' +
                        '<div class="col-md-12">' +
                        delivery+
                        '</div></div>'+
                        '<div class="row m-t-10">' +
                        product+
                        '</div>'+
                        '<div class="row m-t-10">' +
                        '<div class="col-12 text-right">' +
                        '<span class="f_text">'+data["totalPrice"]+'</span>' +
                        '</div>' +
                        "<div class='col-6 m-t-10' style='padding-right: 0'><button class='back btn btn-warning waves-effect waves-light' data-orderid='" + val.id + "'> <i class='ion-arrow-left-a'></i></div>" +
                        "<div class='col-6 m-t-10'><button class='finish btn btn-success waves-effect waves-light' data-orderid='" + val.id + "'>Bake <i class='ion-arrow-right-a'></i>   </button></div>" +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }
                });

                $("#orders_start").html(text);

                $grid =  $('.bbb').masonry({
                    itemSelector: '.aab'
                });

                $grid.masonry('destroy');

                $grid.masonry({
                    itemSelector: '.aab'
                });


            }
        })
}