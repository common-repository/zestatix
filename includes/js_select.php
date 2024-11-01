<script>
jQuery( document ).ready( ( $ ) => {
    'use strict'

    const ajaxurl = '<?= admin_url('admin-ajax.php') ?>';

    const url_zestatix = '<?= esc_url( self_admin_url( 'plugins.php?page=zestatix' ) ) ?>'

    let before_unload = true;

    const html = (() => {
        let result = {}

        $( `<?php include_once( INCLUDES_ZESTATIX . 'html_select.php' ) ?>` ).map( ( i, el ) => {
            if ( $( el ).data( 'key' ) ) result = { ...result, [ $( el ).data( 'key' ) ]: $( el ) }
        } )

        return result
    })()

    const front = new class {
        constructor() {
            this.confirm = html.popup.prependTo( 'body' )
        }

        length() {
            return $( '.selected-el-zestatix' ).length
        }

        update() {
            $( '.selected-el-zestatix' ).removeClass( 'selected-el-zestatix' )

            if (selector.val() && selector.check) $( `body ${ encode_href( selector.val() ) }` ).filter( ( i, el ) => !check_reject( el ) ).addClass( 'selected-el-zestatix' )
        }

        popup( el ) {
            let elem_screen_top = el[0].getBoundingClientRect().top,

                elem_position_left = el.offset().left,

                elem_position_top = el.offset().top,

                elem_width = el.outerWidth(),

                elem_height = el.outerHeight(),

                popup_height = this.confirm.height(),

                popup_width = this.confirm.width(),

                browser_height = document.documentElement.clientHeight,

                browser_width = window.screen.width,

                margin_popup = 10,

                popup_left = elem_position_left + (elem_width / 2) - (popup_width / 2),

                popup_top = parseInt( ( elem_screen_top >= popup_height + margin_popup ) ? elem_position_top - popup_height - margin_popup : elem_position_top + el.outerHeight() + margin_popup )

            this.confirm.css( { left:0, top:0 } );

            popup_left = (popup_left + popup_width > browser_width) ? browser_width - popup_width : popup_left;

            popup_left = parseInt((popup_left >= 0) ? popup_left : 0);

            popup_top = ( elem_height + popup_height > browser_height ) ? $( window ).scrollTop() + (browser_height / 2) - (popup_height / 2) : popup_top;

            if ( this.confirm.is( ':visible' ) ) {
                this.confirm.fadeOut( 300, function() {
                    $( this ).offset( { top:popup_top, left:popup_left } ).fadeIn( 300 )
                })
            } else {
                this.confirm.offset( { top:popup_top, left:popup_left } ).fadeIn( 300 )
            }
        }
    }

    let selector = new class {
        constructor() {
            this.input = $( '.selector-input-zestatix' )

            this.val = ( val ) => {
                if ( val ) $( '.selector-input-zestatix' ).val( $.trim( val ) )
                else return $( '.selector-input-zestatix' ).val()
            }
        }

        update( value ) {
            this.valid( value )

            if ( value ) this.val( value )

            this.textarea_height()

            front.update()

            this.upd_info()
        }

        valid( value = selector.val() ) {
            let msg_error = '';

            this.check = (() => {
                if (value.length) {
                    if ( value.length > 255 ) {
                        msg_error = '<?php esc_html_e( 'maximum number of characters 255', 'zestatix' ) ?>';

                        return false
                    }

                    try {
                        const check = $( value )
                    } catch {
                        msg_error = '<?php esc_html_e( 'wrong selector', 'zestatix' ) ?>'

                        return false
                    }
                }

                return true
            })();

            const msg = (() => {
                let msg = $( '.panel-zestatix .msg-zestatix' );

                if ( !this.check && msg_error ) {
                    msg.text( msg_error ).fadeIn( 300 )
                }

                else {
                    msg.fadeOut( 300 )
                }
            })()

            return this.check
        }

        length() {
            return this.val().length
        }

        upd_info() {
            $( '.selector-length-zestatix' ).text( `${ front.length() } <?php esc_html_e( 'selected', 'zestatix' ) ?>` )

            $( '.parent-selector-length-zestatix' ).toggleClass( 'primary-zestatix', !!this.length() )

            $( '.characters-zestatix' ).text( this.length() )
        }

        textarea_height() {
            this.input.height( 5 ).height( this.input.prop( 'scrollHeight' ) + 10 )
        }

        create( el ) {
            let result = ''

            el = el.clone().removeClass('not-confirmed-el-zestatix selected-el-zestatix')[ 0 ]

            const attributes = (() => {
                const obj_attributes = {};

                obj_attributes['tag'] = el.nodeName.toLowerCase();

                const inner_txt = $.trim( el.innerText ).replace( /[\(\)']/g, '\\$&' );

                if ( inner_txt.length > 0 ) {
                    if ( /\s{2,}|\n/.test( inner_txt ) ) {
                        obj_attributes['paragraph'] = inner_txt.split(/\s{2,}|\n/)
                    } else {
                        obj_attributes['text'] = inner_txt
                    }
                }

                $.each( el.attributes, ( key, attr ) => {
                    if ( attr.nodeValue.length
                        && attr.nodeName != 'style'
                            && attr.nodeName != 'target' )
                    {

                        obj_attributes[attr.nodeName] = (() => {
                            switch( attr.nodeName ) {
                                case 'class':
                                    return attr.nodeValue.replace( /[^\-\w\s]/g, '\\$&' ).split( ' ' )

                                case 'id':
                                    return attr.nodeValue.replace( /[^\-\w\s]/g, '\\$&' )

                                default:
                                    return attr.nodeValue.replace( /"/g, '\\"' )
                            }
                        })()
                    }
                } );

                return obj_attributes;
            })()

            for ( let [ k, v ] of Object.entries( attributes ) ) {
                if ( !v || !k )
                    return

                switch ( k ) {
                    case 'tag':
                        result = v += result
                    break

                    case 'id':
                        result += '#' + v
                    break

                    case 'class':
                        $.each( v, function( key, val ) {
                            result += '.' + val
                        } )
                    break

                    case 'text':
                        result += ':contains(' + v + ')'
                    break

                    case 'paragraph':
                        $.each( v, function( key, val ) {
                            result += ':contains(' + val + ')'
                        } )
                    break

                    default:
                        result += '[' + k + '="' + v + '"]'
                }
            }

            return decode_href( result )
        }
    }

    let panel = new class {
        constructor() {
            this.selected = ( () => {
                let selected

                $.ajax( {
                    url: ajaxurl,
                    async: false,
                    data: {
                        action: 'get_select_zestatix'
                    },
                } ).done( ( data ) => {
                    selected = data.length ? JSON.parse( data ) : {};
                } )

                return selected
            } )();

            if ( Object.keys( this.selected ).length )
                ( {
                    name: this.name,
                    selector: this.selector,
                    track_on: this.track_on,
                    browser_width: this.browser_width,
                } = this.selected )

            this.panel = html.panel.prependTo( 'body' )

            this.panel.find( '.track-zestatix .content-zestatix' ).append( this.path() )

            $( '[ name="name" ]' ).val( this.name ?? '' )

            $( '[ name="selector" ]' ).val( this.selector ?? '' )

            $( '[ name="browser_width/min" ]' ).val( this.browser_width?.min ?? '' )

            $( '[ name="browser_width/max" ]' ).val( this.browser_width?.max ?? '' )

            if (this.browser_width?.min || this.browser_width?.max) {
                $( '.input-width-zestatix[ value="custom" ]' ).prop( 'checked', true )

                $( '.custom-width-zestatix' ).show( 0 )
            }

            else $( '.input-width-zestatix[ value="any" ]' ).prop( 'checked', true )

            if ( $( '.subdirectories-active-zestatix' ).length )
                this.disable_tracked( $( '.subdirectories-active-zestatix' ) )

            selector.update()
        }

        path() {
            let result = ''

            let val_input = ''

            $.each( location.href.split( '://' )[1].match( /(.*?\/)/g ), ( i, v ) => {
                val_input += v;

                let checked = ''

                let subdir = ''

                if ( this.track_on?.[ val_input ] ) {
                    checked = 'checked';

                    if ( +this.track_on[ val_input ][ 'subdirectories' ] ) subdir = 'subdirectories-active-zestatix'

                    delete this.track_on[ val_input ]
                }

                result += `<div class="unit-control-zestatix">
                    <input name="track_on" class="input-track-on-zestatix" type="checkbox" value="${ val_input }" ${ checked } />
                    <span class="dashicons dashicons-editor-break subdirectories-zestatix ${ subdir }" title="<?php esc_html_e( 'SUBDIRECTORIES: DISABLED', 'zestatix' ) ?>"></span>
                    <label class="label-zestatix" title="${ decodeURI( val_input ) }">
                        ${ decodeURI( v.slice( 0, -1 ) ) }
                    </label>
                </div>`
            } )

            result = $( result )

            if ( this.track_on && Object.keys( this.track_on ).length ) {
                result.append( `<div class="tracked-zestatix"><label class="content-label-zestatix"><?php esc_html_e( 'TRACKED', 'zestatix' ) ?></label><div class="content-zestatix"></div></div>` )

                $.each( this.track_on, ( path, subdr ) => {
                    let active = ''

                    if ( +this.track_on[ path ][ 'subdirectories' ] ) {
                        active = 'subdirectories-active-zestatix'
                    }

                    result.find( '.tracked-zestatix .content-zestatix' ).append( `<div class="unit-control-zestatix">
                        <input name="track_on" class="input-track-on-zestatix" type="checkbox" value="${ path }" checked/><span class="dashicons dashicons-editor-break subdirectories-zestatix ${ active }" title="<?php esc_html_e( 'SUBDIRECTORIES: DISABLED', 'zestatix' ) ?>"></span>
                        <label class="label-zestatix" title="${ decodeURI( path ) }">
                            ${ decodeURI( path.slice( 0, -1 ) ) }
                        </label>
                    </div>` )
                } )
            }

            return result
        }

        disable_tracked( el ) {
            const checkbox = el.siblings( '.track-zestatix .input-track-on-zestatix' )

            const idx = checkbox.index( '.input-track-on-zestatix' )

            const checkboxes_greater = $( '.track-zestatix .input-track-on-zestatix:gt( ' + idx + ' )' )

            const checked_checkboxes_greater = $( '.track-zestatix .input-track-on-zestatix:gt( ' + idx + ' ):checked' )

            const subdr_greater = $( '.track-zestatix .subdirectories-zestatix:gt( ' + idx + ' )' )

            const label_greater = $( '.track-zestatix .label-zestatix:gt( ' + idx + ' )' )

            const actived = el.hasClass( 'subdirectories-active-zestatix' )

            checkboxes_greater.prop( 'disabled', actived )

            subdr_greater.add( label_greater ).toggleClass( 'disable-zestatix', actived )

            if ( actived ) {
                checked_checkboxes_greater.click()

                el.prop( 'title', '<?php esc_html_e( 'SUBDIRECTORIES: ENABLED', 'zestatix' ) ?>' )
            } else {
                el.prop( 'title', '<?php esc_html_e( 'SUBDIRECTORIES: DISABLED', 'zestatix' ) ?>' )
            }
        }

        data() {
            let data = {};

            let reject = [ '[ type="checkbox" ]:not( :checked )', '[ type="radio" ].input-width-zestatix' ]

            $( '#root-zestatix [ name ]' ).map( ( idx, el ) => {
                if ( el.matches( reject.join( ',' ) ) ) return

                let val = $.trim( $( el ).val() );

                let	path = $( el ).prop( 'name' ).split( '/' ),

                    last_key = path.pop(),

                    temp = path.reduce( ( acc, v ) => v in acc ? acc[ v ] : ( acc[ v ] = {} ), data );

                if ( last_key == 'track_on' ) {
                    if ( typeof temp[ last_key ] !== 'object' ) temp[ last_key ] = {};

                    Object.assign( temp[ last_key ], {
                        [ val ]: {
                            'subdirectories': ( $( el ).siblings( '.subdirectories-zestatix' ).hasClass( 'subdirectories-active-zestatix' ) ) ? 1 : 0
                        }
                    } )
                } else {
                    temp[ last_key ] = val
                }
            } );

            return data
        }

        toggle( toggle = true ) {
            $( '#root-zestatix' ).toggleClass( 'show-zestatix', toggle )
        }
    }

    const events = ( () => {
        let target

        let delay_timer

        $( document.body )
            .on( 'mouseover', '*', ( e ) => {
                e.stopImmediatePropagation();

                target = $( e.target );

                if ( check_reject( target )
                    || target.hasClass( 'not-confirmed-el-zestatix' )
                        || target.hasClass( 'selected-el-zestatix' ) ) {
                    return;
                }

                delay_timer = setTimeout( () => {
                    const curr_target = target;

                    curr_target.addClass( 'this-el-zestatix' );

                    $.data( curr_target, 'timer',
                        setTimeout( $.proxy(
                            () => {
                                $( '.not-confirmed-el-zestatix' ).removeClass( 'not-confirmed-el-zestatix' )

                                curr_target.addClass( 'not-confirmed-el-zestatix' )

                                front.popup( curr_target )
                            },
                        curr_target ), 2500 )
                    )
                }, 400 )
            } )

            .on( 'mouseout', '*', () => {
                if ( !target )
                    return

                clearTimeout( delay_timer );

                clearTimeout( $.data( target, 'timer' ) )

                target.removeClass( 'this-el-zestatix' )
            } )

            .on( 'click', '.popup-buttons-zestatix a', e => {
                if ( e.target.innerText == 'YES' ) {
                    selector.update( selector.create( $( '.not-confirmed-el-zestatix' ) ) )

                    panel.toggle()
                }

                $( '.not-confirmed-el-zestatix ' ).removeClass( 'not-confirmed-el-zestatix' )

                front.confirm.fadeToggle( 300 )
            } )

            .on( 'input', '.selector-input-zestatix', e => {
                selector.update()
            } )

            .on( 'click', '.input-track-on-zestatix:not( :disabled )', e => {
                const input = $( e.target )

                const subdirectories = input.siblings( '.subdirectories-zestatix' )

                if ( !input.is( ':checked' ) && subdirectories.hasClass( 'subdirectories-active-zestatix' ) )
                    subdirectories.click()
            } )

            .on( 'click', '.subdirectories-zestatix:not( .disable-zestatix )', e => {
                const subdir = $( e.target )

                const checkbox = subdir.siblings( '.input-track-on-zestatix' )

                const check = subdir.toggleClass( 'subdirectories-active-zestatix' ).hasClass( 'subdirectories-active-zestatix' )

                if ( check && !checkbox.is( ':checked' ) ) checkbox.click()

                if ( subdir.parentsUntil( '.track-zestatix' ) ) panel.disable_tracked( subdir )
            } )

            .on( 'click', '.control-selector-zestatix .dashicons-trash', () => {
                $( '.selector-input-zestatix' ).val( '' ).trigger( 'input' )
            } )

            .on( 'click', '.input-width-zestatix', e => {
                $( '.custom-width-zestatix' ).toggle( $( e.target ).val() == 'custom' )
            } )

            .on( 'click', '.helper-zestatix', e => {
                const btn = $( e.target )

                const check_class = btn.toggleClass( 'helper-active-zestatix' ).hasClass( 'helper-active-zestatix' );

                if ( check_class ) {
                    btn.attr( 'title', '<?php esc_html_e( 'hide example', 'zestatix' ) ?>' )
                } else {
                    btn.attr( 'title', '<?php esc_html_e( 'show example', 'zestatix' ) ?>' )
                }

                $( '.table-example-zestatix' ).fadeToggle( 600, 'linear' )
            } )

            .on( 'click', '.label-zestatix', e => {
                $( e.target ).siblings( 'input:not( :disabled )' ).click()
            } )

            .on( 'click', '#togglers-panel-zestatix', () => {
                panel.toggle( !$( '#root-zestatix' ).hasClass( 'show-zestatix' ) )
            } )

            .on( 'click', '#save-panel-zestatix', () => {
                const data = panel.data();

                $.post( ajaxurl,
                    { action: 'save_select_zestatix', data: data, },
                    () => {
                        escape()
                    }
                )
            } )

            .on( 'click', '#escape-select-zestatix', () => {
                $.post( ajaxurl,
                    { action: 'exit_select_zestatix' },
                    () => {
                        escape()
                    },
                )
            } )
    } )()

    function check_reject( el ) {
        const rejects = ['#popup-zestatix', '#wpadminbar', '#root-zestatix', '#control-panel-zestatix']

        return rejects.find( reject => !!$( el ).closest( reject ).length ) ?? false
    }

    function decode_href( str ) {
        let reg_ex = /(\[href=")(.+?)("\])/;

        if ( reg_ex.test( str ) ) {
                str = str.replace( reg_ex, ( match, p1, p2, p3 ) => p1 + decodeURI( p2 ) + p3 )
        }

        return str
    }

    function encode_href( str ) {
        let reg_ex = /(\[href=")(.+?)("\])/

        if ( reg_ex.test( str ) )
                str = str.replace( reg_ex, ( match, p1, p2, p3 ) => p1 + encodeURI( p2 ).toLowerCase() + p3 )

        return str
    }

    function escape() {
        before_unload = false;

        location.href = url_zestatix;
    }

    window.addEventListener( 'beforeunload', () => {
        if ( !before_unload ) return;

        let data = {
            action: 'set_select_zestatix',
            panel_data: JSON.stringify( panel.data() )
        }

        const form_data = new FormData();

        Object.keys(data).map(key => {
            form_data.append( key, data[key] )
        });

        window.navigator.sendBeacon( ajaxurl, form_data )
    } );
} )
</script>