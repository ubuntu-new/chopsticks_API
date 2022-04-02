var selected_device;
var devices = [];
function setup()
{
    //Get the default device from the application as a first step. Discovery takes longer to complete.
    BrowserPrint.getDefaultDevice("printer", function(device)
    {

        //Add device to list of devices and to html select element
        selected_device = device;
        devices.push(device);

        var option = document.createElement("option");
        option.text = device.name;


        //Discover any other devices available to the application
        BrowserPrint.getLocalDevices(function(device_list){
            for(var i = 0; i < device_list.length; i++)
            {
                //Add device to list of devices and to html select element
                var device = device_list[i];
                if(!selected_device || device.uid != selected_device.uid)
                {
                    devices.push(device);
                    var option = document.createElement("option");
                    option.text = device.name;
                    option.value = device.uid;

                }
            }

        }, function(){alert("Error getting local devices")},"printer");

    }, function(error){
        alert(error);
    })
}

function writeToSelectedPrinter(dataToWrite)
{
    selected_device.send(dataToWrite, undefined, errorCallback);
}
var readCallback = function(readData) {
    if(readData === undefined || readData === null || readData === "")
    {
        alert("No Response from Device");
    }
    else
    {
        alert(readData);
    }

}
var errorCallback = function(errorMessage){
    alert("Error: " + errorMessage);
}


window.onload = setup;

var $branch = phpData.branch;
var $grid = null;

var getDuration = function(d1, d2) {
    d3 = new Date(d2 - d1);
    d0 = new Date(0);

    return {
        getHours: function(){
            return d3.getHours() - d0.getHours();
        },
        getMinutes: function(){
            return d3.getMinutes() - d0.getMinutes();
        },
        getMilliseconds: function() {
            return d3.getMilliseconds() - d0.getMilliseconds();
        },
        toString: function(){
            return this.getHours() + ":" +
                this.getMinutes() + ":" +
                this.getMilliseconds();
        }
    };
};




