String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

const ModalVue  =   function({ 
    namespace, 
    
    buttons = [
        {
            class: 'btn-primary',
            namespace: 'ok',
            label: textDomain.ok,
            disabled: false
        }, {
            class: 'btn-secondary',
            label: textDomain.cancel,
            namespace: 'cancel',
            disabled: false
        }
    ], 
    body, 
    title, 
    backdrop        = true, 
    keyboard        = true, 
    focus           = true, 
    width           = '40%', 
    height          = '30%',
    align           = 'center',
    methods         = {},
    mounted         = () => {},
    computed        = {},
    data            = {},
    components      = {},
    hideFooter      =   false,
    buttonClasses   =   'btn',
    modalBodyClass  =   ''
}) {
    if ( namespace.search( /\./g ) !== -1 ) {
        console.error( `"${namespace}" is not a valid namespace for a modal.` );
        return new ModalListener( new Vue({}) );
    }

    /**
     * let's check if the modal already
     * exist and prevent it from being open
     */
    if ( $( `#modal-vue-${namespace}` ).length > 0 ) {
        /**
         * return empty vue instance.
         * We don't what to throw an error if a 
         * "then", "confirm", "cancel" callback is called
         */
        console.error( `"#modal-vue-${namespace}" is already opened or a dom element with the same ID already exists.` );
        return new ModalListener( new Vue({}) );
    }

    const markup        =   `
    <div class="bootstrapiso">
        <div class="modal fade" id="modal-vue-${namespace}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" style="display: flex !important;">
            <div class="modal-dialog d-flex" role="document">
                <div class="modal-content">
                    <div class="modal-vue modal-header" style="flex-shrink: 0;">
                        <h5 class="modal-title" id="exampleModalLongTitle">${title}</h5>
                        <button @click="close()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ${modalBodyClass}">
                        ${body}
                    </div>
                    <div class="modal-footer" style="flex-shrink: 0;" v-if="!hideFooter">
                        <button :disabled="button.disabled" @click="clickOn( button )" v-for="button in buttons" type="button" :class="button.class" class="${buttonClasses}" data-dismiss="modal">{{ button.label }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;  

    $( 'body' ).append( markup );

    const BreakPointCSS     =   
        `<style id="modal-vue-style-{namespace}">
        /* 
        ##Device = Desktops
        ##Screen = 1281px to higher resolution desktops
        */

        @media (min-width: 1281px) {
        
            {elementSelector} .modal-dialog {
                /*xlheight*/
                /*xlwidth*/
            }
        
        }

        /* 
        ##Device = Laptops, Desktops
        ##Screen = B/w 1025px to 1280px
        */

        @media (min-width: 1025px) and (max-width: 1280px) {
        
            {elementSelector} .modal-dialog {
                /*lgheight*/
                /*lgwidth*/
            }
        
        }

        /* 
        ##Device = Tablets, Ipads (portrait)
        ##Screen = B/w 768px to 1024px
        */

        @media (min-width: 768px) and (max-width: 1024px) {
        
            {elementSelector} .modal-dialog {
                /*mdheight*/
                /*mdwidth*/
            }
        
        }

        /* 
        ##Device = Tablets, Ipads (landscape)
        ##Screen = B/w 768px to 1024px
        */

        @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
        
            {elementSelector} .modal-dialog {
                /*smheight*/
                /*smwidth*/
            }
        
        }

        /* 
        ##Device = Low Resolution Tablets, Mobiles (Landscape)
        ##Screen = B/w 360px to 767px
        */

        @media (min-width: 360px) and (max-width: 767px) {
        
            {elementSelector} .modal-dialog {
                /*xsheight*/
                /*xswidth*/
                margin: 1.75rem auto;
            }
        
        }
    </style>`;

    this.VueInstance  =   new Vue({
        el: `#modal-vue-${namespace}`,
        data: Object.assign( data, { namespace, buttons, title, body, width, height, align, markup, hideFooter,
            elementSelector     :   `#modal-vue-${namespace}`
        }),
        components,
        mounted() {
            $( this.$el ).addClass( 'fade' );
            $( this.$el ).addClass( 'show' );
            $( this.$el ).css({
                background: 'rgba(51, 51, 51, 0.41)',
                alignItems: this.align
            });

            let css     =   BreakPointCSS;
            css         =   css.replaceAll('{namespace}', namespace);
            css         =   css.replaceAll('{elementSelector}', this.elementSelector);

            $( `#modal-vue-style-${namespace}` ).remove();

            /**
             * Create Stylesheet a smart way
             */
            [ 'width', 'height' ].forEach( measure => {
                if ( typeof this[ measure ] !== 'string' ) {
                    for( let bp in this[ measure ] ) {
                        if( [ 'xl', 'xs', 'lg', 'sm', 'md' ].indexOf( bp ) !== -1 ) {
                            css     =   css.replace( `/*${bp}${measure}*/`, `
                            /*${bp}${measure}*/
                            ${measure}: ${this[ measure ][bp]};
                            ` )
                        }
                    }
                } else {
                    $( this.$el ).find( '.modal-dialog' ).css({
                        [measure]: this[measure]
                    });
                }
            })

            $( 'body' ).append( css );

            $( this.$el ).find( '.modal-dialog' ).css({
                maxWidth: 'inherit',
                maxHeight: 'inherit'
            });

            /**
             * Exec Mounted
             * @type function
             */
            this.__mounted  =   mounted;
            this.__mounted();
        },
        methods: {
            clickOn( button ) {
                this.$emit( 'button.click', button );
            },

            close() {
                this.$emit( 'force.close.popup' );
            },

            ...methods
        },

        computed: {
            ...computed
        }
    });

    return new ModalListener( this.VueInstance );
}

