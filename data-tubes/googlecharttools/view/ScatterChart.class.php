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
 * @version $Id: ScatterChart.class.php 27 2012-06-16 11:39:45Z patrick.strobel.swt@gmail.com $
 * @package default
 */

namespace googlecharttools\view;

/**
 * Creates a scatter chart.
 *
 * <b>Data format:</b><br />
 * The scatter chart requires a {@link DataTable} with at least two columns.
 * The first column is used for the x-axis labels and values. Each column that
 * follows will be seen as y-values for one data set.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/scatterchart}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class ScatterChart extends ContinuousChart {

    const CURVE_NONE = "none";
    const CURVE_FUNCTION = "function";

    /**
     * Sets the chart's curve type.
     *
     * @param string $type
     *              The series' curve type. Must be one of the <i>CURVE_...</i>
     *              constants or null. If set to null, the default type will be used.
     * @throws \InvalidArgumentException
     *              Thrown, if the given type is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setCurveType($type) {
        if ($type != self::CURVE_FUNCTION && $type != self::CURVE_NONE &&
                $type != null) {
            throw new \InvalidArgumentException("Argument \"type\" is invalid");
        }
        $this->setOption("curveType", $type);
    }

}
?>
