jQuery( document ).ready( function( $ ) {

	/**
		The form builder
	**/
	fc = false;
	var mojo_builder = {

		// makes mdl menus ready for work
		mdl_menu: function() {
			$( '.mojo-grid-columns:visible' ).each( function() {
				var menu_container = $( this ).parent().find( '.mojo-grid-menu' );
				var rand = Math.floor( Math.random() * 26 ) + Date.now();
				var menu_html = $( '.mdl-for-mojo-grid-columns' ).html();
				menu_container.empty().html( menu_html );
				$( this ).attr( 'id', rand );
				menu_container.find( 'ul' ).addClass( 'mdl-js-menu mdl-js-ripple-effect' ).attr( 'for', rand );
				componentHandler.upgradeDom();
			});
		},

		// save form
		save_form: function() {
			el = $( '.mojo_form_builder' );

			var form_id = el.attr( 'data-form_id' );

			var fields = new Array();
			el.find( '.mojo_col_field:visible' ).each( function( i ) {
				fields[i] = {};
				fields[i]['id'] = $( this ).attr( 'data-id' );
				fields[i]['row'] = $( this ).parents( '.mojo_form_row' ).index() + 1;
				fields[i]['column'] = $( this ).parents( '.mdl-cell' ).index() + 1;
				fields[i]['name'] = $( this ).find( '.mojo_field_name input[type=text]' ).val();
			});

			var rows = new Array();
			el.find( '.mojo_form_row:visible:not(.add)' ).each( function( i ) {
				var col_layout = $( this ).find( '.mdl-grid' ).attr( 'data-columns' );
				rows[i] = {};
				rows[i]['col_layout'] = col_layout;
				rows[i]['toggle_state'] = ( $( this ).find( '.mojo_cols' ).is( ':visible' ) ) ? 1 : 0;
				rows[i]['name'] = $( this ).find( '.mojo_row_title input[type=text]' ).val();
			});

			$( '.mojo_form_response' ).hide();

			$.ajax({
				method:   'POST',
				dataType: 'json',
				url:      mojo_form_builder.ajax_url,
				data:     {
					action:      	'mojo_update_form',
					security:    	mojo_form_builder.update_form_nonce,
					form_id:		form_id,
					fields:			fields,
					rows:			rows
				},
				success: function( response ) {

					if ( response.success == false ) {
						mojo_builder.save_error();
					} else {
						mojo_builder.save_unready();
					}

				},
				error: function( response ) {

				}
			});

		},

		// add row
		add_row: function() {
			var row = $( '.mojo_row_placeholder' );
			var rows = $( '.mojo_form_rows' );
			row.clone().appendTo( rows ).removeClass( 'mojo_row_placeholder mojo_hide' ).show();
			mojo_builder.save_ready();
		},

		// duplicates a row
		duplicate_row: function( el ) {
			var $clone = el.clone().appendTo( el.parents( '.mojo_form_rows' ) );
			mojo_builder.mdl_menu();
			mojo_builder.save_ready();
		},

		// delete row
		delete_row: function( el ) {
			mojo_builder.save_ready();
			el.remove();
		},

		// make form ready for saving
		save_ready: function() {
			$( 'a[data-mj=save_form]' ).removeAttr( 'disabled' );
			$( '.mojo_form_response' ).html( mojo_form_builder.save_ready ).fadeIn( 'slow' );
			mojo_builder.sortable_fields();
		},

		// make form unready for saving
		save_unready: function() {
			$( 'a[data-mj=save_form]' ).attr( 'disabled', 'disabled' );
			$( '.mojo_form_response' ).html( mojo_form_builder.save_unready ).fadeIn( 'slow' );
		},

		// form returned error
		save_error: function() {
			$( '.mojo_form_response' ).html( mojo_form_builder.save_error ).fadeIn( 'slow' );
		},

		// close fields modal
		close_modal: function() {
			var inst = $( '.remodal[data-remodal-id="add-element"]' ).remodal();
			inst.close();
			return false;
		},

		// insert an element to the builder
		insert_element: function( el ) {
			var id = el.attr( 'data-id' );
			var name = el.text();
			var l = fc.find( '.mojo_col_field:visible' ).length;
			var $clone = fc.find( '.mojo_col_field.mojo_hide' ).clone().appendTo( fc.find( '.mojo_col_fields' ) );
			var inst = $( '[data-remodal-id=add-element]' ).remodal();

			$clone.attr( 'data-id', id );
			$clone.html( $clone.html().replace( '{field_name}', name ) );
			$clone.removeClass( 'mojo_hide' );

			inst.close();
			mojo_builder.save_ready();
		},

		// delete element
		delete_element: function( el ) {
			el.remove();
			mojo_builder.save_ready();
		},

		// delete column
		delete_column: function( el ) {
			el.find( '.mojo_col_field:not(.mojo_hide)' ).remove(); 
		},

		// duplicates an element
		duplicate_element: function( el ) {
			var $clone = el.clone().appendTo( el.parents( '.mojo_col_fields' ) );
			$clone.hide().fadeIn();
			mojo_builder.save_ready();
		},

		// toggle row
		toggle_row: function( el ) {
			var r = el.parents( '.mojo_form_row' );
			r.find( '.mojo_cols, .mojo_row_layouts' ).hide();
			el.find( 'i' ).html( 'arrow_drop_up' );
			el.attr( 'data-mj', 'untoggle_row' );
			mojo_builder.save_ready();
		},

		// untoggle row
		untoggle_row: function( el ) {
			var r = el.parents( '.mojo_form_row' );
			r.find( '.mojo_cols, .mojo_row_layouts' ).show();
			el.find( 'i' ).html( 'arrow_drop_down' );
			el.attr( 'data-mj', 'toggle_row' );
			mojo_builder.save_ready();
		},

		// allow fields to be sorted across rows
		sortable_fields: function() {
			$( '.mojo_col_fields' ).sortable({
				placeholder: 			'mojo_col_field_highlight',
				forcePlaceholderSize: 	true,
				connectWith: 			'.mojo_col_fields',
				handle: 				'.mojo_handle',
				dropOnEmpty:			true,
				update: 				function( event, ui ) {
					mojo_builder.save_ready();
				}
			});

			$( '.mojo_col_fields' ).disableSelection();
		}

	}

	// Execute as soon as dom is ready
	mojo_builder.sortable_fields();
	mojo_builder.mdl_menu();

	// disable link #
	$( document ).on( 'click', 'a.mojo_disablelink', function( e ) {
		e.preventDefault();
		return false;
	});

	// trigger toggle row
	$( document ).on( 'click', 'a[data-mj=toggle_row]', function() {
		mojo_builder.toggle_row( $( this ) );
	});

	// trigger untoggle row
	$( document ).on( 'click', 'a[data-mj=untoggle_row]', function() {
		mojo_builder.untoggle_row( $( this ) );
	});

	// trigger duplicate row
	$( document ).on( 'click', 'a[data-mj=duplicate_row]', function() {
		mojo_builder.duplicate_row( $( this ).parents( '.mojo_form_row' ) );
	});

	// trigger duplicate field
	$( document ).on( 'click', 'a[data-mj=duplicate_element]', function() {
		mojo_builder.duplicate_element( $( this ).parents( '.mojo_col_field' ) );
	});

	// save the column instance
	$( document ).on( 'click', 'a[href=#add-element]', function() {
		fc = $( this ).parents( '.mdl-cell' );
	});

	// trigger add row
	$( document ).on( 'click', '[data-mj=add_row]', function( e ) {
		e.preventDefault();
		mojo_builder.add_row();
		return false;
	});

	// trigger delete row
	$( document ).on( 'click', 'a[data-mj=delete_row]', function() {
		mojo_builder.delete_row( $( this ).parents( '.mojo_form_row' ) );
	});

	// trigger delete column
	$( document ).on( 'click', 'a[data-mj=delete_column]', function() {
		mojo_builder.delete_column( $( this ).parents( '.mdl-cell' ) );
	});

	// trigger delete element
	$( document ).on( 'click', 'a[data-mj=delete_element]', function() {
		mojo_builder.delete_element( $( this ).parents( '.mojo_col_field' ) );
	});

	// trigger elements that are added
	$( document ).on( 'opened', '.remodal[data-remodal-id="add-element"]', function() {
		if ( ! fc ) {
			fc = $( document.body ).find( '.mdl-cell:visible:first' );
		}
	});

	// trigger element insertion
	$( document ).on( 'click', 'a:not(:disabled)[data-mj=add_element]', function() {
		mojo_builder.insert_element( $( this ) );
	});

	// trigger save form
	$( document ).on( 'click', 'a:not(:disabled)[data-mj=save_form]', function() {
		mojo_builder.save_form();
	});

	// trigger field name changes
	$( document ).on( 'change', '.mojo_field_name input[type=text], .mojo_row_title input[type=text]', function() {
		mojo_builder.save_ready();
	});

	// Grid column layout
	$( document ).on( 'click', 'a[data-mj=row_columns]', function() {
		var cols 			= $( this ).attr( 'data-columns' );
		var grid 			= $( this ).parents( '.mojo_form_row' );
		var current_cols 	= grid.find( '.mdl-grid' ).attr( 'data-columns' );
		var grid_inner 		= grid.find( '.mojo_cols' );
		var template 		= $( '#mojo_form_builder' ).find( '.mojo_builder_template[data-columns="' + cols + '"] ' );

		if ( cols == current_cols ) {
			return false;
		}

		grid_inner.html( template.html() );
		mojo_builder.save_ready();
	});

});