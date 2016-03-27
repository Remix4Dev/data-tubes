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
 * @version $Id: Legend.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The Legend defines the text style and position of the chart's legend.
 *
 * @package view
 * @subpackage options
 */
class Legend extends OptionStorage {

    /**
     * Legend will be displayed on the right of the chart.
     */
    const POSITION_RIGHT = "right";

    /**
     * Legend will be displayed above the chart.
     */
    const POSITION_TOP = "top";

    /**
     * Legend will be displayed below the chart.
     */
    const POSITION_BOTTOM = "bottom";

    /**
     * Legend will be display inside the chart.
     */
    const POSITION_IN = "in";

    /**
     * Legend won't be displayed.
     */
    const POSITION_NONE = "none";

    /**
     * Creates a new Legend option-set.
     *
     * @param string $position
     *              The legend's position. Must be one of the <i>POSITION_...</i>
     *              constants or null.
     * @param string $textStyle
     *              The text style used for the legend.
     * @throws \InvalidArgumentException
     *              Thrown, if the given position is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function __construct($position = null, TextStyle $textStyle = null) {
        $this->setPosition($position);
        $this->setOption("textStyle", $textStyle);
    }

    /**
     * Sets the position of the chart's legend.
     *
     * @param string $position
     *              The legend's position. Must be one of the <i>POSITION_...</i>
     *              constants or null.
     * @throws \InvalidArgumentException
     *              Thrown, if the given position is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setPosition($position) {
        if ($position != self::POSITION_BOTTOM && $position != self::POSITION_IN &&
                $position != self::POSITION_NONE && $position != self::POSITION_RIGHT &&
                $position != self::POSITION_TOP && $position != null) {
            throw new \InvalidArgumentException("Argument \"position\" is invalid");
        }
        $this->setOption("position", $position);
    }

    /**
     * Sets the text style of the chart's legend.
     *
     * @param TextStyle $name
     *              The text style. If set to null, the default style will be used.
     */
    public function setTextStyle(TextStyle $textStyle) {
        $this->setOption("textStyle", $textStyle);
    }

    /**
     * Sets the format used when numeric labels are displayed.
     *
     * This is ignored by some charts.
     *
     * For the format string, a subset of the
     * {@link http://icu-project.org/apiref/icu4c/classDecimalFormat.html#_details ICU pattern set}
     * is used.
     *
     * @param string $format
     *              The format string. Has to follow the ICU pattern.
     *              If set to null, the default format will be used.
     */
    public function setNumberFormat($format) {
        $this->setOption("numberFormat", $format);
    }

}

?>
