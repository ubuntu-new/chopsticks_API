const DiscountPopup     =   new class {
    /**
     * 
     * @param {String} target 
     * @param {Object<any>} data 
     */
    open( target, data ) {
        const modal     =   new ModalVue({
            namespace: 'discount-popup',
            backdrop: true,
            height: {
                xl: '60%',
                lg: '60%',
                md: '60%',
                sm: '70%',
                xs: '80%',
            },
            methods: {
                handlePress( key ) {
                    this.screen     =   key;
                },
                emitClick( button ) {
                    if ([ 'percentage', 'flat' ].includes( button ) ) {
                        this.mode   =   button;

                        if ( this.mode === 'percentage' && parseFloat( this.screen ) > 100 ) {
                            this.screen     =   '100';
                        }

                    } else if ([ 'ok' ].includes( button ) ) {
                    } else if ([ 'backspace' ].includes( button ) && this.screen.length > 0 ) {
                        /**@type {string} */
                        this.screen     =   this.screen.substr( 0, this.screen.length - 1 );

                        if ( this.screen.substr(-1, 1) === '.' ) {
                            this.screen     =  this.screen.substr( 0, this.screen.length - 1 );
                        }

                    } else if( button === 'clear' ) {
                        this.screen     =   '';
                    } else if( button === '.' && this.screen.split('').filter( char => char === '.' ).length === 0 ) {
                        if ( this.screen === '' ) {
                            this.screen     +=  '0.0';
                        } else {
                            this.screen     +=  '.0';
                        }
                    } else if( button !== '.' ) {

                        /**
                         * handle decimals
                         */
                        if ( this.screen.substr(-2, 2) === '.0' ) {
                            this.screen     =   this.screen.substr( 0, this.screen.length - 1 ) + button;
                        } else if ( this.screen === '0' ) {
                            this.screen     =  button.toString();
                        } else {
                            this.screen     +=  button
                        }

                        if ( this.mode === 'percentage' && parseFloat( this.screen ) > 100 ) {
                            this.screen     =   '100';
                        }
                    } 
                }
            },
            data: {
                ...v21DiscountData,
                screen: '',
                mode: 'percentage'
            },
            width: {
                xl: '40%',
                lg: '40%',
                md: '60%',
                sm: '70%',
                xs: '80%',
            },
            mounted() {
                if ( target === 'cart' ) {
                    this.mode       =   v2Checkout.CartRemiseType || 'percentage';
                    this.screen     =   this.mode   ==  'flat' ? v2Checkout.CartRemise.toString() : v2Checkout.CartRemisePercent.toString();
                } else if ( target === 'item' ) {
                    const { item }  =   data;
                    this.mode       =   item.DISCOUNT_TYPE || 'percentage';
                    this.screen     =   this.mode === 'flat' ? item.DISCOUNT_AMOUNT.toString() : item.DISCOUNT_PERCENT.toString();
                }
            },
            modalBodyClass: 'd-flex flex-column p-0',
            body: `
            <div class="discount-wrapper" style="display: flex;flex: 1 0 auto;">
                <num-keyboard :screen="screen" @press="handlePress( $event )">
                    <template v-slot:first>
                        <button @click="emitClick(7)" type="button" class="btn btn-outline-primary border-top-0 rounded-0 border-left-0">
                            <h1 class="m-0">7</h1>
                        </button>
                        <button @click="emitClick(8)" type="button" class="btn btn-outline-primary border-top-0">
                            <h1 class="m-0">8</h1>
                        </button>
                        <button @click="emitClick(9)" type="button" class="btn btn-outline-primary border-top-0 rounded-0">
                            <h1 class="m-0">9</h1>
                        </button>
                        <button @click="emitClick('percentage')" type="button" :class="{ 'btn-outline-primary' : mode === 'flat', 'btn-primary' : mode === 'percentage' }" class="btn border-top-0 rounded-0 border-right-0">
                            <h1 class="m-0">{{ textDomain.percentage }}</h1>
                        </button>
                    </template>
                    <template v-slot:second>
                        <button @click="emitClick(4)" type="button" class="btn btn-outline-primary border-top-0 rounded-0 border-left-0">
                            <h1 class="m-0">4</h1>
                        </button>
                        <button @click="emitClick(5)" type="button" class="btn btn-outline-primary border-top-0">
                            <h1 class="m-0">5</h1>
                        </button>
                        <button @click="emitClick(6)" type="button" class="btn btn-outline-primary border-top-0 rounded-0">
                            <h1 class="m-0">6</h1>
                        </button>
                        <button @click="emitClick('flat')" type="button" :class="{ 'btn-outline-primary' : mode === 'percentage', 'btn-primary' : mode === 'flat' }" class="btn border-top-0 rounded-0 border-right-0">
                            <h1 class="m-0">{{ textDomain.flat }}</h1>
                        </button>
                    </template>
                    <template v-slot:third>
                        <button @click="emitClick(1)" type="button" class="btn btn-outline-primary border-bottom-0 border-top-0 rounded-0 border-left-0">
                            <h1 class="m-0">1</h1>
                        </button>
                        <button @click="emitClick(2)" type="button" class="btn btn-outline-primary border-bottom-0 border-top-0">
                            <h1 class="m-0">2</h1>
                        </button>
                        <button @click="emitClick(3)" type="button" class="btn btn-outline-primary border-bottom-0 border-top-0 rounded-0">
                            <h1 class="m-0">3</h1>
                        </button>
                        <button @click="emitClick( 'backspace' )" type="button" class="btn btn-outline-primary border-bottom-0 border-top-0 rounded-0 border-right-0">
                            <h1 class="m-0"><i class="fa fa-arrow-left"></i></h1>
                        </button>
                    </template>
                    <template v-slot:fourth>
                        <button @click="emitClick('clear')" type="button" class="btn btn-outline-primary border-bottom-0 rounded-0 border-left-0">
                            <h1 class="m-0">{{ textDomain.clear }}</h1>
                        </button>
                        <button @click="emitClick(0)" type="button" class="btn btn-outline-primary border-bottom-0">
                            <h1 class="m-0">0</h1>
                        </button>
                        <button @click="emitClick('.')" type="button" class="btn btn-outline-primary border-bottom-0 rounded-0">
                            <h1 class="m-0">.</h1>
                        </button>
                        <button disabled type="button" class="btn btn-outline-primary border-bottom-0 rounded-0 border-right-0">
                            <h1 class="m-0"></h1>
                        </button>
                    </template>
                </num-keyboard>
            </div>
            `,
            title:  data.title || v21DiscountData.textDomain.discount,
            buttons: [
                {
                    class: 'btn-primary',
                    namespace: 'cancel',
                    label: v21DiscountData.textDomain.cancel
                }, {
                    class: 'btn-default',
                    namespace: 'ok',
                    label: v21DiscountData.textDomain.ok
                }
            ]
        });

        modal.confirm( ( vueInstance ) => {
            
            const { screen, mode }      =   vueInstance;

            if( target === 'cart' ) {
                v2Checkout.CartRemiseType   =   mode;
    
                switch( mode ) {
                    case 'percentage' :
                        v2Checkout.CartRemisePercent    =   parseFloat( screen );
                    break;
                    case 'flat':
                        v2Checkout.CartRemise           =   parseFloat( screen );
                    break;
                }
    
                v2Checkout.CartRemiseEnabled    =   true;
                v2Checkout.refreshCartValues();
                
            } else if ( target === 'item' ) {

                const { item, salePrice, index }   =   data;
                item.DISCOUNT_TYPE      =   vueInstance.mode;

                if( vueInstance.mode === 'flat' ) {
                    item.DISCOUNT_AMOUNT        =   NexoAPI.round( vueInstance.screen );
                    if ( NexoAPI.round( vueInstance.screen ) > salePrice ) {
                        NexoAPI.Notify().info( vueInstance.textDomain.warning, vueInstance.textDomain.discountExceedSalePrice );
                        item.DISCOUNT_AMOUNT    =   salePrice;
                    }
                } else if ( vueInstance.mode === 'percentage' ) {
                    item.DISCOUNT_PERCENT   =   NexoAPI.round( vueInstance.screen );
                }

                v2Checkout.CartItems[ index ]       =   item;
                v2Checkout.buildCartItemTable();
            }

            NexoAPI.events.doAction( 'after_discount_refresh', data );
        });
    }
}

