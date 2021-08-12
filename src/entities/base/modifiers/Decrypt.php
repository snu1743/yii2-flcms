<?php
namespace fl\cms\entities\base\modifiers;

use fl\cms\entities\base\modifiers\base\ModifierInterface;
use fl\cms\entities\base\modifiers\base\ModifierBase;
use fl\cms\helpers\encryption\FLHashEncrypStatic AS FLHashEncryp;

/**
 * Description of Encrypt
 * @author snu5998
 */
final class Decrypt extends ModifierBase implements ModifierInterface
{
    public function initModifier(): array
    {
        $this->iterateProperties();
        return $this->properties;
    }
    
    private function iterateProperties(): void
    {
        if(isset($this->properties[$this->settings['source']])) {
            $this->properties[$this->propName] = $this->decrypt($this->properties[$this->settings['source']]);
        }
        if(isset($this->properties[0][$this->settings['source']])) {
            foreach ($this->properties as $k => $val) {
                $this->properties[$k][$this->propName] = $this->decrypt($val[$this->settings['source']]);
            }
        }
    }

    private function decrypt($property): string
    {
        if(is_string($property)) {
            return FLHashEncryp::decrypt($property);
        }
    }
}
