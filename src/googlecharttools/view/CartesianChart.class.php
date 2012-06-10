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
 * @version $Id$
 * @package view
 */

namespace googlecharttools\view;

use googlecharttools\view\options\Animation;

/**
 * A CartesianChart is used as base for all charts that are based on a coordinate system.
 *
 * All charts that extends the CartesianChart do have x- and y-axis, for which
 * this class provides the specific options.
 *
 * @package view
 */
abstract class CartesianChart extends Chart {
    /**
     * The axis title will be drawn inside the chart area.
     */

    const AXIS_TITLE_IN = "in";

    /**
     * The axis title will be drawn outsite the chart area.
     */
    const AXIS_TITLE_OUT = "out";

    /**
     * The axis title won't be displayed.
     */
    const AXIS_TITLE_NONE = "none";

    /**
     * Focus is given to a single data point (On cell in the {@link DataTable}).
     */
    const FOCUS_DATUM = "datum";

    /**
     * Focus is given to all data point that belong to the same x-value
     * (On row in the {@link DataTable}).
     */
    const FOCUS_CATEGORY = "category";

    /**
     * The chart's area is maximizes and all labels inside the chart and the legend
     * is drawn.
     */
    const THEME_MAXIMIZED = "maximized";

    /**
     * The title is drawn inside the chart's area.
     */
    const TITLE_IN = "in";

    /**
     * The title is not drawn.
     */
    const TITLE_NONE = "none";

    /**
     * The title is drawn outside the chart's area.
     */
    const TITLE_OUT = "out";

    /**
     * Sets the animation that will be used when the data is drawn or redrawn.
     *
     * @param Animation $animation
     *              The animation. If set to null, no animation will be used.
     */
    public function setAnimation(Animation $animation) {
        $this->setOption("animation", $animation);
    }

    /**
     * Sets the position where the axis title will be displayed.
     *
     * @param string $position
     *              The title's position. Must be one of the <i>AXIS_TITLE_...</i>
     *              constants or null. If set to null, the title will be
     *              drawn outsite the chart area.
     * @throws \InvalidArgumentException
     *              Thrown, if the given position is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setAxisTitlesPosition($position) {
        if ($position != self::AXIS_TITLE_IN && $position != self::AXIS_TITLE_NONE &&
                $position != self::AXIS_TITLE_OUT && $position != null) {
            throw new \InvalidArgumentException("Parameter \"position\" is invalid");
        }
        $this->setOption("axisTitlesPosition", $position);
    }

    /**
     * Sets if the chart will react on user actions and throws events.
     *
     * @param boolean $interactivity
     *              If set to true, the chart will react on user actions and
     *              throw events. If set to null, the chart will behave is set
     *              to true.
     */
    public function setEnableInteractivity($interactivity) {
        $this->setOptionBoolean("enableInteractivity", $interactivity);
    }

    /**
     * Sets which elements will recevie the focus on mouse hover and on mouse click.
     *
     * @param string $target
     *              The element that will receive the focus. Must be on of the
     *              <i>FOCUS_...</i> constants or null. If set null, the
     *              focus will be on one single data point.
     * @throws \InvalidArgumentException
     *              Thrown, if the given focus is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setFocusTarget($target) {
        if ($target != self::FOCUS_CATEGORY && $target != self::FOCUS_DATUM &&
                $target != null) {
            throw new \InvalidArgumentException("Parameter \"target\" is invalid");
        }
        $this->setOption("focusTarget", $target);
    }

    /**
     * Sets the appearance of the horizontal axis.
     *
     * @param Axis $axis
     *              The axis definition.
     */
    public function setHAxis(Axis $axis) {
        $this->setOption("hAxis", $axis);
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

    /**
     * Sets the data line's width.
     *
     * @param int $width
     *              The data line's width in pixels. Must be greater or equal than "0".
     *              When set to "0", the lines will be invisible and only the
     *              data point will be displayed.
     *              If set to null, the default width (2 pixels) will be used.
     */
    public function setLineWidth($width) {
        if ($width >= 0) {
            $this->setOptionNumeric("lineWidth", $width);
        }
    }

    /**
     * Sets the data point's size.
     *
     * @param int $size
     *              The data point's size in pixel. Must be greater or equal than "0".
     *              When set to "0", the data points will be invisible.
     *              If set to null, the data points won't be displayed.
     */
    public function setPointSize($size) {
        if ($size >= 0) {
            $this->setOptionNumeric("pointSize", $size);
        }
    }

    /**
     * Sets the format of the chart's series.
     *
     * @param Series[] $series
     *              An array where each element defines a single series.
     *              Either the array's entries are added in the same order
     *              as the chart's lines (the {@link Row}) or the corresponding
     *              line is set as the array's key (a numeric string).
     */
    public function setSeries($series) {
        if (is_array($series)) {
            $this->setOption("series", $series);
        }
    }

    /**
     * Sets the chart's theme.
     *
     * A theme is a set of predefined options.
     *
     * @param string $theme
     *              The theme. Must be on of the <i>THEME_...</i> constants.
     * @throws \InvalidArgumentException
     *              Thrown, if the given theme is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setTheme($theme) {
        if ($theme != self::THEME_MAXIMIZED && $theme != null) {
            throw new \InvalidArgumentException("Parameter \"theme\" is invalid");
        }
        $this->setOption("theme", $theme);
    }

    public function setTitlePosition($position) {
        if ($position != self::TITLE_IN && $position != self::TITLE_NONE &&
                $position != self::TITLE_OUT && $position != null) {
            throw new \InvalidArgumentException("Parameter \"position\" is invalid");
        }
        $this->setOption("titlePosition", $position);
    }

    /**
     * Sets the appearance of the vertical axis.
     *
     * @param Axis $axis
     *              The axis definition.
     */
    public function setVAxis(Axis $axis) {
        $this->setOption("vAxis", $axis);
    }

}

?>
