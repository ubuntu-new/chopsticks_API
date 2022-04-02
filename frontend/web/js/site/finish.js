var $branch = phpData.branch;
var $grid = null;
$(document).ready(function () {
    var date = new Date();
    date.setDate(date.getDate() -1);

    $('#datetimepicker4').datetimepicker(
        {
            defaultDate: date,
            format: 'YYYY-MM-DD'
        }
    );
    get_finish_orders();


    $(document).on("click",".fillterbydate", function () {
        get_finish_orders();
    });

    setInterval(function(){
        get_finish_orders();

    }, 1000*60)

});

var  $interval = null;

Date.prototype.addHours= function(h){
    this.setHours(this.getHours()+h);
    return this;
}
function get_finish_orders() {
    var text = "";
    $.ajax({
        url: phpData.get_orders_for_manager,
        type: 'post',
        dataType: 'json',
        data: {
            branch: $branch,
            status: '4,5',
            date: $("#datetimepicker4").val()
        },
        success: function (result) {


            $.each(result, function(key, val){

                var milliseconds = (new Date(val.finish_date) - new Date(val.created_at).addHours(2));

                var minutes =val.duration - Math.round(milliseconds/60000);

                var product = "";

                var data = JSON.parse(val["order_data"]);

                var st = val.created_at;

                if (val["source"] == 'woocommerce') {
                    var st = data.date_created.split("T");
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

                        product += "<div class='col-12'><span class='f_title'>"+p_v.quantity+" "+ size+"  "+p_v.name+"</span></div>";

                        //product += '<div  class="col-12 f_24">'+p_v.name+" - "+p_v.quantity+"</div>";
                        $.each(p_v.meta_data, function (mt_k, mt_v) {
                            if (mt_v.value == 'medium' || mt_v.value == 'small' || mt_v.value == 'xl' ){}
                            else
                                product += "<div class='col-2'></div>" +
                                    "<div class='col-10 f_16'>"+mt_v.value+"</div>";
                        });
                    });

                    delivery = "<span class='f_text'>"+ (data.shipping.first_name !=""? data.shipping.first_name : data.billing.first_name)+" "+(data.shipping.last_name != "" ?data.shipping.last_name:data.billing.last_name)+"</span>"+
                        "<span class='f_text'>"+ data.billing.email+"</span>"+
                        "<span  class='f_text'>"+(data.meta_data[4].value == ""? data.billing.phone : data.meta_data[4].value)+"</span>"+
                        "<span  class='f_text'>"+(data.shipping.address_1 == "" ? data.billing.address_1:data.shipping.address_1)+"</span>"+
                        "<span  class='f_text'>"+(data.shipping.address_2== ""?data.billing.address_2:data.shipping.address_2)+"</span>";


                    delivery += "<span  class='f_text'>"+
                        ((data.meta_data[5].value == "" && data.meta_data[0].value == "") ? "":((data.meta_data[5].value != "" ? data.meta_data[5].value:data.meta_data[0].value)+" SD/ ")) +
                        ((data.meta_data[6].value == "" && data.meta_data[1].value == "") ? "":((data.meta_data[6].value != "" ? data.meta_data[6].value:data.meta_data[1].value)+" DC/ "))+
                        ((data.meta_data[7].value == "" && data.meta_data[2].value == "") ? "":((data.meta_data[7].value != "" ? data.meta_data[7].value:data.meta_data[2].value)+" SR/ "))+
                        ((data.meta_data[8].value == "" && data.meta_data[3].value == "") ? "":((data.meta_data[8].value != "" ? data.meta_data[8].value:data.meta_data[3].value)+" BN"))+" </span>";



                    if (data.customer_note != "")
                        delivery +="<span  class='f_text font-weight-bold'>"+data.customer_note+"</span>";

                    delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'>"+data.shipping_lines[0].method_title+"</span>";


                    var t = (val.status==1? val.send_to_backer_date :  val.accept_date);
                    t= t.split(" ");
                    text += ' <div class="blockDiv  aab col-sm-4 col-lg-3">' +
                        '<div class="card ">' +
                        '   <div class="card-body">' +
                        '   <h4 class="mt-0 header-title">Web #'+val.order_id.toString().slice(-2)+'<div style="float: right; text-align: right">' +
                        '<span style="font-size: 12px;">'+(st[0]+' '+st[1].slice(0,-3))+'</span>'+
                        '</div> '+'</h4>' +
                        '<div class="row m-t-10 w-100">' +
                        '<div class="col-md-12">' +
                        delivery+
                        '</div></div>'+
                        '<div class="row m-t-10">' +
                        product+
                        '</div>'+

                        '<div class="row m-t-10">' +
                        '<div class="col-12 text-right">' +
                        '<span class="f_text">'+data.total+'</span>' +
                        '</div>' +
                        "<div class='col-6 m-t-10' style='padding-right: 0'>"+(val.status==4?'<i class="mdi mdi-checkbox-blank-circle text-success"></i> Completed':'<i class="mdi mdi-checkbox-blank-circle text-danger"></i> cancelled')+" </button></div>" +
                        "<div class='col-6 m-t-10'></div>" +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                }
                else  if (val["source"] == 'Legacy') {

                    $.each(data[0]["items"], function(key,prod) {
                        product += "<div class='col-12'><span class='f_title'>"+prod["quantity"]+"X "+prod["name"]+"</span></div>";
                        product += "<div class='col-12'><span class='f_title'>"+prod["instructions"]+"</span></div>";
                        $.each(prod["options"],function(p_key,p_val) {
                            product += "<div class='col-2'></div>" +
                                "<div class='col-10 f_16'>"+p_val["group_name"]+": "+p_val["name"]+"</div>";
                        })
                    });



                    delivery = "<span class='f_text'>"+data[0]["client_first_name"]+" "+data[0]["client_last_name"]+"</span>" +
                        "<span class='f_text'>"+data[0]["client_email"]+"</span>"+
                        "<span class='f_text'>"+data[0]["client_phone"]+"</span>"+
                        "<span class='f_text'>"+data[0]["client_address"]+"</span>";
                    delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'>Delivery in "+val["branch"]+"</span>";
                    delivery +="<span  class='f_text font-weight-bold'>"+data[0]["instructions"]+"</span>";

                    text += ' <div class="blockDiv aab col-sm-4 col-lg-3">' +
                        '<div class="card ">' +
                        '   <div class="card-body">' +
                        '   <h4 class="mt-0 header-title">Legacy api #'+data[0]["id"].toString().slice(-2)+'<div style="float: right; text-align: right">' +
                        '<span style="font-size: 12px;">'+data[0]["updated_at"].slice(0,-8)+'</span><br/>'+
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
                        "<div class='col-6 m-t-10' style='padding-right: 0'>"+(val.status==4?'<i class="mdi mdi-checkbox-blank-circle text-success"></i> Completed':'<i class="mdi mdi-checkbox-blank-circle text-danger"></i> cancelled')+" </button></div>" +
                        "<div class='col-6 m-t-10'></div>" +
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

                    if(typeof(data.adress) != "undefined" && data.adress !== null)
                        delivery +=  "<span class='f_text'>"+data.adress+"</span>";


                    if(typeof(data.customer.tel) != "undefined" && data.customer.tel !== null) {
                        delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'> "+data.customer.tel+"</span>";
                    }
                    if(typeof(data.customer.tel2) != "undefined" && data.customer.tel2 !== null) {
                        delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'> "+data.customer.tel2+"</span>";
                    }
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
                        "<div class='col-6 m-t-10' style='padding-right: 0'>"+(val.status==4?'<i class="mdi mdi-checkbox-blank-circle text-success"></i> Completed':'<i class="mdi mdi-checkbox-blank-circle text-danger"></i> cancelled')+" </button></div>" +
                        "<div class='col-6 m-t-10'></div>" +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }


            //
            //    $.each(data.line_items, function (p_k, p_v) {
            //
            //        var size = "";
            //
            //        if (p_v.meta_data.length > 0) {
            //
            //            switch (p_v.meta_data[0].value.toLowerCase()) {
            //                case "small":
            //                    size = "S";
            //                    break;
            //                case  "medium":
            //                    size = "M";
            //                    break;
            //                case  "xl":
            //                    size = "XL";
            //                    break;
            //
            //            }
            //        }
            //        product += "<div class='col-12'><span class='f_title'>"+p_v.quantity+" "+ size+"  "+p_v.name+"</span></div>";
            //
            //        //product += '<span class="f_24"><strong>'+p_v.name+"</strong> - "+p_v.quantity+"</span><br>";
            //        //$.each(p_v.meta_data, function (mt_k, mt_v) {
            //        //
            //        //    product += '<span class="f_24">'+mt_v.value+"</span><br>";
            //        //});
            //
            //    });
            //
            //    var delivery = "<span class='f_text'>"+ data.billing.first_name+" "+data.billing.last_name+"</span>"+
            //        "<span  class='f_text'>"+data.billing.phone+"</span>"+
            //        "<span  class='f_text'>"+data.billing.address_1+"</span>"+
            //        "<span  class='f_text'>"+data.billing.address_2+"</span>";
            //
            //    if (data.customer_note != "")
            //        delivery +="<span  class='f_text font-weight-bold'>"+data.customer_note+"</span>";
            //
            //    delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'>"+data.shipping_lines[0].method_title+"</span>";
            //
            //    var t = (val.status==1? val.send_to_backer_date :  val.accept_date);
            //    t= t.split(" ");
            //    text += ' <div class=" aab col-sm-4 col-lg-3">' +
            //        '<div class="card ">' +
            //        '   <div class="card-body">' +
            //
            //        '   <h4 class="mt-0 header-title"> #'+val.order_id.toString().slice(-2)+'<div style="float: right; text-align: right">' +
            //        '<span style="font-size: 12px;">'+(st.slice(0,-3))+'</span>'+
            //        '</div></h4>' +
            //        '<div class="row m-t-10">' +
            //        '<div class="col-md-12">' +
            //        delivery+
            //        '</div></div>'+
            //        '<div class="row m-t-10">' +
            //        product+
            //        '</div>'+
            //
            //        '<div class="row m-t-10">' +
            //        '<div class="col-12 text-right">' +
            //        '<span class="f_text">'+data.total+'</span>' +
            //        '</div>' +
            //        "<div class='col-6 m-t-10' style='padding-right: 0'>"+(val.status==4?'<i class="mdi mdi-checkbox-blank-circle text-success"></i> Completed':'<i class="mdi mdi-checkbox-blank-circle text-danger"></i> cancelled')+" </button></div>" +
            //        "<div class='col-6 m-t-10'></div>" +
            //        '</div>' +
            //        '</div>' +
            //        '</div>' +
            //        '</div>';
            //
            });



            $("#finish").html(text);

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

