jQuery( document ).ready( function( $ ) {

	// Processing ajax forms
	$( document ).on( 'submit', 'form.mojo_ajax_form', function( e ) {
		e.preventDefault();

		var b 					= $( this ).parents( '.mojo' ).find( 'form' );
		var f					= $( this ).parents( '.mojo_form' );
		var notice 				= $( this ).parents( '.mojo_form' ).find( '.mojo_notices' );
		var form_id 			= $( this ).parents( '.mojo_form' ).attr( 'data-id' );
		var form_data 			= $( this ).serialize();
		var data 				= form_data + "&action=mojo_ajax_form&security=" + mojocommunity_params.ajax_nonce + "&form_id=" + form_id;

		f.find( '.mdl-button[type=submit]' ).find( '.mdl-spinner' ).show();
		f.find( '.mdl-button[type=submit]' ).attr( 'disabled', 'disabled' );
		notice.hide();

		$.ajax({
			method: 	'POST',
			dataType: 	'json',
			url: 		mojocommunity_params.ajax_url,
			data: 		data,
			success: 	function( response ) {

				f.find( '.mdl-button[type=submit]' ).find( '.mdl-spinner' ).hide();
				f.find( '.mdl-button[type=submit]' ).removeAttr( 'disabled' );

				// Handling uncommon errors
				if ( response.success == false ) {
					notice.html( response.data ).show();
					return false;
				}

				// Handling errors
				if ( response.errors ) {
					if ( response.errors.length  > 1 ) {
						notice.html( '<span class="mojo_multiple_errors">' + mojocommunity_params.correct_errors + '</span><ul class="mojo_errors"></ul>' ).show().addClass( 'error' );
						$.each( response.errors, function( key, msg ) {
							notice.find( 'ul.mojo_errors' ).append( '<li>' + msg + '</li>' );
						});
					} else {
						notice.html( response.errors ).show().addClass( 'error' );
					}
				} else {
					notice.removeClass( 'error' );
				}

				// Force redirection
				if ( response.redirect_to ) {
					notice.html( mojocommunity_params.redirecting ).show();
					window.location.replace( response.redirect_to );
				}

				// No errors.
				if ( response.message ) {

					if ( response.success ) {
						notice.addClass( 'success' );
					}

					notice.html( response.message ).show();
					f.find( 'input[type=text], input[type=email]' ).val( '' );

				}

			}
		});

		return false;
	});

	// Single photo upload
	$( 'input[data-mojo="single_photo"]' ).fileuploader({
		limit: 1,
        extensions: ['jpg', 'jpeg', 'png'],
		changeInput: ' ',
		theme: 'thumbnails',
        enableApi: true,
		addMore: true,
		thumbnails: {
			box: '<div class="fileuploader-items single_photo">' +
                      '<ul class="fileuploader-items-list">' +
					      '<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner"><i class="material-icons">photo_camera</i></div></li>' +
                      '</ul>' +
                  '</div>',
			item: '<li class="fileuploader-item">' +
				       '<div class="fileuploader-item-inner">' +
                           '<div class="thumbnail-holder">${image}</div>' +
                           '<div class="actions-holder">' +
							   '<span class="fileuploader-action-popup"></span>' +
                           '</div>' +
                       	   '<div class="progress-holder">${progressBar}</div>' +
                       '</div>' +
                   '</li>',
			item2: '<li class="fileuploader-item">' +
				       '<div class="fileuploader-item-inner">' +
                           '<div class="thumbnail-holder">${image}</div>' +
                           '<div class="actions-holder">' +
                               '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i class="remove"></i></a>' +
							   '<span class="fileuploader-action-popup"></span>' +
                           '</div>' +
                       '</div>' +
                   '</li>',
			startImageRenderer: true,
			canvasImage: false,
			_selectors: {
				list: '.fileuploader-items-list',
				item: '.fileuploader-item',
				start: '.fileuploader-action-start',
				retry: '.fileuploader-action-retry',
				remove: '.fileuploader-action-remove'
			},
			onItemShow: function(item, listEl, parentEl, newInputEl, inputEl) {
				var plusInput = listEl.find('.fileuploader-thumbnails-input'),
					api = $.fileuploader.getInstance(inputEl.get(0));
				
				if(api.getFiles().length >= api.getOptions().limit) {
					plusInput.hide();
				}
				
				plusInput.insertAfter(item.html);
				
				
				if(item.format == 'image') {
					item.html.find('.fileuploader-item-icon').hide();
				}
			},
			onItemRemove: function(html, listEl, parentEl, newInputEl, inputEl) {
				var plusInput = listEl.find('.fileuploader-thumbnails-input'),
					api = $.fileuploader.getInstance(inputEl.get(0));
				
                html.children().animate({'opacity': 0}, 200, function() {
                    setTimeout(function() {
                        html.remove();
						
						if(api.getFiles().length - 1 < api.getOptions().limit) {
							plusInput.show();
						}
                    }, 100);
                });
				
            }
		},
		afterRender: function(listEl, parentEl, newInputEl, inputEl) {
			var plusInput = listEl.find('.fileuploader-thumbnails-input'),
				api = $.fileuploader.getInstance(inputEl.get(0));
		
			plusInput.on('click', function() {
				api.open();
			});
		}
    });

});