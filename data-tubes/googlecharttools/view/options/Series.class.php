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
 * @version $Id: Series.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The Series defines the format of one series in the chart.
 *
 * <b>Note:</b> Not all options that can be set through this class are supported
 * by all charts. Please visit the Google Chart Tools documentation for detailed
 * information.
 *
 * @package view
 * @subpackage options
 */
class Series extends OptionStorage {

    const TYPE_AREA = "area";
    const TYPE_BARS = "bars";
    const TYPE_LINE = "line";
    const CURVE_NONE = "none";
    const CURVE_FUNCTION = "function";

    /**
     * Creates a new Series option-set.
     *
     * @param string $type
     *              The series' marker type. Must be on of the <i>TYPE_...</i>
     *              constants or null.
     * @param string $color
     *              The series' color. Any valid HTML/CSS color definition
     *              (e. g. "white", "#FFF", "#123456").
     * @param int $targetAxisIndex
     *              The axis to which the series will be assigned to
     *              ("0" default axis, "1" opposite axis).
     * @param int $pointSize
     *              Individual size of the series' data points.
     * @param int $lineWidth
     *              Individual size of the series' lines.
     * @param float $areaOpacity
     *              Individual opacity of the series' area. 0.0 means fully
     *              transparent, 1.0 fully opaque.
     * @param $curveType
     *              The series' curve type. Must be one of the <i>CURVE_...</i>
     *              constants or null.
     * @param boolean $visibleInLegend
     *              If set to true, the series will be displayed in the legend.
     * @throws \InvalidArgumentException
     *              Thrown, if the given type or curve type is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function __construct($type = null, $color = null, $targetAxisIndex = null, $pointSize = null, $lineWidth = null, $areaOpacity = null, $curveType = null, $visibleInLegend = null) {
        $this->setType($type);
        $this->setOption("color", $color);
        $this->setTargetAxisIndex($targetAxisIndex);
        $this->setPointSize($pointSize);
        $this->setLineWidth($lineWidth);
        $this->setAreaOpacity($areaOpacity);
        $this->setCurveType($curveType);
        $this->setOptionBoolean("visibleInLegend", $visibleInLegend);
    }

    /**
     * Sets the series' type.
     *
     * @param string $type
     *              The series' marker type. Must be on of the <i>TYPE_...</i>
     *              constants or null. If set to null, the default type will be used.
     * @throws \InvalidArgumentException
     *              Thrown, if the given type is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setType($type) {
        if ($type != self::TYPE_AREA && $type != self::TYPE_BARS &&
                $type != self::TYPE_LINE && $type != null) {
            throw new \InvalidArgumentException("Argument \"type\" is invalid");
        }
        $this->setOption("type", $type);
    }

    /**
     * Sets the series color.
     *
     * Any valid HTML/CSS color definition (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              The series' color. If set to null, the default color will
     *              be used.
     */
    public function setColor($color) {
        $this->setOption("color", $color);
    }

    /**
     * Sets the axis to which the series will be assigned.
     *
     * @param int $index
     *              The axis to which the series will be assigned to
     *              ("0" default axis, "1" opposite axis).
     */
    public function setTargetAxisIndex($index) {
        if ($index == 0 || $index == 1 || $index == null) {
            $this->setOptionNumeric("targetAxisIndex", $index);
        }
    }

    /**
     * Sets the individual data line's width.
     *
     * @param int $width
     *              The data line's width in pixels. Must be greater or equal than "0".
     *              When set to "0", the lines will be invisible and only the
     *              data point will be displayed.
     *              If set to null, the default width (2 pixels) will be used.
     */
    public function setLineWidth($width) {
        if ($width >= 0 || $width == null) {
            $this->setOptionNumeric("lineWidth", $width);
        }
    }

    /**
     * Sets the individual data point size.
     *
     * @param int $size
     *              The data point's size in pixel. Must be greater or equal than "0".
     *              When set to "0", the data points will be invisible.
     *              If set to null, the data points won't be displayed.
     */
    public function setPointSize($size) {
        if ($size >= 0 || $size == null) {
            $this->setOptionNumeric("pointSize", $size);
        }
    }

    /**
     * Sets the individual colored area's opacity.
     *
     * @param float $opacity
     *                  The areas opacity. 0.0 means fully transparent, 1.0
     *                  fully opaque.
     */
    public function setAreaOpacity($opacity) {
        if ($opacity >= 0.0 && $opacity <= 1.0 || $opacity == null) {
            $this->setOptionNumeric("areaOpacity", $opacity);
        }
    }

    /**
     * Sets the series' curve type.
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

    /**
     * Sets if the series will be visible in the legend.
     *
     * @param boolean $visible
     *              If set to true, the series will be displayed in the legend.
     */
    public function setVisibleInLegend($visible) {
        $this->setOptionBoolean("visibleInLegend", $visible);
    }

}

?>
