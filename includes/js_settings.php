<script>
    jQuery( document ).ready( function( $ ) {
        'use strict'

        const href = '<?= home_url( '/' ) ?>'

        const domain = href.split( '://' )[1]

        const data_clicks = <?= json_encode( DB_zeStatix::get_details_click() ) ?>

        class CardChart {
            constructor( stats, text, container ) {
                this.stats = stats

                this.text = text.toUpperCase()

                this.container = container

                this.limiter_pieces = 10

                this.limiter_legend = 5

                this.hole_size = 0.5

                this.colors = [ '#e5431a', '#fd6724', '#fefa3a', '#69f533', '#58cd2b', '#20b58a', '#44b4da', '#1721d3', '#722cf6', '#dc35d9', '#e22f96', ]

                this.total_value = this.stats.reduce( ( acc, val ) => acc + val[ Object.keys( val )[ 0 ] ], 0 )

                this.canvas = this.container.find( 'canvas' ).get( 0 )

                this.container_legend = this.container.find( `.legend-zestatix` )

                this.ctx = this.canvas.getContext( '2d' )

                this.draw()
            }

            draw() {
                const limit = ( () => {
                    if ( this.stats.length > this.limiter_pieces ) {
                        const other = this.stats.splice( this.limiter_pieces ).reduce( ( acc, val ) => acc + val[ Object.keys( val )[ 0 ] ], 0 );

                        this.stats.push( { other: other } );
                    }
                } )();

                let color_index = 0;

                let start_angle = Math.PI * 1.5;

                this.stats.map( ( stat ) => {
                    for ( const [ idx, val ] of Object.entries( stat ) ) {
                        const slice_angle = 2 * Math.PI * val / this.total_value;

                        const end_angle = start_angle + slice_angle;

                        this.draw_value(
                            this.ctx,

                            this.canvas.width / 2,

                            this.canvas.height / 2,

                            Math.min( this.canvas.width / 2, this.canvas.height / 2 ),

                            start_angle,

                            end_angle,

                            this.colors[ color_index ]
                        );

                        this.legend( idx, val, color_index );

                        start_angle = end_angle;

                        ( this.colors.length == color_index + 1 ) ? color_index = 1 : color_index ++;
                    }
                } )

                this.draw_hole();

                this.add_text()
            }

            draw_value( ctx, center_x, center_y, radius, start_angle, end_angle, color ) {
                ctx.fillStyle = color;

                ctx.beginPath();

                ctx.moveTo( center_x, center_y );

                ctx.arc( center_x, center_y, radius, start_angle, end_angle );

                ctx.closePath();

                ctx.fill();
            }

            draw_hole() {
                this.draw_value(
                    this.ctx,

                    this.canvas.width / 2,

                    this.canvas.height / 2,

                    this.hole_size * Math.min( this.canvas.width / 2, this.canvas.height / 2 ),

                    0,

                    2 * Math.PI,

                    '#dde3e8'
                );
            }

            legend( idx, val, color_index ) {
                const percentage = Math.round( 100 * val / this.total_value );

                const limit = ( () => {
                    const check = this.container_legend.find( '.unit-legend-zestatix' ).length < this.limiter_legend;

                    if ( !check )
                        this.container_legend.find( '.toggler-legend-zestatix' ).removeClass( 'none-zestatix' );
                    else
                        return 'active-zestatix';
                } )();

                const html = `<div class="unit-legend-zestatix ${ limit ?? '' }"><span class="color-legend-zestatix" style="background-color: ${ this.colors[ color_index ] };"></span><span class="legend-stat-zestatix"> ${ val } / ${ percentage }%</span><span class="legend-key-zestatix">${ idx }</span></div>`;

                this.container_legend.find( '.toggler-legend-zestatix' ).before( html );
            }

            add_text() {
                this.ctx.font = '13px Roboto';

                this.ctx.textAlign = 'center';

                this.ctx.textBaseline = 'middle';

                this.ctx.fillStyle = '#454749';

                this.ctx.fillText( this.text, this.canvas.width / 2, this.canvas.height / 2 );
            }
        }

        class CardStats {
            constructor( selector ) {
                ({ loaded: this.data_loaded, clicks: this.data_clicks, created: this.data_created } = data_clicks[ selector ])

                this.table = $( '<table class="table-zestatix table-details-zestatix"></table>' )

                this.table.append(this.default())

                this.table.append(this.loaded())

                this.table.append(this.clicks())
            }

            default() {
                return `
                    <tr scope="row">
                        <td><?php esc_html_e( 'status', 'zestatix' ) ?>:</td>
                        <td class="status-element-zestatix"><?php esc_html_e( 'tracked', 'zestatix' ) ?></td>
                    </tr>
                    <tr scope="row">
                        <td><?php esc_html_e( 'date of creation', 'zestatix' ) ?>:</td>
                        <td>${ this.data_created }</td>
                    </tr>`
            }

            loaded() {
                return `
                    <tr scope="row">
                        <td><?php esc_html_e( 'loaded', 'zestatix' ) ?>:</td>
                        <td>
                            <span>
                                ${ this.data_loaded?.count ?? 0 } <?php esc_html_e( 'times', 'zestatix' ) ?>
                            </span>
                            <span>
                                / ${ this.data_loaded?.urls?.length ?? 0 } <?php esc_html_e( 'pages', 'zestatix' ) ?>
                            </span>
                            <span>
                                / ${ this.data_loaded?.users?.length ?? 0 } <?php esc_html_e( 'visitors', 'zestatix' ) ?>
                            </span>
                        </td>
                    </tr>`
            }

            clicks() {
                return `
                    <tr scope="row">
                        <td><?php esc_html_e( 'clicks', 'zestatix' ) ?>:</td>
                        <td>
                            <span>
                                ${ this.data_clicks?.length ?? 0 } <?php esc_html_e( 'times', 'zestatix' ) ?>
                            </span>
                            <span>
                                / ${ ( this.data_clicks ) ? this.unique_length( this.data_clicks.map( obj => obj.url ) ) : 0 } <?php esc_html_e( 'pages', 'zestatix' ) ?>
                            </span>
                            <span>
                                / ${ ( this.data_clicks ) ? this.unique_length( this.data_clicks.map( obj => ( obj.login.length ) ? obj.login : obj.ip ) ) : 0 } <?php esc_html_e( 'visitors', 'zestatix' ) ?>
                            </span>
                        </td>
                    </tr>`
            }

            unique_length( arr ) {
                return arr.filter( ( item, pos, arr ) => arr.indexOf( item ) === pos ).length
            }
        }

        class CardSelector {
            constructor( card ) {
                this.card = card

                this.input = card.find( '.selector-element-zestatix' )

                this.value = () => this.input.val().trim()

                this.counter = card.find( '.characters-zestatix' )

                this.alert_danger = card.find( '.alert-zestatix' )
            }

            valid() {
                this.alert_danger.html('')

                if ( this.value().length > 255 ) {
                    this.alert_danger.html('<?php esc_html_e( 'maximum number of characters 255', 'zestatix' ) ?>')

                    return false
                }

                if ( /[^\\]'/.test( this.value() ) ) {
                    this.alert_danger.html('<?php esc_html_e( 'insert \ character before \'', 'zestatix' ) ?>' + '<button class="button-zestatix correct-this-zestatix">correct this</button>')

                    return false
                }

                try {
                    let selector = $( this.value() )
                } catch {
                    this.alert_danger.html('<?php esc_html_e( 'wrong selector', 'zestatix' ) ?>')

                    return false
                }

                return true
            }

            set_length() {
                this.counter.text( this.value().length )
            }

            action() {
                let valid = this.valid();

                if ( valid ) {
                    this.alert_danger.slideUp( 400 );

                    this.card.removeClass( 'wrong-zestatix' );

                    this.input.removeClass( 'wrong-selector-zestatix' );
                } else {
                    this.alert_danger.slideDown( 400 );

                    this.card.addClass( 'wrong-zestatix' );

                    this.input.addClass( 'wrong-selector-zestatix' );
                }

                this.set_length()

                height_textarea( this.input )
            }
        }

        class Card {
            constructor( data ) {
                this.data = data || {}

                this.init()
            }

            init() {
                this.card = $( '<div class="card-zestatix"></div>' )

                this.card.append( this.control_card(), this.units( this.data.name, this.data.selector, this.data.track_on, this.data.browser_width ) )

                this.selector = new CardSelector( this.card )

                if ( data_clicks[ this.data.selector ] ) {
                    this.stats = new CardStats( this.data.selector )

                    this.stats.table.append(this.control_stats())

                    let wrap = $( '<div class="stat-zestatix unit-zestatix"></div>' ).append( this.stats.table )

                    this.card.append(wrap)

                    this.card.find( '.visible-charts-zestatix' ).css( 'display', 'inline-flex' )
                }

                if ( data_clicks[ this.data.selector ]?.clicks ) {
                    let wrap = $( `<tr scope="row"><td colspan="2"></td></tr>` )

                    wrap.find( 'td' ).append( this.charts() )

                    this.card.find( '.table-control-history-zestatix' ).before( wrap )
                }

                if ( this.data?.visible?.charts === '0' ) this.card.find( '.stat-zestatix' ).css('display','none')

                if ( this.data?.visible?.setting === '0' ) this.card.find( '.setting-zestatix' ).css('display','none')

                if ( this.data?.tracked === '0' ) this.card.find( '.pause-zestatix' ).trigger( 'click' )
            }

            remove_history() {
                delete data_clicks[ this.data.selector ];

                let tmp = $( '<tmp></tmp>' )

                this.card.before( tmp )

                this.card.remove()

                this.init()

                tmp.before( this.card )

                tmp.remove()
            }

            charts() {
                const container = (name) => `<div class="unit-chart-zestatix ${name}-chart-zestatix" style="order: ${order.indexOf(name)}"><canvas></canvas><div class="legend-zestatix"><div class="toggler-legend-zestatix none-zestatix"><span class="dashicons dashicons-arrow-down-alt2"></span></div></div></div>`

                let root = $('<div class="charts-zestatix"></div>')

                let order = [ 'url', 'login', 'location', 'width', 'device', ]

                let data = {}

                data_clicks[ this.selector.value() ].clicks.reduce( ( acc, curr ) => {
                    for ( let [ chart, key ] of Object.entries( curr ) ) {
                        if ( !order.includes( chart ) )
                            continue;

                        if ( chart == 'url' )
                            key = decodeURI( key.replace( domain, 'home/' ) );

                        if ( !acc?.[ chart ] )
                            acc[ chart ] = [];

                        let idx;

                        for ( const i in acc[ chart ] ) {
                            if ( acc[ chart ][ i ]?.[ key ] ) {
                                idx = i;

                                break;
                            }
                        }

                        if ( idx )
                            acc[ chart ][ idx ][ key ]++;
                        else
                            acc[ chart ].push( { [ key ]: 1 } );
                    }

                    return acc;
                }, data )

                for ( const [ chart, keys ] of Object.entries( data ) ) {
                    data[ chart ] = keys.sort( (a, b) => b[ Object.keys( b )[ 0 ] ] - a[ Object.keys( a )[ 0 ] ] )
                }

                $.each( data, ( name, stats ) => {
                    root.append( new CardChart( stats, name, $( container( name ) ) ).container )
                } )

                return root
            }

            details() {
                const data = data_clicks[ this.selector.value() ].clicks

                if ( !data ) return

                let table = (() => {
                    let table = this.card.find( '.table-details-click-zestatix' )

                    if ( !table.length ) {
                        table = $( '<table class="table-zestatix table-details-click-zestatix"></table>' ).appendTo( this.card.find( '.stat-zestatix' ) ).hide()

                        table.append( '<tr scope="row"><td>#</td><td><?php esc_html_e( 'LOGIN', 'zestatix' ) ?></td><td><?php esc_html_e( 'URL', 'zestatix' ) ?></td><td class="device-zestatix"><?php esc_html_e( 'DEVICE', 'zestatix' ) ?></td><td title="<?php esc_html_e( 'DISPLAY', 'zestatix' ) ?> / <?php esc_html_e( 'BROWSER', 'zestatix' ) ?>"><?php esc_html_e( 'D / B', 'zestatix' ) ?></td><td><?php esc_html_e( 'LOCATION', 'zestatix' ) ?></td><td><?php esc_html_e( 'DATE', 'zestatix' ) ?></td></tr>' )

                        let num = data.length

                        for ( let row of data.reverse() ) {
                            const device = ( row[ 'device' ] == 'mobile' ) ? '<span class="dashicons dashicons-smartphone" title="<?php esc_html_e( 'mobile', 'zestatix' ) ?>"></span>' : '<span class="dashicons dashicons-laptop" title="<?php esc_html_e( 'PC', 'zestatix' ) ?>"></span>'

                            const url = decodeURI( row[ 'url' ].replace( domain, 'home/' ) )

                            table.append( '<tr scope="row"><td>' + num -- + '</td><td>' + row[ 'login' ] + '</td><td>' + url + '</td><td>' + device + '</td><td title="<?php esc_html_e( 'DISPLAY', 'zestatix' ) ?> / <?php esc_html_e( 'BROWSER', 'zestatix' ) ?>">' +  row[ 'width' ] + '</td><td title="' + row[ 'ip' ] + '">' + row[ 'location' ] + '<br>' + row[ 'ip' ] + '</td><td>' + row[ 'date' ] + '</td></tr>' )
                        }

                        this.card.append( table )
                    }

                    return table
                })()

                table.fadeToggle( 700 )
            }

            control_card() {
                return `
                    <div class="control-element-zestatix">
                        <span class="dashicons dashicons-trash center-flex-zestatix remove-card-zestatix" title="<?php esc_html_e( 'REMOVE', 'zestatix' ) ?>"></span>
                        <span class="dashicons dashicons-controls-pause center-flex-zestatix pause-zestatix ${ +this.data.tracked || !this.data.tracked ? 'active-zestatix' : '' }" title="<?php esc_html_e( 'TRACKED', 'zestatix' ) ?>">
                            <input type="hidden" name="tracked" value="1"/>
                        </span>
                        <span class="dashicons dashicons-admin-settings center-flex-zestatix visible-setting-zestatix ${ +this.data?.visible?.setting || !this.data?.visible?.setting ? 'active-zestatix' : '' }" title="<?php esc_html_e( 'SETTINGS', 'zestatix' ) ?>">
                            <input type="hidden" name="visible/setting" value="1"/>
                        </span>
                        <span class="dashicons dashicons-chart-pie center-flex-zestatix visible-charts-zestatix ${ +this.data?.visible?.charts || !this.data?.visible?.charts ? 'active-zestatix' : '' }" title="<?php esc_html_e( 'STATISTICS', 'zestatix' ) ?>">
                            <input type="hidden" name="visible/charts" value="1"/>
                        </span>
                    </div>`
            }

            control_stats() {
                return `
                    <tr scope="row" class="table-control-history-zestatix">
                        <td  colspan="2" class="control-history-zestatix">
                            <div class="center-flex-zestatix container-zestatix">
                                ${data_clicks[ this.selector.value() ].clicks ? `
                                    <button type="button" class="button-zestatix btn-details-zestatix">
                                        <?php esc_html_e( 'CLICKS DETAILS', 'zestatix' ) ?>
                                    </button>
                                ` : ''}
                                <button type="button" class="button-zestatix btn-c-h-zestatix">
                                    <?php esc_html_e( 'CLEAR HISTORY', 'zestatix' ) ?>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="tr-del-zestatix">
                        <td colspan="2">
                            <div class="del-detail-zestatix">
                                <label class="del-label-zestatix"><?php esc_html_e( 'HISTORY WILL BE DELETED', 'zestatix' ) ?></label>
                                <button type="button" class="button-zestatix btn-del-ok-zestatix"><span>OK</span></button>
                            </div>
                        </td>
                    </tr>`
            }

            track_on( data ) {
                let result = ''

                const create_unit = ( href, data = {} ) => {
                    let unit = $('<div class="unit-track-on-zestatix"></div>')

                    unit.html( `
                        <div class="unit-track-on-zestatix">
                            <label>home/</label>
                            <textarea name="track_on" class="border-bottom-zestatix input-track-on-zestatix" placeholder="${ ( + data?.subdirectories || !data.hasOwnProperty('subdirectories') ) ? ' <?php esc_html_e( ' selected all pages', 'zestatix' ) ?>' : '<?php esc_html_e( ' only home page', 'zestatix' ) ?>' }">${ href ? decodeURI( href.replace( domain, '' ) ) : '' }</textarea>
                            <div class="control-track-on-zestatix">
                                <span class="dashicons dashicons-editor-break subdirectories-zestatix ${( +data?.subdirectories || !data.hasOwnProperty('subdirectories') ) ? 'active-zestatix' : ''}" title="${ +data?.subdirectories ? '<?php esc_html_e( 'SUBDIRECTORIES: ENABLED', 'zestatix' ) ?>' : '<?php esc_html_e( 'SUBDIRECTORIES: DISABLED', 'zestatix' ) ?>' }"></span>
                                <span class="dashicons dashicons-trash btn-remove-unit-track-on-zestatix" title="<?php esc_html_e( 'REMOVE', 'zestatix' ) ?>"></span>
                            </div>
                        </div>` )

                    return unit.prop( 'outerHTML' )
                }

                if ( data )
                    $.each( data, ( href, data ) => {
                        let unit = create_unit( href, data )

                        result += unit
                    } )
                else result = create_unit()

                return result
            }

            units(name, selector, track_on, browser_width = {}) {
                return `
                    <div class="unit-zestatix">
                        <div class="name-element-zestatix">
                            <input type="text" name="name" class="name-card-zestatix border-bottom-zestatix" value="${name ? escape_html(name) : ''} " placeholder="<?php esc_html_e( 'name', 'zestatix' ) ?>">
                        </div>
                    </div>
                    <div class="unit-zestatix setting-zestatix">
                        <div class="unit-zestatix">
                            <label class="unit-label-zestatix"><?php esc_html_e( 'SELECTOR', 'zestatix' ) ?> jQuery( '</label>
                            <div class="unit-content-zestatix selector-zestatix">
                                <div style="position:relative;">
                                    <textarea name="selector" class="selector-element-zestatix" placeholder="<?php esc_html_e( 'enter element selector', 'zestatix' ) ?>">${ selector ? decode_href( selector ) : '' }</textarea>
                                    <div class="control-selector-zestatix center-flex-zestatix">
                                        <span class="characters-zestatix">${selector ? selector.length : 0}</span>
                                        <span class="max-characters-zestatix"> / 255</span>
                                        <span class="dashicons dashicons-trash" title="<?php esc_html_e( 'clear selector', 'zestatix' ) ?>"></span>
                                    </div>
                                    <div class="alert-zestatix"></div>
                                    <button class="button-zestatix btn-example-zestatix">
                                        <?php esc_html_e( 'SHOW EXAMPLE', 'zestatix' ) ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="unit-zestatix tracked-zestatix">
                            <label class="unit-label-zestatix">
                                <?php esc_html_e( 'TRACK ON', 'zestatix' ) ?>
                            </label>
                            <div class="unit-content-zestatix">
                                ${ this.track_on( track_on ) }
                                <button class="button-zestatix btn-add-unit-track-on">
                                    <span>
                                        <?php esc_html_e( 'ADD PAGE', 'zestatix' ) ?>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="width-zestatix unit-zestatix">
                            <label class="unit-label-zestatix"><?php esc_html_e( 'BROWSER WIDTH', 'zestatix' ) ?></label>
                            <div class="unit-content-zestatix">
                                <select class="select-width-zestatix">
                                    <option value="any width" ${ !browser_width?.min && !browser_width?.max ? 'selected' : '' }><?php esc_html_e( 'any width', 'zestatix' ) ?></option>
                                    <option value="custom width" ${ browser_width?.min || browser_width?.max ? 'selected' : '' }><?php esc_html_e( 'custom width', 'zestatix' ) ?></option>
                                </select>
                                <div class="custom-width-zestatix ${ !browser_width?.min && !browser_width?.max ? 'none-zestatix' : '' }">
                                    <div>
                                        <label>min</label>
                                        <input type="text" size="5" name="browser_width/min" class="input-number-valid-zestatix border-bottom-zestatix removed_element_zestatix" value="${browser_width?.min ? escape_html( browser_width.min ) : ''}">
                                        <label>px</label>
                                    </div>
                                    <div>
                                        <label>max</label>
                                        <input type="text" size="5" name="browser_width/max" class="input-number-valid-zestatix border-bottom-zestatix removed_element_zestatix" value="${browser_width?.max ? escape_html( browser_width.max ) : ''}">
                                        <label>px</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`
            }
        }

        const Cards = new class {
            constructor() {
                this.cards = []

                let data = <?= json_encode( get_option( 'zestatix' ) ) ?>

                if ( !data ) return;

                this.unpack( data )
            }

            unpack( data ) {
                for ( let card of data ) {
                    this.create( card )
                }
            }

            create( card, duration ) {
                this.insert( this.cards.push( new Card( card ) ) - 1, duration )

                return this.cards[ this.cards.length - 1 ]
            }

            insert( i, duration ) {
                if ( duration )
                    $( this.cards[ i ].card.get(0) ).hide().insertAfter( '.card-control-zestatix' ).slideDown( duration )
                else
                    $( this.cards[ i ].card.get(0) ).insertAfter( '.card-control-zestatix' )
            }

            get( card ) {
                return this.cards.find(item => item.card.get(0) == card)
            }

            data() {
                const result = [];

                for ( let card of $( '.card-zestatix:not( .wrong-zestatix )' ) ) {
                    card = $( card );

                    if ( !$.trim( card.find( '[name="selector"]' ).val() ).length ) return;

                    const obj = {};

                    card.find( '[ name ]' ).map( function() {
                        const path = $( this ).prop( 'name' ).split( '/' );

                        const val = $.trim( $( this ).val() );

                        const last_key = path.pop();

                        let temp = path.reduce( ( acc, key ) => ( key in acc ) ? acc[ key ] : acc[ key ] = {}, obj );

                        switch ( last_key ) {
                            case 'track_on':
                                temp[ last_key ] = temp?.[ last_key ] ?? {};

                                Object.assign( temp[ last_key ], {
                                    [ domain + val.replace( /\s+/gm, '' ) ]: {
                                        'subdirectories': ( $( this ).parents( '.unit-track-on-zestatix' ).find( '.subdirectories-zestatix' ).hasClass( 'active-zestatix' ) ) ? 1 : 0
                                    }
                                } );

                                break;

                            case 'selector':
                                temp[ last_key ] = encode_href( val );

                                break;

                            default:
                                temp[ last_key ] = val;
                        }
                    } );

                    result.unshift( obj )
                }

                return result
            }

        }

        function save_data( data = {}, callback ) {
            $.post( ajaxurl, {
                action: 'data_settings_zestatix',

                settings: Cards.data(),

                toggler: $( '#toggler-value-zestatix' ).val(),

                ...data },

                function() {
                    if ( typeof callback == 'function' ) callback()
                }
            )
        }

        function toggle_popup( popup = $( '.root-popup-zestatix[ style *= "display: block" ]' ) ) {
            popup.add( '#overley-zestatix' ).fadeToggle( 200 )
        }

        function toggle_preloader( timer ) {
            $( '#body-zestatix' ).toggleClass( 'opacity-zestatix');

            $( '#preloader-zestatix' ).fadeToggle( 800, 'linear' );

            if ( timer ) {
                setTimeout( () => {
                    toggle_preloader();
                }, timer );
            }
        }

        function escape_html( text ) {
            if ( typeof text == 'undefined' || !text.length ) {
                return '';
            } else {
                return text
                .replace( /&/g, '&amp;' )
                .replace( /</g, '&lt;' )
                .replace( />/g, '&gt;' )
                .replace( /"/g, '&quot;' )
                .replace( /'/g, '&#039;' )
            }
        }

        function decode_href( str ) {
            // [href="(this decode)"]
            let reg_ex = /(\[href=")(.+?)("\])/;

            if ( reg_ex.test( str ) ) {
                str = str.replace( reg_ex, ( match, p1, p2, p3 ) => p1 + decodeURI( p2 ) + p3 )
            }

            return str
        }

        function encode_href( str ) {
            // [href="this encode"]
            let reg_ex = /(\[href=")(.+?)("\])/;

            if ( reg_ex.test( str ) ) {
                str = str.replace( reg_ex, ( match, p1, p2, p3 ) => p1 + encodeURI( p2 ).toLowerCase() + p3 )
            }

            return str
        }

        function position_sticky() {
            let height_adminbar = $( '#wpadminbar' ).height();

            const margin_top = 5;

            if ( $( '#wpadminbar' ).css( 'position' ) == 'fixed' )
                $( '#sticky-zestatix' ).css( 'top', height_adminbar + margin_top );
            else
                $( '#sticky-zestatix' ).css( 'top', margin_top );
        }

        function height_textarea( el ) {
            el.height( 5 ).height( el.prop( 'scrollHeight' ) )
        }

        const ready = (() => {
            if ( !<?= TOGGLE_ZESTATIX ?> ) $( '#toggler-zestatix' ).trigger( 'click' )

            $( '#toggler-zestatix' ).removeClass( 'no-animation-zestatix' )

            position_sticky()

            toggle_preloader()
        })()

        $( window ).resize( function() {
            position_sticky();

            setTimeout(
                function() {
                    $( '.selector-element-zestatix:visible, .input-track-on-zestatix:visible' ).each( function() {
                        height_textarea( $( this ) )
                    } )
                },
            300 )
        } )

        $( document.body )
            .on( 'click', '#zeStatix button', e => {
                e.preventDefault()
            } )

            .on( 'click', '#btn-save-zestatix', () => {
                toggle_preloader( 1000 )

                save_data()
            } )

            .on( 'click', '#btn-navg-zestatix', () => {
                let html = ''

                $( '.name-card-zestatix:visible' ).each( function() {
                    if ( !$( this ).val().trim().length ) return

                    html += `<label class="navg-label-zestatix" data-scroll="${ $( this ).parents( '.card-zestatix' ).offset().top }">${ $( this ).val() }</label>`
                } )

                if ( !html.length )
                    html = '<label id="not-name-element-zestatix"><?php esc_html_e( 'There are no names for navigation', 'zestatix' ) ?></label>'

                $( '#navigator-popup-zestatix' ).find( '.popup-body-zestatix' ).html( html )

                toggle_popup( $( '#navigator-popup-zestatix' ) )
            } )

            .on( 'click', '.navg-label-zestatix', e => {
                $( 'html, body' ).animate( {
                    scrollTop: $( e.target ).attr( 'data-scroll' ),
                }, 1000 )

                toggle_popup()
            } )

            .on( 'click', '#btn-descrption-zestatix', () => {
                toggle_popup( $( '#description-popup-zestatix' ) )
            } )

            .on( 'click', '.popup-close-zestatix', () => {
                toggle_popup()
            } )

            .on( 'click', '#overley-zestatix', () => {
                toggle_popup()
            } )

            .on( 'click', '#btn-select-element-zestatix', () => {
                    toggle_preloader();

                    save_data( { select: 1 }, () => {
                        location.href = href
                    } )
            } )

            .on( 'click', '#btn_add_element_zestatix', () => {
                Cards.create( '', 800 )
            } )

            .on( 'click', '#toggler-zestatix', e => {
                const check = $( e.target ).toggleClass( 'toggler-zestatix-off' ).hasClass( 'toggler-zestatix-off' );

                $( '#logo-img-zestatix' ).removeClass( 'logo-anim-on-zestatix logo-anim-off-zestatix' ).addClass( () => {
                    $( '#toggler-value-zestatix' ).val( + ! check );

                    return ( check ) ? 'logo-anim-off-zestatix' : 'logo-anim-on-zestatix';
                } )
            } )

            .on( 'click', '.description-name-zestatix', e => {
                $( e.target ).toggleClass( 'active-description-name-zestatix' ).siblings( '.description-zestatix' ).fadeToggle( 400 )
            } )

            .on( 'click', '.remove-card-zestatix', e => {
                const card = $( e.target ).parents( '.card-zestatix' );

                card.fadeOut( 600, () => {
                    card.remove();
                } )
            } )

            .on( 'click', '.visible-setting-zestatix, .visible-charts-zestatix', e => {
                const el = $( e.target );

                const check = el.toggleClass( 'active-zestatix' ).hasClass( 'active-zestatix' );

                const field = ( el.hasClass( 'visible-setting-zestatix' ) ) ? el.parents( '.card-zestatix' ).children( '.setting-zestatix' ) : el.parents( '.card-zestatix' ).children( '.stat-zestatix' );

                el.children( 'input[type=hidden]' ).val( +check );

                field.fadeToggle( 400 );

                height_textarea( field.find( '.selector-element-zestatix' ) );
            } )

            .on( 'click', '.pause-zestatix', e => {
                const el = $( e.target );

                const paste_state = el.parents( '.card-zestatix' ).find( '.status-element-zestatix' );

                const check = el.toggleClass( 'active-zestatix' ).hasClass( 'active-zestatix' );

                const text_state = ( check ) ? '<?php esc_html_e( 'tracked', 'zestatix' ) ?>' : '<?php esc_html_e( 'paused', 'zestatix' ) ?>';

                paste_state.fadeOut( 300, () => {
                    paste_state.text( text_state ).fadeIn( 300 )
                } );

                el.attr( 'title', text_state )
                    .removeClass( 'dashicons-controls-pause dashicons-controls-play' )
                        .addClass( () => ( check ) ? 'dashicons-controls-pause' : 'dashicons-controls-play' )
                            .children( 'input[type=hidden]' ).val( +check );
            } )

            .on( 'keydown', '.input-number-valid-zestatix', e => {
                if ( !( e.keyCode == 8 || e.keyCode > 47 && e.keyCode < 58 ) ) {
                    e.preventDefault();
                }
            } )

            .on( 'input', '.input-track-on-zestatix', e => {
                height_textarea( $( e.target ) )
            } )

            .on( 'click', '.subdirectories-zestatix', e => {
                const el = $( e.target );

                const input_track_on = el.parents( '.unit-track-on-zestatix' ).find( '.input-track-on-zestatix' );

                const check = el.toggleClass( 'active-zestatix' ).hasClass( 'active-zestatix' );

                if ( check ) {
                    el.prop( 'title', '<?php esc_html_e( 'SUBDIRECTORIES: ENABLED', 'zestatix' ) ?>' );

                    input_track_on.prop( 'placeholder', ' <?php esc_html_e( ' selected all pages', 'zestatix' ) ?>' )
                } else {
                    el.prop( 'title', '<?php esc_html_e( 'SUBDIRECTORIES: DISABLED', 'zestatix' ) ?>' );

                    input_track_on.prop( 'placeholder', ' <?php esc_html_e( ' only home page', 'zestatix' ) ?>' )
                }
            } )

            .on( 'click', '.btn-add-unit-track-on', function() {
                $( this ).before( Cards.get( $( this ).parents( '.card-zestatix' ).get( 0 ) ).track_on() )
            } )

            .on( 'change', '.select-width-zestatix', function() {
                $( this ).siblings( '.custom-width-zestatix' ).toggle( $( this ).val() == 'custom width' );
            } )

            .on( 'input', '.selector-element-zestatix', e => {
                Cards.get( $( e.target ).parents( '.card-zestatix' ).get( 0 ) ).selector.action()
            } )

            .on( 'click', '.btn-remove-unit-track-on-zestatix', e => {
                const el = $( e.target );

                if ( el.parents( '.unit-content-zestatix' ).find( '.unit-track-on-zestatix:visible' ).length > 1 ) {
                    el.parents( '.unit-track-on-zestatix' ).fadeOut( 300, function() {
                        $( this ).remove();
                    } )
                }
            } )

            .on( 'click', '.correct-this-zestatix', e => {
                let input = $( e.target ).parents( '.unit-zestatix' ).find( '.selector-element-zestatix' ),

                // заменить ' на  \'
                replace_val = input.val().replace( /\\'|'/g, "\\'" );

                input.val( replace_val );

                Cards.get( $( e.target ).parents( '.card-zestatix' ).get( 0 ) ).selector.action()
            } )

            .on( 'click', '.control-selector-zestatix .dashicons-trash', e => {
                let input = $( e.target ).parents( '.unit-content-zestatix' ).find( '.selector-element-zestatix' );

                input.val( '' );

                Cards.get( $( e.target ).parents( '.card-zestatix' ).get( 0 ) ).selector.action()
            } )

            .on( 'click', '.btn-example-zestatix', e => {
                const el = $( e.target )

                if ( !el.siblings( '.table-example-zestatix' ).length ) {
                    const table = $( '.table-example-zestatix:first' ).clone();

                    table.hide().insertAfter( el )
                }

                el.siblings( '.table-example-zestatix' ).fadeToggle( 400, 'linear' );
            } )

            .on( 'click', '.toggler-legend-zestatix', e => {
                const el = $( e.target ).closest( '.toggler-legend-zestatix' );

                const check = el.toggleClass( 'active-zestatix' ).hasClass( 'active-zestatix' );

                const hidden_el = el.parents( '.legend-zestatix' ).find( '.unit-legend-zestatix:not( .active-zestatix )' );

                if ( check ) {
                    hidden_el.slideDown( 500 )
                } else {
                    hidden_el.slideUp( 500 )
                }
            } )

            .on( 'click', '.btn-details-zestatix', e => {
                Cards.get( $( e.target ).parents( '.card-zestatix' ).get( 0 ) ).details()
            } )

            .on( 'click', '.btn-c-h-zestatix', e => {
                const el = $( e.target ).closest( '.btn-c-h-zestatix' );

                el.parents( '.table-control-history-zestatix' ).siblings( '.tr-del-zestatix' ).toggle( 'slow', 'linear' );
            } )

            .on( 'click', '.btn-del-ok-zestatix', e => {
                toggle_preloader();

                const card = $( e.target ).parents( '.card-zestatix' );

                const selector = encode_href( card.find( '.selector-element-zestatix' ).val() );

                $.post( ajaxurl,
                    {
                        action: 'clear_history_zestatix',
                        selector: selector
                    },

                    () => {
                        Cards.get( $( e.target ).parents( '.card-zestatix' ).get( 0 ) ).remove_history()

                        toggle_preloader()
                    }
                )
            } )

            .on( 'input', 'input[name ^= "browser_width/"]', e => {
                const el = $( e.target );

                const length = el.val().length;

                const size = ( length < 5 ) ? 5 : 1 + length;

                el.attr( 'size', size );
                }
            )

        const animate_btn = {
            details: new ToggleLayer({
                el: document.querySelectorAll( '.btn-details-zestatix' ),
                layer: '<?php esc_html_e( 'HIDE DETAILS', 'zestatix' ) ?>',
                delay: 0
            }),

            clear_history: new ToggleLayer({
                el: document.querySelectorAll( '.btn-c-h-zestatix' ),
                layer: '<?php esc_html_e( 'CANCEL', 'zestatix' ) ?>',
                delay: 0
            }),

            example: new ToggleLayer({
                el: document.querySelectorAll( '.btn-example-zestatix' ),
                layer: '<?php esc_html_e( 'HIDE EXAMPLE', 'zestatix' ) ?>',
                delay: 0
            })
        }

    } )
</script>