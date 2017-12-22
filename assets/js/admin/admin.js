jQuery( document ).ready( function( $ ) {

	// Body events
	$( document.body )
		.on( 'init_tabs', function( evt, el ) {
			var tabs = $( '.mdl-tabs__tab-bar' );
			var tab = el.attr( 'href' );

			tabs.find( 'a' ).removeClass( 'is-active' );
			el.addClass( 'is-active' );

			$( '.mdl-tabs__panel' ).removeClass( 'is-active' );
			$( '.mdl-tabs__panel' + tab ).addClass( 'is-active' );
		})

		.on( 'init_conditionals', function() {

			if ( $( '*[data-condition=yes]' ).length == 0 )
				return false;

			$( '*[data-condition=yes]' ).hide();

			$( '*[data-condition=yes]' ).each( function( i ) {

				var el 		= $( this );
				var c 		= $( this ).attr( 'data-conditions' );
				var c 		= c.replace( '{', '' ).replace( '}', '' );
				var m 		= c.split( '|' );
				var show 	= 0;

				$.each( m, function( i ) {

					var s 		= m[i].split( ':' );
					var field 	= s[0];
					var value 	= s[1];

					if ( value == 'checked' ) { // checkbox

						if ( $( '#' + field ).is( ':checked' ) ) {
							show += 1;
						}

					} else if ( value == 'unchecked' ) { // checkbox

						if ( ! $( '#' + field ).is( ':checked' ) ) {
							show += 1;
						}

					} else {

						if ( value.indexOf( ' ' ) >= 0 ) {

							v = value.split( ' ' );
							$.each( v, function( i ) {
								if ( $( '#' + field ).val() == v ) {
									show += 1;
								}
							} );

						} else {

							if ( $( '#' + field ).val() == value ) {
								show += 1;
							}

						}

					}

				});

				if ( show == m.length ) {
					el.show();
				}

			});

		});

	// Trigger events
	$( document.body ).trigger( 'init_tabs', [ $( '.mdl-tabs__tab-bar a:first' ) ] );
	$( document.body ).trigger( 'init_conditionals' );

	// Default behaviour for plugin links
	$( document ).on( 'click', 'a[data-mj]', function( e ) {
		e.preventDefault();
		return false;
	});

	// Toggle element
	$( document ).on( 'click', 'a[data-mj=toggle]', function() {
		el = $( '#' + $( this ).attr( 'data-div' ) );
		el.slideToggle( 'fast', function() {} );
	});

	// Trigger conditional fields
	$( '.mdl-selectfield select, input[type=checkbox].mdl-switch__input' ).on( 'change', function() {
		$( document.body ).trigger( 'init_conditionals' );
	});

	// Fix MDL checkbox handling via switch
	$( document ).on( 'click', '.mdl-switch', function() {
		if ( $( this ).hasClass( 'is-checked' ) ) {
			$( this ).find( 'input[type=checkbox]' ).removeAttr( 'checked' );
		} else {
			$( this ).find( 'input[type=checkbox]' ).attr( 'checked', 'checked' );
		}
	});

});