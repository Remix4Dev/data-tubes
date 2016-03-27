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
 * @version $Id: Bubble.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The Bubble stores the appearance of a bubble in the bubble chart.
 *
 * @package view
 * @subpackage options
 */
class Bubble extends OptionStorage {

    /**
     * Creates a new BackgroundColor option-set.
     *
     *
     * @param float $opacity
     *              The bubbles' opacity. 0.0 means fully transparent, 1.0
     *              fully opaque.
     * @param string $stroke
     *              The bubbles' border color. Any valid HTML/CSS color definition
     *              (e. g. "white", "#FFF", "#123456").
     * @param TextStyle $textStyle
     *              The bubbles' labels text style.
     */
    public function __construct($opacity = null, $stroke = null, TextStyle $textStyle = null) {
        $this->setOpacity($opacity);
        $this->setOption("stroke", $stroke);
        $this->setOption("textStyle", $textStyle);
    }

    /**
     * Sets the buuble's opacity.
     *
     * @param float $opacity
     *              The bubble's opacity. 0.0 means fully transparent, 1.0
     *              fully opaque.
     */
    public function setOpacity($opacity) {
        if ($opacity >= 0.0 && $opacity <= 1.0 || $opacity == null) {
            $this->setOptionNumeric("opacity", $opacity);
        }
    }

    /**
     * Sets the bubbles' border color.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setStroke($color) {
        $this->setOption("stroke", $color);
    }

    /**
     * Sets the text style of the bubbles' labels.
     *
     * @param TextStyle $name
     *              The text style. If set to null, the default text style will be used.
     */
    public function setTextStyle(TextStyle $textStyle) {
        $this->setOption("textStyle", $textStyle);
    }

}

?>
