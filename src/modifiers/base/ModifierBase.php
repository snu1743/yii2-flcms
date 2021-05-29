<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace fl\cms\entities\base\modifiers\base;

/**
 * Description of ModifierBase
 *
 * @author snu5998
 */
abstract class ModifierBase implements ModifierInterface 
{
    public $config;
    public $properties;
    public $settings;
    public $propName;


    public function __construct(array $config, array $properties, array $settings, string $propName)
    {
        $this->config = $config;
        $this->properties= $properties;
        $this->settings = $settings;
        $this->propName = $propName;
    }
    
    public function initModifier()
    {
        return $this->properties;
    }
}
