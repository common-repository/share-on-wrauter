/**
 * wrauterwp Admin Settings Save process
 */
jQuery.fn.setLoading = function(pct) {
    var indicatorID = jQuery(this).attr('id');
    $('#loading-indicator-' + indicatorID).html(pct + '%');
};
jQuery.fn.showLoading = function(options) {
    var indicatorID;
    var settings = {
        'addClass': '',
        'beforeShow': '',
        'afterShow': '',
        'hPos': 'center',
        'vPos': 'center',
        'indicatorZIndex' : 5001,
        'overlayZIndex': 5000,
        'parent': '',
        'waitingText' : '',
        'marginTop': 0,
        'marginLeft': 0,
        'overlayWidth': null,
        'overlayHeight': null
    };
    jQuery.extend(settings, options);
    var loadingDiv = jQuery('<div style="text-align:center"></div>');
    var loadingTextDiv = jQuery('<div style="text-align:center">'+settings.waitingText+'</div>');
    var overlayDiv = jQuery('<div></div>');
    if ( settings.indicatorID ) {
        indicatorID = settings.indicatorID;
    } else {
        indicatorID = jQuery(this).attr('id');
    }
    jQuery(loadingDiv).attr('id', 'loading-indicator-' + indicatorID );
    jQuery(loadingDiv).addClass('loading-indicator');
    jQuery(loadingTextDiv).attr('id', 'loading-indicator-text' );
    jQuery(loadingTextDiv).addClass('loading-indicator-text');
    if ( settings.addClass ){
        jQuery(loadingDiv).addClass(settings.addClass);
    }
    jQuery(overlayDiv).css('display', 'none');
    jQuery(document.body).append(overlayDiv);
    jQuery(overlayDiv).attr('id', 'loading-indicator-' + indicatorID + '-overlay');
    jQuery(overlayDiv).addClass('loading-indicator-overlay');
    if ( settings.addClass ){
        jQuery(overlayDiv).addClass(settings.addClass + '-overlay');
    }
    var overlay_width;
    var overlay_height;
    var border_top_width = jQuery(this).css('border-top-width');
    var border_left_width = jQuery(this).css('border-left-width');
    border_top_width = isNaN(parseInt(border_top_width)) ? 0 : border_top_width;
    border_left_width = isNaN(parseInt(border_left_width)) ? 0 : border_left_width;
    var overlay_left_pos = jQuery(this).offset().left + parseInt(border_left_width);// +  $(document.body).css( "border-left" );
    var overlay_top_pos = jQuery(this).offset().top + parseInt(border_top_width);
    if ( settings.overlayWidth !== null ) {
        overlay_width = settings.overlayWidth;
    } else {
        overlay_width = parseInt(jQuery(this).width()) + parseInt(jQuery(this).css('padding-right')) + parseInt(jQuery(this).css('padding-left'));
    }
    if ( settings.overlayHeight !== null ) {
        overlay_height = settings.overlayWidth;
    } else {
        overlay_height = parseInt(jQuery(this).height()) + parseInt(jQuery(this).css('padding-top')) + parseInt(jQuery(this).css('padding-bottom'));
    }
    jQuery(overlayDiv).css('width', overlay_width.toString() + 'px');
    jQuery(overlayDiv).css('height', overlay_height.toString() + 'px');
    jQuery(overlayDiv).css('left', overlay_left_pos.toString() + 'px');
    jQuery(overlayDiv).css('position', 'absolute');
    jQuery(overlayDiv).css('top', overlay_top_pos.toString() + 'px' );
    jQuery(overlayDiv).css('z-index', settings.overlayZIndex);
    if ( settings.overlayCSS ) {
        jQuery(overlayDiv).css ( settings.overlayCSS );
    }
    jQuery(loadingDiv).css('display', 'none');
    jQuery(document.body).append(loadingDiv);
    jQuery(loadingTextDiv).css('display', 'none');
    jQuery(document.body).append(loadingTextDiv);
    jQuery(loadingDiv).css('position', 'absolute');
    jQuery(loadingDiv).css('z-index', settings.indicatorZIndex);
    jQuery(loadingTextDiv).css('position', 'absolute');
    jQuery(loadingTextDiv).css('z-index', settings.indicatorZIndex);
    var indicatorTop = overlay_top_pos;
    if ( settings.marginTop ) {
        indicatorTop += parseInt(settings.marginTop);
    }
    var indicatorLeft = overlay_left_pos;
    if ( settings.marginLeft ) {
        indicatorLeft += parseInt(settings.marginTop);
    }
    if ( settings.hPos.toString().toLowerCase() == 'center' ) {
        jQuery(loadingDiv).css('left', (indicatorLeft + ((jQuery(overlayDiv).width() - parseInt(jQuery(loadingDiv).width())) / 2)).toString()  + 'px');
        jQuery(loadingTextDiv).css('left', (indicatorLeft + ((jQuery(overlayDiv).width() - parseInt(jQuery(loadingTextDiv).width())) / 2)).toString()  + 'px');
    } else if ( settings.hPos.toString().toLowerCase() == 'left' ) {
        jQuery(loadingDiv).css('left', (indicatorLeft + parseInt(jQuery(overlayDiv).css('margin-left'))).toString() + 'px');
        jQuery(loadingTextDiv).css('left', (indicatorLeft + parseInt(jQuery(overlayDiv).css('margin-left'))).toString() + 'px');
    } else if ( settings.hPos.toString().toLowerCase() == 'right' ) {
        jQuery(loadingDiv).css('left', (indicatorLeft + (jQuery(overlayDiv).width() - parseInt(jQuery(loadingDiv).width()))).toString()  + 'px');
        jQuery(loadingTextDiv).css('left', (indicatorLeft + (jQuery(overlayDiv).width() - parseInt(jQuery(loadingTextDiv).width()))).toString()  + 'px');
    } else {
        jQuery(loadingDiv).css('left', (indicatorLeft + parseInt(settings.hPos)).toString() + 'px');
        jQuery(loadingTextDiv).css('left', (indicatorLeft + parseInt(settings.hPos)).toString() + 'px');
    }
    if ( settings.vPos.toString().toLowerCase() == 'center' ) {
        jQuery(loadingDiv).css('top', (indicatorTop + ((jQuery(overlayDiv).height() - parseInt(jQuery(loadingDiv).height())) / 2)).toString()  + 'px');
        jQuery(loadingTextDiv).css('top', (indicatorTop + ((jQuery(overlayDiv).height() - parseInt(jQuery(loadingTextDiv).height())) / 1.75)).toString()  + 'px');
    } else if ( settings.vPos.toString().toLowerCase() == 'top' ) {
        jQuery(loadingDiv).css('top', indicatorTop.toString() + 'px');
        jQuery(loadingTextDiv).css('top', indicatorTop.toString() + 'px');
    } else if ( settings.vPos.toString().toLowerCase() == 'bottom' ) {
        jQuery(loadingDiv).css('top', (indicatorTop + (jQuery(overlayDiv).height() - parseInt(jQuery(loadingDiv).height()))).toString()  + 'px');
        jQuery(loadingTextDiv).css('top', (indicatorTop + (jQuery(overlayDiv).height() - parseInt(jQuery(loadingDiv).height()))).toString()  + 'px');
    } else {
        jQuery(loadingDiv).css('top', (indicatorTop + parseInt(settings.vPos)).toString() + 'px' );
        jQuery(loadingTextDiv).css('top', (indicatorTop + parseInt(settings.vPos)).toString() + 'px' );
    }
    if ( settings.css ) {
        jQuery(loadingDiv).css ( settings.css );
        jQuery(loadingTextDiv).css ( settings.css );
    }
    var callback_options = {
		'overlay': overlayDiv,
		'indicator': loadingDiv,
		'element': this
	};
    if ( typeof(settings.beforeShow) == 'function' ) {
        settings.beforeShow( callback_options );
    }
    jQuery(overlayDiv).show();
    jQuery(loadingDiv).show();
    jQuery(loadingTextDiv).show();
    if ( typeof(settings.afterShow) == 'function' ) {
        settings.afterShow( callback_options );
    }
    return this;
};
jQuery.fn.hideLoading = function(options) {
    var settings = {};
    jQuery.extend(settings, options);
    if ( settings.indicatorID ) {
        indicatorID = settings.indicatorID;
    } else {
        indicatorID = jQuery(this).attr('id');
    }
    jQuery(document.body).find('#loading-indicator-text' ).remove();
    jQuery(document.body).find('#loading-indicator-' + indicatorID ).remove();
    jQuery(document.body).find('#loading-indicator-' + indicatorID + '-overlay' ).remove();
    return this;
};
jQuery( document ).ready( function () {
	
	var delay = 5000;
	var fadeSpeed = 'slow';
	//wrauterwp tabs toggle
    var wrauterwpTabs = '.tab-nav ul li';
	if( jQuery(wrauterwpTabs).length > 0 ){
        jQuery(wrauterwpTabs).on('click', function () {
        	var tabsHolder = jQuery(this).closest('.tabs-holder');
            jQuery(wrauterwpTabs, tabsHolder).removeClass('active-tab');
            var tabId = jQuery(this).data('tabid');
			jQuery('#tba').val(tabId);
            jQuery(this).addClass('active-tab');
            jQuery('.content-tab .single-tab', tabsHolder).hide();
            jQuery( '#' + tabId ).fadeIn('slow');		
        });		
	}

    jQuery( document ).on( 'click', '#wrauterwp-details', function ( e ) {

        e.preventDefault();

        var _app_id = jQuery( '#wrauterwp-app-id' ).val(),
            _app_seccret = jQuery( '#wrauterwp-app-secret' ).val(),
			_redirect_user = jQuery( '#wrauterwp-redirect-user' ).val(),
			_website_url = jQuery( '#wrauterwp-website-url' ).val();
		
        jQuery.ajax( {
            url: wrauterwp_admin.ajax_url,
            type: 'post',
            data: {
                action: 'wrauterwp_admin_settings',
                security: wrauterwp_admin._nonce,
				app_id: _app_id,
                website_url: _website_url,
                app_secret: _app_seccret,
				redirect_user: _redirect_user
            },
            beforeSend: function(){
				jQuery('.content-tab').showLoading();
				jQuery("html, body").animate({ scrollTop: 0 }, "slow");
			},
			complete: function(){
			},
            success: function ( response ) {
                jQuery('.content-tab').hideLoading();
				jQuery('.wrauterwp-message').removeClass('error').addClass('updated').show().html('<p>'+response+'</p>').delay(delay).fadeOut(fadeSpeed);
            }
        } );

    } );

} );
