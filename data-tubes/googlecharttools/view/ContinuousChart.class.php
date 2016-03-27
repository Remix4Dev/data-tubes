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
 * @version $Id: ContinuousChart.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package view
 */

namespace googlecharttools\view;

use googlecharttools\view\options\Animation;

/**
 * A ContinuousChart is used as abstract base class for all charts whose x-axis
 * represents continues values.
 *
 * @package view
 */
abstract class ContinuousChart extends CartesianChart {

    /**
     * Sets the data line's width.
     *
     * @param int $width
     *              The data line's width in pixels. Must be greater or equal than "0".
     *              When set to "0", the lines will be invisible and only the
     *              data point will be displayed.
     *              If set to null, the default width will be used, which depents on
     *              the chart type.
     */
    public function setLineWidth($width) {
        if ($width >= 0 || $width == null) {
            $this->setOptionNumeric("lineWidth", $width);
        }
    }

    /**
     * Sets the data point's size.
     *
     * @param int $size
     *              The data point's size in pixel. Must be greater or equal than "0".
     *              When set to "0", the data points will be invisible.
     *              If set to null, the default size will be used, which depents on
     *              the chart type
     */
    public function setPointSize($size) {
        if ($size >= 0 || $size == null) {
            $this->setOptionNumeric("pointSize", $size);
        }
    }

}

?>
