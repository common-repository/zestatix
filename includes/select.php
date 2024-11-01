<?php if ( !defined( 'ABSPATH' ) && !current_user_can( 'edit_plugins' ) ) exit;

add_filter('show_admin_bar', '__return_false');

wp_enqueue_style( 'dashicons' );

add_action('wp_enqueue_scripts', 'include_jquery' );

add_action( 'wp_print_styles', 'style' );

add_action( 'wp_print_footer_scripts', 'js_select' );

function include_jquery() {
	wp_enqueue_script('jquery');
}

function style() { ?>
	<style>
		:root {
			--panel-width: 300px;
		}
		#root-zestatix, #root-zestatix * {
			all: initial;
		}
		#root-zestatix .table-example-zestatix {
			display: none;
		}
		#root-zestatix .center-x-y-zestatix {
			position: absolute;
			top: 50%;
			left: 50%;
			-webkit-transform: translate( -50%,-50% );
			-ms-transform: translate( -50%,-50% );
			transform: translate( -50%,-50% );
		}
		.this-el-zestatix {
			cursor: progress !important;
			background-color: transparent;
			color: #333 !important;
			z-index: 99998 !important;
		}
		.this-el-zestatix:hover {
			animation: color-animation 3s linear 1;
		}
		@keyframes color-animation {
			1% {
				outline: 1px solid #1d66bb38;
			}
			10% {
				outline-color: #1d66bb;
			}
			75% {
				background-color: #bcd5eb;
			}
			100% {
				background-color: #bcd5eb;
				outline: 1px solid #1d66bb;
			}
		}
		.not-confirmed-el-zestatix {
			background-color: #fc7169 !important;
			outline: 1px solid #dd345f !important;
			color: #333 !important;
			cursor: pointer !important;
			z-index: 99998 !important;
		}
		.selected-el-zestatix {
			background-color: #77aaf4 !important;
			outline: 2px solid #114787 !important;
			color: #333 !important;
			cursor: pointer !important;
			z-index: 99998 !important;
		}
		#root-zestatix {
			height: 100%;
			display: flex;
			position: fixed;
			z-index: 99999;
			left: calc( 0px - var(--panel-width) );
			transition: .5s ease-in .2s left;
		}
		#root-zestatix.show-zestatix {
			left: 0px;
		}
		#root-zestatix *:not( .dashicons ), #popup-zestatix * {
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		}
		#root-zestatix * {
			display: block;
			color: #28303d;
			box-sizing: border-box;
		}
		#root-zestatix input::-webkit-input-placeholder, #root-zestatix textarea::-webkit-input-placeholder {
			opacity: .5;
			-webkit-transition: 150ms opacity ease-in-out;
			transition: 150ms opacity ease-in-out;
		}
		#root-zestatix input:-ms-input-placeholder, #root-zestatix textarea:-ms-input-placeholder {
			opacity: .5;
			-ms-transition: 150ms opacity ease-in-out;
			transition: 150ms opacity ease-in-out;
		}
		#root-zestatix input::-ms-input-placeholder, #root-zestatix textarea::-ms-input-placeholder {
			opacity: .5;
			-ms-transition: 150ms opacity ease-in-out;
			transition: 150ms opacity ease-in-out;
		}
		#root-zestatix input::-moz-placeholder, #root-zestatix textarea::-moz-placeholder {
			opacity: .5;
			-moz-transition: 150ms opacity ease-in-out;
			transition: 150ms opacity ease-in-out;
		}
		#root-zestatix input::placeholder, #root-zestatix textarea::placeholder {
			opacity: .5;
			-webkit-transition: 150ms opacity ease-in-out;
			-o-transition: 150ms opacity ease-in-out;
			transition: 150ms opacity ease-in-out;
		}
		#root-zestatix input:focus::-webkit-input-placeholder,
		#root-zestatix textarea:focus::-webkit-input-placeholder {
			opacity: 0;
			-webkit-transition: 150ms opacity ease-in-out;
			transition: 150ms opacity ease-in-out;
		}
		#root-zestatix input:focus::-moz-placeholder,
		#root-zestatix textarea:focus::-moz-placeholder {
			opacity: 0;
			-moz-transition: 150ms opacity ease-in-out;
			transition: 150ms opacity ease-in-out;
		}
		#root-zestatix input:focus:-moz-placeholder,
		#root-zestatix textarea:focus:-moz-placeholder {
			opacity: 0;
			-moz-transition: 150ms opacity ease-in-out;
			transition: 150ms opacity ease-in-out;
		}
		#root-zestatix input:focus:-ms-input-placeholder,
		#root-zestatix textarea:focus:-ms-input-placeholder {
			opacity: 0;
			-ms-transition: 150ms opacity ease-in-out;
			transition: 150ms opacity ease-in-out;
		}
		#root-zestatix input[type=text], #root-zestatix textarea {
			width: 100%;
			font-size: 16px;
			background-color: #fff;
			border: 1px solid #ced4da;
		}
		#root-zestatix input[type=text] {
			padding: 4px 6px;
			border-radius: 3px;
		}
		#root-zestatix textarea {
			padding: 6px 6px 4px 6px;
			border-top-width: 0px;
			border-bottom-width: 0px;
		}
		#root-zestatix .panel-zestatix::-webkit-scrollbar-track,
		#sideNav::-webkit-scrollbar-track {
			background-color: #c1c1c1;
		}
		#root-zestatix .panel-zestatix::-webkit-scrollbar,
		#sideNav::-webkit-scrollbar {
			width: 7px;
		}
		#root-zestatix .panel-zestatix::-webkit-scrollbar-thumb,
		#sideNav::-webkit-scrollbar-thumb {
			background-color: #737375;
		}
		@supports (-webkit-appearance: none) or (-moz-appearance: none) or (appearance: none) {
			#root-zestatix input[type='checkbox'], #root-zestatix input[type='radio'] {
				-webkit-appearance: none;
				-moz-appearance: none;
				appearance: none;
				height: 21px;
				outline: none;
				display: inline-block;
				vertical-align: top;
				position: relative;
				margin: 0;
				cursor: pointer;
				border: 1px solid #bbc1e1;
				background-color: #fff;
				transition: background-color .3s, border-color .3s;
			}
			#root-zestatix input[type='checkbox']:after, #root-zestatix input[type='radio']:after {
				content: '';
				display: block;
				left: 0;
				top: 0;
				position: absolute;
				transition: transform .3s ease, opacity .2s;
			}
			#root-zestatix input[type='checkbox']:checked, #root-zestatix input[type='radio']:checked {
				background-color: #275efe;
				border-color: #275efe;
				transition: transform .6s cubic-bezier(0.2, 0.85, 0.32, 1.2), opacity .3s;
			}
			#root-zestatix input[type='checkbox']:disabled, #root-zestatix input[type='radio']:disabled {
				background-color: #e9ebf1;
				cursor: not-allowed;
				opacity: .9;
			}
			#root-zestatix input[type='checkbox']:hover:not(:checked):not(:disabled), #root-zestatix input[type='radio']:hover:not(:checked):not(:disabled) {
				border-color: #275efe;
			}
			#root-zestatix input[type='checkbox'], #root-zestatix input[type='radio'] {
				width: 21px;
			}
			#root-zestatix input[type='checkbox']:after, #root-zestatix input[type='radio']:after {
				opacity: 0;
			}
			#root-zestatix input[type='checkbox']:checked:after, #root-zestatix input[type='radio']:checked:after {
				opacity: 1;
			}
			#root-zestatix input[type='radio'] + label {
				margin-left: 10px;
			}
			#root-zestatix input[type='checkbox'] {
				border-radius: 3px;
			}
			#root-zestatix input[type='checkbox']:after {
				width: 5px;
				height: 9px;
				border: 2px solid #fff;
				border-top: 0;
				border-left: 0;
				left: 7px;
				top: 4px;
				transform: rotate( 20deg );
			}
			#root-zestatix input[type='checkbox']:checked:after {
				transform: rotate( 43deg );
			}
			#root-zestatix input[type='radio'] {
				border-radius: 50%;
			}
			#root-zestatix input[type='radio']:after {
				width: 19px;
				height: 19px;
				border-radius: 50%;
				background-color: #fff;
				opacity: 0;
				transform: scale( .7 );
			}
			#root-zestatix input[type='radio']:checked:after {
				transform: scale( .5 );
			}
		}
		#popup-zestatix {
			display: none;
			position: absolute;
		}
		#popup-zestatix .popup-container-zestatix {
			z-index: 99999;
			position: relative;
			background-color: #e6e8ef;
			text-align: center;
			border: 1px solid #8a8e98;
			border-radius: 4px;
		}
		#popup-zestatix .popup-container-zestatix > * {
			margin-top: 10px;
		}
		#popup-zestatix .popup-container-zestatix p {
			display: inline-block;
			width: 80%;
			margin-bottom: 0px;
		}
		#popup-zestatix .popup-container-zestatix .popup-buttons-zestatix {
			padding: 0px;
		}
		#popup-zestatix .popup-container-zestatix .popup-buttons-zestatix div {
			display: inline-block;
			width: 50%;
			list-style: none;
			margin: 0px;
		}
		#popup-zestatix .popup-container-zestatix .popup-buttons-zestatix div a {
			display: block;
			cursor: pointer;
			text-align: center;
			height: 60px;
			line-height: 60px;
			text-transform: uppercase;
		}
		#popup-zestatix .popup-buttons-zestatix a {
			color: #3f474a;
		}
		#popup-zestatix .popup-buttons-zestatix div:first-child a {
			background-color: #ff655c;
			-webkit-border-radius: 0 0 0 4px;
			border-radius: 0 0 0 4px;
		}
		#popup-zestatix .popup-container-zestatix .popup-buttons-zestatix div:last-child a {
			background-color: #c0c7d5;
			-webkit-border-radius: 0 0 4px 0;
			border-radius: 0 0 4px 0;
		}
		#root-zestatix .panel-zestatix .dashicons {
			font-family: dashicons;
			display: inline-block;
			font-weight: 400;
			font-style: normal;
			text-decoration: inherit;
			text-transform: none;
			text-rendering: auto;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
			width: 20px;
			height: 20px;
			font-size: 20px;
			text-align: center;
		}
		#control-panel-zestatix {
			display: flex;
			flex-direction: column;
			justify-content: center;
			margin-left: 5px;
			z-index: 99999;
		}
		#control-panel-zestatix > span:not( :last-child ) {
			margin-bottom: 5px;
		}
		#control-panel-zestatix span {
			display: block;
			cursor: pointer;
			font-family: dashicons;
			width: 60px;
			height: 60px;
			font-size: 40px;
			background-color: #b1b2b4;
			border-radius: 50%;
			line-height: 60px;
			text-align: center;
			opacity: .5;
			box-shadow: inset -2px -1px 4px 0 #5e5c5c;
			transition: opacity .3s;
		}
		#control-panel-zestatix > span:hover {
			opacity: .8;
		}
		#togglers-panel-zestatix::before {
			display: inline-block;
			-moz-transform:rotate(360deg);
			-webkit-transform:rotate(360deg);
			transform:rotate(360deg);
			-moz-transition: all .5s linear;
			-webkit-transition: all .5s linear;
			transition: all .5s linear;
		}
		#root-zestatix.show-zestatix #togglers-panel-zestatix::before {
			-moz-transform:rotate(180deg);
			-webkit-transform:rotate(180deg);
			transform:rotate(180deg);
		}
		#root-zestatix .panel-zestatix {
			height: 100%;
			background: #ecedef;
			width: var(--panel-width);
			padding: 20px;
			overflow-x: hidden;
			overflow-y: auto;
		}
		#root-zestatix .panel-zestatix > *:not( :last-child ) {
			padding-bottom: 20px;
		}
		#root-zestatix .panel-zestatix .content-zestatix {
			padding-left: 20px;
		}
		#root-zestatix .panel-zestatix .content-label-zestatix {
			cursor: default;
			margin-bottom: 10px;
			font-size: 16px;
			font-weight: 600;
		}
		#root-zestatix .panel-zestatix .selector-zestatix .content-label-zestatix {
			display: inline-block;
		}
		#root-zestatix .panel-zestatix .selector-length-zestatix {
			cursor: default;
		}
		#root-zestatix .panel-zestatix .helper-zestatix {
			cursor: pointer;
			width: 30px;
			height: 30px;
			text-align: center;
			border-radius: 50%;
			background-color: #b1b2b4;
			line-height: 30px;
			display: inline-block;
			position: absolute;
			right: 0;
			bottom: 4px;
			transition: background-color .3s;
		}
		#root-zestatix .panel-zestatix .helper-active-zestatix {
			background-color: #f65757;
		}
		#root-zestatix .table-zestatix.table-example-zestatix {
			width: 100%;
			margin-top: 10px;
		}
		#root-zestatix .table-zestatix.table-example-zestatix tr {
			display: table-row;
		}
		#root-zestatix .table-zestatix.table-example-zestatix th {
			height: 30px;
		}
		#root-zestatix .table-zestatix.table-example-zestatix tr:nth-child(odd) {
			background-color: #bbbdc0;
		}
		#root-zestatix .table-zestatix.table-example-zestatix tr:nth-child(even) {
			background-color: #fff;
		}
		#root-zestatix .table-zestatix.table-example-zestatix th,
		#root-zestatix .table-zestatix.table-example-zestatix td {
			display: table-cell;
			word-break: break-word;
			width: 50%;
			padding: 5px;
			vertical-align: middle;
		}
		#root-zestatix .parent-selector-length-zestatix {
			border: 1px solid #ced4da;
			border-bottom-width: 0px;
			transition: background-color .3s;
		}
		#root-zestatix .selector-input-zestatix {
			width: 100%;
			resize: none;
			word-break: break-all;
		}
		#root-zestatix .primary-zestatix {
			color: #004085;
			background-color: #cce5ff;
		}
		#root-zestatix .msg-zestatix {
			background-color: #f8d7da;
		}
		#root-zestatix .alert-zestatix {
			position: relative;
			height: 30px;
			width: 100%;
			-webkit-border-radius: 5px 5px 0px 0px;
			border-radius: 5px 5px 0px 0px;
		}
		#root-zestatix .danger-selector-zestatix {
			display: none;
			margin-top: 10px;
			padding: 20px;
			border: 1px solid #f5aab1;
			border-radius: 5px;
		}
		#root-zestatix .control-selector-zestatix {
			position: relative;
			border: 1px solid #ced4da;
			border-bottom-right-radius: 3px;
			border-bottom-left-radius: 3px;
			text-align: center;
			border-top-width: 0;
			height: 30px;
		}
		#root-zestatix .characters-zestatix, #root-zestatix .max-characters-zestatix {
			cursor: default;
			display: inline-block;
			font-size: 14px;
			line-height: 30px;
		}
		#root-zestatix .dashicons-trash {
			cursor: pointer;
			line-height: 30px;
			vertical-align: top;
		}
		#root-zestatix label {
			cursor: default;
			font-size: 14px;
		}
		#root-zestatix .unit-control-zestatix {
			display: flex;
			align-items: center;
		}
		#root-zestatix .unit-control-zestatix input {
			flex-shrink: 0;
		}
		#root-zestatix .content-zestatix .unit-control-zestatix:not( :last-child ) {
			margin-bottom: 5px;
		}
		#root-zestatix .unit-control-zestatix label {
			cursor: pointer;
			word-break: break-all;
		}
		#root-zestatix .subdirectories-zestatix {
			cursor: pointer;
			transform: scale(-1, 1);
			margin: 0px 6px;
		}
		#root-zestatix .subdirectories-active-zestatix {
			color: #007bff;
		}
		#root-zestatix .disable-zestatix {
			cursor: not-allowed !important;
			color: #999eb4;
		}
		#root-zestatix .custom-width-zestatix {
			display: none;
			margin: 10px 0px 0px 20px;
		}
		#root-zestatix .custom-width-zestatix div * {
			display: inline-block;
		}
		#root-zestatix .custom-width-zestatix div:not(:last-child) {
			margin-bottom: 10px;
		}
		#root-zestatix .custom-width-zestatix div input {
			width: 60px;
		}
		#root-zestatix .custom-width-zestatix div label:first-child {
			width: 50px;
		}
		#root-zestatix .custom-width-zestatix div label:last-child {
			margin-left: 10px;
		}
		@media (max-width: 400px) {
			.show-zestatix #control-panel-zestatix {
				margin-left: -65px;
			}
		}
	</style>
<?php }

function js_select() {
	include_once( INCLUDES_ZESTATIX . 'js_select.php' );
}
