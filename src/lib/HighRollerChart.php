<?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 1:04 PM
 * Desc: HighRoller Chart Class
 *
 *  Licensed to Gravity.com under one or more contributor license agreements.
 * See the NOTICE file distributed with this work for additional information
 * regarding copyright ownership.  Gravity.com licenses this file to you use
 * under the Apache License, Version 2.0 (the License); you may not this
 * file except in compliance with the License.  You may obtain a copy of the
 * License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an AS IS BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

class HighRollerChart {

  public $type;
  public $renderTo;
  public $height;
  public $width;
  public $marginTop;
  public $marginRight;
  public $marginBottom;
  public $marginLeft;
  public $spacingTop;
  public $spacingRight;
  public $spacingLeft;
  public $borderWidth;
  public $borderColor;
  public $borderRadius;
  public $backgroundColor;
  public $animation;
  public $shadow;

  function __construct(){

    $this->type = 'line';             // highcharts chart type obj defaults to line, but, let's set it anyway
    $this->renderTo = 'mychart';
    $this->height = 300;
    $this->width = 400;

    $this->marginTop = 60;
    $this->marginLeft = 80;
    $this->marginRight = 40;
    $this->marginBottom = 80;

    $this->spacingTop = 10;
    $this->spacingLeft = 40;
    $this->spacingRight = 20;
    $this->spacingBottom = 15;

    $this->borderWidth = 1;
    $this->borderColor = '#eee';
    $this->borderRadius = 8;

    $this->backgroundColor = new HighRollerBackgroundColorOptions();

    $this->animation = new HighRollerChartAnimation();

    $this->shadow = true;
  }

}
?>