$(document).ready(function () {

    $(document).on("click",".showcancelModal", function () {
        var text = "order #"+$(this).data("orderid")+", "+$(this).data("username");
        $("#exampleModalLabel").html(text);
        $("#cancel_order_id").val($(this).data("orderid"));
        $("#cancel_user").val($(this).data("username"));
        $("#cancel_mail").val($(this).data("mail"));
        $('#mymodal').modal('show');
    });

    $(document).on("click",".updateOrderKDS", function () {
        var el = $(this).closest("div.blockDiv");

        $.ajax({
            url: phpData.updateOrderStatus,
            type: 'post',
            dataType: 'text',
            data: {
                order_id: $(this).data("orderid"),
                status: $(this).data("status"),
                duration: $(this).data("duration")
            },
            beforeSend: function () {
                el.block({ css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                } });
            },
            success: function (result) {
                el.unblock();
                if (result == 0) {
                    getorder();
                    get_complete_orders();
                    getp_roccesing_orders();
                    PNotify.success({
                        delay: 5000,
                        title: 'Add to cart',
                        text: 'Product add Successfuly'
                    });
                }

                else
                    PNotify.error({
                        delay: 5000,
                        title: 'Send to backer',
                        text: 'Something went wrong'
                    });


            },
            error: function() {
                el.unblock();
            }
        })
    });

    $(document).on("click",".updateOrder", function () {
        var el = $(this).closest("div.blockDiv");
     /*   writeToSelectedPrinter('^XA^FWR^FO100,0^A0N,30,30^FDORDER #'
            +$(this).data("realid")+'^FS^FO30,0^A0,25,25^FDRONNYS^FS^FO100,30^A0N,30,30^FD'+
            $(this).data("name")+'^FS^FO100,70^A0N,30,30^FD'
            +$(this).data("phone")+'^FS^XZ');*/
        writeToSelectedPrinter('^XA' +
            '^CWZ,E:ARIALUNI.TTF^FS'+
            '^FO0,20^GFA,678,678,6,,I0E,003FC,003F8FC,003F0FE,003E1FF,:003F1FF8,003F3CF8,001FFCF8,071FFCF8,0F8FF8F87C,0FC7F8FCFE,0FC3F8F9FF,0FE1F1FBFF,060063FBFF,I0C07F3F8,001F03F7E,003FC0C7C,003FF0078,003FFE0F8,001IF8F,I03FFEF,I01JF,J07IF,J03IF,J01IFC,001C0FBFE,003F87CFE,003FE3E7F,003FFBE7F8,003JF778,I07IF79C,I01IF3DC,J03FF3FC,K0FF3FC,001E01C1FC,003F81C1FC,003FE0F0FC,003FF8F078,001JF8,:I0JF8,I07IF8,I03CFF8,001FE3F,003FF,003FF8,003FFE,I0IF8,I03FFE,J0IF8,J01FF8,K07F8,001E01F8,003F81E,003FE0E,001FF8F,001JF8,:I0JF8,I07IF8,I03CFF8,001FE3F,003FF,003FF8,007FFE,I0IF8,I03FFE,J0IF8,J01FF8,I0787F8,001FF1F8,003FFC7,003IF,007IF8,007IFE,007E1FE,007C07F,003CC1F86,003CC1F878,003FE0F87C,001FF0F83E,I0JFC3F,07E7IF83F,0FF3IF83F8,1FF9IF87F8,3FFC1FF07F8,3FFE07C0FF,7FFEI01FF,7E7FI03FE,7C3FI07FE,7C1F801FFC,7C0F807FF8,FC0F83FFE,FC0FDIFC,FC07JF,FC47IFE,FCKF,7CJFC,7EIFE,7EIF,7EIFC,7EJF,3F7IFE,3F0JFC,3F81IFC,1F803FFC,1FC00FFC,0FE001F8,07F8,03F,,^FS' +
            '^FO100,20^A0N,30,30^FDORDER #'+$(this).data("realid")+'^FS'+
            '^FO100,60^CI28^AON,30,30^FD'+$(this).data("name")+'^FS'+
            '^FO100,100^A0N,30,30^FD'+$(this).data("phone")+'^FS'+
            '^XZ');
        return false;
        $.ajax({
            url: phpData.updateOrderStatus,
            type: 'post',
            dataType: 'text',
            data: {
                order_id: $(this).data("orderid"),
                status: $(this).data("status")
            },
            beforeSend: function () {
                el.block({ css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                } });
            },
            success: function (result) {
                el.unblock();
                if (result == 0) {
                    getorder();
                    get_complete_orders();
                    getp_roccesing_orders();
                    PNotify.success({
                        delay: 5000,
                        title: 'Add to cart',
                        text: 'Product add Successfuly'
                    });
                }

                else
                    PNotify.error({
                        delay: 5000,
                        title: 'Send to backer',
                        text: 'Something went wrong'
                    });


            },
            error: function() {
                el.unblock();
            }
        })
    });

    $(document).on("click",".cancelorder", function () {
        var el = $(this).closest("div.blockDiv");
        var text = '';
        if  ($(this).data("text") == 1)
            text = $("#costum_notification").val();
        else text = $(this).data("text");
        $.ajax({
            url: phpData.cancelOrder,
            type: 'post',
            dataType: 'text',
            data: {
                order_id: $("#cancel_order_id").val(),
                user:$("#cancel_user").val(),
                mail:$("#cancel_mail").val(),
                text: text
            },
            beforeSend: function () {
                el.block({ css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                } });
            },
            success: function (result) {
                getorder();
                el.unblock();
            if (result == 0) {
                $("#mymodal").modal('hide');

            }
            },
            error: function() {
                el.unblock();
            }

        })
    });

    $(document).on("click",".sendchangeaddress", function () {
        var el = $(this).closest("div.blockDiv");


     var order_id = $("#changeAddress_id");
     var method_id  = $("#changeAddress_methodid");

        $.ajax({
            url: phpData.updateAddress,
            type: 'post',
            dataType: 'text',
            data: {
                order_id: order_id.val(),
                method_id: method_id.val(),
                address: $(this).data("target")
            },
            beforeSend: function () {
                el.block({ css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                } });
            },
            success: function (result) {
                el.unblock();
                if (result == 0) {
                    $("#changebranch").modal('hide');
                    getorder();
                    get_complete_orders();
                    getp_roccesing_orders();
                    PNotify.success({
                        delay: 5000,
                        title: 'Change Branch',
                        text: 'Successfuly'
                    });
                }

                else
                    PNotify.error({
                        delay: 5000,
                        title: 'Change Branch',
                        text: 'Something went wrong'
                    });


            },
            error: function() {
                el.unblock();
            }
        })
    });

    getorder();
    get_complete_orders();
    getp_roccesing_orders();

    setInterval(function(){
        get_complete_orders();
        getp_roccesing_orders();
        getorder()
    }, 1000*30);


    $(document).on("click",".changeAddress", function () {
        var method_type = ($(this).data('methodtitle')).toLowerCase();

        if (method_type.includes('delivery')>0) {

            $("#delivery_binder").show();
            $("#takeout_binder").hide();
        } else {

            $("#delivery_binder").hide();
            $("#takeout_binder").show();
        }
        $("#changeAddress_id").val($(this).data('orderid'));
        $("#changeAddress_methodid").val($(this).data('methodid'));
        $("#changebranch").modal()
    })

});

