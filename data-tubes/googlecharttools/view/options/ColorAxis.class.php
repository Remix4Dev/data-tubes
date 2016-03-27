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
 * @version $Id: ColorAxis.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The ColorAxis is used to map the chart's elements to a color or a gradient.
 *
 * @package view
 * @subpackage options
 */
class ColorAxis extends OptionStorage {

    /**
     * Creates a new ColorAxis option-set.
     *
     * @param int $minValue
     *              The minimum value.
     * @param int $maxValue
     *              The maximum value.
     * @param int[] $values
     *              The mapping between colors and elements.
     * @param string[] $colors
     *              An array of colors. Each element must be a valid HTML color
     *              string.
     * @param Legend $legend
     *              The legend's style.
     */
    public function __construct($minValue = null, $maxValue = null, $values = null, $colors = null, Legend $legend = null) {
        $this->setOptionNumeric("minValue", $minValue);
        $this->setOptionNumeric("maxValue", $maxValue);
        $this->setOptionArray("values", $values);
        $this->setOptionArray("colors", $colors);
        $this->setOption("legend", $legend);
    }

    /**
     * Sets the minimum value for the elements' color.
     *
     * @param int $min
     *              The minimum value. If set to null, no minimum value will be
     *              used.
     */
    public function setMinValue($min) {
        $this->setOptionNumeric("minValue", $min);
    }

    /**
     * Sets the maximum value for the elements' color.
     *
     * @param int $min
     *              The maximum value. If set to null, no maximum value will be
     *              used.
     */
    public function setMaxValue($min) {
        $this->setOptionNumeric("maxValue", $min);
    }

    /**
     * Sets the values used to assigne the colors to the chart's elements.
     *
     * @param int[] $values
     *              The mapping between colors and elements.
     */
    public function setValues($values) {
        $this->setOptionArray("values", $values);
    }

    /**
     * Sets the colors used as gradient or single color for the chart's elements.
     *
     * @param string[] $colors
     *              An array of colors. Each element must be a valid HTML color
     *              string.
     */
    public function setColors($colors) {
        $this->setOptionArray("colors", $colors);
    }

    /**
     * Sets the style of the gradiant color's legend.
     *
     * @param Legend $legend
     *              The legend's style.
     */
    public function setLegend(Legend $legend) {
        $this->setOption("legend", $legend);
    }

}

?>
