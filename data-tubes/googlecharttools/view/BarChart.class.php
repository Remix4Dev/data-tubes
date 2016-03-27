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
 * @version $Id: BarChart.class.php 27 2012-06-16 11:39:45Z patrick.strobel.swt@gmail.com $
 * @package view
 */

namespace googlecharttools\view;

/**
 * Creates a bar chart.
 *
 * <b>Data format:</b><br />
 * The bar chart requires a {@link DataTable} with at least two columns.
 * The first column is used for the x-axis labels and values. Each column that
 * follows will be seen as y-values for one bar.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/barchart}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class BarChart extends DiscreteChart {

    /**
     * Sets if the elements should be stacked.
     *
     * @param boolean $stacked
     *              If set to true, elements of the same type are stacked.
     *              If set to false or null, the elements are not stacked.
     */
    public function setIsStacked($stacked) {
        $this->setOptionBoolean("isStacked", $stacked);
    }

    /**
     * Sets the properties of more than one horzontal axis.
     *
     * The specified array index must be mapped to the same number as set in
     * the array given to {@link Series::setTargetAxisIndex()}.
     *
     * @param Axis[] $axes
     *              A property for each axis. If set to null, the default properties
     *              will be used.
     */
    public function setHAxes($axes) {
        $this->setOptionArray("hAxes", $axes);
    }

}

?>
