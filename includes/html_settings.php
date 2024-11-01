<?php if ( !defined( 'ABSPATH' ) && !current_user_can( 'edit_plugins' ) ) exit ?>

<style>
	#zeStatix label {
		cursor: default;
	}
	body {
		background: #e3e6ea !important;
	}
	#preloader-zestatix {
		width: 100vw;
		height: 100vh;
		position: fixed;
		z-index: 999999;
		left: 0;
		top: 0;
		background-color: rgb(0, 0, 0, 50%);
	}
	#infinity-preloader-zestatix {
		width: 130px;
		height: 70px;
		z-index: 2;
	}
	#infinity-preloader-zestatix .bg-zestatix div,
	#infinity-preloader-zestatix > .fg-zestatix > div > div {
		width: 70px;
		height: 70px;
		border: 10px solid #aaa;
		-webkit-box-sizing: border-box;
    	box-sizing: border-box;
		-webkit-border-radius: 50%;
    	border-radius: 50%;
		position: absolute;
	}
	#infinity-preloader-zestatix .right-bg-zestatix {
		-webkit-transform: translate( 100%, 0 );
    	-ms-transform: translate( 100%, 0 );
    	transform: translate( 100%, 0 );
		left: -10px;
	}
	#infinity-preloader-zestatix > .fg-zestatix > div > div {
		border-color: red red transparent transparent;
		-webkit-transform: rotate( 135deg );
    	-ms-transform: rotate( 135deg );
    	transform: rotate( 135deg );
		-webkit-animation: spin-zestatix 1s linear infinite;
    	animation: spin-zestatix 1s linear infinite;
		position: static;
	}
	#infinity-preloader-zestatix > .fg-zestatix > div {
		clip: rect( 0, 70px, 35px, 0 );
		position: absolute;
	}
	#infinity-preloader-zestatix > .fg-zestatix > .bottom-right-rect-zestatix {
		left: -10px;
		-webkit-transform: translateX( 100% ) scale( 1, -1 );
    	-ms-transform: translateX( 100% ) scale( 1, -1 );
    	transform: translateX( 100% ) scale( 1, -1 );
	}
	#infinity-preloader-zestatix > .fg-zestatix > .bottom-right-rect-zestatix > div {
		-webkit-animation-delay: 0.25s;
    	animation-delay: 0.25s;
	}
	#infinity-preloader-zestatix > .fg-zestatix > .top-right-rect-zestatix {
		left: -10px;
		-webkit-transform: translateX( 100% ) scale( -1, 1 );
    	-ms-transform: translateX( 100% ) scale( -1, 1 );
    	transform: translateX( 100% ) scale( -1, 1 );
	}
	#infinity-preloader-zestatix > .fg-zestatix > .top-right-rect-zestatix > div {
		-webkit-animation-delay: 0.5s;
    	animation-delay: 0.5s;
	}
	#infinity-preloader-zestatix > .fg-zestatix > .bottom-left-rect-zestatix {
		-webkit-transform: scale( -1 );
    	-ms-transform: scale( -1 );
    	transform: scale( -1 );
	}
	#infinity-preloader-zestatix > .fg-zestatix > .bottom-left-rect-zestatix > div {
		-webkit-animation-delay: 0.75s;
    	animation-delay: 0.75s;
	}
	#infinity-preloader-zestatix > .fg-zestatix {
		-webkit-filter: drop-shadow( 0 0 6px red );
    	filter: drop-shadow( 0 0 6px red );
	}
	@-webkit-keyframes spin-zestatix {
		50%, 100% {
			-webkit-transform: rotate( 495deg );
		}
	}
	@keyframes spin-zestatix {
		50%, 100% {
        	transform: rotate( 495deg );
		}
	}
	#zeStatix input, #zeStatix input[type=text]:focus {
		background-image: none;
		background-color: transparent;
		-webkit-box-shadow: none;
		-moz-box-shadow: none;
		box-shadow: none;
		outline: none
	}
	#zeStatix input::-webkit-input-placeholder, #zeStatix textarea::-webkit-input-placeholder {
		opacity: .4;
		-webkit-transition: 150ms opacity ease-in-out;
		-o-transition: 150ms opacity ease-in-out;
		transition: 150ms opacity ease-in-out;
	}
	#zeStatix input::-ms-input-placeholder, #zeStatix textarea::-ms-input-placeholder {
		opacity: .4;
		-webkit-transition: 150ms opacity ease-in-out;
		-o-transition: 150ms opacity ease-in-out;
		-ms-transition: 150ms opacity ease-in-out;
		transition: 150ms opacity ease-in-out;
	}
	#zeStatix input::-moz-placeholder, #zeStatix textarea::-moz-placeholder {
		opacity: .4;
		-webkit-transition: 150ms opacity ease-in-out;
		-o-transition: 150ms opacity ease-in-out;
		-moz-transition: 150ms opacity ease-in-out;
		transition: 150ms opacity ease-in-out;
	}
	#zeStatix input:-ms-input-placeholder, #zeStatix textarea:-ms-input-placeholder {
		opacity: .4;
		-webkit-transition: 150ms opacity ease-in-out;
		-o-transition: 150ms opacity ease-in-out;
		-ms-transition: 150ms opacity ease-in-out;
		transition: 150ms opacity ease-in-out;
	}
	#zeStatix input::placeholder, #zeStatix textarea::placeholder {
		opacity: .4;
		-webkit-transition: 150ms opacity ease-in-out;
		-o-transition: 150ms opacity ease-in-out;
		transition: 150ms opacity ease-in-out;
	}
	#zeStatix input:focus::-webkit-input-placeholder, #zeStatix textarea:focus::-webkit-input-placeholder {
		opacity: 0;
		-webkit-transition: 150ms opacity ease-in-out;
		-o-transition: 150ms opacity ease-in-out;
		transition: 150ms opacity ease-in-out;
	}
	#zeStatix input:focus::-moz-placeholder, #zeStatix textarea:focus::-moz-placeholder {
		opacity: 0;
		-webkit-transition: 150ms opacity ease-in-out;
		-o-transition: 150ms opacity ease-in-out;
		-moz-transition: 150ms opacity ease-in-out;
		transition: 150ms opacity ease-in-out;
	}
	#zeStatix {
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		font-size: 14px;
		font-weight: 400;
		color: #2f2f2f;
		margin-right: 20px;
	}
	#zeStatix .button-zestatix {
		text-align: center;
		background-color: #fff;
		color: #454749;
		border: solid 1px #808288;
    	border-radius: 2px;
		max-width: 300px;
	}
	button.button-zestatix:not( #sticky-zestatix button ),
	#zeStatix select {
		width: 15em;
		height: 40px;
		padding: 0px;
	}
	#zeStatix select {
		text-align: center;
		font-size: 1em;
	}
	button.button-zestatix span {
		vertical-align: middle;
		line-height: 100%;
	}
	.card-header-zestatix {
		width: 100%;
		height: 100px;
		position: relative;
		z-index: 1;
	}
	#parent-toggler-zestatix {
		position: absolute;
		left: 0;
		max-width: 700px;
		min-width: 600px;
		width: 100%;
		height: 100%;
	}
	#toggler-zestatix {
    	cursor: pointer;
		left: 10px;
	}
	.toggler-zestatix-on {
		background-image: url( '<?= plugins_url( 'zestatix/img/toggle.png' ) ?>' );
		background-repeat: no-repeat;
		background-size: 600%;
		height: 0;
		width: 120px;
		padding-bottom: 7.5%;
		-webkit-animation: toggle-on-zestatix .3s steps( 5 );
		animation: toggle-on-zestatix .3s steps( 5 );
		background-position: 0% 0%;
	}
	.toggler-zestatix-off {
		-webkit-animation: toggle-off-zestatix .3s steps( 5 );
    	animation: toggle-off-zestatix .3s steps( 5 );
		background-position: 100% 0%;
	}
	@-webkit-keyframes toggle-on-zestatix {
	    from {
			background-position: 100%;
		}
	    to {
			background-position:    0%;
		}
	}
	@keyframes toggle-on-zestatix {
	    from {
			background-position: 100%;
		}
	    to {
			background-position:    0%;
		}
	}
	@-webkit-keyframes toggle-off-zestatix {
	    from {
			background-position:    0%;
		}
	    to {
			background-position: 100%;
		}
	}
	@keyframes toggle-off-zestatix {
	    from {
			background-position:    0%;
		}
	    to {
			background-position: 100%;
		}
	}
	.no-animation-zestatix {
		animation-timing-function: steps(1);
	}
	#logo-zestatix a {
		height: 145px;
		width: 180px;
		z-index: 3;
	}
	#logo-zestatix a:focus {
    	box-shadow: none;
	}
	#logo-img-zestatix {
		position: absolute;
		background-image: url( '<?= plugins_url( 'zestatix/img/logo.png' ) ?>' );
		background-repeat: no-repeat;
		background-size: 900px 145px;
		background-position: -720px 0px;
		height: 145px;
		width: 180px;
		z-index: 1;
	}
	#logo-img-zestatix.logo-anim-on-zestatix {
		-webkit-animation: on-zestatix .3s steps( 4 );
    	animation: on-zestatix .3s steps( 4 );
	}
	@-webkit-keyframes on-zestatix {
		from {
			background-position: 0px;
		}
		to {
			background-position: -720px;
		}
	}
	@keyframes on-zestatix {
		from {
			background-position: 0px;
		}
		to {
			background-position: -720px;
		}
	}
	#logo-img-zestatix.logo-anim-off-zestatix {
		-webkit-animation: off-zestatix .3s steps( 4 );
    	animation: off-zestatix .3s steps( 4 );
		background-position: 0px 0px;
	}
	@-webkit-keyframes off-zestatix {
		from {
			background-position: -720px;
		}
		to {
			background-position: 0px;
		}
	}
	@keyframes off-zestatix {
		from {
			background-position: -720px;
		}
		to {
			background-position: 0px;
		}
	}
	#zeStatix #logo-zestatix.center-flex-zestatix {
		flex-direction: column;
		z-index: 2;
		user-select: none;
	}
	#zeStatix #text-zestatix span {
		position: relative;
		color: #fff;
		font-size: 2em;
		vertical-align: super;
		z-index: 2;
	}
	#zeStatix #version-zestatix {
		align-self: flex-end;
		position: absolute;
		padding-top: 3em;
		color: #fff;
		font-size: .75em;
		z-index: 2;
	}
	.container-zestatix {
		width: 100%;
		max-width: 600px;
		height: 90px;
		flex-wrap: wrap;
		justify-content: space-around !important;
		padding: 10px;
	}
	#zeStatix input.name-card-zestatix {
		text-align: center;
		width: 100%;
		height: 34px;
	}
	#zeStatix .correct-this-zestatix {
		font-size: inherit;
		margin-left: 10px;
	}
	#sticky-zestatix {
		float: right;
		position: -webkit-sticky;
		position: sticky;
		margin: 5px 10px -45px 0px;
		z-index: 10;
	}
	#sticky-zestatix button {
		position: relative;
		width: 40px;
		height: 40px;
		vertical-align: middle;
	}
	#zeStatix #sticky-zestatix button span {
		font-size: 20px;
	}
	#sticky-zestatix > button {
		margin-left: 5px;
	}
	#body-zestatix {
		display: block;
		padding: 0px;
		margin: 10px 0px;
		max-width: 100%;
		opacity: 0;
		transition: opacity .4s;
	}
	.opacity-zestatix {
		opacity: 1 !important;
	}
	.card-zestatix {
		overflow: hidden;
		border-bottom: 1px solid #d7d7d7;
		position: relative;
		padding: 20px;
	}
	.card-zestatix > *:not( .stat-zestatix ) {
		margin-bottom: 1.5em;
	}
	.unit-zestatix {
		position: relative;
	}
	.unit-zestatix > div {
		padding-left: 2em;
	}
	.setting-zestatix > .unit-zestatix:not(:last-child) {
		margin-bottom: 2em;
	}
	#body-zestatix > [ class ^= card- ]:nth-of-type( even ):not( .wrong-zestatix ) {
		background-color: #fff;
	}
	#body-zestatix > [ class ^= card- ]:nth-of-type( odd ):not( .wrong-zestatix ) {
		background-color: #eff2f5;
	}
	.card-zestatix.wrong-zestatix {
    	background-color: #f8d7da59 !important;
	}
	.control-element-zestatix > .dashicons {
		display: inline-flex;
		font-size: 2em;
		color: #fff;
		cursor: pointer;
		background-color: #646c74;
		-webkit-border-radius: 50px;
		border-radius: 50%;
		transition: background-color .3s;
		width: 40px;
		height: 40px;
	}
	.control-element-zestatix > .dashicons:not( :last-child ) {
		margin-right: 10px;
	}
	#zeStatix .control-element-zestatix .dashicons.active-zestatix {
		background-color: #31bcfb;
	}
	#zeStatix .border-bottom-zestatix {
		background-color: transparent;
		border: none;
		-webkit-border-radius: 0px;
		border-radius: 0px;
		border-bottom: 2px solid #ced4da;
	}
	#zeStatix .border-bottom-zestatix:focus {
		border: none;
		border-bottom: 2px solid #ced4da;
	}
	#zeStatix textarea {
		resize: none;
		word-break: break-all;
		-webkit-box-shadow: none;
		box-shadow: none;
		border-radius: 4px;
		padding: 6px;
	}
	#zeStatix select:focus {
		border-color: inherit;
		box-shadow: none;
	}
	.custom-width-zestatix > *:not( :last-child ) {
		margin-bottom: 1em;
	}
	#zeStatix label.unit-label-zestatix {
		display: block;
    	font-weight: 600;
		font-size: 1.2em;
  		color: #505152;
	}
	.unit-content-zestatix > * {
		margin-top: 1em;
	}
	.selector-zestatix > div > *:not(:last-child) {
		margin-bottom: 1em;
	}
	.unit-track-on-zestatix {
		display: flex;
		align-items: center;
		flex-basis: 100%;
	}
	.control-track-on-zestatix {
		display: flex;
	}
	#zeStatix .control-track-on-zestatix > span {
		cursor: pointer;
	}
	#zeStatix .control-track-on-zestatix span {
		margin-left: .75em;
	}
	#zeStatix .input-track-on-zestatix {
		width: 100%;
		padding: 0px;
		height: 22px;
	}
	#zeStatix .selector-element-zestatix {
		width: 100%;
		border: 2px solid #ced4da;
		border-color: #b8daff;
	}
	.wrong-selector-zestatix {
		border-color: #f5c6cb !important;
	}
	.control-selector-zestatix {
		height: 2.5em;
		text-align: center;
		background-color: #dde3e8;
		border-radius: 4px;
	}
	.control-selector-zestatix .dashicons-trash {
		cursor: pointer;
	}
	#zeStatix .alert-zestatix {
		text-align: center;
		color: red;
		padding: 5px;
		background-color: #f9aeae;
		border-radius: 4px;
	}
	.table-zestatix {
		width: 100%;
		border-collapse: collapse;
		border: none;
	}
	.table-zestatix td {
    	word-break: break-word;
		padding: 5px;
	}
	.table-zestatix tr:nth-of-type(odd) {
	  	background-color: #dde3e8;
	}
	.table-example-zestatix {
		table-layout: fixed;
	}
	.table-details-zestatix td {
		width: 50%
	}
	.table-details-zestatix tr:nth-of-type(-n+4) td:first-child {
		text-align: right;
	}
	.table-details-zestatix, .table-details-click-zestatix {
		table-layout: auto;
	}
	.table-details-click-zestatix {
		margin-top: 15px;
	}
	.table-details-click-zestatix tr td {
    	text-align: center;
	}
	.table-details-click-zestatix tr td:nth-child( 7 ) {
		word-break: break-word;
	}
	.table-details-click-zestatix tr:last-of-type {
		border-bottom: 1px solid #dee2e6;
	}
	.tr-del-zestatix {
    	text-align: center;
	}
	.del-detail-zestatix label {
    	display: block;
		margin: 10px 0px;
	}
	.charts-zestatix {
		display: -webkit-box;
		display: -webkit-flex;
		display: -ms-flexbox;
		display: flex;
		-webkit-flex-wrap: wrap;
		-ms-flex-wrap: wrap;
		flex-wrap: wrap;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
		-ms-flex-pack: center;
		justify-content: center;
		word-break: break-all;
		padding: 15px;
	}
	.charts-zestatix > * {
		text-align: center;
		display: -webkit-box;
		display: -webkit-flex;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-webkit-flex-direction: column;
		-ms-flex-direction: column;
		flex-direction: column;
		-webkit-box-align: center;
    	-ms-flex-align: center;
	}
	.unit-chart-zestatix {
		padding: 1em 0;
	}
	.charts-zestatix canvas {
		width: 300px;
		height: auto;
	}
	.url-chart-zestatix {
		width: 100%;
	}
	.unit-legend-zestatix {
		display: -webkit-box;
		display: -webkit-flex;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-align: center;
		-webkit-align-items: center;
		-ms-flex-align: center;
		align-items: center;
		padding: 5px;
		line-height: 1.5;
	}
	.color-legend-zestatix {
		-webkit-flex-shrink: 0;
		-ms-flex-negative: 0;
		flex-shrink: 0;
		width: 20px;
		height: 20px;
	}
	.legend-stat-zestatix {
		-webkit-flex-shrink: 0;
		-ms-flex-negative: 0;
		flex-shrink: 0;
		margin: 0px 10px;
	}
	.legend-key-zestatix {
		-webkit-flex-shrink: 1;
		-ms-flex-negative: 1;
		flex-shrink: 1;
	}
	.legend-zestatix > *:not(:first-child) {
		border-top: 1px solid #dee2e6;
	}
	.unit-legend-zestatix:not( .active-zestatix ) {
		display: none;
	}
	.unit-legend-zestatix[style *= 'display: block'] {
		display: flex !important;
	}
	.toggler-legend-zestatix {
		width: 100%;
		cursor: pointer;
		-webkit-border-radius: 0px 0px 5px 5px;
    	border-radius: 0px 0px 5px 5px;
	}
	.toggler-legend-zestatix,
	.toggler-legend-zestatix .dashicons-arrow-down-alt2 {
		-webkit-transition: all .3s linear;
		-o-transition: all .3s linear;
		transition: all .3s linear;
	}
	.toggler-legend-zestatix.active-zestatix .dashicons-arrow-down-alt2 {
		-webkit-transform: rotate( 180deg );
		-ms-transform: rotate( 180deg );
		transform: rotate( 180deg );
	}
	.toggler-legend-zestatix:hover {
		background-color: #f3f3f6;
	}
	.popup-zestatix {
		background-color: #e3e7ec;
		overflow-y: auto;
		z-index: 999999;
		position: fixed;
		transform: translate( -50%, -50% );
		left: 50%;
		top: 50%;
		max-width: 500px;
		width: 100%;
		height: 80%;
		max-height: 500px;
	}
	.popup-header-zestatix {
		padding: 10px 15px;
		background-color: #dadde2;
		position: sticky;
    	top: 0;
	}
	#zeStatix .popup-header-zestatix > * {
		font-size: 1.4em;
		font-weight: 600;
		user-select: none;
	}
	.popup-close-zestatix {
		float: right;
		cursor: pointer;
	}
	.popup-body-zestatix {
		padding: 15px 30px;
	}
	.popup-tema-zestatix:not(:last-child) {
		margin-bottom: 10px;
	}
	.popup-descript-img {
		width: 100%
	}
	#zeStatix .description-name-zestatix {
		display: block;
		text-align: center;
		cursor: pointer;
	}
	#zeStatix .active-description-name-zestatix {
		color: #222;
		font-weight: 500;
	}
	.parent-img-d-zestatix {
		text-align: center;
	}
	.parent-img-d-zestatix span {
		display: block;
		margin-top: 5px;
		font-weight: 500;
	}
	.description-zestatix > *:not(:last-child) {
		margin-bottom: 10px;
	}
	.parent-img-d-zestatix {
		margin-top: 10px;
	}
	.text-description-zestatix {
		line-height: 1.5;
		margin-left: 20px;
	}
	.text-description-zestatix p {
		margin: 10px 0px;
	}
	.text-description-zestatix ul {
		list-style: none;
		margin-top: 10px;
	}
	.text-description-zestatix ul ul {
		margin-left: 20px;
	}
	.text-description-zestatix li::before {
		content: '-';
		margin-right: 5px;
	}
	.number-d-zestatix {
		display: inline-block;
		text-align: center;
		min-width: 26px;
		line-height: 26px;
		background: #6fff00;
		margin-right: 5px;
	}
	#zeStatix .navg-label-zestatix {
		display: block;
		font-size: 1.2em;
		line-height: 1.5;
		cursor: pointer;
	}
	#overley-zestatix {
		display: none;
		position: fixed;
		width: 100vw;
		height: 100vh;
		left: 0;
		top: 0;
		bottom: 0;
		right: 0;
		z-index: 99999;
		background-color: rgba( 0, 0, 0, 50% );
	}
	#zeStatix span.blink-cursor-zestatix {
		font-size: 2em;
		opacity: 1;
		-webkit-animation: blink-zestatix 0.7s infinite;
		-moz-animation: blink-zestatix 0.7s infinite;
		animation: blink-zestatix 0.7s infinite;
	}
	@keyframes blink-zestatix {
		50% {
			opacity: 0;
		}
		100% {
			opacity: 1;
		}
	}
	@-webkit-keyframes blink-zestatix {
		50% {
			opacity: 0;
		}
		100% {
			opacity: 1;
		}
	}
	#zeStatix .visible-charts-zestatix, .alert-zestatix, .tr-del-zestatix, .description-zestatix, #wpfooter, .table-example-zestatix, #navigator-popup-zestatix, #description-popup-zestatix {
		display: none;
	}
	#zeStatix .subdirectories-zestatix.active-zestatix {
		color: #007bff;
	}
	.none-zestatix {
		display: none;
	}
	.center-flex-zestatix {
		display: flex;
		justify-content: center;
		align-items: center;
		margin: 0px auto;
	}
	.center-y-zestatix {
		position: absolute;
		top: 50%;
		-webkit-transform: translateY( -50% );
		-ms-transform: translateY( -50% );
		transform: translateY( -50% );
	}
	#zeStatix .center-x-y-zestatix {
		position: absolute;
		top: 50%;
		left: 50%;
		-webkit-transform: translate( -50%,-50% );
		-ms-transform: translate( -50%,-50% );
		transform: translate( -50%,-50% );
	}
	@media (max-width: 782px) {
		body.auto-fold:not( .sticky-menu ) #wpcontent {
			padding-left: 0px;
		}
		#zeStatix {
			margin-right: 0px;
		}
		#logo-zestatix {
			display: none;
		}
		input,
		textarea,
		label,
		button,
		select {
			font-size: 1em;
		}
		#sticky-zestatix {
			margin-right: 5px;
		}
		#zeStatix .input-track-on-zestatix::-webkit-input-placeholder {
			content-visibility: hidden;
    	}
		#zeStatix .input-track-on-zestatix::-ms-input-placeholder {
			content-visibility: hidden;
		}
		#zeStatix .input-track-on-zestatix::-moz-placeholder {
			content-visibility: hidden;
		}
		#zeStatix .input-track-on-zestatix:-ms-input-placeholder {
			content-visibility: hidden;
		}
		#zeStatix .input-track-on-zestatix::placeholder {
			content-visibility: hidden;
		}
		.card-header-zestatix  {
			height: 80px;
		}
		.text-description-zestatix {
			margin-left: 0em;
		}
		.charts-zestatix {
			padding: 0;
		}
	}
	@media (max-width: 420px) {
		.card-zestatix {
			padding: 1em;
		}
		.unit-zestatix > div {
			padding-left: 0;
		}
		.stat-zestatix {
			display: flex;
			justify-content: center;
			align-items: center;
		}
	}
