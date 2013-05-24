<?php
class MLCAceServerControlAction extends MJaxServerControlAction{
    public function Render(){
        $strRendered = 'function(objEvent){';
        $strRendered .= sprintf("MLC.Ace.TriggerControlEvent(objEvent, '%s', '%s');", $this->objEvent->Selector, $this->objEvent->EventName);
        //The following wont render anything unless blnOnce is set to true
        $strRendered .= $this->objEvent->RenderUnbind();
        $strRendered .= '}';
        return $strRendered;
    }
}