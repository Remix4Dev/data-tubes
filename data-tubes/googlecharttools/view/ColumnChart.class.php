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
 * @version $Revision$
 * @package view
 */

namespace googlecharttools\view;

/**
 * Creates a column chart.
 *
 * <b>Data format:</b><br />
 * The column chart requires a {@link DataTable} with at least two columns.
 * The first column is used for the x-axis labels and values. Each column that
 * follows will be seen as y-values for one column.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/columnchart}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class ColumnChart extends DiscreteChart {

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
     * Sets the properties of more than one vertical axis.
     *
     * The specified array index must be mapped to the same number as set in
     * the array given to {@link Series::setTargetAxisIndex()}.
     *
     * @param Axis[] $axes
     *              A property for each axis. If set to null, the default properties
     *              will be used.
     */
    public function setVAxes($axes) {
        $this->setOptionArray("vAxes", $axes);
    }

}

?>
