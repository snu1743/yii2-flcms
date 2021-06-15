<?php

namespace fl\cms\entities\base\modifiers\base;

/**
 * Description of ModifierBase
 *
 * @author snu5998
 */
class Modifiers {
    
    const MODIFIERS_NAMESPACE = 'fl\cms\entities\base\modifiers';
    private static $properties;
    private static $config;

    public static function apply(array $config, array $properties, array $params, string $type = 'out'): array
    {
        self::$properties = $properties;
        self::$config = $config;
        
        foreach ($config[$params['action_name']]['properties'] as $propName => $propSettings) {
            if(isset($propSettings["modifiers_$type"]) && is_array($propSettings["modifiers_$type"])) {
                self::parseModifiersSettings($propName, $propSettings["modifiers_$type"]);
            }
        }
        return self::$properties;
    }
    
    private static function parseModifiersSettings(string $propName, array $modifiersSettings)
    {
        foreach ($modifiersSettings as $modifierName => $modifierSettings) {
            self::initModifier($modifierName, $modifierSettings, $propName);
        }
    }
    
    private static function initModifier(string $modifierName , array $modifierSettings, string $propName) {
        $n = explode("_", $modifierName);
        $className = '';
        foreach ($n as $segment) {
            $className = $className . ucfirst($segment);
        }
        $class = self::MODIFIERS_NAMESPACE . "\\"  . $className;
        if (class_exists($class)) {
             $modifier = new $class(self::$config, self::$properties, $modifierSettings, $propName);
             self::$properties = $modifier->initModifier();
        }
        
        
    }
}
