<div id="root-zestatix" data-key="panel">
    <div class="panel-zestatix">
        <div class="name-el-zestatix">
            <label class="content-label-zestatix"><?php esc_html_e( 'NAME', 'zestatix' ) ?></label>
            <div class="content-zestatix">
                <input name="name" type="text" class="input-zestatix" value="" placeholder="<?php esc_html_e( 'enter name', 'zestatix' ) ?>">
            </div>
        </div>
        <div class="selector-zestatix">
            <div style="position:relative">
                <label class="content-label-zestatix">
                    <?php esc_html_e( 'SELECTOR', 'zestatix' ) ?> jQuery( \'
                </label>
                <span class="helper-zestatix" title="<?php esc_html_e( 'SHOW EXAMPLE', 'zestatix' ) ?>">
                    ?
                </span>
            </div>
            <div class="content-zestatix">
                <div>
                    <div class="parent-selector-length-zestatix alert-zestatix">
                        <span class="selector-length-zestatix center-x-y-zestatix"></span>
                    </div>
                    <textarea name="selector" class="selector-input-zestatix input-zestatix" wrap="soft" placeholder="<?php esc_html_e( 'enter selector', 'zestatix' ) ?>"></textarea>
                    <div class="control-selector-zestatix">
                        <span class="characters-zestatix">0</span>
                        <span class="max-characters-zestatix">&nbsp/ 255</span>
                        <span class="dashicons dashicons-trash" title="<?php esc_html_e( 'clear selector', 'zestatix' ) ?>"></span>
                    </div>
                </div>
                <div class="msg-zestatix danger-selector-zestatix"></div>
                <?php require_once( INCLUDES_ZESTATIX . 'table_example.php' ) ?>
            </div>
        </div>
        <div class="track-zestatix">
            <label class="content-label-zestatix">
                <?php esc_html_e( 'TRACK ON', 'zestatix' ) ?>
            </label>
            <div class="content-zestatix"></div>
        </div>
        <div class="width-zestatix">
            <label class="content-label-zestatix"><?php esc_html_e( 'BROWSER WIDTH', 'zestatix' ) ?></label>
            <div class="content-zestatix">
                <div class="unit-control-zestatix">
                    <input name="browser_width" class="input-width-zestatix" type="radio" value="any" >
                    <label class="label-zestatix"><?php esc_html_e( 'any width', 'zestatix' ) ?></label>
                </div>
                <div class="unit-control-zestatix">
                    <input name="browser_width" class="input-width-zestatix" type="radio" value="custom" >
                    <label class="label-zestatix"><?php esc_html_e( 'custom width', 'zestatix' ) ?></label>
                </div>
                <div class="custom-width-zestatix">
                    <div>
                        <label>min</label>
                        <input type="text" name="browser_width/min" value="">
                        <label>px</label>
                    </div>
                    <div>
                        <label>max</label>
                        <input type="text" name="browser_width/max" value="">
                        <label>px</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="control-panel-zestatix">
        <span id="save-panel-zestatix" class="dashicons dashicons-yes"></span>
        <span id="togglers-panel-zestatix" class="dashicons dashicons-arrow-right-alt2"></span>
        <span id="escape-select-zestatix" class="dashicons dashicons-no-alt"></span>
    </div>
</div>
<div id="popup-zestatix" data-key="popup">
    <div class="popup-container-zestatix">
        <p><?php esc_html_e( 'Are you sure you want to track this element?', 'zestatix' ) ?></p>
        <div class="popup-buttons-zestatix">
            <div><a>Yes</a></div><div><a>No</a></div>
        </div>
    </div>
</div>