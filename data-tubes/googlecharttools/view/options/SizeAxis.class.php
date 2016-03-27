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
 * @version $Id: SizeAxis.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The SizeAxis is used to map the chart's elements according to their size to the size axis.
 *
 * @package view
 * @subpackage options
 */
class SizeAxis extends OptionStorage {

    /**
     * Creates a new SizeAxis option-set.
     *
     * @param int $minSize
     *              The minimum size in pixels.
     * @param int $minValue
     *              The minimum value.
     * @param int $maxSize
     *              The maximum size in pixels.
     * @param int $maxValue
     *              The maximum value.
     */
    public function __construct($minSize = null, $minValue = null, $maxSize = null, $maxValue = null) {
        $this->setOptionNumeric("minSize", $minSize);
        $this->setOptionNumeric("minValue", $minValue);
        $this->setOptionNumeric("maxSize", $maxSize);
        $this->setOptionNumeric("maxValue", $maxValue);
    }

    /**
     * Sets the minimum radius of the elements.
     *
     * @param int $min
     *              The minimum size in pixels. If set to null, 5 px will be used.
     */
    public function setMinSize($min) {
        $this->setOptionNumeric("minSize", $min);
    }

    /**
     * Sets the minimum value for the elements' size.
     *
     * @param int $min
     *              The minimum value. If set to null, the minimum value set in
     *              the size column will be used.
     */
    public function setMinValue($min) {
        $this->setOptionNumeric("minValue", $min);
    }

    /**
     * Sets the maximum radius of the elements.
     *
     * @param int $min
     *              The maximum size in pixels. If set to null, 30 px will be used.
     */
    public function setMaxSize($min) {
        $this->setOptionNumeric("maxSize", $min);
    }

    /**
     * Sets the maximum value for the elements' color.
     *
     * @param int $min
     *              The maximum value. If set to null, the maximum value set in
     *              the size column will be used.
     */
    public function setMaxValue($min) {
        $this->setOptionNumeric("maxValue", $min);
    }

}

?>