var  $interval = null;

function getorder() {
    var text = "";
    $.ajax({
        url: phpData.getOrders,
        type: 'post',
        dataType: 'json',
        data: { },
        async: true,
        success: function (result) {

            var cnt = 0;

            $.each( result, function( index, value ){
                var branchAddress =(value.shipping_lines[0].method_title).toLowerCase();
                var item = "";
                var meta_data_text = "";
                var delivery = "";
                var size = "";
                var st = value.date_created.split("T");
                if (phpData.userid == 4 || phpData.userid == 6) {

                    cnt++;
                    // item += "<div># "+value.id+"</div>";
                    $.each(value.line_items, function (key, items) {

                        var size = "";

                        if (items.meta_data.length > 0) {

                            switch (items.meta_data[0].value) {
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
                        item += "<div class='col-12'><span class='f_title'>"+items.quantity+" "+ size+"  "+items.name+"</span></div>";

                        meta_data_text = "";
                        //
                        //  $.each(items.meta_data, function (k, meta_data_val) {
                        //      meta_data_text += "<span class='f_16'>"+ meta_data_val.value+"</span>" +"<br>";
                        //  });
                        //
                        //  item += meta_data_text;

                    });

                    delivery += "<span class='f_text'>"+ (value.shipping.first_name !=""? value.shipping.first_name : value.billing.first_name)+" "+(value.shipping.last_name != "" ?value.shipping.last_name:value.billing.last_name)+"</span>"+
                        "<span  class='f_text'>"+(value.meta_data[4].value == ""? value.billing.phone : value.meta_data[4].value)+"</span>"+
                        "<span  class='f_text'>"+(value.shipping.address_1 == "" ? value.billing.address_1:value.shipping.address_1)+"</span>"+
                        "<span  class='f_text'>"+(value.shipping.address_2== ""?value.billing.address_2:value.shipping.address_2)+"</span>";

                    //if (value.meta_data[0].value !="")
                    //    delivery +="<span  class='f_text'>"+value.meta_data[0].value+"</span>";

                    delivery += "<span  class='f_text'>"+
                        ((value.meta_data[5].value == "" && value.meta_data[0].value == "") ? "":((value.meta_data[5].value != "" ? value.meta_data[5].value:value.meta_data[0].value)+" SD/ ")) +
                        ((value.meta_data[6].value == "" && value.meta_data[1].value == "") ? "":((value.meta_data[6].value != "" ? value.meta_data[6].value:value.meta_data[1].value)+" DC/ "))+
                        ((value.meta_data[7].value == "" && value.meta_data[2].value == "") ? "":((value.meta_data[7].value != "" ? value.meta_data[7].value:value.meta_data[2].value)+" SR/ "))+
                        ((value.meta_data[8].value == "" && value.meta_data[3].value == "") ? "":((value.meta_data[8].value != "" ? value.meta_data[8].value:value.meta_data[3].value)+" BN"))+" </span>";

                    if (value.customer_note != "")
                        delivery +="<span  class='f_text font-weight-bold'>"+value.customer_note+"</span>";

                    delivery +="<span  class='f_text font-weight-bold changeAddress' style='color: #f5b225' data-orderid='"+value.id+"' data-methodtitle='"+value.shipping_lines[0].method_title+"' data-methodid='"+value.shipping_lines[0].id+"'>"+value.shipping_lines[0].method_title+"</span>";


                    text += ' <div class="blockDiv aab col-sm-4 col-lg-3">' +
                        '<div class="card ">' +
                        '   <div class="card-body">' +
                        '   <h4 class="mt-0 header-title"> #'+value.id.toString().slice(-2)+'<div style="float: right; text-align: right">' +
                        '<span style="font-size: 12px;">'+st[0]+' '+(st[1].slice(0,-3))+'</span>'+
                       '</div></h4>' +


                        '<div class="row m-t-10 w-100">' +
                        '<div class="col-md-12">' +
                        delivery+
                        '</div></div>'+
                        '<div class="row m-t-10">' +
                        item+
                        '</div>'+
                        '<div class="row m-t-10">' +
                        '<div class="col-12 text-right">' +
                        '<span class="f_text">'+value.total+'</span>' +
                        '</div>' +
                        "<div class='col-md-2 m-t-10'><button class='showcancelModal btn btn-danger waves-effect waves-light' data-mail='"+ value.billing.email+"' data-username='"+ value.billing.first_name+" "+value.billing.last_name+"' data-status='5' data-orderid='"+value.id+"'><i class='mdi mdi-close-circle-outline'></i></button></div>" +
                        "<div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='20'  data-status='1' data-orderid='"+value.id+"'>20</button></div>" +
                        "<div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='30'  data-status='1' data-orderid='"+value.id+"'>30</button></div>" +
                        "<div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='40'  data-status='1' data-orderid='"+value.id+"'>40</button></div>" +
                        "<div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='60'  data-status='1' data-orderid='"+value.id+"'>60</button></div>" +
                        "<div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='80'  data-status='1' data-orderid='"+value.id+"'>80</button></div>" +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';

                }
                else { if (branchAddress.indexOf($branch) > 0) {
                    cnt++;
                    // item += "<div># "+value.id+"</div>";
                    $.each(value.line_items, function (key, items) {

                        var size = "";

                        if (items.meta_data.length > 0) {

                            switch (items.meta_data[0].value) {
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
                        item += "<div class='col-12'><span class='f_title'>"+items.quantity+" "+ size+"  "+items.name+"</span></div>";

                        meta_data_text = "";
                        //
                      //  $.each(items.meta_data, function (k, meta_data_val) {
                      //      meta_data_text += "<span class='f_16'>"+ meta_data_val.value+"</span>" +"<br>";
                      //  });
                        //
                      //  item += meta_data_text;

                    });

                    delivery += "<span class='f_text'>"+ (value.shipping.first_name !=""? value.shipping.first_name : value.billing.first_name)+" "+(value.shipping.last_name != "" ?value.shipping.last_name:value.billing.last_name)+"</span>"+
                        "<span  class='f_text'>"+(value.meta_data[4].value == ""? value.billing.phone : value.meta_data[4].value)+"</span>"+
                        "<span  class='f_text'>"+(value.shipping.address_1 == "" ? value.billing.address_1:value.shipping.address_1)+"</span>"+
                        "<span  class='f_text'>"+(value.shipping.address_2== ""?value.billing.address_2:value.shipping.address_2)+"</span>";

                    //if (value.meta_data[0].value !="")
                    //    delivery +="<span  class='f_text'>"+value.meta_data[0].value+"</span>";

                    delivery += "<span  class='f_text'>"+
                        ((value.meta_data[5].value == "" && value.meta_data[0].value == "") ? "":((value.meta_data[5].value != "" ? value.meta_data[5].value:value.meta_data[0].value)+" SD/ ")) +
                        ((value.meta_data[6].value == "" && value.meta_data[1].value == "") ? "":((value.meta_data[6].value != "" ? value.meta_data[6].value:value.meta_data[1].value)+" DC/ "))+
                        ((value.meta_data[7].value == "" && value.meta_data[2].value == "") ? "":((value.meta_data[7].value != "" ? value.meta_data[7].value:value.meta_data[2].value)+" SR/ "))+
                        ((value.meta_data[8].value == "" && value.meta_data[3].value == "") ? "":((value.meta_data[8].value != "" ? value.meta_data[8].value:value.meta_data[3].value)+" BN"))+" </span>";


                    //    delivery += "<span  class='f_text'>"+(value.meta_data[1].value!=""?value.meta_data[1].value+"sd / ":"")+(value.meta_data[2].value!=""?value.meta_data[2].value+"dc / ":"")+(value.meta_data[3].value!=""?value.meta_data[3].value+"sr / ":"")+(value.meta_data[4].value!=""?value.meta_data[4].value+"bn":"")+"</span>";

                    if (value.customer_note != "")
                        delivery +="<span  class='f_text font-weight-bold'>"+value.customer_note+"</span>";

                    delivery +="<span  class='f_text font-weight-bold changeAddress' style='color: #f5b225' data-orderid='"+value.id+"' data-methodtitle='"+value.shipping_lines[0].method_title+"' data-methodid='"+value.shipping_lines[0].id+"'>"+value.shipping_lines[0].method_title+"</span>";


                    text += ' <div class="blockDiv  aab col-sm-4 col-lg-3">' +
                        '<div class="card ">' +
                        '   <div class="card-body">' +
                        '   <h4 class="mt-0 header-title"> #'+value.id.toString().slice(-2)+'<div style="float: right; text-align: right">' +
                        '<span style="font-size: 12px;">'+st[0]+' '+(st[1].slice(0,-3))+'</span>'+
                        '</div></h4>' +
                        '<div class="row m-t-10 w-100">' +
                        '<div class="col-md-12">' +
                            delivery+
                        '</div></div>'+
                        '<div class="row m-t-10">' +
                        item+
                        '</div>'+
                        '<div class="row m-t-10">' +
                            '<div class="col-12 text-right">' +
                                '<span class="f_text">'+value.total+'</span>' +
                            '</div>' +

                        "<div class='col-md-2 m-t-10'><button class='showcancelModal btn btn-danger waves-effect waves-light' data-mail='"+ value.billing.email+"' data-username='"+ value.billing.first_name+" "+value.billing.last_name+"' data-status='5' data-orderid='"+value.id+"'><i class='mdi mdi-close-circle-outline'></i></button></div>" +
                        "<div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='20'  data-status='1' data-orderid='"+value.id+"'>20</button></div>" +
                        "<div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='30'  data-status='1' data-orderid='"+value.id+"'>30</button></div>" +
                        "<div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='40'  data-status='1' data-orderid='"+value.id+"'>40</button></div>" +
                        "<div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='60'  data-status='1' data-orderid='"+value.id+"'>60</button></div>" +
                        "<div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='80'  data-status='1' data-orderid='"+value.id+"'>80</button></div>" +


                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';


                    //text += "<tr>" +
                    //    "<td>#"+value.id+"</td>"+
                    //    "<td>"+item+"</td>"+
                    //    "<td>"+value.total+"</td>"+
                    //    "<td>"+value.date_created+"</td>"+
                    //    "<td>"+value.status+"</td>"+
                    //    "<td>"+delivery+"</td>"+
                    //    "<td>"+value.shipping_lines[0].method_title+"</td>"+
                    //    "<td>"+ "<p><button class='updateOrder btn btn-primary waves-effect waves-light' data-status='1' data-orderid='"+value.id+"'><i class='ion-arrow-right-a'></i> Send to Kitchen </button></p>"+
                    //    "<p><button class='updateOrder btn btn-danger waves-effect waves-light' data-status='5'  style='margin-top: 10px;' data-orderid='"+value.id+"'> <i class='mdi mdi-close'></i> Cancel Order</button></p>"+"</td>"+
                    //    "</tr>";
                } }





            });
            var soundFx = $( '#song' ); // Get our sound FX.
            if (cnt > 0) {
                $("#pending_count").html(cnt);

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

            $("#orders").html(text)


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


function getp_roccesing_orders() {
    var text = "";
    $.ajax({
        url: phpData.get_orders_for_manager,
        type: 'post',
        dataType: 'json',
        data: {
            branch: $branch,
            status: '1,2'
        },
        success: function (result) {


            $.each(result, function(key, val){

                var product = "";



                var data = JSON.parse(val["order_data"]);

                var st = data.date_created.split("T");
                var milliseconds = (new Date() - new Date(val.created_at).addHours(2));

                var minutes =val.duration - Math.round(milliseconds/60000);


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

               //var delivery = "<span class='f_text'>"+ data.billing.first_name+" "+data.billing.last_name+"</span>"+
               //     "<span  class='f_text'>"+data.billing.phone+"</span>"+
               //     "<span  class='f_text'>"+data.billing.address_1+"</span>"+
               //     "<span  class='f_text'>"+data.billing.address_2+"</span>";
               //
               // if (data.customer_note != "")
               //     delivery +="<span  class='f_text font-weight-bold'>"+data.customer_note+"</span>";
               //
               // delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'>"+data.shipping_lines[0].method_title+"</span>";
               //




                //  var  delivery = "<span class='f_text'>"+ data.shipping.first_name+" "+data.shipping.last_name+"</span>"+
                //    "<span  class='f_text'>"+data.billing.phone+"</span>"+
                //    "<span  class='f_text'>"+data.shipping.address_1+"</span>"+
                //    "<span  class='f_text'>"+data.shipping.address_2+"</span>";
                //
                //
                //
                //if (data.meta_data[0].value !="")
                //    delivery +="<span  class='f_text'>"+data.meta_data[0].value+"</span>";
                //
                //if (data.meta_data[1]) {
                //    delivery += "<span  class='f_text d-inline'>"+(data.meta_data[1].value !="" ? data.meta_data[1].value+"sd / ":""+"</span>");
                //}
                //if (data.meta_data[2]) {
                //    delivery += "<span  class='f_text d-inline'>"+(data.meta_data[2].value !="" ? data.meta_data[2].value+"sd / ":""+"</span>");
                //}
                //if (data.meta_data[3]) {
                //    delivery += "<span  class='f_text d-inline'>"+(data.meta_data[3].value !="" ? data.meta_data[3].value+"sd / ":""+"</span>");
                //}
                //if (data.meta_data[4]) {
                //    delivery += "<span  class='f_text d-inline'>"+(data.meta_data[4].value !="" ? data.meta_data[4].value+"sd / ":""+"</span>");
                //}

               var delivery = "<span class='f_text'>"+ (data.shipping.first_name !=""? data.shipping.first_name : data.billing.first_name)+" "+(data.shipping.last_name != "" ?data.shipping.last_name:data.billing.last_name)+"</span>"+
                    "<span  class='f_text'>"+(data.meta_data[4].value == ""? data.billing.phone : data.meta_data[4].value)+"</span>"+
                    "<span  class='f_text'>"+(data.shipping.address_1 == "" ? data.billing.address_1:data.shipping.address_1)+"</span>"+
                    "<span  class='f_text'>"+(data.shipping.address_2== ""?data.billing.address_2:data.shipping.address_2)+"</span>";

//if (data.meta_data[0].value !="")
//    delivery +="<span  class='f_text'>"+data.meta_data[0].value+"</span>";

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
                    '   <h4 class="mt-0 header-title"> #'+val.order_id.toString().slice(-2)+'<div style="float: right; text-align: right">' +
                    '<span style="font-size: 12px;">'+(st[0]+' '+st[1].slice(0,-3))+'</span><br>'+
                    '<span class="badge badge-pill badge-danger">' +minutes+
                    ' Min</span></div> '+'</h4>' +

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
                    "<div class='col-6 m-t-10' style='padding-right: 0'>"+(val.status==1?'<i class="mdi mdi-checkbox-blank-circle text-primary"></i> Waiting':'<i class="mdi mdi-checkbox-blank-circle text-warning"></i> Prepearing')+" </button></div>" +
                    "<div class='col-6 m-t-10'><button class='updateOrder btn btn-success waves-effect waves-light'" +
                    " data-status='3' data-name='"+(data.shipping.first_name !=""? data.shipping.first_name : data.billing.first_name)+" "+(data.shipping.last_name != "" ?data.shipping.last_name:data.billing.last_name) +
                    "' data-phone='"+(data.meta_data[4].value == ""? data.billing.phone : data.meta_data[4].value)+"' data-realid='"+val.order_id+"' data-orderid='"+val.id+"'>Box <i class='ion-arrow-right-a'></i>   </button></div>" +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                //text += "<tr>" +
                //    "<td> #"+val.order_id+"</td>"+
                //    "<td>"+product+"</td>"+
                //    "<td>"+data.total+"</td>"+
                //    "<td></td>"+
                //    "<td>"+(val.status==1?'<i class="mdi mdi-checkbox-blank-circle text-warning"></i> Waiting':'<i class="mdi mdi-checkbox-blank-circle text-primary"></i> In Proccess')+"</td>"+
                //    "<td>"+data.shipping.address_1+"</td>"+
                //    "<td>"+data.shipping_lines[0].method_title+"</td>"+
                //
                //    "<td>"+ "<p><button class='updateOrder btn btn-success waves-effect waves-light' data-status='4' data-orderid='"+val.id+"'> <i class='mdi mdi-checkbox-marked-circle-outline'></i> Finish </button></p>"+
                //    "</tr>";
            })




            $("#in-process").html(text);

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

function get_complete_orders() {
    var text = "";
    $.ajax({
        url: phpData.get_orders_for_manager,
        type: 'post',
        dataType: 'json',
        data: {
            branch: $branch,
            status: 3
        },
        success: function (result) {
            $.each(result, function(key, val){



                var product = "";


                var milliseconds = (new Date() - new Date(val.created_at).addHours(2));

                var minutes =val.duration - Math.round(milliseconds/60000);

                var data = JSON.parse(val["order_data"]);

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

                    //product += '<span class="f_24"><strong>'+p_v.name+"</strong> - "+p_v.quantity+"</span><br>";
                    //$.each(p_v.meta_data, function (mt_k, mt_v) {
                    //
                    //    product += '<span class="f_24">'+mt_v.value+"</span><br>";
                    //});

                });

                //var delivery = "<span class='f_text'>"+ data.billing.first_name+" "+data.billing.last_name+"</span>"+
                //    "<span  class='f_text'>"+data.billing.phone+"</span>"+
                //    "<span  class='f_text'>"+data.billing.address_1+"</span>"+
                //    "<span  class='f_text'>"+data.billing.address_2+"</span>";
                //
                //if (data.customer_note != "")
                //    delivery +="<span  class='f_text font-weight-bold'>"+data.customer_note+"</span>";
                //
                //delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'>"+data.shipping_lines[0].method_title+"</span>";
                //


                var delivery = "<span class='f_text'>"+ (data.shipping.first_name !=""? data.shipping.first_name : data.billing.first_name)+" "+(data.shipping.last_name != "" ?data.shipping.last_name:data.billing.last_name)+"</span>"+
                    "<span  class='f_text'>"+(data.meta_data[4].value == ""? data.billing.phone : data.meta_data[4].value)+"</span>"+
                    "<span  class='f_text'>"+(data.shipping.address_1 == "" ? data.billing.address_1:data.shipping.address_1)+"</span>"+
                    "<span  class='f_text'>"+(data.shipping.address_2== ""?data.billing.address_2:data.shipping.address_2)+"</span>";

//if (data.meta_data[0].value !="")
//    delivery +="<span  class='f_text'>"+data.meta_data[0].value+"</span>";
                delivery += "<span  class='f_text'>"+
                    ((data.meta_data[5].value == "" && data.meta_data[0].value == "") ? "":((data.meta_data[5].value != "" ? data.meta_data[5].value:data.meta_data[0].value)+" SD/ ")) +
                    ((data.meta_data[6].value == "" && data.meta_data[1].value == "") ? "":((data.meta_data[6].value != "" ? data.meta_data[6].value:data.meta_data[1].value)+" DC/ "))+
                    ((data.meta_data[7].value == "" && data.meta_data[2].value == "") ? "":((data.meta_data[7].value != "" ? data.meta_data[7].value:data.meta_data[2].value)+" SR/ "))+
                    ((data.meta_data[8].value == "" && data.meta_data[3].value == "") ? "":((data.meta_data[8].value != "" ? data.meta_data[8].value:data.meta_data[3].value)+" BN"))+" </span>";



                if (data.customer_note != "")
                    delivery +="<span  class='f_text font-weight-bold'>"+data.customer_note+"</span>";

                delivery +="<span  class='f_text font-weight-bold' style='color: #f5b225'>"+data.shipping_lines[0].method_title+"</span>";


                var t = val.finish_date;
                t= t.split(" ")

                text += ' <div class="blockDiv  aab col-sm-4 col-lg-3">' +
                    '<div class="card ">' +
                    '   <div class="card-body">' +

                    '   <h4 class="mt-0 header-title"> #'+val.order_id.toString().slice(-2)+'<div style="float: right; text-align: right">' +
                    '<span style="font-size: 12px;">'+(st[0]+' '+st[1].slice(0,-3))+'</span><br>'+
                    '<span class="badge badge-pill badge-danger">' +minutes+
                    ' Min</span></div> '+'</h4>' +
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
                    "<div class='col-6 m-t-10' style='padding-right: 0'><i class='mdi mdi-checkbox-blank-circle text-success'></i> Ready</button></div>" +
                    "<div class='col-6 m-t-10'><button class='updateOrder btn btn-success waves-effect waves-light' data-status='4' data-orderid='"+val.id+"'>Ready <i class='ion-arrow-right-a'></i>   </button></div>" +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                //text += ' <div class=" aab col-sm-4 col-sm-3 col-lg-2">' +
                //    '<div class="card ">' +
                //    '   <div class="card-body">' +
                //    '   <h4 class="mt-0 header-title"> #'+val.order_id+'</h4>' +
                //    '<div class="row m-t-10">' +
                //    product+
                //    '</div>'+
                //    '<div class="row m-t-10">' +
                //    '   <div class="col-5">' +
                //    '   <p class="text-muted">Cost</p>' +
                //    '   <h5 class="">'+data.total+'</h5>' +
                //    '   </div>' +
                //    '   <div class="col-7">' +
                //    '   <p class="text-muted">Time</p>' +
                //    '   <h5 class="">'+t[1]+'</h5>' +
                //    '</div>' +
                //    '</div>' +
                //    '<div class="row m-t-10"><div class="col-12"> ' +
                //    '<i class="mdi mdi-checkbox-blank-circle text-success"></i> Ready'+
                //    '</div></div>'+
                //    '<div class="row m-t-10">' +
                //    '   <div class="col-12"><span>'+data.shipping_lines[0].method_title+'</span></div>' +
                //    "<div class='col-6 m-t-10'><button class='updateOrder btn btn-success waves-effect waves-light' data-status='4' data-orderid='"+val.id+"'><i class='mdi mdi-checkbox-marked-circle-outline'></i> Finish  </button></div>" +
                //    '</div>' +
                //    '</div>' +
                //    '</div>' +
                //    '</div>';



                //text += "<tr>" +
                //    "<td>#"+val.order_id+"</td>"+
                //    "<td>"+product+"</td>"+
                //    "<td>"+data.total+"</td>"+
                //    "<td></td>"+
                //    "<td>Ready</td>"+
                //    "<td>"+data.shipping.address_1+"</td>"+
                //    "<td>"+data.shipping_lines[0].method_title+"</td>"+
                //
                //    "<td>"+
                //    "<p><button class='updateOrder  btn btn-success waves-effect waves-light' style='margin-top: 10px;' data-status='4' data-orderid='"+val.id+"'><i class='mdi mdi-checkbox-marked-circle-outline'></i>  Finish</button></p>"+"</td>"+
                //    "</tr>";
            });

            $("#completed").html(text);

            $grid =  $('.ccc').masonry({
                itemSelector: '.aab'
            });

            $grid.masonry('destroy');

            $grid.masonry({
                itemSelector: '.aab'
            });

        }
    })
}