const ModalListener     =   function( VueInstance ) {

    this.VueInstance    =   VueInstance;

    /**
     * Listen when the popup attempt to close
     * pass the argument defined on ModalVue.close()
     */
    this.VueInstance.$on( 'force.close.popup', ( response ) => {
        this.removePopupMarkup();

        if ( this.callback !== undefined ) {
            this.callback( response );
        }
    });

    /**
     * check if a modal is already open
     * @param string modal namespace to fetch
     * @return boolean
     */
    this.exists     =   ( namespace ) => {
        return $( `#modal-vue-${namespace}` ).length > 0;
    }

    /**
     * Listen bouton click
     * @return void
     */
    this.VueInstance.$on( 'button.click', ( button ) => {

        let callbackResponse  =   undefined;

        /**
         * if the cancel callback is defined and the
         * cancel button has been clicked.
         */
        if ( this.cancelCallBack !== undefined && [ 'cancel' ].indexOf( button.namespace ) !== -1 ) {

            /**
             * let's call a vue instance method for cancel
             * onCancel()
             */
            if ( this.VueInstance.onCancel !== undefined ) {
                /**
                 * If the vue instance has a onCancel()
                 * callback, we might need to call it instead of using the cancelCallback
                 */
                callbackResponse    =   this.cancelCallBack( this.VueInstance.onCancel() );

            } else {
                /**
                 * The VueInstance is passed to the cancel method
                 * so that a further check can be performed
                 */
                callbackResponse    =   this.cancelCallBack( this.VueInstance );
            }


        } else if ( this.confirmCallback !== undefined && [ 'ok' ].indexOf( button.namespace ) !== -1 ) {

            if( this.VueInstance.onConfirm !== undefined ) {
                /**
                 * If the vue instance has a onCancel()
                 * callback, we might need to call it instead of using the confirmCallback
                 */
                callbackResponse    =   this.confirmCallback( this.VueInstance.onConfirm() );
            } else {
                /**
                 * The VueInstance is passed to the confirm method
                 * so that a further check can be performed
                 */
                callbackResponse    =   this.confirmCallback( this.VueInstance );
            }

        } else if( this.callback !== undefined ) {
            /**
             * If the callback doesn't return nothing
             * then we can close the popup
             */
            callbackResponse  =   this.callback( button.namespace );
        }

        /**
         * Decide to keep the popup
         * open or close it according to the response
         * of the callback
         */
        if( callbackResponse instanceof Promise ) {
            callbackResponse.then( result => {
                if( result === true || result.status === 'success' ) {
                    this.removePopupMarkup();
                }
            }).catch( error => {
                console.log( error );
            });
        } else if ( [ true, undefined ].indexOf( callbackResponse ) !== -1 ) {
            this.removePopupMarkup();
        }
    })

    this.removePopupMarkup  =   function() {
        $( this.VueInstance.$el ).removeClass( 'fade' );
        $( this.VueInstance.$el ).removeClass( 'show' );
        $( this.VueInstance.$el ).remove();
        $( '.bootstrapiso' ).remove();

        /**
         * Freed the memory by deleting the instance
         */
        // this.VueInstance.$destroy();
        // delete this[ 'VueInstance' ];
    }

    /**
     * Trigger Then method for the popup
     * @param {any} callback 
     */
    this.then           =   function( callback ) {
        this.callback   =   callback;
        return this;
    }

    /**
     * Trigger confirm method for the popup
     * @param {any} callback 
     */
    this.confirm           =   function( callback ) {
        this.confirmCallback   =   callback;
        return this;
    }

    /**
     * Trigger cancel method for the popup
     * @param {any} callback
     */
    this.cancel         =   function( callback ) {
        this.cancelCallBack     =   callback;
        return this;
    }
    
    this.close          =   function( response = false ) {
        this.VueInstance.$emit( 'force.close.popup', response );
    }
}