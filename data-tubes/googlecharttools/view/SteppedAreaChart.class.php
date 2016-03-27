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
 * @version $Id: SteppedAreaChart.class.php 20 2012-06-12 15:57:59Z patrick.strobel.swt@gmail.com $
 * @package view
 */

namespace googlecharttools\view;

/**
 * Creates a stepped area chart.
 *
 * <b>Data format:</b><br />
 * The stepped area chart requires a {@link DataTable} with at least two columns.
 * The first column is used for the x-axis labels and values. Each column that
 * follows will be seen as y-values for one line.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/areachart}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class SteppedAreaChart extends AreaChart {

    /**
     * Sets if the steps will be connected.
     *
     * @param boolean $connect
     *              If set to true or null, the steps will be connected.
     */
    public function setConnectSteps($connect) {
        $this->setOptionBoolean("connectSteps", $connect);
    }

}

?>
