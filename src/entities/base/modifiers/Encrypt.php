<?php
namespace fl\cms\entities\base\modifiers;

use fl\cms\entities\base\modifiers\base\ModifierInterface;
use fl\cms\entities\base\modifiers\base\ModifierBase;
use fl\cms\helpers\encryption\FLHashEncrypStatic AS FLHashEncryp;

/**
 * Description of Encrypt
 *
 * @author snu5998
 */
final class Encrypt extends ModifierBase implements ModifierInterface
{
    /**
     * @return array
     */
    public function initModifier(): array
    {
        $this->iterateProperties();
        return $this->properties;
    }
    
    /**
     * @return void
     */
    private function iterateProperties(): void
    {
        if(isset($this->properties[$this->settings['source']])) {
            $this->properties[$this->propName] = $this->encrypt($this->properties[$this->settings['source']]);
        }
        if(isset($this->properties[0][$this->settings['source']])) {
            foreach ($this->properties as $k => $val) {
                $this->properties[$k][$this->propName] = $this->encrypt($val[$this->settings['source']]);
            }
        }
    }

    /**
     * @param type $property
     * @return string
     */
    private function encrypt($property): string
    {
        if(is_string($property)) {
            return FLHashEncryp::encrypt($property);
        }
        if(is_integer($property)) {
            return FLHashEncryp::encrypt((string)$property);
        }
    }
}
