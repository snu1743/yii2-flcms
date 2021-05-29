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
final class Rename extends ModifierBase implements ModifierInterface
{
    public function initModifier() {
        $this->iterateProperties();
        return $this->properties;
    }
    
    private function iterateProperties() {
        if(isset($this->properties[$this->settings['source']])) {
            $this->properties[$this->propName] = $this->rename($this->properties[$this->settings['source']]);
        }
        if(isset($this->properties[0][$this->settings['source']])) {
            foreach ($this->properties as $k => $val) {
                $this->properties[$k][$this->propName] = $this->rename($val[$this->settings['source']]);
            }
        }
    }


    private function rename($property) {
        if(is_string($property)) {
            return $property;
        }
    }
}
