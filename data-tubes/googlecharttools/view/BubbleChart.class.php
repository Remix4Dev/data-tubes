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
 * @version $Id: BubbleChart.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package view
 */

namespace googlecharttools\view;

use googlecharttools\view\options\Bubble;
use googlecharttools\view\options\ColorAxis;
use googlecharttools\view\options\SizeAxis;

/**
 * Creates a bubble chart.
 *
 * <b>Data format:</b><br />
 * The bubble chart requires a {@link DataTable} with at least three columns.
 * The first column is used for the bubble's ID, the socond column for its x-value
 * and the third for its y-value. Two more optional columns are supported, where
 * the first optional column is used to group bubbles or to assigne a color.
 * The second optional column is used to set the bubble's size.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/bubblechart}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class BubbleChart extends CartesianChart {

    /**
     * Sets the bubbles' appearance.
     *
     * @param Bubble $bubble
     *              The appearance of the chart's bubbles. If set to null, the
     *              default appearance will be used.
     */
    public function setBubble(Bubble $bubble) {
        $this->setOption("bubble", $bubble);
    }

    /**
     * Sets the mapping between bubbles and their color specified in the color column.
     *
     * @param ColorAxis $axis
     *              The color mapping. If set to null, no color axis will be displayed.
     */
    public function setColorAxis(ColorAxis $axis) {
        $this->setOption("colorAxis", $axis);
    }

    /**
     * Sets the mapping between bubbles and their size specified in the size column.
     *
     * @param SizeAxis $axis
     *              The size mapping. If set to null, the size will be calculatted
     *              automatically.
     */
    public function setSizeAxis(SizeAxis $axis) {
        $this->setOption("sizeAxis", $axis);
    }

    /**
     * Sets if the bubble should be sorted by size or by the value set in the
     * {@link DataTable}.
     *
     * @param boolean $sort
     *              If set to true, the bubbles or sorted by their size.
     *              If set to false or null, they are sorted by their value.
     */
    public function setSortBubblesBySize($sort) {
        $this->setOptionBoolean("sortBubblesBySize", $sort);
    }

}

?>
