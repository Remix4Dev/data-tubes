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
 * @version $Id: TextStyle.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The TextStyle defines the color, size and font name of a text element.
 *
 * @package view
 * @subpackage options
 */
class TextStyle extends OptionStorage {

    /**
     * Creates a new TextStyle option-set.
     *
     * @param string $color
     *              The text-color. Any valid HTML/CSS color definition
     *              (e. g. "white", #FFF, #123456).
     * @param string $fontName
     *              The font type's name.
     * @param int $fontSize
     *              The font size in pixels.
     */
    public function __construct($color = null, $fontName = null, $fontSize = null) {
        $this->setOption("color", $color);
        $this->setOption("fontName", $fontName);
        $this->setOptionNumeric("fontSize", $fontSize);
    }

    /**
     * Sets the font's color.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", #FFF, #123456).
     *
     * @param string $color
     *              HTML color string. If set to null, the default color
     *              assigned to the outer element will be used.
     */
    public function setColor($color) {
        $this->setOption("color", $color);
    }

    /**
     * Sets the font-type.
     *
     * @param int $name
     *              The font type's name. If set to null, the default font
     *              assigned to the outer element will be used.
     */
    public function setFontName($name) {
        $this->setOption("fontName", $name);
    }

    /**
     * Sets the font's size.
     *
     * @param int $size
     *              The font size in pixels. If set to null, the default size
     *              assigned to the outer element will be used.
     */
    public function setFontSize($size) {
        $this->setOption("fontSize", $size);
    }

}

?>