</style>

<div id="zeStatix">
	<div id="body-zestatix">
		<div id="sticky-zestatix">
			<button id="btn-save-zestatix" class="button-zestatix" title="<?php esc_html_e( 'SAVE', 'zestatix' ) ?>">
				<span class="center-x-y-zestatix dashicons dashicons-yes"></span>
			</button>
			<button id="btn-navg-zestatix" class="button-zestatix" title="<?php esc_html_e( 'NAVIGATOR', 'zestatix' ) ?>">
				<span class="center-x-y-zestatix dashicons dashicons-location"></span>
			</button>
			<button id="btn-descrption-zestatix" class="button-zestatix" title="<?php esc_html_e( 'DESCRIPTION', 'zestatix' ) ?>">
				<span class="center-x-y-zestatix">?</span>
			</button>
		</div>
		<div class="card-header-zestatix center-flex-zestatix">
			<div id="parent-toggler-zestatix">
				<div id="toggler-zestatix" class="toggler-zestatix-on center-y-zestatix no-animation-zestatix" alt="toggler"></div>
				<input type="hidden" id="toggler-value-zestatix" value="<?= TOGGLE_ZESTATIX ?>">
			</div>
			<div id="logo-zestatix" class="center-flex-zestatix">
				<div id="logo-img-zestatix"></div>
				<div id="text-zestatix">
					<span>zeStatix</span>
				</div>
				<div id="version-zestatix"><?php esc_html_e( 'version', 'zestatix' ) ?> <?= VERSION_ZESTATIX ?></div>
				<a class="center-x-y-zestatix" href="http://x9618502.beget.tech/" target="_blank"></a>
			</div>
		</div>
		<div class="card-control-zestatix center-flex-zestatix">
			<div class="container-zestatix center-flex-zestatix">
				<button id="btn-select-element-zestatix" class="button-zestatix"><span><?php esc_html_e( 'SELECT ELEMENT', 'zestatix' ) ?></span></button>
				<button id="btn_add_element_zestatix" class="button-zestatix"><span><?php esc_html_e( 'PRINT ELEMENT', 'zestatix' ) ?></span></button>
			</div>
		</div>
	</div>
	<div id="preloader-zestatix">
		<div id="infinity-preloader-zestatix" class="center-x-y-zestatix">
			<div class="bg-zestatix">
				<div class="left-bg-zestatix"></div>
				<div class="right-bg-zestatix"></div>
			</div>
			<div class="fg-zestatix">
				<div class="top-left-rect-zestatix">
					<div></div>
				</div>
				<div class="bottom-right-rect-zestatix">
					<div></div>
				</div>
				<div class="top-right-rect-zestatix">
					<div></div>
				</div>
				<div class="bottom-left-rect-zestatix">
					<div></div>
				</div>
			</div>
		</div>
	</div>
	<div id="navigator-popup-zestatix" class="root-popup-zestatix">
		<div class="popup-zestatix">
			<div class="popup-header-zestatix">
				<span class="popup-title-zestatix">NAVIGATOR</span>
				<span class="popup-close-zestatix">&times;</span>
			</div>
			<div class="popup-body-zestatix"></div>
		</div>
	</div>
	<div id="description-popup-zestatix" class="root-popup-zestatix">
		<div class="popup-zestatix">
			<div class="popup-header-zestatix">
				<span class="popup-title-zestatix">DESCRIPTION</span>
				<span class="popup-close-zestatix">&times;</span>
			</div>
			<div class="popup-body-zestatix">
				<div class="popup-tema-zestatix">
					<label class="description-name-zestatix">ABOUT zeStatix</label>
					<div class="description-zestatix">
						<div class="text-description-zestatix">
							<p>zeStatix is counter clicks for the specified HTML elements.</p>
							<p><b>Features</b></p>
							<ul>
								<li>No additional servers are used.</li>
								<li>It is possible to select any item on the page.</li>
								<li>Statistics of elements are kept by the number of clicks:
									<ul>
										<li>click</li>
										<li>views</li>
										<li>click time</li>
										<li>location</li>
										<li>IP</li>
										<li>device</li>
										<li>width device</li>
										<li>width browser</li>
									</ul>
								</li>
							</ul>
							<p><b>Why track click statistics</b></p>
							<ul>
								<li>Proper Design page.</li>
								<li>Web analytics.</li>
							</ul>
							<p><b>Involved</b></p>
							<ul>
								<li>WordPress</li>
								<li>jQuery</li>
								<li>geoPlugin</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="popup-tema-zestatix">
					<label class="description-name-zestatix">CREATE FIRST ELEMENT</label>
					<div class="description-zestatix">
						<div class="text-description-zestatix">
							<p>To start tracking element statistics, use the "SELECT ELEMENT" button (section a. Image 1.2 item 1) or the "PRINT ELEMENT" button (section a. Image 1.2 item 2).</p>
							<p>If selected "SELECT ELEMENT".
							Hover over the desired element in PREVIEWER (section b. Image 2.1, item 2) to confirm the selection, leave the mouse cursor on the element (2.5 sec) and confirm the selection with the "YES" button.</p>
							<p>An element needs to be observed only on certain pages, use the "TRACK ON" menu (section b. Image 2.1, item 8).</p>
							<p>You need to refine an element, use the optional jQuery selectors.</p>
							<p>You need to track an element only on devices with a certain width, use the "BROWSER WIDTH" menu.</p>
							<p>If the item is added by "PRINT ELEMENT".
							Enter the jQuery selector in the text box (section c. Image 3.1, item 10).</p>
							<p>If an element needs to be observed only on certain pages, use the “TRACK ON” menu (section c. Image 3.1, item 2).</p>
							<p>If you need to track an element only on devices with a certain width, use the "BROWSER WIDTH" menu (section c. Image 3.1, item 7).</p>
						</div>
					</div>
				</div>
				<div class="popup-tema-zestatix">
					<label class="description-name-zestatix">a. MAIN SETTINGS</label>
					<div class="description-zestatix">
						<div class="parent-img-d-zestatix">
							<img src="<?= plugins_url( 'zestatix/img/description/main-settings-1.jpg' ) ?>" class="popup-descript-img">
							<span>1.1</span>
						</div>
						<div class="text-description-zestatix">
							<span class="number-d-zestatix">1</span>Toggle enable or disable plugin.
						</div>
						<div class="text-description-zestatix">
							<span class="number-d-zestatix">2</span>Save settings button.
						</div>
						<div class="text-description-zestatix">
							<span class="number-d-zestatix">3</span>Description.
						</div>
					</div>
				<div class="description-zestatix">
					<div class="parent-img-d-zestatix">
						<img src="<?= plugins_url( 'zestatix/img/description/main-settings-2.jpg' ) ?>" class="popup-descript-img">
						<span>1.2</span>
						</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">1</span>
						Go in menu of selection element. (section b).
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">2</span>
						Add form to element (section c).
					</div>
					</div>
			</div>
			<div class="popup-tema-zestatix">
				<label class="description-name-zestatix">b. SELECT ELEMENT</label>
				<div class="description-zestatix">
					<div class="parent-img-d-zestatix">
						<img src="<?= plugins_url( 'zestatix/img/description/select-element-1.jpg' ) ?>" class="popup-descript-img">
						<span>2.1</span>
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">1</span>PANEL.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">2</span>PREVIEWER.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">3</span>Go back to settings page.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">4</span>Save settings button.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">5</span>Name for element. Optional setting.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">6</span>
							Add or remove identification items for more correct results.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">7</span>
							Selector (jQuery) of the selected element (10).
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">8</span>
							Selection of pages on which the element will be monitored.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">9</span>
							Choose browser width at which to fix click.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">10</span>
							Selected element. To select an element, hover over  and click.
					</div>
				</div>
			</div>
			<div class="popup-tema-zestatix">
				<label class="description-name-zestatix">c. FORM FOR ELEMENT</label>
				<div class="description-zestatix">
					<div class="parent-img-d-zestatix">
						<img src="<?= plugins_url( 'zestatix/img/description/card-element-1.jpg' ) ?>" class="popup-descript-img">
						<span>3.1</span>
						</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">1</span>
							Delete form.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">2</span>
							Toggle pause or tracking.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">3</span>
							Toggle show or hide settings.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">4</span>
							Toggle show or hide charts.
					</div>
				</div>
				<div class="description-zestatix">
					<div class="parent-img-d-zestatix">
						<img src="<?= plugins_url( 'zestatix/img/description/card-element-2.jpg' ) ?>" class="popup-descript-img">
						<span>3.2</span>
						</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">1</span>
							Name for element. Optional setting.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">2</span>
							Tracking settings.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">3</span>
							Address page on which to monitor element.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">4</span>
							Button allows you to add all subdirectories to the list of monitored.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">5</span>
							Remove page from list.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">6</span>
							Add page to tracking list.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">7</span>
							Choose browser width at which to fix click.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">8</span>
							Any width - fixation at any width<br>
					Custom width - you can specify the width of the browser window.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">9</span>
							min width - browser window width not less than specified.<br>
							max width - browser window width no more than specified.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">10</span>
							Selector (jQuery) needed for element identification.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">11</span>
							The number of characters of the selector and the maximum number of characters. Trash can icon used to clear selector.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">12</span>
							Show or hide auxiliary table.
					</div>
				</div>
				<div class="description-zestatix">
					<div class="parent-img-d-zestatix">
						<img src="<?= plugins_url( 'zestatix/img/description/card-element-3.jpg' ) ?>" class="popup-descript-img">
						<span>3.3</span>
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">1</span>
							Status tracked or paused. Switches see image 3.1 [ 2 ].
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">2</span>
							Total clicks.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">3</span>
							Number of clicks of users with different IP.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">4</span>
							Element creation date.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">5</span>
							Ring chart for visitor countries.
						</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">6</span>
							Legend for a pie chart - color on the chart, quantity, percentage, location.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">7</span>
							Ring chart for visitor device.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">8</span>
							Ring chart for pages click event.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">9</span>
							Switch show or hide statistics details table.
					</div>
					<div class="text-description-zestatix">
						<span class="number-d-zestatix">10</span>
							Clear statistics history.
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="overley-zestatix"></div>

<?php require_once( INCLUDES_ZESTATIX . 'table_example.php' ); ?>