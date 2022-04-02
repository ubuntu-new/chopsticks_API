"use strict";
// Money format

Number.prototype.format = function(n, x, s, c) {
  var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
    num = this.toFixed(Math.max(0, ~~n));

  return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};

// Nexo API

var NexoAPI			=	new Object();

/*
  jQuery Hooks for WordPress, now for NexoPOS

  Examples:

  // Add three different test actions
  NexoAPI.events.addAction( 'test', function() { alert('Foo!'); } );
  NexoAPI.events.addAction( 'test', function() { alert('Bar!'); } );
  NexoAPI.events.addAction( 'test', function() { alert('Baz!'); } );

  // Remove the first one
  NexoAPI.events.removeAction( 'test', 'test_1' );

  // Do the remaining test actions
  NexoAPI.events.doAction( 'test' );


  // Add a filter somewhere
  NexoAPI.events.addFilter('filterOptions',function(options) {
  // Do stuff here to modify variable options
  return options;
  } );

  // Use the filter here
  options = NexoAPI.events.applyFilters('filterOptions',options);

 */

  NexoAPI.events = {

  /**
   * Implement a WordPress-link Hook System for Javascript
   * TODO: Change 'tag' to 'args', allow number (priority), string (tag), object (priority+tag)
   */

  hooks: { action: {}, filter: {} },
  addAction: function( action, callable, tag ) {
    NexoAPI.events.addHook( 'action', action, callable, tag );
  },
  addFilter: function( action, callable, tag ) {
    NexoAPI.events.addHook( 'filter', action, callable, tag );
  },
  doAction: function( action, args ) {
    NexoAPI.events.doHook( 'action', action, null, args );
  },
  applyFilters: function( action, value, args ) {
    return NexoAPI.events.doHook( 'filter', action, value, args );
  },
  removeAction: function( action, tag ) {
    NexoAPI.events.removeHook( 'action', action, tag );
  },
  removeFilter: function( action, tag ) {
    NexoAPI.events.removeHook( 'filter', action, tag );
  },
  addHook: function( hookType, action, callable, tag ) {
    if ( undefined == NexoAPI.events.hooks[hookType][action] ) {
    NexoAPI.events.hooks[hookType][action] = [];
    }
    var hooks = NexoAPI.events.hooks[hookType][action];
    if ( undefined == tag ) {
    tag = action + '_' + hooks.length;
    }
    NexoAPI.events.hooks[hookType][action].push( { tag:tag, callable:callable } );
  },
  doHook: function( hookType, action, value, args ) {
    if ( undefined != NexoAPI.events.hooks[hookType][action] ) {
    var hooks = NexoAPI.events.hooks[hookType][action];
    for( var i=0; i<hooks.length; i++) {
      if ( 'action'==hookType ) {
      hooks[i].callable(args);
      } else {
      value = hooks[i].callable(value, args);
      }
    }
    }
    if ( 'filter'==hookType ) {
    return value;
    }
  },
  removeHook: function( hookType, action, tag ) {
    if ( undefined != NexoAPI.events.hooks[hookType][action] ) {
    var hooks = NexoAPI.events.hooks[hookType][action];
    for( var i=hooks.length-1; i>=0; i--) {
      if (undefined==tag||tag==hooks[i].tag)
      hooks.splice(i,1);
      }
    }
    }
  }
  /**
   * Money format
   * @param int amount
   * @return string
  **/

  NexoAPI.Format	=	function( int, format ){
    var format	=	typeof format == 'undefined' ? '0,0.00' : format;
    return numeral( int ).format( format );
  };

  /**
   * Print specific dom element
   * @param object
   * @return void
  **/

  NexoAPI.PrintElement	=	function(elem) {
    NexoAPI.Popup( $(elem).html() );
  };

  /**
   * Nexo Customer parseFloat
   * @params val
   * @return val
  **/

  NexoAPI.ParseFloat		=	function( val ) {
    return parseFloat( val );
    if( typeof val == 'string' ) {
      return roundNumber( parseFloat( val ), 2 ); // parseFloat( parseFloat( val ).toFixed( 2 ) )
    } else if( typeof val == 'number' ) {
      return roundNumber( val, 2 ); // parseFloat( val.toFixed( 2 ) );
    } else {
      return val;
    }
  }

  NexoAPI.round     = function( amount ) {
    return parseFloat( accounting.toFixed( 
			amount, 
			parseInt( storeDetails.is_multistore ? tendooOptions[ storeDetails.store_prefix + 'decimal_precision' ] : tendooOptions.decimal_precision ) 
		) )
  }

  function roundNumber(num, scale) {
    if(!("" + num).includes("e")) {
      return +(Math.round(num + "e+" + scale)  + "e-" + scale);
    } else {
      var arr = ("" + num).split("e");
      var sig = ""
      if(+arr[1] + scale > 0) {
        sig = "+";
      }
      return + ( Math.round ( +arr[0] + "e" + sig + (+arr[1] + scale)) + "e-" + scale);
    }
  }
