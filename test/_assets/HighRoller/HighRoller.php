<?php
 /*
 * HighRoller -- PHP wrapper for the popular JS charting library Highcharts
 * Author:       jmaclabs@gmail.com
 * File:         HighRoller.php
 * Date:         Tue Dec  6 21:01:56 PST 2011
 * Version:      1.0.2
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
?>
<?php
/**
 * Author: jmaclabs
 * Date: 9/14/11
 * Time: 5:46 PM
 * Desc: HighRoller Parent Class
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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

class HighRoller {

  public $chart;
  public $title;
  public $legend;
  public $credits;
  public $tooltip;
  public $plotOptions;
  public $series = array();

  function __construct(){

    $this->chart = new HighRollerChart();
    $this->title = new HighRollerTitle();
    $this->legend = new HighRollerLegend();
    $this->tooltip = new HighRollerToolTip();
    $this->plotOptions = new HighRollerPlotOptions($this->chart->type);
    $this->series = new HighRollerSeries();
    $this->credits = new HighRollerCredits();

  }

  /** returns a javascript script tag with path to your HighCharts library source
   * @static
   * @param $location - path to your highcharts JS
   * @return string - html script tag markup with your source location
   */
  public static function setHighChartsLocation($location){
    return $scriptTag = "<!-- High Roller - High Charts Location-->
  <script type='text/javascript' src='" . $location . "'></script>";

  }

  /** returns a javascript script tag with path to your HighCharts library THEME source
   * @static
   * @param $location - path to your highcharts theme file
   * @return string - html script tag markup with your source location
   */
  public static function setHighChartsThemeLocation($location){
    return $scriptTag = "<!-- High Roller - High Charts Theme Location-->
  <script type='text/javascript' src='" . $location . "'></script>";

  }

  /** returns chart object with newly set obj property name
   * @param $objName - string, name of the HighRoller Object you're operating on
   * @param $propertyName - string, name of the property you want to set, can be a new property name
   * @param $value - mixed, value you wish to assign to the property
   * @return HighRoller
   */
  public function setProperty($objName, $propertyName, $value){
    $this->$objName->$propertyName = $value;
    return $this;
  }

  /** add data to plot in your chart
   * @param $chartdata - array, data provided in 1 of 3 HighCharts supported array formats (array, assoc array or mult-dimensional array)
   * @return void
   */
  public function addData($chartdata){
    if(!is_array($chartdata)){
      die("HighRoller::addData() - data format must be an array.");
    }
    $this->series = array($chartdata);
  }

  /** add series to your chart
   * @param $chartdata - array, data provided in 1 of 3 HighCharts supported array formats (array, assoc array or mult-dimensional array)
   * @return void
   */
  public function addSeries($chartData){
    if(!is_object($chartData)){
      die("HighRoller::addSeries() - series input format must be an object.");
    }

    if(is_object($this->series)){   // if series is an object
      $this->series = array($chartData);
    } else if(is_array($this->series)) {                        // else
      array_push($this->series, $chartData);
    }
  }

  /** enable auto-step calc for xAxis labels for very large data sets.
   * @return void
   */
  public function enableAutoStep(){

    if(is_array($this->series)) {
      $count = count($this->series[0]->data);
      $step = number_format(sqrt($count));
      if($count > 1000){
        $step = number_format(sqrt($count/$step));
      }

      $this->xAxis->labels->step = $step;
    }

  }

  /** returns new Highcharts javascript
   * @return string - highcharts!
   */
  function renderChart($engine = 'jquery'){
    $options = new HighRollerOptions();   // change file/class name to new HighRollerGlobalOptions()

    if ( $engine == 'mootools')
      $chartJS = 'window.addEvent(\'domready\', function() {';
    else
      $chartJS = '$(document).ready(function() {';

    $chartJS .= "\n\n    // HIGHROLLER - HIGHCHARTS UTC OPTIONS ";

    $chartJS .= "\n    Highcharts.setOptions(\n";
    $chartJS .= "       " . json_encode($options) . "\n";
    $chartJS .= "    );\n";
    $chartJS .= "\n\n    // HIGHROLLER - HIGHCHARTS '" . $this->title->text . "' " . $this->chart->type . " chart";
    $chartJS .= "\n    var " . $this->chart->renderTo . " = new Highcharts.Chart(\n";
    $chartJS .= "       " . $this->getChartOptionsObject() . "\n";
    $chartJS .= "    );\n";
    $chartJS .= "\n  });\n";
    return trim($chartJS);
  }

  /** returns valid Highcharts javascript object containing your HighRoller options, for manipulation between the markup script tags on your page`
   * @return string - highcharts options object!
   */
  function getChartOptionsObject(){
    return trim(json_encode($this));
  }

  /** returns new Highcharts.Chart() using your $varname
   * @param $varname - name of your javascript object holding getChartOptionsObject()
   * @return string - a new Highcharts.Chart() object with the highroller chart options object
   */
  function renderChartOptionsObject($varname){
    return "new Highcharts.Chart(". $varname . ")";
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/23/11
 * Time: 12:49 PM
 * Desc: HighRoller Animation Settings
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
 
class HighRollerAnimation {

  public $duration;
  public $easing;

  function __construct(){
    $this->duration = 1500;
    $this->easing = 'easeOutBounce';
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 8:56 PM
 * Desc: HighRoller xAxis Labels
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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

class HighRollerAxisLabel {

  public $align = "center";
  public $step = null;
  public $style;

  function __construct(){
    $this->style = new HighRollerStyle();
    $this->style->color = "#6D869F";
    $this->style->fontWeight = "bold";
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 1:10 PM
 * Desc: HighRoller xAxis Class
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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

class HighRollerAxisTitle {

  public $align = "middle";
  public $text = null;
  public $style;

  function __construct(){
    $this->style = new HighRollerStyle();
    $this->style->fontWeight = "bold";
    $this->style->color = "#6D869F";
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/23/11
 * Time: 5:32 PM
 * Desc: HighRoller Background Color Options
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerBackgroundColorOptions {

  public $linearGradient;
  public $stops;
  
  function __construct(){
    $this->linearGradient = array();
    $this->stops = array();
  }
  
}
?><?php
/**
 * Author: jmac
 * Date: 9/23/11
 * Time: 12:52 PM
 * Desc: HighRoller Bar Options
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerBarOptions {

  public $borderWidth;
  public $borderColor;
  public $strokeweight;
  public $shadow;
  public $dataLabels;

  function __construct(){
    $this->borderWidth = 0;
    $this->borderColor ='#555';
    $this->strokeweight = '10pt';
    $this->shadow = true;
    $this->dataLabels = new HighRollerDataLabels();
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/27/11
 * Time: 12:59 AM
 * Desc: HighRoller Bar Plot Options
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
 
class HighRollerBarPlotOptions {

  public $shadow;
  public $size;
  public $center;
  public $dataLabels;

  function __construct(){

    $this->borderWidth = 0;
    $this->borderColor ='#555';

    $this->strokeweight = '10pt';

    $this->shadow = true;

    $this->dataLabels = new HighRollerDataLabels();
    $this->dataLabels->formatter = null;

  }

}
?><?php
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

  public $alignTicks = true;
  public $type = 'line';
  public $renderTo;
  public $height = null;
  public $width = null;
  public $marginTop = null;
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

    $this->type = 'line';         // highcharts chart type obj defaults to line, but, let's set it anyway
    $this->renderTo = 'mychart';
    $this->height = null;         // was 300
    $this->width = null;          // was 400

    $this->alignTicks = true;

    $this->marginTop = null;      // 60
    $this->marginLeft = 80;       // highcharts default
    $this->marginRight = 50;      // 50
    $this->marginBottom = 70;     // 80

    $this->spacingTop = 10;       // highcharts default
    $this->spacingLeft = 10;      // 40
    $this->spacingRight = 10;     // 20
    $this->spacingBottom = 15;    // highcharts default

    $this->borderWidth = 0;       // highcharts default
    $this->borderColor = '#4572A7';     // highcharts default
    $this->borderRadius = 5;      // highcharts default

    $this->backgroundColor = new HighRollerBackgroundColorOptions();

    $this->animation = new HighRollerChartAnimation();

    $this->shadow = false;         // true
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 1:06 PM
 * Desc: HighRoller Chart Animation Class
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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

class HighRollerChartAnimation {

  public $enabled;
  public $duration;
  public $easing;

  function __construct(){
    $this->enabled = true;
    $this->duration = 500;
    $this->easing = 'swing';
  }

}
?>
<?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 1:10 PM
 * Desc: HighRoller Credits Class
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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

class HighRollerCredits {

  public $enabled;

  function __construct(){
    $this->enabled = false;
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/23/11
 * Time: 10:03 PM
 * Desc: HighRoller Data Labels
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerDataLabels {

  public $enabled = false;
  public $align = "center";
  public $color = null;

  function __construct(){
    $this->enabled = false;
    $this->style = new HighRollerStyle();
  }

}
?><?php
/**
 * Author: jmac
 * Date: 10/9/11
 * Time: 11:27 PM
 * Desc: HighRoller Date Time Label Formats
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerDateTimeLabelFormats {

  public $second;
  public $minute;
  public $hour;
  public $day;
  public $week;
  public $month;
  public $year;

  function __construct(){
    $this->second = '%H:%M:%S';
    $this->minute = '%H:%M';
    $this->hour = '%H:%M';
    $this->day = '%e. %b';
    $this->week = '%e. %b';
    $this->month = '%b \'%y';
    $this->year =  '%Y';

  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 12:44 PM
 * Desc: HighRoller Engine Class
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerEngine {

  public $type;

  function __construct(){
    $this->type = "jquery";
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 11:48 PM
 * Desc: HighRoller Formatter
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerFormatter {

  public $formatter;
  
  function __construct(){
    $this->formatter = "";
  }

}
?>
<?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 1:10 PM
 * Desc: HighRoller Legend Class
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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

class HighRollerLegend {

  public $align = "center";
  public $enabled = true;
  public $shadow = false;
  public $borderColor = "#909090";
  public $style;
  public $backgroundColor;

  function __construct(){
//    $this->align = "center";
//    $this->enabled = true;
//    $this->shadow = true;
//    $this->borderColor = "#909090";
    $this->style = new HighRollerStyle();
    $this->backgroundColor = new HighRollerBackgroundColorOptions();
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/26/11
 * Time: 7:51 PM
 * Desc: HighRoller Line Plot Options
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerLinePlotOptions {

  public $shadow;
  public $size;
  public $center;
  public $dataLabels;

  function __construct(){
    $this->shadow = true;
    $this->size = '70%';
    $this->center = array('50%', '50%');
    $this->dataLabels = new HighRollerDataLabels();
    $this->dataLabels->formatter = null;

  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 1:03 PM
 * Desc: HighRoller Options Class
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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

class HighRollerOptions {

  public $global;

  function __construct(){
    $this->global = new HighRollerOptionsGlobal();
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 12:48 PM
 * Desc: HighRoller Options Global Class
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerOptionsGlobal {

  public $useUTC;

  function __construct(){
    $this->useUTC = true;
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/24/11
 * Time: 3:28 PM
 * Desc: HighRoller Pie Plot Options
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerPiePlotOptions {

  public $shadow;
  public $size;
  public $center;
  public $dataLabels;

  function __construct(){
    $this->shadow = true;
    $this->size = '70%';
    $this->center = array('50%', '50%');
    $this->dataLabels = new HighRollerDataLabels();
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 11:13 PM
 * Desc: HighRoller Plot Lines
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerPlotLines {

  public $color = null;
  public $dashStyle = "Solid";
  public $events;
  public $id = null;
  public $label;
  public $value = null;
  public $width = null;
  public $zIndex = null;

  function __construct(){
    $this->events = array();
    $this->label = new HighRollerAxisLabel();
    $this->label->verticalAlign = "top";
  }

}
?>

<?php
/**
 * Author: jmac
 * Date: 9/23/11
 * Time: 12:38 PM
 * Desc: HighRoller Plot Options
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerPlotOptions {

  public $series;

  function __construct($chartType){

    // default HighRoller Series PlotOptions
    $this->series = new HighRollerSeriesOptions();

    if($chartType == 'area'){ $this->area = null; }
    else if($chartType == 'bar'){ $this->bar = null; }
    else if($chartType == 'column'){ $this->column = null; }
    else if($chartType == 'line'){ $this->line = null; }
    else if($chartType == 'pie'){ $this->pie = null; }
    else if($chartType == 'scatter'){ $this->scatter = null; }
    else if($chartType == 'spline'){ $this->spline = null; }

//    $this->areaspline = null;
//    $this->bar = null;
//    $this->column = null;
//    $this->line = null;
//    $this->pie = null;
//    $this->scatter = null;
//    $this->spline = null;

  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/24/11
 * Time: 3:28 PM
 * Desc: HighRoller Plot Options By Chart Type
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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

class HighRollerPlotOptionsByChartType {

  public $allowPointSelect = false;
  public $showInLegend = true;
  public $shadow = true;
  public $formatter;

  function __construct($type){

    $this->borderColor = '#FFFFFF';           //#555
    $this->borderRadius = 0;

    if($type == 'pie' || $type == 'bar' || $type !== 'column'){
      $this->borderWidth = 1;
    } else {
      $this->borderWidth = 0;
    }

    $this->dataLabels = new HighRollerDataLabels();

    if($type == 'pie'){
      $this->size = '75%';                    // 100
      $this->center = array('50%', '50%');    // 25, 65
      $this->showInLegend = false;            // true
      $this->dataLabels->align = null;
      $this->dataLabels->enabled = true;
      $this->dataLabels->connectorWidth = 1;
      $this->dataLabels->connectorPadding = 5;
      $this->dataLabels->distance = 30;
      $this->dataLabels->softConnector = true;
    }

    $this->formatter = new HighRollerFormatter();
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 1:11 PM
 * Desc: HighRoller Series Class
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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

class HighRollerSeries {

  public $data;
  public $name;

  function __construct(){
    $this->name = '';
    $this->data = '';
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/23/11
 * Time: 12:40 PM
 * Desc: HighRoller Series Data Options
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerSeriesOptions {

  public $animation = true;
  public $dataLabels;

  function __construct(){
    $this->dataLabels = new HighRollerDataLabels();
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/24/11
 * Time: 1:28 AM
 * Desc: HighRoller Style
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerStyle {

  public $color = "#3E576F";


  function __construct(){

  }
}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 1:07 PM
 * Desc: HighRoller Title Class
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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

class HighRollerTitle {

  public $align = "middle";
  public $floating = false;
  public $text = "Chart Title";
  public $style;
  public $x;

  function __construct(){
    $this->style = new HighRollerStyle();
    $this->style->fontSize = "16px";
    $this->x = 0;     // 5
  }
  
}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 11:46 PM
 * Desc: HighRoller Tool Tip
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerToolTip {

  public $backgroundColor;

  function __construct(){

    $this->backgroundColor = new HighRollerBackgroundColorOptions();
  }

}
?>
<?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 1:10 PM
 * Desc: HighRoller xAxis Class
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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

class HighRollerXAxis {

  public $endOnTick = false;
  public $labels;
  public $title;
  public $categories = array();
  public $dataLabels;
  public $plotLines = array();    // @TODO instantiating a new plotLines object isn't working, setting as an array
  public $formatter;

  function __construct(){
    $this->labels = new HighRollerXAxisLabels();
    $this->labels->enabled = true;
    $this->title = new HighRollerAxisTitle();
    $this->dateTimeLabelFormats = new HighRollerDateTimeLabelFormats();
    $this->plotLines = array();   // @TODO need to revisit why declaring this as an empty class or a hydrated class isn't working
    $this->formatter = new HighRollerFormatter();
  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 8:56 PM
 * Desc: HighRoller xAxis Labels
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerXAxisLabels {

  public $align = "center";
  public $enabled = true;
  public $step = null;
  public $style;

  function __construct(){

    $this->style = new HighRollerStyle();
    $this->style->color = "#6D869F";
    $this->style->fontWeight = "bold";

  }

}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 8:44 PM
 * Desc: HighRoller yAxis
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerYAxis {

  public $title;
  public $labels;
//  public $min;
//  public $max;
  public $plotLines = array();    // @TODO instantiating a new plotLines object isn't working, setting as an array
  public $formatter;

  function __construct(){
    $this->labels = new HighRollerAxisLabel();
    $this->labels->enabled = true;
    $this->labels->align = "right";
    $this->title = new HighRollerAxisTitle();
    $this->title->margin = 40;
    $this->plotLines = array();   // @TODO need to revisit why declaring this as an empty class or a hydrated class isn't working    $this->dateTimeLabelFormats = new HighRollerDateTimeLabelFormats();
    $this->formatter = new HighRollerFormatter();
  }
  
}
?><?php
/**
 * Author: jmac
 * Date: 9/21/11
 * Time: 8:49 PM
 * Desc: HighRoller yAxis Labels
 *
 * Licensed to Gravity.com under one or more contributor license agreements.
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
 
class HighRollerYAxisLabels {

  public $enabled;

  function __construct(){
    $this->enabled = true;
  }
  
}
?>