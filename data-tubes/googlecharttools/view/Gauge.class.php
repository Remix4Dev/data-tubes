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
 * @version $Id: Gauge.class.php 27 2012-06-16 11:39:45Z patrick.strobel.swt@gmail.com $
 * @package default
 */

namespace googlecharttools\view;

/**
 * Creates a gauge.
 *
 * <b>Data format:</b><br />
 * The gauge requires a {@link DataTable} which has two columns.
 * The first column is used for the gauge's label and and the second one for its value.
 * Each {@link Row} will be used for one gauge.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/gauge}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class Gauge extends Chart {

    public function getPackage() {
        return "gauge";
    }

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
     * Sets the color that will be used for the green section.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setGreenColor($color) {
        $this->setOption("greenColor", $color);
    }

    /**
     * Sets the lowest value used for the green section.
     *
     * @param int $from
     *              The lowest value for the section. If set to null, no
     *              section will be displayed.
     */
    public function setGreenFrom($from) {
        $this->setOptionNumeric("greenFrom", $from);
    }

    /**
     * Sets the highest value used for the green section.
     *
     * @param int $from
     *              The highest value for the section. If set to null, no
     *              section will be displayed.
     */
    public function setGreenTo($to) {
        $this->setOptionNumeric("greenTo", $to);
    }

    /**
     * Sets the labels that will be displayed at the gauge's major ticks.
     *
     * @param string[] $labels
     *              An array with the tick's labels. The number of array-elements
     *              will be used as the number of ticks. If set to null,
     *              five ticks will be displayed and the tick with the lowest
     *              and highest value will be labeled.
     */
    public function setMajorTicks($labels) {
        $this->setOptionArray("majorTicks", $labels);
    }

    /**
     * Sets the maximum value that will be displayed in the gauge.
     *
     * @param int $max
     *              The maxiumum value. If set to null, "100" will be used.
     */
    public function setMax($max) {
        $this->setOptionNumeric("max", $max);
    }

    /**
     * Sets the minimum value that will be displayed in the gauge.
     *
     * @param int $min
     *              The miniumum value. If set to null, "0" will be used.
     */
    public function setMin($min) {
        $this->setOptionNumeric("min", $min);
    }

    /**
     * Sets the number of minor tickst that will be displayed between every
     * two major ticks.
     *
     * @param int $ticks
     *              A non-negative number of minor ticks. If set to null,
     *              "2" will be used.
     */
    public function setMinorTicks($ticks) {
        if ($ticks >= 0 || $ticks == null) {
            $this->setOptionNumeric("minorTicks", $ticks);
        }
    }

    /**
     * Sets the color that will be used for the red section.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setRedColor($color) {
        $this->setOption("redColor", $color);
    }

    /**
     * Sets the lowest value used for the red section.
     *
     * @param int $from
     *              The lowest value for the section. If set to null, no
     *              section will be displayed.
     */
    public function setRedFrom($from) {
        $this->setOptionNumeric("redFrom", $from);
    }

    /**
     * Sets the highest value used for the red section.
     *
     * @param int $from
     *              The highest value for the section. If set to null, no
     *              section will be displayed.
     */
    public function setRedTo($to) {
        $this->setOptionNumeric("redTo", $to);
    }

    /**
     * Sets the color that will be used for the yellow section.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param string $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setYellowColor($color) {
        $this->setOption("yellowColor", $color);
    }

    /**
     * Sets the lowest value used for the yellow section.
     *
     * @param int $from
     *              The lowest value for the section. If set to null, no
     *              section will be displayed.
     */
    public function setYellowFrom($from) {
        $this->setOptionNumeric("yellowFrom", $from);
    }

    /**
     * Sets the highest value used for the yellow section.
     *
     * @param int $from
     *              The highest value for the section. If set to null, no
     *              section will be displayed.
     */
    public function setYellowTo($to) {
        $this->setOptionNumeric("yellowTo", $to);
    }

}

?>
