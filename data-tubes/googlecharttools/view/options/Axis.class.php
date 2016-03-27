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
 * @version $Id: Axis.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package view
 * @subpackage options
 */

namespace googlecharttools\view\options;

/**
 * The Axis defines the appearance of the chart's axis.
 *
 * @package view
 * @subpackage options
 */
class Axis extends OptionStorage {

    const TEXT_POSITION_IN = "in";
    const TEXT_POSITION_NONE = "none";
    const TEXT_POSITION_OUT = "out";

    /**
     * Some space is left between the hightest (lowest) data point and the
     * axis maximum (minimum) value.
     */
    const VIEW_MODE_PRETTY = "pretty";

    /**
     * No space is left between the highest (lowest) data point and the axis
     * maximum (minimum) value.
     */
    const VIEW_MODE_MAXIMIZED = "maximized";

    /**
     * The value specified with the {@link setViewWindow()} method is used.
     */
    const VIEW_MODE_EXPLICIT = "explicit";


    /**
     * Creates a new Axis option-set
     *
     * @param string $title
     *              The axis' title.
     * @param TextStyle $textStyle
     *              The axis' text style.
     * @param $int $minValue
     *              The lowest value.
     * @param $int $maxValue
     *              The highest value.
     */
    public function __construct($title = null, TextStyle $textStyle = null, $minValue = null, $maxValue = null) {
        $this->setOption("title", $title);
        $this->setOption("textStyle", $textStyle);
        $this->setOptionNumeric("minValue", $minValue);
        $this->setOptionNumeric("maxValue", $maxValue);
    }
    /**
     * Sets the axis' baseline.
     *
     * @param int $baseline
     *              The baseline's position. If set to null, the default position
     *              will be used.
     */
    public function setBaseline($baseline) {
        $this->setOptionNumeric("baseline", $baseline);
    }

    /**
     * Sets the axis baseline's color.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              The basline color. If set to null, the default color will be used.
     */
    public function setBaselineColor($color) {
        $this->setOption("baselineColor", $color);
    }

    /**
     * Sets the direction in which the axis' values grow.
     *
     * @param int $direction
     *              The direction. Must be -1 for reverse order or 1 for normal
     *              order. If set to null, the normal order will be used.
     */
    public function setDirection($direction) {
        if ($direction == -1 || $direction == 1 ||$direction == null)
            $this->setOptionNumeric("direction", $direction);
    }

    /**
     * Sets the format used for display the axis label.
     *
     * For the format string, a subset of the
     * {@link http://icu-project.org/apiref/icu4c/classDecimalFormat.html#_details ICU pattern set}
     * is used.
     *
     * @param string $format
     *              The format string. Has to follow the ICU pattern.
     *              If set to null, the default format will be used.
     */
    public function setFormat($format) {
        $this->setOption("format", $format);
    }

    /**
     * Sets the appearance of the gridlines.
     *
     * @param Gridlines $gridlines
     *              The gridlines. If set to null, the default appearance is used.
     */
    public function setGridlines(Gridlines $gridlines) {
        $this->setOption("gridlines", $gridlines);
    }

    /**
     * Sets if the axis will use a logarithmic scale.
     *
     * @param boolean $loc
     *              If set to true, a logarithmic scale will be used.
     *              If set to null or false, no logarithmic scale will be used
     */
    public function setLocScale($loc) {
        $this->setOptionBoolean("locScale", $loc);
    }

    /**
     * Sets the label's position.
     *
     * @param string $position
     *              The text position. Must be one of the <i>TEXT_POSITION_...</i>
     *              constants or null. If set to null, the default position will
     *              be used.
     * @throws \InvalidArgumentException
     *              Thrown, if the given position is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setTextPosition($position) {
        if ($position != self::TEXT_POSITION_IN && $position != self::TEXT_POSITION_NONE &&
                $position != self::TEXT_POSITION_OUT) {
            throw new \InvalidArgumentException("Argument \"position\" is invalid");
        }
        $this->setOption("textPosition", $position);
    }

    /**
     * Sets the text style of the axis' labels.
     *
     * @param TextStyle $name
     *              The text style. If set to null, the default style will be used.
     */
    public function setTextStyle(TextStyle $style) {
        $this->setOption("textStyle", $style);
    }

    /**
     * Sets the axis' title.
     *
     * @param string $title
     *              The title that will be displayed near the axis. If se to null,
     *              no title will be displayed.
     */
    public function setTitle($title) {
        $this->setOption("title", $title);
    }

    /**
     * Sets the text style of the axis' title.
     *
     * @param TextStyle $name
     *              The text style. If set to null, the default style will be used.
     */
    public function setTitleTextStyle(TextStyle $style) {
        $this->setOption("titleTextStyle", $style);
    }

    /**
     * Sets the axis' highest visible value.
     *
     * @param float $max
     *              The highest value. If set to null, the value will be calculated
     *              automatically.
     */
    public function setMaxValue($max) {
        $this->setOptionNumeric("maxValue", $max);
    }

    /**
     * Sets the axis' lowest visible value.
     *
     * @param float $max
     *              The lowest value. If set to null, the value will be calculated
     *              automatically.
     */
    public function setMinValue($min) {
        $this->setOptionNumeric("minValue", $min);
    }

    /**
     * Sets how the data points are positioned inside the chart area.
     *
     * @param string $mode
     *              The mode used to render the data points. Must be on of the
     *              <i>VIEW_WINDOW_...</i> constants or null.
     * @throws \InvalidArgumentException
     *              Thrown, if the given mode is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setViewWindowMode($mode) {
        if ($mode != self::VIEW_MODE_EXPLICIT && $mode != self::VIEW_MODE_MAXIMIZED &&
                $mode != self::VIEW_MODE_PRETTY && $mode != null) {
            throw new \InvalidArgumentException("Argument \"mode\" is invalid");
        }
        $this->setOption("viewWindowMode", $mode);
    }

    /**
     * Sets the view window.
     *
     * This has not effect if {@link setViewWindowMode} is NOT set to
     * {@link VIEW_MODE_EXPLICIT}.
     *
     * @param ViewWindow $window
     *              The view window. If set to null, no view window is defined.
     */
    public function setViewWindow(ViewWindow $window) {
        $this->setOption("viewWindow", $window);
    }

}

?>
