<?php

class Net_EPP_Frame_Command_Transfer_Domain extends Net_EPP_Frame_Command_Transfer
{
    public function __construct()
    {
        parent::__construct('domain');
    }

    public function setPeriod($period, $units = 'y')
    {
        $el = $this->createObjectPropertyElement('period');
        $el->setAttribute('unit', $units);
        $el->appendChild($this->createTextNode($period));
        $this->payload->appendChild($el);
    }
}
