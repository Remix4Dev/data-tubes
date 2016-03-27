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
 * @version $Id: CandlestickChart.class.php 27 2012-06-16 11:39:45Z patrick.strobel.swt@gmail.com $
 * @package view
 */

namespace googlecharttools\view;

/**
 * Creates a candlestick chart.
 *
 * <b>Data format:</b><br />
 * The candlestick chart requires a {@link DataTable} with at least five columns.
 * The first column is used for the x-axis labels and values. The second and fith
 * column are used for the minimim and maximum value (vertical lines), respectively.
 * The marker's range is defined by the third and fourth column. The sixth column
 * is optional and will be used for the tooltip-text.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/candlestickchart}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class CandlestickChart extends DiscreteChart {

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
