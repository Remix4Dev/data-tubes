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
 * @version $Id: AreaChart.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package view
 */

namespace googlecharttools\view;

/**
 * Creates an area chart.
 *
 * <b>Data format:</b><br />
 * The area chart requires a {@link DataTable} with at least two columns.
 * The first column is used for the x-axis labels and values. Each column that
 * follows will be seen as y-values for one line/area.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/areachart}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class AreaChart extends LineChart {

    /**
     * Sets the colored area's opacity.
     *
     * This value will be used for all data sets.
     *
     * @param float $opacity
     *              The areas opacity. 0.0 means fully transparent, 1.0
     *              fully opaque. If set to null, the default opacity will be used.
     */
    public function setAreaOpacity($opacity) {
        if ($opacity >= 0.0 && $opacity <= 1.0 || $opacity == null) {
            $this->setOptionNumeric("areaOpacity", $opacity);
        }
    }

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

}

?>
