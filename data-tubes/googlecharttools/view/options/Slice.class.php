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
 * @version $Id: Slice.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The Slice defines the color and text style of a single slice in a {@link PieChart}.
 *
 * @package view
 * @subpackage options
 */
class Slice extends OptionStorage {

    /**
     * Creates a new Slice option-set.
     *
     * @param string $color
     *              The slice's color. Any valid HTML/CSS color definition
     *              (e. g. "white", #FFF, #123456).
     * @param TextStyle $textStyle
     *              The slice's individual text style
     */
    public function __construct($color = null, TextStyle $textStyle = null) {
        $this->setOption("color", $color);
        $this->setOption("textStyle", $textStyle);
    }

    /**
     * Sets the slice's color.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", #FFF, #123456).
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setColor($color) {
        $this->setOption("color", $color);
    }

    /**
     * Sets the slices's text style.
     *
     * @param TextStyle $size
     *              The text style. If set to null, the default style will be used.
     */
    public function setTextStyle(TextStyle $style) {
        $this->setOption("textStyle", $style);
    }

}
?>
