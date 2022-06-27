<?php

namespace app\widgets;

use kartik\select2\Select2;
use ReflectionException;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\JsExpression;

class CountrySelect2 extends Select2
{
    /**
     * @var array
     */
    public $countries;

    /**
     * @throws ReflectionException
     * @throws InvalidConfigException
     */
    public function init()
    {
        $countriesJson = Json::encode($this->countries);

        $jsTemplateFunction = 'function format(country) {
            if (!country.id) return country.text;
            let countries = ' . $countriesJson . ';
            return \'<i class="flag-icon flag-icon-\' + countries[country.id].code.toLowerCase() + \'"></i> \' + country.text;
        }';

        $this->pluginOptions = ArrayHelper::merge($this->pluginOptions, [
            'templateResult' => new JsExpression($jsTemplateFunction),
            'templateSelection' => new JsExpression($jsTemplateFunction),
            'escapeMarkup' => new JsExpression("function(m) { return m; }"),
        ]);

        $this->data = ArrayHelper::map($this->countries, 'id', 'name');

        parent::init();
    }

    /**
     * @return string|void
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function run()
    {
        return parent::run();
    }
}