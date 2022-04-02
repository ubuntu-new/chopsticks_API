/**
 * this introduce a popup on small device which notify when an 
 * item has been added to the cart
 */
$( document ).ready( function() {
    NexoAPI.events.addAction( 'add_to_cart', () => {
        if([ 'xs', 'sm', 'md' ].includes( layout.is() ) ) {
            NexoAPI.Toast()( v2NotificationData.textDomain.itemAdded )
        }
    })
});