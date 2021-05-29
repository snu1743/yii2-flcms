<?php
namespace fl\cms\entities\base\modifiers;

use fl\cms\entities\base\modifiers\base\ModifierInterface;
use fl\cms\entities\base\modifiers\base\ModifierBase;
use fl\cms\base\encryption\FLHashEncrypStatic AS FLHashEncryp;

/**
 * Description of Encrypt
 *
 * @author snu5998
 */
final class ListEncryptedUniqueValues extends ModifierBase implements ModifierInterface
{
    private $list;
    
    public function initModifier() {
        $this->iterateSettings();
//        print_r($this->properties);
        return $this->properties;
        
    }
    
    private function iterateSettings() {
//        print_r($this->settings);
        foreach ($this->settings as $key => $value) {
            $source = array_key_first($value);
            $target = $value[array_key_first($value)];
            $this->iterateProperties($source, $target);
        }
    }
    
    private function iterateProperties($source, $target) {
//        if(isset($this->properties[$source])) {
//            $this->properties[$this->propName] = $this->encrypt($this->properties[$source]);
//        }
        if(isset($this->properties[0][$source])) {
            foreach ($this->properties as $k => $val) {
                $this->properties[$k][$target] = $this->encrypt($val[$source]);
            }
        }
    }

    private function encrypt($property) {
        if(is_string($property) && !isset($this->list[$property])) {
            $this->list[$property] = FLHashEncryp::encrypt($property);
        }
        if($this->list[$property]) {
            return $this->list[$property];
        }
        
    }
}
