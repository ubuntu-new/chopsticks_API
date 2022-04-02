const element    =   document.querySelector( '.cash-out' );
element.addEventListener( 'click', ( e ) => {
    const textDomain    =   CashRegisterData.textDomain;
    swal({
        title: textDomain.outingCash,
        html: `
        <div id="cash-out-container" style="text-align: left;">
            <div class="form-group">
                <label for="input" class="control-label">${textDomain.amount}</label>
                <input type="number" name="" id="input" class="form-control out-amount"/>
            </div>            
            <div class="form-group">
                <label for="input" class="control-label">${textDomain.reason}</label>
                <textarea style="height: 200px" type="number" name="" id="input" class="form-control out-reason"></textarea>
            </div>            
        </div>
        `,
        showCancelButton: true,
    });

    setTimeout( _ => {
        const button            =   document.querySelector( '.swal2-confirm' ).cloneNode();
        button.textContent      =   textDomain.ok;
        document.querySelector( '.swal2-confirm' ).remove();
        document.querySelector( '.swal2-buttonswrapper' ).prepend( button );    

        button.addEventListener( 'click', () => {
            const domWrapper    =   document.querySelector( '#cash-out-container' );
            const postData      =   {};
            postData.amount     =   domWrapper.querySelector( '.out-amount' ).value;
            postData.reason     =   domWrapper.querySelector( '.out-reason' ).value;

            if ( ! ( parseFloat( postData.amount ) > 0 ) ) {
                NexoAPI.Toast()( textDomain.theAmountIsInvalid );
                return false;
            }

            if ( postData.reason.length === 0 ) {
                NexoAPI.Toast()( textDomain.theReasonIsMissing );
                return false;
            }

            swal({
                title: textDomain.confirmYourAction,
                text: textDomain.wouldYouLikeToCashOut,
                showCancelButton: true
            }).then( result => {
                if ( result.value ) {
                    HttpRequest.post( CashRegisterData.url.post.replace( '{id}', v2Checkout.CartRegisterID ), postData ).then( result => {
                        NexoAPI.Toast()( result.data.message );
                    }).catch( result => {
                        if ( result.response.data.message ) {
                            swal({
                                type: 'error',
                                title: textDomain.anErrorOccured,
                                text: result.response.data.message
                            });
                        } else {
                            NexoAPI.Toast()( result.response.data.message || textDomain.unexpectedErrorOccured );
                            element.click();
                        }
                    })
                }
            })
        })
    }, 200 );
})