jQuery( document ).ready( function() {
    NexoAPI.events.addFilter( 'pay_box_footer', ( data ) => {
        return data + `
        <a @click="openDiscount( 'cart' )" id="paybox-discount" type="button" class="btn btn-info">
            <i class="fa fa-gift"></i> ${v21DiscountData.textDomain.discount}
            <span v-if="cartDiscount() > 0">&mdash; {{ cartDiscount() | moneyFormat }}</span>
        </a>
        `
    });

    const posDiscount   =   new Vue({
        el: '#cart-discount',
        mounted() {
            
        },
        methods: {
            openDiscount( subject ) {
                if ( subject === 'cart' ) {
                    DiscountPopup.open( 'cart', {
                        title: v21DiscountData.textDomain.cartDiscount
                    });
                }
            }
        }
    });

    NexoAPI.events.addAction( 'pay_box_loaded', () => {
        const payBoxDiscount    =   new Vue({
            el: '#paybox-discount',
            computed: {
                
            },
            mounted() {
                NexoAPI.events.addAction( 'after_discount_refresh', () => {
                    this.refreshDiscount();
                }); 

                NexoAPI.events.addAction( 'paybox_discount_cancelled', () => {
                    this.refreshDiscount();
                })
            },
            methods: {
                cartDiscount() {
                    return v2Checkout.CartRemise;
                },

                refreshDiscount() {
                    setTimeout( () => {
                        this.$forceUpdate();
                    }, 400 );
                },

                openDiscount( subject ) {
                    if ( subject === 'cart' ) {
                        DiscountPopup.open( 'cart', {
                            title: v21DiscountData.textDomain.cartDiscount
                        });
                    }
                }
            }
        })
    });
})