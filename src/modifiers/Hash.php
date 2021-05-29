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
final class Hash extends ModifierBase implements ModifierInterface
{
    public function initModifier() {
        $this->iterateProperties();
        return $this->properties;
    }
    
    private function iterateProperties() {
        if(isset($this->properties[$this->settings['source']])) {
            $this->properties[$this->propName] = $this->encrypt($this->properties[$this->settings['source']]);
        }
        if(isset($this->properties[0][$this->settings['source']])) {
            foreach ($this->properties as $k => $val) {
                $this->properties[$k][$this->propName] = $this->encrypt($val[$this->settings['source']]);
            }
        }
    }


    private function encrypt($property) {
        if(is_string($property)) {
            return FLHashEncryp::getHash($property);
        }
    }
}
