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
 * @version $Id: PieChart.class.php 27 2012-06-16 11:39:45Z patrick.strobel.swt@gmail.com $
 * @package default
 */

namespace googlecharttools\view;

/**
 * Creates a pie chart.
 *
 * <b>Data format:</b><br />
 * The pie chart requires a {@link DataTable} which has two columns.
 * The first column is used for the labels and and the second one for the values.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/piechart}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class PieChart extends Corechart {

    /**
     * The slice's name will be displayed.
     */
    const TEXT_LABEL = "label";

    /**
     * No text will be displayed.
     */
    const TEXT_NONE = "none";

    /**
     * The slice's percentage value will be displayed.
     */
    const TEXT_PERCENTAGE = "percentage";

    /**
     * The slice's value will be displayed.
     */
    const TEXT_VALUE = "value";

    /**
     * Sets if the chart will be displayed in 3D.
     *
     * @param boolean $is3D
     *              If set to true, the chart will be displayed in 3D.
     *              If set to false or null, it will be displayed in normal mode.
     */
    public function setIs3D($is3D) {
        $this->setOptionBoolean("is3D", $is3D);
    }

    /**
     * Sets the format of the chart's slices.
     *
     * @param Slice[] $series
     *              An array where each element defines a single slice.
     *              Either the array's entries are added in the same order
     *              as the chart's slices (the {@link Row}s) or the corresponding
     *              slice is set as the array's key (a numeric string).
     */
    public function setSlices($slices) {
        $this->setOptionArray("slices", $slices);
    }

    /**
     * Sets the slices' border color.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", #FFF, #123456).
     *
     * @param string $color
     *              HTML color string. If set to null, the default color
     *              will be used.
     */
    public function setPieSliceBorderColor($color) {
        $this->setOption("pieSliceBorderColor", $color);
    }

    /**
     * Sets what will be displayed on the chart's slices.
     *
     * @param string $position
     *              The value that will be displayed. Must be one of the <i>TEXT_...</i>
     *              constants or null. If set to null, the percentage value will be displayed.
     * @throws \InvalidArgumentException
     *              Thrown, if the given text is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setPieSliceText($text) {
        if ($text != self::TEXT_LABEL && $text != self::TEXT_NONE &&
                $text != self::TEXT_PERCENTAGE && self::TEXT_VALUE &&
                $text != null) {
            throw new \InvalidArgumentException("Argument \"text\" is invalid");
        }
        $this->setOption("pieSliceText", $text);
    }

    /**
     * Sets the text style of the chart's slices.
     *
     * @param TextStyle $name
     *              The text style. If set to null, the default style will be used.
     */
    public function setPieSliceTextStyle(TextStyle $textStyle) {
        $this->setOption("pieSliceTextStyle", $textStyle);
    }

    /**
     * Sets the visiblility threshold.
     *
     * All slices below this threshold will be displayed as one slice.
     *
     * @param float $treshold
     *              The treshold. If set to null, a "1/720" treshold will be used.
     */
    public function setSliceVisibilityThreshold($treshold) {
        $this->setOptionNumeric("sliceVisibilityThreshold", $treshold);
    }

    /**
     * Sets the combination slice's color.
     *
     * All slices being below the treshold will be included in this slice.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", #FFF, #123456).
     *
     * @param string $color
     *              HTML color string. If set to null, the default color
     *              will be used ("#ccc").
     */
    public function setPieResidueSliceColor($color) {
        $this->setOption("pieResidueSliceColor", $color);
    }

    /**
     * Sets the combination slice's label that will be displayed in the legend.
     *
     * All slices being below the treshold will be included in this slice.
     * @param string $label
     *              The slice's label. If set to null, the default label
     *              will be used ("Other").
     */
    public function setPieResidueSliceLabel($label) {
        $this->setOption("pieResidueSliceLabel", $label);
    }

}

?>
