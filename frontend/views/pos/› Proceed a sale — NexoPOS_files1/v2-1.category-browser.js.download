$( document ).ready( function() { 
    const CategoriesBrowserHandler    =   function( ) {
        this.vue    =   new Vue({
            el: '#filter-categories',
            computed: {
                categories() {
                    return v2Checkout.orderedCategories;
                }
            },
            methods: {
                openCategory( cat ) {
                    $( `.slick-track [data-cat-id="${cat.ID}"]` ).trigger( 'click' );
                    ToggleCategories.status     =   false;
                    ToggleCategories.$forceUpdate();
                }
            }
        });
    }
    const ToggleCategories  =   new Vue({
        el: '.toggle-categories',
        mounted() {
            this.hide();
        },
        data : {
            status: false
        },
        methods: {
            show() {
                $( '#filter-list' ).hide();
                $( '#filter-categories' ).show();
                $( '#filter-categories' ).html('');
                $( '#filter-categories' ).append(
                    `
                    <div v-for="category of categories" @click="openCategory( category )" class="col-lg-2 col-md-3 col-xs-4 noselect text-center category-browser-item"
                        style=";padding:5px; border-right: solid 1px #DEDEDE;border-bottom: solid 1px #DEDEDE;background:#F1F1F1;min-height: 80px;display: flex;justify-content: center;flex-direction: column;"
                        :dataCatId="category.ID" :dataCategoryName="category.NOM">
                        <div class="caption text-center"
                            style="padding: 2px;overflow: hidden;width: 100%;background: #ffffffc9;">
                            <strong class="item-grid-title"><span class="marquee_me" style="white-space: wrap">{{ category.NOM }}</span></strong></div>
                    </div>
                    `
                );
                
                $( '.toggle-categories' ).siblings().each( function() {
                    $(this).hide();
                });

                new CategoriesBrowserHandler();
            }, 
            hide() {
                $( '#filter-list' ).show();
                $( '#filter-categories' ).hide();
                $( '.toggle-categories' ).siblings().each( function() {
                    $(this).show();
                })
            }
        },
        watch: {
            status() {
                if ( this.status ) {
                    this.show();
                } else {
                    this.hide();
                }
            }
        }
    });
})