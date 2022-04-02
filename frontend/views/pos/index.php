<?php
use frontend\assets\PosAsset;

PosAsset::register($this);
?>
<div class="container">

    <div class="row">

        <div class="col-5 mt-2">
            <div class="sidebar-content">
                <div class="row">
                    <div class="col-10">
                        <div class="vcard text-right">
                            <p class="fn">
                                <a class="url" href="#">
                                    <svg class="bi bi-emoji-smile-upside-down" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 1a7 7 0 1 1 0 14A7 7 0 0 1 8 1zm0-1a8 8 0 1 1 0 16A8 8 0 0 1 8 0z"/>
                                        <path fill-rule="evenodd" d="M4.285 6.433a.5.5 0 0 0 .683-.183A3.498 3.498 0 0 1 8 4.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.498 4.498 0 0 0 8 3.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683z"/>
                                        <path d="M7 9.5C7 8.672 6.552 8 6 8s-1 .672-1 1.5.448 1.5 1 1.5 1-.672 1-1.5zm4 0c0-.828-.448-1.5-1-1.5s-1 .672-1 1.5.448 1.5 1 1.5 1-.672 1-1.5z"/>
                                    </svg>
                                    <strong>Mr. Webertela.Online</strong>
                                </a>

                            <p>
                            <p class="adr">
                                <span class="postal-code">0160,</span>
                                <span class="street-address">215 Nustrubidze str.</span><br>
                                <span class="region">Tbilisi,</span>
                                <span class="country-name">Georgia.</span>
                            </p>
                            <p> <span class="tel">+995 577 230988</span></p>
                        </div>
                    </div>

                    <div class="col-2">
                        <button data-toggle="modal" data-target="#Pos_addCustomer">Add user</button>
                    </div>
                </div>
                <hr>
                <div class="row m-1" style="max-height:300px; overflow: auto; overflow-x: hidden">
                    <div class="col-12">
                        <div class="row ">
                            <div class="col-1 mb-2"><strong>Qty</strong></div>
                            <div class="col-7 text-right"><strong>Descriprion</strong></div>
                            <div class="col-2"><strong>Price</strong></div>
                            <div class="col-2"><strong>Subtotal</strong></div>
                        </div>
                        <div id="order_view"></div>

                    </div>
                </div>

                <hr>

                <div class="row m-1">
                    <div class="col-12">
                        <div class="row mt-1">
                            <div class="col-10 text-right"><strong>Tax:</strong></div>
                            <div class="col-2 text-left"><strong>36</strong></div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-10 text-right"><strong>Net:</strong></div>
                            <div class="col-2 text-left"><strong>36</strong></div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="col-7 mt-2">
            <div class="row">
                <div class="col gray m-1"><spa>Logged user</spa></div>
                <div class="col gray m-1"><spa>POS Vake</spa></div>
                <div class="col gray m-1"><spa>Till open</spa></div>
                <div class="col gray m-1"><spa>Tue. May 19 2020</spa></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row mt-1">
                        <div class="col w-1 ">

                                <p class="text-left">
                                    <span>
                                        <strong> #<span class="activeOrder">2253</span></strong>
                                    </span>
                                </p>
                                <p>
                                    <span>
                                        <strong>12:15</strong>
                                    </span>
                                </p>

                        </div>
                        <div class="col w-1 gray">
                            <p class="text-left updateCart">
                                                    <span >
                                                        <strong>#2254</strong>
                                                    </span>
                            </p>
                            <p>
                                                    <span>
                                                        <strong>12:17</strong>
                                                    </span>
                            </p>
                        </div>
                        <div class="col w-1 gray">
                            <div class="row">
                                <div><h4>Total:</h4></div>
                                <div><h4 id="total_price">0</h4></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <table class="w-100 d-noe">
                <tr>
                    <td>
                        <table class="table quantityTable" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <div class="w-b-1-circle float-left quantity "><span>0</span></div>
                                </td>
                                <td>
                                    <div class="w-b-1-circle float-left quantity quantityActive"><span>1</span></div>
                                </td><td>
                                    <div class="w-b-1-circle float-left quantity"><span>2</span></div>
                                </td><td>
                                    <div class="w-b-1-circle float-left quantity"><span>3</span></div>
                                </td><td>
                                    <div class="w-b-1-circle float-left quantity"><span>4</span></div>
                                </td><td>
                                    <div class="w-b-1-circle float-left quantity"><span>5</span></div>
                                </td><td>
                                    <div class="w-b-1-circle float-left quantity"><span>6</span></div>
                                </td><td>
                                    <div class="w-b-1-circle float-left quantity"><span>7</span></div>
                                </td><td>
                                    <div class="w-b-1-circle float-left quantity"><span>8</span></div>
                                </td>
                                <td>
                                    <div class="w-b-1-circle float-left quantity"><span>9</span></div>
                                </td>
                                <td>
                                    <span id="pizza_quantity">1</span>
                                </td>
                            </tr>
                        </table>


                        <div class="row">
                            <?php
                            foreach($categories as $key=>$cat) {
                                echo '<div class=" col-md-2" style="padding-left: 0">
                                   <div class="w-b-1 showCat green square '.($cat->name=='Pizza'?"active":"").'" data-target="'.strtolower($cat->name).'">
                                       <span class="position-relative" style="top: 12px;"><strong>'.$cat->name.'</strong></span>
                                   </div>
                                   </div>
                               ';
                            }
                            ?>
                        </div>
                       <div class="row pizza p_binder">
                           <?php
                           foreach($pizzas as $pizza) {
                               echo '<div class=" col-md-2" style="padding-left: 0">
                                   <div class="w-blue-1 square addPizza" data-price="'.$pizza->price.'"  data-id="'.$pizza->id.'" data-name="'.$pizza->name.'">
                                       <span class="position-relative" style="top: 5px;"><strong>'.$pizza->name.'</strong></span>
                                   </div>
                                   </div>
                               ';
                           }
                           ?>
                       </div>
                        <div class="row  drinks p_binder" style="display: none">
                           <?php
                            foreach($drinks as $drink) {
                               echo '<div class=" col-md-2" style="padding-left: 0">
                                   <div class="w-blue-1 square  addPizza" data-price="'.$drink->price.'"  data-id="'.$drink->id.'" data-name="'.$drink->name.'">
                                       <span class="position-relative" style="top: 5px;"><strong>'.$drink->name.'</strong></span>
                                   </div>
                                   </div>
                               ';
                           }
                           ?>
                       </div>
                        <div class="row  extras  p_binder" style="display: none">
                           <?php
                           foreach($extras as $extra) {
                               echo '<div class=" col-md-2" style="padding-left: 0">
                                   <div class="w-blue-1 square addPizza" data-price="'.$extra->price.'"  data-id="'.$extra->id.'" data-name="'.$extra->name.'">
                                       <span class="position-relative" style="top: 5px;"><strong>'.$extra->name.'</strong></span>
                                   </div>
                                   </div>
                               ';
                           }
                           ?>
                       </div>
                        <div class="row">
                            <div class="col-md-2" style="padding-left: 0">
                                <div class="w-b-1 square">
                                    <span class="position-relative" style="top: 16px;"><strong>A</strong></span>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding-left: 0">
                                <div class="w-b-1 square">
                                    <span class="position-relative" style="top: 16px;"><strong>B</strong></span>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding-left: 0">
                                <div class="w-b-1 square">
                                    <span class="position-relative" style="top: 16px;"><strong>A/B</strong></span>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding-left: 0">
                                <div class="w-b-1 square">
                                    <span class="position-relative" style="top: 16px;"><strong>Pos Set</strong></span>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding-left: 0">
                                <div class="w-b-1 square">
                                    <span class="position-relative" style="top: 16px;"><strong>Drafts</strong></span>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding-left: 0">
                                <div class="w-litegreen-1 square">
                                    <span class="position-relative" data-toggle="modal" data-target="#exampleModal" style="top: 16px;"><strong>Invoice</strong></span>
                                </div>
                            </div>
                        </div>


                    </td>
                </tr>
            </table>


                <div class="row mt-1">
                    <div class="col p-0">
                        <div class=" w-1 green  font-weight-bold"><span>Thin</span></div>
                    </div>

                    <div class="col p-0">
                        <div class=" w-1 yellow  font-weight-bold"><span>S</span></div>
                    </div>
                    <div class="col p-0">
                        <div class=" w-1 yellow  font-weight-bold active"><span>M</span></div>
                    </div>
                    <div class="col p-0">
                        <div class=" w-1 yellow  font-weight-bold"><span>XL</span></div>
                    </div>
                    <div class="col p-0">
                        <div class=" w-1 gray  font-weight-bold"><span>16 Cut</span></div>
                    </div>
                    <div class="col p-0">
                        <div class=" w-1 pink  font-weight-bold"><span>No Sauce</span></div>
                    </div>
                    <div class="col p-0">
                        <div class=" w-1 pink font-weight-bold active"><span>Original Sauce</span></div>
                    </div>
                    <div class="col p-0">
                        <div class=" w-1 pink  font-weight-bold"><span>Less Sauce</span></div>
                    </div>
                </div>

            <div class="row mt-1">
                <?php
                    foreach($ingredients as $ingrdient) { ?>
                        <div class="col-md-2 p-0">
                            <div class="w-1  font-weight-bold"><span><?=$ingrdient->name?><br></span></div>
                        </div>
                   <?php  }
                ?>
                </div>

                <div class="row mt-1">
                    <div class="col w-1 lightGray  font-weight-bold">&nbsp;</div>
                </div>

                <hr>
                <div class="row mt-1">
                    <div class="col w-1 lightGray  font-weight-bold active"><span>A</span></div>
                    <div class="col w-1 lightGray  font-weight-bold"><span>B</span></div>
                    <div class="col w-1 lightGray  font-weight-bold active"><a href="constructor.html"><span>A/B</span></a></div>
                    <div class="col w-1 lightGray  font-weight-bold"><span>Thin</span></div>


                    <div class="col w-1 lightGray  font-weight-bold"><a href="index_1.html"><span>Cancel</span></a></div>
                    <div class="col w-1 lightGray  font-weight-bold"><a href="pay.html"><span>Pay</span></a></div>
                    <!--                    <div class="col w-1 lightGray pt-3 font-weight-bold"><a href="index_1.html"><span> Cancel</span></a></div>-->
                </div>
            </div>
        </div>

    </div>