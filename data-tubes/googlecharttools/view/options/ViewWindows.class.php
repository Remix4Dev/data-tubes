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
 * @version $Id: ViewWindows.class.php 26 2012-06-15 13:12:28Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The ViewWindow defines the visible area of the chart.
 *
 * @package view
 * @subpackage options
 */
class ViewWindow extends OptionStorage {

    /**
     * Creates a new ViewWindow option-set.
     *
     * @param float $max
     *              The highest visible value.
     * @param float $min
     *              The lowest visible value.
     */
    public function __construct($max, $min) {
        $this->setOptionNumeric("max", $max);
        $this->setOptionNumeric("min", $min);
    }

    /**
     * Sets the highest visible value.
     *
     * @param float $max
     *              The highest visible value. If set to null, the value will
     *              be calculated automatically.
     */
    public function setMax($max) {
        $this->setOptionNumeric("max", $max);
    }

    /**
     * Sets the highest visible value.
     *
     * @param float $max
     *              The highest visible value. If set to null, the value will
     *              be calculated automatically.
     */
    public function setMin($min) {
        $this->setOptionNumeric("min", $min);
    }

}

?>
