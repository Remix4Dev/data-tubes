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
 * @version $Id: Column.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package model
 */

namespace googlecharttools\model;

/**
 * A Column stores the definition of one column of the {@link DataTable}.
 *
 * For each column that will be used in the data of the {@link DataTable}, a
 * column-definiton has to be added to the DataTable.
 *
 * Please visit Google's API documentation at
 * {@link https://google-developers.appspot.com/chart/interactive/docs/reference#dataparam}
 * for detailed background information.
 *
 * @package model
 */
class Column {

    const TYPE_BOOLEAN   = "boolean";
    const TYPE_NUMBER    = "number";
    const TYPE_STRING    = "string";
    const TYPE_DATE      = "date";
    const TYPE_DATETIME  = "datetime";
    const TYPE_TIMEOFDAY = "timeofday";

    /** @var string */
    private $type;

    /** @var string */
    private $id;

    /** @var string */
    private $label;

    /** @var string */
    private $properties;

    /**
     * Creates a new Column.
     *
     * @param string $type
     *              Used to define the data type of all cells that will be added to
     *              this column. Has to be one of the <i>TYPE_...</i> constants.
     * @param string $id
     *              A unique identifier used to access the column (no two columns
     *              in the same {@link DataTable} are allowed to have the same ID).
     *              As this will be passed directly to the JavaScript API, please
     *              make sure that you do not use a JavaScript keyword.
     * @param string $label
     *              The column's label. This will be displayed on most charts.
     * @param string $properties
     *              Any additional properties that will be used by some chart types.
     *              This is typically used to specifiy the column's role, so that
     *              the data in this column will, for example, be used for annotations
     *              (when set to <i>{"role": "annotation"}</i> in this case).
     *              See {@link https://google-developers.appspot.com/chart/interactive/docs/roles}
     *              for more information about different roles.
     * @throws \InvalidArgumentException
     *              Thrown, if the given type is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function __construct($type, $id = null, $label = null, $properties = null) {
        $this->setType($type);
        $this->id = $id;
        $this->label = $label;
        $this->properties = $properties;
    }

    /**
     * Gets the column's ID.
     *
     * @return string
     *              The column's ID or null, if no ID has been set.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the column's label.
     *
     * @return string
     *              The column's label or null, if no label has been set.
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * Gets the properties that have been added to the column.
     *
     * @return string
     *              The column's properties as JavaScript/JSON-objects or null, if no
     *              properties have been set.
     */
    public function getProperties() {
        return $this->properties;
    }

    /**
     * Gets the data type of all {@link Cell}s added in this column.
     *
     * @return string
     *              The column's type. One of the <i>TYPE_...</i> constants.
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set's the column's ID.
     *
     * @param string $id
     *              A unique identifier used to access the column (no two columns
     *              in the same DataTable are allowed to have the same ID). As this
     *              will be passed directly to the JavaScript API, please make sure
     *              that you do not use a JavaScript keyword.
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Set's the columns label.
     *
     * @param string $label
     *              The label will be displayed on most charts.
     */
    public function setLabel($label) {
        $this->label = $label;
    }

    /**
     * Set's the columns properties.
     *
     *
     * @param string $properties
     *              Any additional properties that will be used by some chart types.
     *              This is typically used to specifiy the column's role, so that
     *              the data in this column will, for example, be used for annotations
     *              (when set to <i>{"role": "annotation"}</i> in this case).
     *              See {@link https://google-developers.appspot.com/chart/interactive/docs/roles}
     *              for more information on different roles.
     *
     */
    public function setProperties($properties) {
        $this->properties = $properties;
    }

    /**
     * Sets the column's type.
     *
     * The type defines what data types are added to this column by the {@link Cell}s.
     *
     * @param string $type
     *              Used to define the data type of all cells that will added in
     *              this column. Has to be one of the <i>TYPE_...</i> constants.
     * @throws \InvalidArgumentException
     *              Thrown, if the given type is invalid. That is, if a value
     *              other than one of the constants is used.
     */
    public function setType($type) {
        if ($type != self::TYPE_BOOLEAN && $type != self::TYPE_DATE &&
                $type != self::TYPE_DATETIME && $type != self::TYPE_NUMBER &&
                $type != self::TYPE_STRING && $type != self::TYPE_TIMEOFDAY) {
            throw new \InvalidArgumentException("Argument \"type\" is invalid");
        }
        $this->type = $type;
    }

    /**
     * Converts this object into a JSON-object.
     *
     * This representation follows the JavaScript literal format specified
     * at {@link https://google-developers.appspot.com/chart/interactive/docs/reference#dataparam}
     * as element of the "cols" JSON-array.
     *
     * @return string
     *              The JSON representation of this Column.
     */
    public function toJson() {
        $string = "{\"type\": \"" . $this->type . "\"";
        if ($this->id != null) {
            $string .= ", \"id\": \"" . $this->id . "\"";
        }

        if ($this->label != null) {
            $string .= ", \"label\": \"" . $this->label . "\"";
        }

        if ($this->properties != null) {
            $string .= ", \"p\": " . $this->properties;
        }
        $string .= "}";

        return $string;
    }

}

?>
