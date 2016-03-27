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
 * @version $Id: GeoChart.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package default
 */

namespace googlecharttools\view;

use googlecharttools\view\options\BackgroundColor;
use googlecharttools\view\options\ColorAxis;
use googlecharttools\view\options\Legend;
use googlecharttools\view\options\MagnifyingGlass;
use googlecharttools\view\options\SizeAxis;

/**
 * Creates a geo chart.
 *
 * <b>Data format:</b><br />
 * The geo chart requires a {@link DataTable} which has two or three columns.
 * The first column is used to set the region or the marker's position.
 * Ihe second column is optional and can be used to set the
 * region's or marker's color. In marker mode, the third column can be used to set
 * the markers size.
 *
 * See {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/geochart}
 * for examples and detailed background information on the required data format.
 *
 * @package view
 */
class GeoChart extends Chart {

    /**
     * The mode is set based on the structure of the {@link DataTable}.
     */
    const MODE_AUTO = "auto";

    /**
     * A marker map will be used.
     */
    const MODE_MARKERS = "markers";

    /**
     * A region map will be used.
     */
    const MODE_REGIONS = "regions";

    const RESOLUTION_COUNTRIES = "countries";
    const RESOLUTION_METROS = "metros";
    const RESOLUTION_PROVINCES = "provinces";

    public function getPackage() {
        return "geochart";
    }

    /**
     * Sets the chart's background color and border.
     *
     * @param BackgroundColor $background
     *              Background color and border. If set to null, the default
     *              background color and border will be used.
     */
    public function setBackgroundColor(BackgroundColor $background) {
        $this->setOption("backgroundColor", $background);
    }

    /**
     * Sets the mapping between the regions/markers and their color specified in the color column.
     *
     * @param ColorAxis $axis
     *              The color mapping. If set to null, no color axis will be
     *              displyed.
     */
    public function setColorAxis(ColorAxis $axis) {
        $this->setOption("colorAxis", $axis);
    }

    /**
     * Sets the color that will be used for regions with no assigned data.
     *
     * Any valid HTML/CSS color definition can be used (e. g. "white", "#FFF", "#123456").
     *
     * @param type $color
     *              HTML color string. If set to null, the default color will be used.
     */
    public function setDatalessRegionColor($color) {
        $this->setOption("datalessRegionColor", $color);
    }

    /**
     * Sets the map's type.
     *
     * @param string $mode
     *              The map's type. Must be on of the <i>MODE_...</i> constants
     *              or null. If set to null, the map's type will be set automatically.
     * @throws \InvalidArgumentException
     *              Thrown, if the given mode is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setDisplayMode($mode) {
        if ($mode != self::MODE_AUTO && $mode != self::MODE_MARKERS &&
                $mode != self::MODE_REGIONS && $mode != null) {
            throw new \InvalidArgumentException("Argument \"mode\" is invalid");
        }
        $this->setOption("displayMode", $mode);
    }

    /**
     * Sets if the chart will react on mouse hover and select.
     *
     * @param boolean $enable
     *              If set to true, the chart will react on mouse actions.
     *              If set to null, the interactivity will be enabled in
     *              region maps and disabled in marker maps.
     */
    public function setEnableRegionInteractivity($enable) {
        $this->setOptionBoolean("enableRegionInteractivity", $enable);
    }

    /**
     * Sets if the chart should keep its aspect ratio.
     *
     * @param boolean $keep
     *              If set to true or null, the chart will keep its aspect ratio.
     */
    public function setKeepAspectRatio($keep) {
        $this->setOptionBoolean("keepAspectRatio", $keep);
    }

    /**
     * Sets the legends position and text style.
     *
     * @param Legend $legend
     *              Position and text style. If set to null, the default position
     *              and text style will be used.
     */
    public function setLegend(Legend $legend) {
        $this->setOption("legend", $legend);
    }

    /**
     * Sets the region that will be displayed in the chart.
     *
     * @param string $region
     *              The region that will be displyed. See the chart's
     *              documentation about the parameter "region" at
     *              {@link https://google-developers.appspot.com/chart/interactive/docs/gallery/geochart}
     *              for valid values. If set to null, "world" will be used.
     */
    public function setRegion($region) {
        $this->setOption("region", $region);
    }

    /**
     * Sets the magnifying glass behaviour.
     *
     * @param MagnifyingGlass $magnifyingGlass
     *              The magnifying glass. If set to null, the default behaviour
     *              will be used.
     */
    public function setMagnifyingGlass(MagnifyingGlass $magnifyingGlass) {
        $this->setOption("magnifyingGlass", $magnifyingGlass);
    }

    /**
     * Sets the marker's opacity.
     *
     * @param float $opacity
     *              The markers opacity. 0.0 means fully transparent, 1.0
     *              fully opaque. If set to null, the default opacity will be used.
     */
    public function setMarkerOpacity($opacity) {
        if ($opacity >= 0.0 && $opacity <= 1.0 || $opacity == null) {
            $this->setOptionNumeric("markerOpacity", $opacity);
        }
    }

    /**
     * Sets the map's resolution.
     *
     * @param string $resolution
     *              The maps resolution. Must be one of the <i>RESOLUTION...</i>
     *              constants or null. If set to null, "countries" will be used.
     * @throws \InvalidArgumentException
     *              Thrown, if the given resolution is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setResolution($resolution) {
        if ($resolution != self::RESOLUTION_COUNTRIES && $resolution != self::RESOLUTION_METROS &&
                $resolution != self::RESOLUTION_PROVINCES && $resolution != null) {
            throw new \InvalidArgumentException("Argument \"resolution\" is invalid");
        }
        $this->setOption("resolution", $resolution);
    }

    /**
     * Sets the mapping between regions/markers and their size specified in the size column.
     *
     * @param SizeAxis $axis
     *              The size mapping. If set to null, the mapping is caluclated
     *              automatically.
     */
    public function setSizeAxis(SizeAxis $axis) {
        $this->setOption("sizeAxis", $axis);
    }

    /**
     * Sets the behaviour and style of the chart's tooltip.
     *
     * @param Tooltip $tooltip
     *              The tooltip behaviour and style. If set to null, the default
     *              behaviour and style will be used.
     */
    public function setTooltip(Tooltip $tooltip) {
        $this->setOption("tooltip", $tooltip);
    }

}

?>
