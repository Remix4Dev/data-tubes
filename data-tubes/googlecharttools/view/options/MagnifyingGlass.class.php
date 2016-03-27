<?php

/*
 * Copyright 2012 Patrick Strobel.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @author Patrick Strobel
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License, Version 2.0
 * @link http://code.google.com/p/googlecharttools-php
 * @version $Id: MagnifyingGlass.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The MagnifyingGlass defines the magnifying glass' behaviour.
 *
 * @package view
 * @subpackage options
 */
class MagnifyingGlass extends OptionStorage {

    /**
     * Creates a new MagnifyingGlass option-set.
     *
     * @param boolean $enable
     *              If set to true, the magnifying glass will be shown
     *              on mouse hover on a cluttered area.
     * @param float $zoomFactor
     *              The zoom factor that will be used. Must be greater than "0".
     */
    public function __construct($enable = null, $zoomFactor = null) {
        $this->setOptionBoolean("enable", $enable);
        $this->setZoomFactor($zoomFactor);
    }

    /**
     * Sets if the magnifying glass will be enabled.
     *
     * @param boolean $enable
     *              If set to true, the magnifying glass will be shown
     *              on mouse hover on a cluttered area.
     *              If set to false or null, no magnifying glass will be shown.
     */
    public function setEnable($enable) {
        $this->setOptionBoolean("enable", $enable);
    }

    /**
     * Sets the magnifying glass' zoom factor.
     *
     * @param float $zoom
     *              The zoom factor that will be used. Must be greater than "0".
     *              If set to null, "5.0" will be used.
     */
    public function setZoomFactor($zoom) {
        if ($zoom > 0 || $zoom == null) {
            $this->setOptionNumeric("zoomFactor", $zoom);
        }
    }

}
?>
